<?php
/**
 * Created by PhpStorm.
 * User: LongBoQiang
 * Date: 2018/5/25
 * Time: 10:40
 */

namespace App\Service;

use App\Models\PacketRecord;
use App\Models\RedPacket;
use App\Models\User;
use App\Models\Token;
use Illuminate\Support\Facades\Redis;

class PacketStatService
{

    const STAT_DATA_KEY = 'PSDZ:';
    /**
     * 发送糖包的人数（排重）
     * @param string $beginDate
     * @param string $endDate
     * @param int $tokenId
     * @param int $type
     * @return int
     */
    public static function numsOfSendPacketMember($beginDate = '0000-00-00', $endDate = '0000-00-00', $tokenId = 0, $type = 0)
    {
        if(strtotime($beginDate) > strtotime($endDate)){
            return 0;
        }
        $begin = $beginDate;
        $end = $endDate .' 23:59:59';
        $num = RedPacket::where('created_at','>=',$begin)->where('created_at','<=',$end)
            ->where(function ($query) use ($tokenId,$type){
                if($tokenId > 0) {
                    $query->where('token_id',$tokenId);
                }
                if($type > 0) {
                    $query->where('type',$type);
                }
            })->selectRaw('count(distinct user_id) as num')
            ->get();
        return isset($num[0]->num) ? $num[0]->num : 0;
    }

    /**
     * 领取糖果的人数（排重）
     * @param string $beginDate
     * @param string $endDate
     * @param int $tokenId
     * @param int $type
     * @return int
     */
    public static function numsOfPickPacketMember($beginDate = '0000-00-00', $endDate = '0000-00-00', $tokenId = 0, $type = 0)
    {
        if(strtotime($beginDate) > strtotime($endDate)){
            return 0;
        }
        $begin = $beginDate;
        $end = $endDate .' 23:59:59';
        //微信领红包人数
        $result = PacketRecord::from('base_packet_record as pr')
            ->where('pr.created_at','>=',$begin)->where('pr.created_at','<=',$end)
            ->where('pr.is_wx_user',1)
            ->where(function ($query) use ($tokenId,$type){
                if($tokenId > 0) {
                    $query->leftJoin('base_packet as p','p.id','=','pr.packet_id')->where('p.token_id',$tokenId);
                }
                if($type > 0) {
                    $query->leftJoin('base_packet as p','p.id','=','pr.packet_id')->where('p.type',$type);
                }
            })
            ->select('pr.wx_user_id')
            ->distinct()
            ->get()->toArray();
        $wechatIds = array_column($result,'wx_user_id');
        $wechatNums = count($wechatIds);
        //UID领红包人数
        $result = PacketRecord::from('base_packet_record as pr')
            ->where('pr.created_at','>=',$begin)->where('pr.created_at','<=',$end)
            ->where('pr.is_wx_user',0)
            ->where(function ($query) use ($tokenId,$type){
                if($tokenId > 0) {
                    $query->leftJoin('base_packet as p','p.id','=','pr.packet_id')->where('p.token_id',$tokenId);
                }
                if($type > 0) {
                    $query->leftJoin('base_packet as p','p.id','=','pr.packet_id')->where('p.type',$type);
                }
            })
            ->select('pr.wx_user_id')
            ->distinct()
            ->get()->toArray();
        //去重微信ID与UID为同一人的数据
        $userIds = array_column($result,'wx_user_id');
        $userIdForWechatId = User::select('id','wx_user_id')->whereIn('wx_user_id',$wechatIds)->get()->toArray();
        $userIdAsKey = array_flip($userIds);
        foreach (array_column($userIdForWechatId,'id') as $dupUserId){
            unset($userIdAsKey[$dupUserId]);
        }
        $userNums = count($userIdAsKey);
        return $userNums + $wechatNums;
    }

