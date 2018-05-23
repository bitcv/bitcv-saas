<?php

namespace App\Utils;

use Request;
use Redis;
use Validator;

class Service {

    //调试日志和系统日志分开
    private static $loggers = array();
    public static function log($msg, $file = 'debug') {
        if (empty(self::$loggers[$file])) {
            self::$loggers[$file] = new \Illuminate\Log\Writer(new \Monolog\Logger($file));
            self::$loggers[$file]->useFiles(storage_path()."/logs/{$file}.log");
        }
        return self::$loggers[$file]->info($msg);
        //不能改写默认日志，会同时写2个文件
        //Log::useFiles(storage_path()."/logs/{$file}.log");
    }

    //中美手机号格式校验
    public static function checkMobile($mobile, $nation = 86) {
        $nation = strlen($mobile) == 11 ? 86 : 1;
        $data = ['mobile' => $mobile];
        $reg = $nation == 86 ? 'regex:/^1[35789]\d{9}$/' : 'regex:/^\d{8,10}$/';
        $v = Validator::make($data, ['mobile' => $reg]);
        if ($v->fails()) {
            return false;
        }
        return true;
    }

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
if ($vcode == env('SMS_TEST_VCODE')) {
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
        preg_match('/\<code\>(\d+)\<\/code\>/', $ret, $matches);
        $code = $matches[1];
        if ($code == '01' || $code == '03') {
            Redis::incr($key);
            Redis::expire($key, 60);
            return array('err' => 0);
        } else {
            return array('err' => 2, 'msg' => 'send sms failed', 'data' => $code);
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

    public static function curlRequest($url, $method = 'get', $data = array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        } else if ($method == 'get') {
            if (is_array($data) && $data != null) {
                $url = $url . '?' . http_build_query($data);
            }
            curl_setopt($ch, CURLOPT_URL, $url);
        }
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }
        curl_close($ch);
        return $result;
    }

    //驼峰转下划线
    public static function lineToHump($str) {
        $str = preg_replace_callback('/([A-Z]{1})/',function($matches){
            return '_'.strtolower($matches[0]);
        }, $str);
        return $str;
    }

    //获取加密密码
    public static function getPwd($pass) {
        return password_hash($pass.env('PASS_SALT'), PASSWORD_DEFAULT);
    }
    //校验密码
    public static function checkPwd($pass, $hash) {
        return password_verify($pass.env('PASS_SALT'), $hash);
    }


}