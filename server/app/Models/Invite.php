<?php

namespace App\Models;
use Service;

/**
 * 邀请
 * Class Invite
 * @package App\Http\Models
 */
class Invite extends Base {

    private static $table;

    public function __construct() {
        self::$table = 'mod_invite_'.app()->proj['proj_id'];
    }

    public function getAll() {
        return \DB::table(self::$table)->get()->all();
    }

    public function getTotal($num) {
        return 50 + $num * 10;
    }

    /**
     * 邀请业务逻辑
     * @param $code
     * @param $address
     * @return bool|string
     */
    public function invites($code, $address, $uid) {
        $inviteInfo = $this->getByUid($uid);
        if ($inviteInfo['address']) {
            return ['retcode'=>201, 'data'=>self::genInviteUrl($inviteInfo['id'])];
        }

        $hasBindAddress = $this->getByAddress($address);
        if ($hasBindAddress) {
            return ['retcode'=>201, 'data'=>self::genInviteUrl($hasBindAddress['id'])];
        }

        $url = $this->inviteDbOpt($code, $address, $uid);
        if (!$url) {
            return ['retcode'=>2, 'msg' => '保存地址失败'];
        }

        return ['retcode'=>200, 'data'=>$url];
    }

    /**
     * 添加钱包地址
     * @param $address
     * @return mixed
     */
    public function upAddressById($address, $uid) {
        $data = array(
            'address'   => $address,
        );

        $where = array(
            'id'    => $uid,
        );

        return \DB::table(self::$table)->where($where)->update($data);
    }

    public function getByUid($uid) {
        $data = (array)\DB::table(self::$table)->where('id', $uid)->first();
        $data['num'] = $data['num'];
        $data['total'] = $this->getTotal($data['num']);
        $data['url'] = self::genInviteUrl($uid);
        return $data;
    }

    public function getUidByMobile($mobile, $fromid = 0) {
        $data = \DB::table(self::$table)->where('mobile', $mobile)->first();
        if ($data) {
            $data   = (array)$data;
            $uid    = $data['id'];
            $num = $data['num'];
        } else {
            $data = array(
                'mobile'    => $mobile,
                'fromid'    => $fromid,
            );
            $uid = \DB::table(self::$table)->insertGetId($data);
            $num = 0;
            Service::sms($mobile, '恭喜您获得50BCV，详情 http://t.cn/');
        }

        $total = $this->getTotal($num);
        $url = self::genInviteUrl($uid);

        return ['retcode'=>202, 'data'=>['num'=>$num,'total'=>$total,'url'=>$url, 'uid'=>$uid]];
    }

    /**
     * 获取用户数据
     * @param $address
     * @return mixed
     */
    public function getByAddress($address) {
        $data = \DB::table(self::$table)->where('address', $address)->first();
        return (array)$data;
    }

    /**
     * 增加邀请人数
     * @param $id
     */
    public function addNumById($id) {
        return \DB::table(self::$table)->where('id', $id)->increment('num');
    }

    /**
     * 邀请好友的db操作
     * @param $code
     * @param $address
     * @return bool|string
     */
    public function inviteDbOpt($code, $address, $mobile) {
        $id = $this->upAddressById($address, $mobile);
        if (!$id) {
            return false;
        }

        if ($code) {
            $this->addNumById(self::decode($code));
        }

        return self::genInviteUrl($id);
    }

    /**
     * 获取邀请链接
     * @param $id
     * @return string
     */
    public static function genInviteUrl($id) {
        return route('invite').'?code='.self::encode($id);
    }

    /**
     * id加密
     * @param $id
     * @return string
     */
    public static function encode($id) {
        return urlencode(base64_encode($id));
    }

    /**
     * id解密
     * @param $code
     * @return bool|string
     */
    public static function decode($code) {
        return base64_decode(urldecode($code));
    }
}