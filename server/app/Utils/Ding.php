<?php

namespace App\Utils;
use Illuminate\Support\Facades\Redis;

class Ding {

    const OAPI_HOST = 'https://oapi.dingtalk.com';
    const CORPID = 'dingf1e4f380bfc5370135c2f4657eb6378f';
    //const SECRET = '';
    const AGENTID = '163742958'; //公告 '163742957'; //日志

    protected static $messageType = array('text', 'image', 'voice', 'file', 'link', 'oa');


    //向单个或多个用户发送文本消息
    public static function sendMsg($touser, $msg) {
        if (is_array($touser)) {
            $touser = implode('|', $touser);
        }
        return self::message_send('text', array('content' => $msg), $touser);
    }

    /**
     * 发送企业会话消息
     * @param  string $touser     【可选】员工ID列表（消息接收者，多个接收者用’ | '分隔）。特殊情况：指定为@all，则向该企业应用的全部成员发送
     * @param  string $toparty    【可选】部门id列表，多个接收者用’ | '分隔。当touser为@all时忽略本参数 touser或者toparty 二者有一个必填
     * @param  string $msgtype    【必须】消息类型，可选值：text，image，voice，file，link，oa
     * @param  array  $msgContent 【必须】消息内容
     * @return [type]             [description]
     */
    public static function message_send($msgtype, array $msgContent, $touser='', $toparty=''){
        $msgtype = strtolower($msgtype);
        if(!in_array($msgtype, self::$messageType)){
            return array('errcode' => 34004, 'errmsg' => '无效的会话消息的类型',);
        }
        $data = array(
            'agentid'       =>  self::AGENTID,
            'toparty'       =>  $toparty,
            'touser'        =>  $touser,
            'msgtype'       =>  $msgtype
        );
        $data[$msgtype] = $msgContent;
        $res = BaseUtil::curlPost(self::OAPI_HOST.'/message/send?'.http_build_query(array('access_token' => self::getAccessToken())), $data);
        $res = json_decode($res, true);
        \Log::info('Ding$res'.var_export($res,true));
        return $res;
    }

    public static function getAccessToken() {
        $accessToken = Redis::get('ding_access_token');
        if (empty($accessToken)) {
            $response = BaseUtil::curlGet(self::OAPI_HOST.'/gettoken', array('corpid' => self::CORPID, 'corpsecret' => env('DING_SECRET')));
            $response = json_decode($response, true);
            $accessToken = $response['access_token'];
            if ($accessToken) {
                Redis::set('ding_access_token', $accessToken);
                Redis::expire('ding_access_token', 7200);
            }
        }
        return $accessToken;
    }

}