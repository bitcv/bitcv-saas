<?php

namespace App\Models;

/**
 * 邀请
 * Class Invite
 * @package App\Http\Models
 */
class Invite extends Base {

    private static $table = 't_invite';

    public function __construct() {
        self::$encrypt_keys = array(
            'mobile',
        );
    }

    /**
     * 邀请业务逻辑
     * @param $code
     * @param $address
     * @return bool|string
     */
    public function invites($code, $address, $mobile) {
        $hasBindResult = $this->getByMobile($mobile);
        if ($hasBindResult) {
            if ($hasBindResult['address'] != $address) {
                throw new \Exception('该手机号已绑定以太坊钱包', 500002);
            }

            return self::genInviteUrl($hasBindResult['id']);
        }

        $result = $this->getByAddress($address);
        if ($result && $result['mobile'] != $mobile) {
            throw new \Exception('该钱包已绑定手机号', 500003);
        }

        if ($result) {
            return self::genInviteUrl($result['id']);
        }

        return $this->inviteDbOpt($code, $address, $mobile);
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

        return \DB::table(self::$table)->insertGetId(self::encrypt($data));
    }

    public function getByMobile($mobile) {
        $data = \DB::table(self::$table)->where('mobile', self::encrypt($mobile))->first();
        if ($data) {
            $data = (array)$data;
            $data = self::decrypt($data);
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
        $data = (array)$data;

        return self::decrypt($data);
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
        return env('APP_URL').'/invite?code='.self::encode($id);
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