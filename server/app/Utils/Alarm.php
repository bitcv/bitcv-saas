<?php

namespace App\Utils;

/*
 * 使用示例:
 \App\Utils\Alarm::send('500严重，马上检查服务器', 'zhihua', \App\Utils\Alarm::LEVEL_ERROR);
 \App\Utils\Alarm::send('数据错误，请相关开发人员查询');
 \App\Utils\Alarm::send('大额取款，请相关人员及时审核', 'service');
 */

class Alarm {

    const LEVEL_WARN = 1; //只发钉钉消息
    const LEVEL_ERROR = 2; //同时发短信

    const USERS = [
//        'wuxing' => ['mobile'=>'', 'ding'=>'08136943653490'],
//        'zhihua' => ['mobile'=>'13810055038', 'ding'=>'016327561820978287'],
//        'daqing' => ['mobile'=>'', 'ding'=>'060215312533339402'],
        'xiaofei' => ['mobile'=>'', 'ding'=>'060834091724171983'],
//        'haoyang' => ['mobile'=>'', 'ding'=>'026712071521092570'],
//        'douhuan' => ['mobile'=>'18500335694', 'ding'=>'051818195838207495'],
//        'ruanying' => ['mobile'=>'', 'ding'=>''],
//        'baojin' => ['mobile'=>'', 'ding'=>''],
    ];
    const GROUPS = [
        'dev' => ['xiaofei'],
//        'service' => ['ruanying', 'baojin'],
    ];
    
    //user 用户或分组
    public static function send($msg, $user = 'dev', $level = Alarm::LEVEL_WARN) {
        if (isset(self::USERS[$user])) {
            $ding = self::USERS[$user]['ding'];
            $mobile = self::USERS[$user]['mobile'];
        } elseif (isset(self::GROUPS[$user])) {
            $ding = array();
            $mobile = array();
            foreach (self::GROUPS[$user] as $u) {
                if (self::USERS[$u]['ding']) {
                    $ding[] = self::USERS[$u]['ding'];
                }
                if (self::USERS[$u]['mobile']) {
                    $mobile[] = self::USERS[$u]['mobile'];
                }
            }
            $mobile = implode(',', $mobile);
        } else {
            Service::log("error user: {$user}, msg: {$msg}", 'alarm');
            return false;
        }
        //发钉钉消息
        $ret = Ding::sendMsg($ding, $msg);
        //发短消息
        if ($level == self::LEVEL_ERROR && $mobile) {
            $ret = Service::smsGuodu($mobile, $msg);
        }
        return true;
    }

}