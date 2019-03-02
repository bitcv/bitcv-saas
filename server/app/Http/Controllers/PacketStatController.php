<?php

namespace App\Http\Controllers;

use App\Service\PacketStatService;
use App\Utils\Service;
use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\DB;
use App\Utils\DictUtil;
use App\Utils\BaseUtil;
use App\Models\OpenUser;

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
            'projId' => 'required|numeric',
            'tokenId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }

        extract($params);
        // 测试使用
//        $params['projId'] = 2;
        $depositBoxModel = Model\DepositBox::leftJoin('proj_info', 'depo_box.proj_id', '=', 'proj_info.id')
            ->leftJoin('depo_user_box', 'depo_box.id', '=', 'depo_user_box.deposit_box_id')
            ->leftJoin('base_user', 'depo_user_box.user_id', '=', 'base_user.id');
        $dataCount = $depositBoxModel->count();
        $dataList = $depositBoxModel
            ->select('depo_box.id', 'depo_box.proj_id', 'depo_box.total_amount', 'depo_box.min_amount', 'depo_box.remain_amount', 'depo_box.lock_time', 'depo_box.interest_rate', 'depo_box.status', 'proj_info.name_cn','base_user.mobile','depo_box.name','depo_user_box.amount')
            ->orderBy('depo_box.created_at', 'desc')
//            ->where('depo_box.proj_id', $params['projId'])
            ->where('depo_box.token_id', '=', $params['tokenId'])
            ->where('depo_box.status', '!=', 0)
            ->get()->toArray();
