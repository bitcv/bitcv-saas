<?php

namespace App\Models;

/**
 * 邀请
 * Class Invite
 * @package App\Http\Models
 */
class Invite {

    private static $table = 't_invite';
    /**
     * 邀请业务逻辑
     * @param $code
     * @param $address
     * @return bool|string
     */
    public function invites($code, $address) {
        $result = $this->getByAddress($address);
        if ($result) {
            return self::genInviteUrl($result->id);
        }

        return $this->inviteDbOpt($code, $address);
    }

    /**
     * 添加钱包地址
     * @param $address
     * @return mixed
     */
    public function add($address) {
        $data = array(
            'address'  => $address,
        );

        return \DB::table(self::$table)->insertGetId($data);
    }

    /**
     * 获取用户数据
     * @param $address
     * @return mixed
     */
    public function getByAddress($address) {
        return \DB::table(self::$table)->where('address', $address)->first();
    }

    /**
     * 增加邀请人数
     * @param $id
     */
    public function addNumById($id) {
        return \DB::table(self::$table)->where('id', $id)->update(array('num'=>1));
    }

    /**
     * 邀请好友的db操作
     * @param $code
     * @param $address
     * @return bool|string
     */
    public function inviteDbOpt($code, $address) {
        $id = $this->add($address);
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
        return $_REQUEST['HTTP_HOST'].'/invite?code='.self::encode($id);
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