    /**
     * 发送糖包总数
     * @param string $beginDate
     * @param string $endDate
     * @param int $tokenId
     * @param int $type
     * @return int
     */
    public static function numsOfPacketsSent($beginDate = '0000-00-00', $endDate = '0000-00-00', $tokenId = 0, $type = 0)
    {
        if(strtotime($beginDate) > strtotime($endDate)){
            return 0;
        }
        $begin = $beginDate;
        $end = $endDate .' 23:59:59';
        $num = RedPacket::where('created_at','>=',$begin)->where('created_at','<=',$end)
            ->where(function ($query) use ($tokenId,$type){
                if($tokenId > 0) {
                    $query->where('token_id',$tokenId);
                }
                if($type > 0) {
                    $query->where('type',$type);
                }
            })->count('id');
        return $num;
    }

    /**
     * 发送糖果个数
     * @param string $beginDate
     * @param string $endDate
     * @param int $tokenId
     * @param int $type
     * @return int
     */
    public static function amountOfCandySent($beginDate = '0000-00-00', $endDate = '0000-00-00', $tokenId = 0, $type = 0)
    {
        if(strtotime($beginDate) > strtotime($endDate)){
            return 0;
        }
        $begin = $beginDate;
        $end = $endDate .' 23:59:59';
        $amount = RedPacket::where('created_at','>=',$begin)->where('created_at','<=',$end)
            ->where(function ($query) use ($tokenId,$type){
                if($tokenId > 0) {
                    $query->where('token_id',$tokenId);
                }
                if($type > 0) {
                    $query->where('type',$type);
                }
            })->sum('total_amount');
        return $amount;
    }

    /**
     * 已经领取糖果个数
     * @param string $beginDate
     * @param string $endDate
     * @param int $tokenId
     * @param int $type
     * @return int
     */
    public static function amountOfCandyPicked($beginDate = '0000-00-00', $endDate = '0000-00-00', $tokenId = 0, $type = 0)
    {
        if(strtotime($beginDate) > strtotime($endDate)){
            return 0;
        }
        $begin = $beginDate;
        $end = $endDate .' 23:59:59';
        $amount = PacketRecord::from('base_packet_record as pr')
            ->where('pr.created_at','>=',$begin)->where('pr.created_at','<=',$end)
            ->where('pr.wx_user_id','>',0)
            ->where(function ($query) use ($tokenId,$type){
                if($tokenId > 0) {
                    $query->leftJoin('base_packet as p','p.id','=','pr.packet_id')->where('p.token_id',$tokenId);
                }
                if($type > 0) {
                    $query->leftJoin('base_packet as p','p.id','=','pr.packet_id')->where('p.type',$type);
                }
            })->sum('pr.amount');
        return $amount;
    }

    /**
     * 领取糖果的总人数
     * @param int $tokenId
     * @param int $type
     * @return int
     */
    public static function numsOfPickPacketMemberTotal($tokenId = 0, $type = 0)
    {
        $beginData = '0000-00-00';
        $endDate = date('Y-m-d H:i:s',time());
        return self::numsOfPickPacketMember($beginData,$endDate,$tokenId,$type);
    }

    /**
     * 发放token种类
     * @param string $beginDate
     * @param string $endDate
     * @return int
     */
    public static function kindsOfTokenSent($beginDate = '0000-00-00', $endDate = '0000-00-00')
    {
        if(strtotime($beginDate) > strtotime($endDate)){
            return 0;
        }
        $begin = $beginDate;
        $end = $endDate .' 23:59:59';
        $symbol = RedPacket::from('base_packet AS p')
            ->where('p.created_at','>=',$begin)
            ->leftJoin('base_token AS t','t.id','=','p.token_id')
            ->where('p.created_at','<=',$end)
            ->where('t.symbol','!=',null)
            ->select('t.symbol')
            ->groupBy('t.symbol')
            ->get()->toArray();
        if($symbol){
            return array_column($symbol,'symbol');
        }
        return [];
    }

