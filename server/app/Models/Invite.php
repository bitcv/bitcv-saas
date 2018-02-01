<?php

namespace App\Models;

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

    /**
     * 邀请业务逻辑
     * @param $code
     * @param $address
     * @return bool|string
     */
    public function invites($code, $address, $mobile) {
        $hasBindMobile = $this->getByMobile($mobile);
        if ($hasBindMobile) {
            return ['retcode'=>201, 'data'=>self::genInviteUrl($hasBindMobile['id'])];
        }
        $hasBindAddress = $this->getByAddress($address);
        if ($hasBindAddress) {
            return ['retcode'=>201, 'data'=>self::genInviteUrl($hasBindAddress['id'])];
        }

        $url = $this->inviteDbOpt($code, $address, $mobile);
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
    public function add($address, $mobile) {
        $data = array(
            'address'   => $address,
            'mobile'    => $mobile,
        );

        return \DB::table(self::$table)->insertGetId($data);
    }

    public function getByMobile($mobile) {
        $data = \DB::table(self::$table)->where('mobile', $mobile)->first();
        if ($data) {
            $data = (array)$data;
        }

        return $data;
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
        $id = $this->add($address, $mobile);
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