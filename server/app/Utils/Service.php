<?php

namespace App\Utils;

use Request;
use Redis;

class Service {

    //生成随机码
    public static function genRandChars($len = 6) { 
        //$chars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ023456789'; 
        $chars = '0123456789'; 
        $password = ''; 
        for ($i = 0; $i < $len; $i++ ) { 
            $password .= $chars[mt_rand(0, strlen($chars) - 1)]; 
        } 
        return $password; 
    }

    //生成验证码
    public static function getVcode($type, $id) {
        if (empty($id)) {
            return array('err' => 1, 'msg' => 'no id');
        }
        if ($type == 'reg') {
            $vcode = self::genRandChars(6);
            Redis::set("{$type}_{$id}", $vcode);
            Redis::expire("{$type}_{$id}", 600);
            return array('err' => 0, 'data' => $vcode);
        } else {
            return array('err' => 1, 'msg' => 'invalid vcode type');
        }
    }
    //校验验证码
    public static function checkVCode($type, $id, $vcode) {
        if (!($type && $id && $vcode)) {
            return array('err' => 1, 'msg' => 'no vcode');
        }
//for test 上线删除
if ($vcode == '424242') {
    return array('err' => 0);
}
        if (0 != strcasecmp($vcode, Redis::get("{$type}_{$id}"))) {
            return array('err' => 2, 'msg' => 'vilidation code is invalid');
        }
        return array('err' => 0);
    }

    public static function sms($mobile, $msg) {
        $ip = Request::getClientIp();
        $key = "saas_vcode_{$ip}";
        $num = Redis::get($key);
        if ($num > 10) { //每ip每分钟只能发10条
            return array('err' => 1, 'msg' => 'too many sms');
        }
        $ret = self::smsGuodu($mobile, $msg);
        if ($ret) {
            Redis::incr($key);
            Redis::expire($key, 60);
            return array('err' => 0);
        } else {
            return array('err' => 2, 'msg' => 'send sms failed');
        }
    }

    public static function smsGuodu($mobile, $msg) {
        $msg = iconv("UTF-8","GB2312//IGNORE", $msg);
        
        $param['OperID'] = config('services.sms.id');
        $param['OperPass'] = config('services.sms.pass');
        $param['SendTime'] = '';
        $param['ValidTime'] = '';
        $param['AppendID'] = '';
        $param['DesMobile'] = $mobile;
        $param['Content'] =  '[MSG]';
        $param['ContentType'] = 15;
        $api = 'http://qxtsms.guodulink.net:8000/QxtSms/QxtFirewall?'.urldecode(http_build_query($param));
        $api = str_replace('[MSG]',  urlencode($msg) , $api);
        $res = file_get_contents($api);

        return $res;
    }

}