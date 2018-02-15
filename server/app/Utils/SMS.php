<?php

namespace App\Utils;

use Qcloud\Sms\SmsSingleSender;
use Redis;
use Request;
use Service;

class SMS {
    
    //单发短信
    public static function sendSingle($nation, $mobile, $tplId, $params = []) {
        $ip = Request::getClientIp();
        Service::log("{$mobile}\t{$ip}\t{$tplId}", 'sms');
        if (!Service::checkMobile($mobile)) {
            return false;
        }
        try {
            $sender = new SmsSingleSender(config('services.qcloudsms.sdkappid'), config('services.qcloudsms.appkey'));
            $result = $sender->sendWithParam($nation, $mobile, $tplId, $params, "", "", "");
            $rsp = json_decode($result, true);
            if ($rsp['result'] == 0) {
                return true;
            } else {
                Service::log("sms error: ".$result, 'sms');
                return false;
            }
        } catch(\Exception $e) {
            return false;
        }
    }
    //群发，一次最多200
    public static function sendMulti($nation, $mobiles, $tplId, $params = []) {
        try {
            $phoneNumbers = [$phoneNumber1, $phoneNumber2, $phoneNumber3];
            $sender = new SmsMultiSender(config('keys.sdkappid'), config('keys.sms_appkey'));
            $params = ["指定模板群发", "深圳", "小明"];
            $result = $sender->sendWithParam($nation, $mobiles, $tplId, $params, "", "", "");
            $rsp = json_decode($result, true);
            return $rsp['result'] == 0 ? true : false;
        } catch(\Exception $e) {
            echo var_dump($e);
            return false;
        }
    }

    //您的验证码为{1}，请在{2}分钟内输入。
    public static function sendVcode($mobile, $vcode) {
        $nation = strlen($mobile) == 10 ? 1 : 863;
        $ip = Request::getClientIp();
        $num = Redis::get("sms_vcode_{$ip}");
        if ($num > 10) { //每ip每分钟只能发10条
            return array('err' => 1, 'msg' => 'too many sms');
        }
        if ($nation == 86) {
            $result = Service::sms($mobile, '【BitCV】Your validation code is '.$vcode.', please input in 5 minutes.');
            $ret = $result['err'] > 0 ? false : true;
        } else {
            $ret = self::sendSingle($nation, $mobile, $nation==86?88164:88162, [$vcode, 5]);
        }
        if ($ret) {
            Redis::incr("sms_vcode_{$ip}");
            Redis::expire("sms_vcode_{$ip}", 60);
            return array('err' => 0);
        } else {
            return array('err' => 2, 'msg' => 'send vcode failed');
        }
    }

}