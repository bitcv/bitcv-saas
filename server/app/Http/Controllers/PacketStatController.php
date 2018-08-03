<?php

namespace App\Http\Controllers;

use App\Service\PacketStatService;
use App\Utils\Service;
use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\DB;
use App\Utils\DictUtil;

class PacketStatController extends Controller
{
    public function getPacketStatByMonth(Request $request)
    {
        $yearMonth = $request->get('month','2018-01');
        $beginDate = Service::getCurMonthFirstDay($yearMonth);
        $endDate = Service::getCurMonthLastDay($beginDate);
        $nowStamp = time();
        $endDate = strtotime($endDate) > $nowStamp ? date('Y-m-d',$nowStamp) : $endDate;
        $tokenId = (int)$request->get('tokenId',0);
        $tokenId = $tokenId >= 0 ? $tokenId : 0;
        $data = PacketStatService::getStatDataByDay($beginDate,$endDate,$tokenId);
        foreach ($data['dailyData'] as &$item) {
            $sentPacketRate = $item['numsOfSendPacketMember'] > 0 ? bcdiv($item['numsOfPacketsSent'],$item['numsOfSendPacketMember'],2) : 0;
            $avgAmountOfPacket = $item['numsOfPacketsSent'] > 0 ?  bcdiv($item['amountOfCandySent'],$item['numsOfPacketsSent'],2) : 0;
            $item = array_add($item,'sentPacketRate',number_format($sentPacketRate,2,'.',','));
            $item = array_add($item,'avgAmountOfPacket',number_format($avgAmountOfPacket,2,'.',','));
            $item['amountOfCandyPicked'] = number_format($item['amountOfCandyPicked'],2,'.',',');
            $item['cnyValueOfTokenSent'] = number_format($item['cnyValueOfTokenSent'],2,'.',',');
        }
        $results = [];
        foreach ($data['dailyData'] as $value) {
            $results[] = $value;
        }
        return $this->output($results);
    }

    public function getAdminDepositBoxList (Request $request) {
        $params = $this->validation($request, [
            'projId' => 'required|numeric'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        // 测试使用
//        $params['projId'] = 2;
        $depositBoxModel = Model\DepositBox::join('proj_info', 'depo_box.proj_id', '=', 'proj_info.id')
            ->join('depo_order', 'depo_box.id', '=', 'depo_order.deposit_box_id')
            ->join('base_user', 'depo_order.user_id', '=', 'base_user.id');
        $dataCount = $depositBoxModel->count();
        $dataList = $depositBoxModel
            ->select('depo_box.id', 'depo_box.proj_id', 'depo_box.total_amount', 'depo_box.min_amount', 'depo_box.remain_amount', 'depo_box.lock_time', 'depo_box.interest_rate', 'depo_box.from_addr', 'depo_box.to_addr', 'depo_box.contract_addr', 'depo_box.status', 'proj_info.name_cn','base_user.mobile','depo_box.name','depo_order.order_amount')
            ->orderBy('depo_box.created_at', 'desc')
            ->where('depo_box.proj_id', $params['projId'])
            ->get()->toArray();
        foreach ($dataList as $key => $value) {
            $dataList[$key]['totalAmount2'] = round($value['total_amount'],4);
            $dataList[$key]['minAmount2'] = round($value['min_amount'],4);
            $dataList[$key]['statusName'] = DictUtil::DepositBox_Status[$value['status']];
        }
        \Log::info('$dataList'.var_export($dataList,true));
        $ids = array_values(array_unique(array_column($dataList,'id')));
        foreach ($dataList as $key => $value) {
            foreach ($ids as $k => $id) {
                if ($id == $value['id']) {
                    $data[$k][] = $value;
                    $data[$k]['stat']['name'] = $value['name'];
                    $data[$k]['stat']['total_amount'] = $value['totalAmount2'];
                    $data[$k]['stat']['min_amount'] = $value['min_amount'];
                    $data[$k]['stat']['lock_time'] = $value['lock_time'];
                    $data[$k]['stat']['interest_rate'] = $value['interest_rate'];
                    $data[$k]['stat']['minAmount2'] = $value['minAmount2'];
                    $data[$k]['stat']['statusName'] = $value['statusName'];
                }
            }
        }
        $temp = [];
        foreach ($data as $key => $value) {
            foreach ($value as $k => $val) {
                $temp[$key]['people'] = count($data[$key]) - 1;
                $temp[$key]['name'] = $value['stat']['name'];
                $temp[$key]['totalAmount2'] = $value['stat']['total_amount'];
                $temp[$key]['minAmount2'] = $value['stat']['minAmount2'];
                $temp[$key]['lock_time'] = $value['stat']['lock_time'];
                $temp[$key]['interest_rate'] = $value['stat']['interest_rate'];
                $temp[$key]['statusName'] = $value['stat']['statusName'];
                if ($k == 'stat') {
                    continue;
                }
                $temp[$key]['totalorder'] = array_sum(array_column($data[$key], 'order_amount'));
            }
        }
        foreach ($temp as $key => $value) {
            $temp[$key]['lastamount'] = $value['totalAmount2'] - $value['totalorder'];
            $temp[$key]['rate'] = round(($value['totalorder'] / $value['totalAmount2'] * 100),2).'%';
        }
        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $temp,
        ]);
    }

