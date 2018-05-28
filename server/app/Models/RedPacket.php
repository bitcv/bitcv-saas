<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedPacket extends Model
{
    protected $table = 'base_packet';
    protected $guarded = [];

    const AVAILABLE_STATUS = 1;
    const FINISH_STATUS = 2;
    const EXPIRED_STATUS = 3;

    /**
     * 根据packetNumber获取红包信息
     * @param string $packetNumber
     * @return bool|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getPacketByNumber($packetNumber = '')
    {
        if($packetNumber == '')
            return false;
        $packetObj = self::where('packet_no','=',$packetNumber)->get();
        if($packetObj != false && count($packetObj) > 0){
            return $packetObj[0];
        }
        return false;
    }

    /**
     * 根据packetNumber获取红包信息
     * @param string $packetNumber
     * @return bool|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getPacketById($id = '')
    {
        if($id < 1)
            return false;
        $packetObj = self::where('id','=',$id)->get();
        if($packetObj != false && count($packetObj) > 0){
            return $packetObj[0];
        }
        return false;
    }

    /**
     * 生成红包ID
     * @param $user_id  用户ID
     * @return string
     */
    public static function genNewPacketNumber($user_id)
    {
        //把用户ID补充到7位
        $user_pad_key = str_pad($user_id, 7, 0, STR_PAD_LEFT);

        //从随机位开始采集5位字符
        $random_seed = md5(uniqid('bitcvredpacket', true).LARAVEL_START.$user_pad_key);
        $rand_start = intval(mt_rand(0, 20));
        $random_key = substr($random_seed, $rand_start, 12) . chr(rand(65, 90));

        $packetNumber = strtoupper('RP'.$random_key);
        $checker = self::getPacketByNumber($packetNumber);
        if ($checker != false && count($checker) > 0 ) {
            return self::genNewPacketNumber($user_id);
        }
        return $packetNumber;
    }

    /**
     * 计算当前打开红包金额
     * @param $packet
     * @param $packetMin
     * @return bool|string
     */
    public static function applyPacketAmount($packet, $packetMin)
    {
        if(empty($packet) || $packetMin <= 0){
            return false;
        }
        $packetDecimal = \App\Utils\Service::getFloatLength($packetMin);
        bcscale($packetDecimal);
        if($packet->type == 2){
            return bcdiv($packet->total_amount,$packet->packet_count);
        }
        $remainAmount = $packet->remain_amount;
        $remainCount = $packet->remain_count;
        if ($remainCount == 1) {
            $myAmount = $remainAmount;
        } else {
            $maxAmount = bcsub($remainAmount, bcmul(($remainCount - 1), $packetMin), $packetDecimal);
            $avgAmount = bcmul(bcdiv($remainAmount, $remainCount, $packetDecimal), 2, $packetDecimal);
            $randMax = min($maxAmount, $avgAmount);
            $rate = $remainCount == 2 ? 0.8 : 1;
            $myAmount = bcmul(mt_rand(1, bcmul(bcdiv($randMax, $packetMin), $rate)), $packetMin, $packetDecimal);
        }
        return $myAmount;
    }

    /**
     * @param $packetMin
     * @return int
     */
    public static function getPacketDecimal($packetMin)
    {
        $maxDecimal = '0.000000000000000001';
        $added = bcadd($packetMin,$maxDecimal,18);
        $addString = sprintf('%s',$added);
        $decimalStringExploded = explode('.',$addString);
        $decimal = 0;
        if(isset($decimalStringExploded[1])){
            $decimalString = $decimalStringExploded[1];
            if($decimalString == '000000000000000000'){
                return strlen($decimalString);
            }
            $len = strlen($decimalString);
            if($decimalString[$len - 1] > 1){
                return $len;
            }
            for($i = 0; $i <= $len - 2 ; $i++){
                if(intval($decimalString[$i]) > 0){
                    $decimal = $i + 1;
                }
            }
        }
        return $decimal;
    }
}