    /**
     * 获取发放的token价值人民币金额
     * @param string $beginDate
     * @param string $endDate
     * @param int $tokenId
     * @return int|string
     */
    public static function getCnyValueOfTokenSent($beginDate = '0000-00-00', $endDate = '0000-00-00', $tokenId = 0)
    {
        if(strtotime($beginDate) > strtotime($endDate)){
            return 0;
        }
        $begin = $beginDate;
        $end = $endDate .' 23:59:59';
        $result = RedPacket::from('base_packet AS p')
            ->leftJoin('base_token AS t','t.id','=','p.token_id')
            ->select('p.total_amount','t.price')
            ->where('p.created_at','>=',$begin)->where('p.created_at','<=',$end)
            ->where(function ($query) use ($tokenId){
                if($tokenId > 0){
                    $query->where('p.token_id',$tokenId);
                }
            })
            ->get()->toArray();
        if(empty($result)){
            return 0;
        }
        $tokenValue = 0;
        bcscale(2);
        $cnyExchangeRate = Redis::get('USD_CNY_PRICE') ?: 6.3;
        foreach($result as $item){
            $tokenValue = bcadd($tokenValue,bcmul(bcmul($item['total_amount'],$item['price']),$cnyExchangeRate));
        }
        return $tokenValue;
    }

    /**
     * 获取统计数据按日返回
     * @param string $beginDate
     * @param string $endDate
     * @param int $tokenId
     * @return array
     */
    public static function getStatDataByDay($beginDate = '0000-00-00', $endDate = '0000-00-00', $tokenId = 0)
    {
        $beginStamp = strtotime($beginDate);
        $endStamp = strtotime($endDate);
        if($beginStamp > $endStamp){
            return [];
        }
        $redis = Redis::connection('stat');
        $statDataKey = self::STAT_DATA_KEY.$tokenId;
        $statData = $redis->zrevrangebyscore($statDataKey,$endStamp,$beginStamp,'WITHSCORES');
        $statData = array_flip($statData);
        $currentStamp = $endStamp;

        while ($currentStamp >= $beginStamp) {
            $currentDate = date('Y-m-d',$currentStamp);
            if(!isset($statData[$currentStamp])){
                $currentData = self::statDataSingleDay($currentDate,$tokenId);
                print_r($currentData);die;
                if(isset($currentData['amountOfCandySent']) && $currentData['amountOfCandySent'] > 0)
                $redis->zadd($statDataKey,$currentStamp,json_encode($currentData,true));
                $returnData[$currentDate] = $currentData;
            } else {
                $returnData[$currentDate] = json_decode($statData[$currentStamp],true);
            }
            $currentStamp -= 86400;
        }
        return $returnData;
    }

    /**
     * 获取某一天的统计数据
     * @param string $date
     * @param int $tokenId
     * @return array
     */
    public static function statDataSingleDay($date = '0000-00-00', $tokenId = 0)
    {
        $beginDate = $date;
        $endDate = date('Y-m-d',strtotime($beginDate) + 86400);
        $data = [
            'numsOfPickPacketMemberTotal' => PacketStatService::numsOfPickPacketMemberTotal($tokenId),
            'amountOfCandySent'           => PacketStatService::amountOfCandySent($beginDate,$endDate,$tokenId),
            'numsOfPacketsSent'           => PacketStatService::numsOfPacketsSent($beginDate,$endDate,$tokenId),
            'kindsOfTokenSent'            => PacketStatService::kindsOfTokenSent($beginDate,$endDate),
            'amountOfCandyPicked'         => PacketStatService::amountOfCandyPicked($beginDate,$endDate,$tokenId),
            'numsOfPickPacketMember'      => PacketStatService::numsOfPickPacketMember($beginDate,$endDate,$tokenId),
            'numsOfSendPacketMember'      => PacketStatService::numsOfSendPacketMember($beginDate,$endDate,$tokenId),
            'cnyValueOfTokenSent'         => PacketStatService::getCnyValueOfTokenSent($beginDate,$endDate,$tokenId),
            'date'                        => $date
        ];
        return $data;
    }
}