    public function getOrderDepositBoxList (Request $request)
    {
        $params = $this->validation($request, [
            'perpage' => 'required|numeric',
            'pageno' => 'required|numeric',
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        // 测试使用
//        $params['projId'] = 2;
        $allparams = $request->all();
        $result = DB::table('depo_box')->select('name')->where('proj_id',$params['projId'])->get()->toArray();
        $names = [];
        foreach ($result as $key => $value) {
            $names[] = $value->name;
        }
        $offset = $perpage * ($pageno - 1);

        $query = DB::table('depo_order');
        $query = $query->join('base_user', 'depo_order.user_id', '=', 'base_user.id');
        $query = $query->join('depo_box', 'depo_order.deposit_box_id', '=', 'depo_box.id');
        $query = $query->join('proj_info', 'depo_box.proj_id', '=', 'proj_info.id');
        $query = $query->select('depo_order.id', 'depo_order.deposit_box_id', 'depo_order.order_amount', 'depo_order.from_addr', 'depo_order.to_addr', 'depo_order.to_addr', 'depo_order.contract_addr', 'depo_order.status', 'depo_order.created_at', 'depo_box.proj_id', 'depo_box.name', 'depo_box.total_amount', 'depo_box.min_amount', 'depo_box.remain_amount', 'depo_box.lock_time', 'depo_box.interest_rate', 'proj_info.name_cn', 'base_user.mobile');
        if (array_key_exists('name',$allparams) && $allparams['name']) {
            $query = $query->where('depo_box.name','like','%'.$allparams['name'].'%');
        }
        $dataList = $query->orderBy('depo_order.created_at', 'desc')
            ->where('depo_box.proj_id', $params['projId'])
            ->offset($offset)->limit($perpage)
            ->get()->toArray();
        $dataCount = $query->count();
        /*$depositOrderModel = Model\DepositOrder::join('base_user', 'depo_order.user_id', '=', 'base_user.id')
            ->join('depo_box', 'depo_order.deposit_box_id', '=', 'depo_box.id')
            ->join('proj_info', 'depo_box.proj_id', '=', 'proj_info.id')
            ->select('depo_order.id', 'depo_order.deposit_box_id', 'depo_order.order_amount', 'depo_order.from_addr', 'depo_order.to_addr', 'depo_order.to_addr', 'depo_order.contract_addr', 'depo_order.status', 'depo_order.created_at', 'depo_box.proj_id', 'depo_box.name', 'depo_box.total_amount', 'depo_box.min_amount', 'depo_box.remain_amount', 'depo_box.lock_time', 'depo_box.interest_rate', 'proj_info.name_cn', 'base_user.mobile');
        $dataCount = $depositOrderModel->count();
        $dataList = $depositOrderModel
                ->orderBy('depo_order.created_at', 'desc')
                ->where('depo_box.proj_id', $params['projId'])
                ->where('depo_box.name','like','%'.$allparams['name'].'%')
                ->offset($offset)->limit($perpage)
                ->get()->toArray();
        foreach ($dataList as $key => $value) {
            $dataList[$key]['endTime'] = date('Y-m-d H:i:s',strtotime("{$value['created_at']} +".$value['lock_time']." day"));
            $dataList[$key]['endGet'] = round($value['order_amount'] * (1 + $value['interest_rate']),4);
        }*/
        foreach ($dataList as $key => $value) {
            $dataList[$key]->endTime = date('Y-m-d H:i:s',strtotime("{$value->created_at} +".$value->lock_time." day"));
            $dataList[$key]->endGet = round($value->order_amount * (1 + $value->interest_rate),4);
        }
        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $dataList,
            'statusDict' => DictUtil::DepositOrder_Status,
            'names' => array_unique($names), // 余币宝名称
        ]);
    }

}