//        \Log::info('$dataList'.var_export($dataList, true));
        foreach ($dataList as $key => $value) {
            $dataList[$key]['totalAmount2'] = round($value['total_amount'],4);
            $dataList[$key]['minAmount2'] = round($value['min_amount'],4);
            $dataList[$key]['statusName'] = DictUtil::DepositBox_Status[$value['status']];
        }
        $ids = array_values(array_unique(array_column($dataList,'id')));
        $data = [];
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
                $temp[$key]['countm'] = count(array_unique(array_column($data[$key],'mobile')));
                $temp[$key]['totalorder'] = array_sum(array_column($data[$key], 'amount'));
            }
        }
        foreach ($temp as $key => $value) {
            $value['totalorder'] = array_key_exists('totalorder',$temp[$key]) ? $value['totalorder'] : 0 ;
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
            'tokenId' => 'required|numeric',
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

        $query = DB::table('depo_user_box');
        $query = $query->join('base_user', 'depo_user_box.user_id', '=', 'base_user.id');
        $query = $query->join('depo_box', 'depo_user_box.deposit_box_id', '=', 'depo_box.id');
        $query = $query->join('proj_info', 'depo_box.proj_id', '=', 'proj_info.id');
        $query = $query->join('base_user_wallet', 'depo_user_box.user_id', '=', 'base_user_wallet.user_id'); // 钱包地址
        $query = $query->select('depo_user_box.id', 'depo_user_box.deposit_box_id', 'depo_user_box.amount', 'depo_user_box.status', 'depo_user_box.created_at', 'depo_box.proj_id', 'depo_box.name', 'depo_box.total_amount', 'depo_box.min_amount', 'depo_box.remain_amount', 'depo_box.lock_time', 'depo_box.interest_rate', 'proj_info.name_cn', 'base_user.mobile', 'base_user_wallet.addr');
        if (array_key_exists('name',$allparams) && $allparams['name']) {
            $query = $query->where('depo_box.name','like','%'.$allparams['name'].'%');
        }
        $dataList = $query->orderBy('depo_user_box.created_at', 'desc')
//            ->where('depo_box.proj_id', $params['projId'])
            ->where('depo_box.token_id', '=', $params['tokenId'])
            ->where('base_user_wallet.token_protocol', '=', 1)
            ->offset($offset)->limit($perpage)
            ->get()->toArray();
        $dataCount = $query->count();
        foreach ($dataList as $key => $value) {
            $dataList[$key]->endTime = date('Y-m-d H:i:s',strtotime("{$value->created_at} +".$value->lock_time." day"));
            $dataList[$key]->endGet = round($value->amount * (1 + $value->interest_rate),4);
            $dataList[$key]->amount2 = round($value->amount,4);
        }
        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $dataList,
            'statusDict' => DictUtil::DespositUserBox_Status,
            'names' => array_unique(array_filter($names)), // 余币宝名称
        ]);
    }

    // aac 转盘统计
    /**
     * 统计使用，无索引
     *
     * @param Request $request
     */
    public  function  staCoin1(Request $request){
        $param  = $this->validation($request, [
            'date'   =>  'string',
            'coin'   => 'string',
            'pageno'   => 'int',
            'perpage'  => 'int',
            'isReal' => 'int'
        ]);
        $allparams = $request->all();
        $page  = intval($allparams['pageno']);
        $limit = intval($allparams['perpage']);
        $coin  = strval($allparams['coin']);
        $date =  isset($allparams['date'])?$allparams['date']:date("Ymd",time());
        $isReal  = intval($allparams['isReal']);
        if ($allparams['isReal'] == 2) {
            $isReal = 0;// aac $isReal = 1 是实物
        }
        $arr = array(
            'date' =>  $date,
            'coin' => $coin,
            'limit' => $limit,
            'page' => $page,
            'isReal' => $isReal,
        );

        $url = "https://openzp.bitcv.cn/api/apStaCoin1?".http_build_query($arr);
        //$url = "http://openzp.ucai.net//api/apStaCoin1?".http_build_query($arr);
        $retJson = BaseUtil::curlPost($url,array());
        $retArr =  json_decode($retJson,true);
        if (is_array($retArr['data']['list']) && count($retArr['data']['list']) > 0) {
            foreach ($retArr['data']['list'] as $k=> $v){
                $openUser = OpenUser::getUserInfoByOpenId($v['openid'],1);
                $retArr['data']['list'][$k]['mobile'] =  isset($openUser['mobile'])?$openUser['mobile']:'';
                $retArr['data']['list'][$k]['nickname'] =  isset($openUser['nickname'])?$openUser['nickname']:'';
                unset($retArr['data']['list'][$k]['uid']);
            }
        }

        return $this->output([
            'dataList' => $retArr['data']['list'],
            'dataCount' => $retArr['data']['count'],
        ]);

    }

    // 获取币的重量
    public function getTotalToken (Request $request)
    {
        $params  = $this->validation($request, [
            'tokenId'   =>  'int',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        $result = DB::table('base_user_asset')->select('amount')->where('token_id', '=', $params['tokenId'])->get()->toArray();
        $totalToken = round(array_sum(array_column($result,'amount')),5);
        return $this->output(['totalToken' => $totalToken]);
    }

    // 资产统计
    public function getAssetStat (Request $request)
    {

        $tokenId = $request->input('symbol');
        /*if ($params === false) {
            return $this->error(100);
        }
        $query = DB::table('base_user_asset as ua');
        $query = $query->leftjoin('base_token as bt', 'ua.token_id', '=', 'bt.id');
        $query = $query->where('bt.symbol', '=',$params['symbol']);
        $query = $query->select('ua.id', 'ua.token_id', 'ua.user_id', 'ua.amount', 'bt.symbol', 'bt.price', 'bt.price_cny');
        $dataList = $query->orderBy('ua.created_at', 'desc')
            ->get()->toArray();
        $tokens = array_values(array_unique(array_column($dataList, 'symbol')));
        // 汇总
        $middleTemp = $statData =  [];
        if ($dataList) {
            foreach ($dataList as $key => $list) {
                foreach ($tokens as $k => $token) {
                    if ($list->symbol == $token) {
                        $middleTemp[$k][] = $list;
                    }
                }
            }
            foreach ($middleTemp as $key => $middle) {
                $statData[$key]['symbol'] = $middle[0]->symbol;
                $statData[$key]['countPeople'] = count($middleTemp[$key]);
                $statData[$key]['amountTotal'] = round(array_sum(array_column($middleTemp[$key], 'amount')),6);
                $statData[$key]['priceTotal'] = round(($middle[0]->price_cny * $statData[$key]['amountTotal']), 6);
            }
        }*/

//        \DB::connection()->enableQueryLog(); // 开启查询日志
        $assetList = DB::table('base_user_asset')->where([['amount', '>', 0],['token_id', '=', $tokenId]])->select(\DB::raw('token_id, sum(amount) as amount, count(*) as count'))
            ->groupBy('token_id')->get()->toArray();
//        $queries = \DB::getQueryLog(); // 获取查询日志
//        print_r($queries);
//        \Log::info('$sql'.$queries[0]['query']);
        $tokenIdArr = array_column($assetList, 'token_id');
        $tokenDict = DB::table('base_token')->whereIn('id', $tokenIdArr)->get()->toArray();
//        \Log::info('$tokenDict'.var_export($tokenDict,true));
        foreach ($assetList as $key => $assetData) {
            foreach ($tokenDict as $token) {
                if ($assetData->token_id == $token->id) {
                    $assetList[$key]->symbol = $token->symbol;
                    $assetList[$key]->value = bcmul($assetData->amount, $token->price_cny, 4);
                }
            }
        }
        return $this->output([
            'dataList' => $assetList,
        ]);
    }

    // OTC 统计
    public function getOtcStatList (Request $request)
    {
        //获取请求参数
        $params = $this->validation($request, [
            'pageno' => 'required|numeric',
            'perpage' => 'required|numeric',
        ]);

        if ($params === false) {
            return $this->error(100);
        }

        $allparams = $request->all();
        $data = json_decode(BaseUtil::curlPost(env('OTCAPI').'otc/getOtcStatList', [
            'pageno' => $params['pageno'],
            'perpage' => $params['perpage'],
            'sdate' => array_key_exists('sdate',$allparams) && $allparams['sdate'] ? $allparams['sdate'] : '',
            'edate' => array_key_exists('edate',$allparams) && $allparams['edate'] ? $allparams['edate'] : '',
            'symbol' => array_key_exists('symbol',$allparams) && $allparams['symbol'] ? $allparams['symbol'] : '',
        ]), true);
        $resultData = [];
        if ($data['data']['statData']) {
            if ($params['pageno'] == 1) {
                $resultData = array_slice($data['data']['statData'],$params['pageno'] - 1, $params['perpage']);
            } else {
                $offset = $params['perpage'] * ($params['pageno'] - 1);
                $resultData = array_slice($data['data']['statData'],$offset, $params['perpage']);
            }
        }

        return $this->output([
            'statData' => $resultData,
            'totalCount' => $data['data']['dataCount'],
            'sumData' => $data['sumData'],
        ]);
    }


    // 币币兑换功能统计
    public function getExchangeRecords(Request $request)
    {
        $params = $this->validation($request, [
            'pageno' => 'required|numeric',
            'perpage' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        $allparams = $request->all();
        $data = json_decode(BaseUtil::curlPost(env('OTCAPI').'bb/getExchangeRecords', [
            'pageNo' => $params['pageno'],
            'perPage' => $params['perpage'],
            'symbol' => $allparams['symbol'],
//            'symbol' => 'ABCB',
        ]), true);
        return $this->output($data);
    }

    public function getExchangeStatData(Request $request)
    {
        $params = $this->validation($request, [
            'id' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }

        $data = json_decode(BaseUtil::curlPost(env('OTCAPI').'bb/getExchangeStatData', [
            'id' => $params['id'],
        ]), true);

        return $this->output($data);
    }

    // 充值记录
    public function getRechargeRecord(Request $request)
    {
        $params = $this->validation($request, [
            'tokenId' => 'required',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        $data = json_decode(BaseUtil::curlPost('https://bitcv.com/api/getRechargeRecords', [
            'tokenId' => $params['tokenId'],
            'passwd' => md5('bitcv_saas_0301')
        ]), true);
        return $this->output($data);
    }

    // 我的兑换资产
    public function getMyExchange (Request $request)
    {
        $params = $this->validation($request, [
            'id' => 'required',
        ]);
        if ($params === false) {
            return $this->error(100);
        }

        $data = json_decode(BaseUtil::curlPost(env('OTCAPI').'bb/getMyExchange', [
            'id' => $params['id'],
        ]), true);

        return $this->output($data);
    }

    // abcb 资产快照
    public function getAssetSnapShot(Request $request)
    {
        $params = $this->validation($request, [
            'tokenId' => 'required',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        $allparams = $request->all();
//        $params['tokenId'] = 496; // 测试
        $airDropDetail = [];
        $result = DB::table('base_airdrop')->where([['asset_token_id', '=', $params['tokenId']]])->get()->toArray();
        if (isset($allparams['airdropId']) && $allparams['airdropId']) {
            $airDropDetail = DB::table('base_airdrop_asset')->where([['airdrop_id', '=', $allparams['airdropId']],['token_id', '=', $params['tokenId']]])->orderBy('amount', 'desc')->get()->toArray();
            if ($airDropDetail) {
                $status = ['未领取', '已领取'];
                foreach ($airDropDetail as $key => $detail) {
                    foreach ($status as $k => $s) {
                        if ($detail->status == $k) {
                            $airDropDetail[$key]->statusName = $s;
                        }
                    }
                }
            }
        }
        return $this->output([
           'airDrop' => $result,
           'airDropDetail' => $airDropDetail
        ]);
    }
}
