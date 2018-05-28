<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PacketRecord
 * @package App\Models
 */
class PacketRecord extends Model
{
    protected $table = 'base_packet_record';
    protected $guarded = [];

    const UNBOOKED_STATUS = 0;
    const BOOKED_STATUS = 1;

    /**
     * 根据微信ID查询用户领取记录
     * @param $wxUserId
     * @param $packetId
     * @return
     */
    public static function getRecordByWxUserId($wxUserId = 0, $packetId = 0)
    {
        if($wxUserId <= 0 || $packetId <= 0 ){
            return false;
        }
        $record = self::where([
            'wx_user_id'=> $wxUserId,
            'is_wx_user'=> 1,
            'packet_id' => $packetId
        ])->get()->toArray();
        return $record;
    }

    /**
     * 根据uid查询用户领取记录
     * @param $userId
     * @param $packetId
     * @return
     */
    public static function getRecordByUserId($userId = 0, $packetId = 0)
    {
        if($userId <= 0 || $packetId <= 0 ){
            return false;
        }
        $record = self::where([
            'wx_user_id'=>$userId,
            'is_wx_user'=>0,
            'packet_id' => $packetId
            ])->get()->toArray();
        return $record;
    }

    /**
     * 查询用户是否领取过
     * @param $userId
     * @param $wxUserId
     * @param $packetId
     *
     * @return bool
     */
    public static function hasUserGotThePacket($userId = 0,$wxUserId = 0, $packetId = 0)
    {
        if($userId <= 0 && $wxUserId <= 0 || $packetId <= 0 ){
            return false;
        }
        if($wxUserId <= 0){
            $wxUserObj = WxUser::getWxUserByUid($userId);
            $wxUserId = isset($wxUserObj->id) ? $wxUserObj->id : 0;
        }
        if($userId <= 0) {
            $userObj = User::getUserByWxUserId($wxUserId);
            $userId = isset($userObj->id) ? $userObj->id : 0;
        }
        $wxRecord = self::getRecordByWxUserId($wxUserId,$packetId);
        if($wxRecord != false && count($wxRecord) > 0){
            return true;
        }
        $userRecord = self::getRecordByUserId($userId,$packetId);
        if($userRecord != false && count($userRecord) > 0){
            return true;
        }
        return false;
    }


    /**
     * 获取用户红包领取总额
     * @param int $userId
     * @param int $packetId
     * @return bool|int|string
     */
    public static function getAmountOfPacketByUser($userId = 0, $packetId = 0)
    {
        if($userId <= 0 || $packetId <= 0 ){
            return false;
        }
        $recordList = self::getRecordByUserId($userId,$packetId);
        if(!$recordList){
            return 0;
        }
        $amount = 0;
        foreach ($recordList as $record){
            $amount = bcadd($amount,$record['amount'],18);
        }
        return $amount;
    }

    /**
     * 获取微信用户红包领取总额
     * @param int $userId
     * @param int $packetId
     * @return bool|int|string
     */
    public static function getAmountOfPacketByWxUser($wxUserId = 0, $packetId = 0)
    {
        if($wxUserId <= 0 || $packetId <= 0 ){
            return false;
        }
        $recordList = self::getRecordByWxUserId($wxUserId,$packetId);
        if(!$recordList){
            return 0;
        }
        $amount = 0;
        foreach ($recordList as $record){
            $amount = bcadd($amount,$record['amount'],18);
        }
        return $amount;
    }


    /**
     * 获取用户红包领取总金额
     * @param int $userId
     * @param int $wxUserId
     * @param int $packetId
     * @return int|string
     */
    public static function getTotalAmountOfPacketByUser($userId = 0, $wxUserId = 0, $packetId = 0)
    {
        if($userId <= 0 && $wxUserId <= 0 || $packetId <= 0 ){
            return 0;
        }
        $userAmount = self::getAmountOfPacketByUser($userId,$packetId);
        $wxAmount = self::getAmountOfPacketByWxUser($wxUserId,$packetId);
        return bcadd($userAmount,$wxAmount,18);
    }


    /**
     * 根据红包ID获取用户领取记录
     * @param int $userId
     * @param int $wxUserId
     * @param int $packetId
     * @return array|bool
     */
    public static function getTotalRecordByUser($userId = 0, $wxUserId = 0, $packetId = 0)
    {
        if($userId <= 0 && $wxUserId <= 0 || $packetId <= 0 ){
            return false;
        }
        $recordList = self::from('base_packet_record AS pr')
            ->join('base_packet as B', 'A.packet_id', '=', 'B.id')
            ->join('base_token as C', 'B.token_id', '=', 'C.id')
            ->join('base_user as D', 'B.user_id', '=', 'D.id')
            ->join('base_wx_user as E', 'D.wx_user_id', '=', 'E.id')
            ->select('B.token_id', 'C.symbol as token_symbol', 'C.price', 'A.packet_id', 'E.avatar_url', 'E.nickname', 'A.created_at as receive_time', 'A.amount')
            ->where(['wx_user_id'=>$userId, 'is_wx_user' => 0])
            ->orWhere(['wx_user_id' => $wxUserId, 'is_wx_user' => 1])
            ->orderBy('A.created_at', 'desc')
            ->get()->toArray();
        return $recordList;
    }

}
