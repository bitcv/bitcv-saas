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
    public function invites($code, $address, $invite_uid) {
        $inviteInfo = $this->getByUid($invite_uid);
        if ($inviteInfo['address']) {
            return ['retcode'=>201, 'data'=>self::genInviteUrl($inviteInfo['id'])];
        }

        $hasBindAddress = $this->getByAddress($address);
        if ($hasBindAddress) {
            return ['retcode'=>201, 'data'=>self::genInviteUrl($hasBindAddress['id'])];
        }

        $url = $this->inviteDbOpt($code, $address, $invite_uid);
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
        $data['url'] = self::genInviteUrl($uid);
        $data['nums'] = $data['bcv_num'].' BCV<br>'.$data['doge_num'].' DOGE<br>'.$data['btc_num'].' BTC<br>'.$data['eth_num'].' ETH<br>'.$data['eos_num'].' EOS<br>'.$data['neo_num'].' NEO';
        return $data;
    }

    public function getTotalToken() {
        $data = (array)\DB::select('select sum(bcv_num) as totalbcv,sum(doge_num) as totaldoge,sum(btc_num) as totalbtc,sum(eth_num) as totaleth,sum(eos_num) as totaleos,sum(neo_num) as totalneo from '.self::$table);
        return (array)$data[0];
    }

    public function getUidByMobile($mobile, $fromid = 0) {
        $data = \DB::table(self::$table)->where('mobile', $mobile)->first();
        if ($data) {
            $data   = (array)$data;
            $uid    = $data['id'];
            $num    = $data['num'];
            $register_bcv_coin  = $total_bcv_num = $data['bcv_num'];
            $register_dog_coin  = $total_dog_num = $data['doge_num'];
            $register_btc_coin  = $total_btc_num = $data['btc_num'];
            $register_eth_coin  = $total_eth_num = $data['eth_num'];
            $register_eos_coin  = $total_eos_num = $data['eos_num'];
            $register_neo_coin  = $total_neo_num = $data['neo_num'];
        } else {
            $total_bcv_num = $register_bcv_coin   = rand(8, 18);
            $total_dog_num = $register_dog_coin   = 0;//rand(8, 18);
            $register_btc_coin  = $total_btc_num = 0;
            $register_eth_coin  = $total_eth_num = 0;
            $register_eos_coin  = $total_eos_num = 0;
            $register_neo_coin  = $total_neo_num = 0;

            $total = $this->getTotalToken();
            if ($total['totalbcv'] >= 200000) {
                $total_bcv_num = 0;
            }
            if ($total['totaldoge'] >= 1000000) {
                $total_dog_num = 0;
            }

            $data = array(
                'mobile'    => $mobile,
                'fromid'    => $fromid,
                'bcv_num'   => $register_bcv_coin,
                'doge_num'  => $register_dog_coin,
                'btc_num'  => $register_btc_coin,
                'eth_num'  => $register_eth_coin,
                'eos_num'  => $register_eos_coin,
                'neo_num'  => $register_neo_coin,
            );
            $uid = \DB::table(self::$table)->insertGetId($data);

            $inviteReward = new InviteReward();
            $coins = $data;
            unset($coins['mobile']);
            unset($coins['fromid']);
            $inviteReward->register($uid, $coins);

            if ($fromid) {
                $fromid_data = (array)$this->getByUid($fromid);
                if ($fromid_data['num'] < 10) {
                    //邀请发币（前期8-18，bcv为准，超过300就减少为5-10）
                    if ($fromid_data['bcv_num'] >= 300) {
                        $invite_bcv_coin    = 0;
                        $invite_dog_coin    = 0;
                    } else if ($fromid_data['bcv_num'] > 100) {
                        $invite_bcv_coin   = rand(5, 10);
                        $invite_dog_coin   = 0;//rand(5, 10);
                    } else {
                        $invite_bcv_coin   = rand(8, 18);
                        $invite_dog_coin   = 0;//rand(8, 18);
                    }

                    $inviteReward->invite($fromid, $uid, $invite_bcv_coin, $invite_dog_coin);

                    //操作邀请表
                    $sql = 'update '.self::$table.' set bcv_num=bcv_num+?, doge_num=doge_num+?, num=num+1 where id=?';
                    $invite_data = array(
                        'bcv_num'   => $invite_bcv_coin,
                        'doge_num'  => $invite_dog_coin,
                        'id'        => $fromid
                    );

                    \DB::update($sql, array_values($invite_data));

                }

            }

            $num = 0;
            Service::sms($mobile, 'Congratulations, you\'ve got '.$register_bcv_coin.' BCV,'.$register_dog_coin.' DOGE, click to view details http://t.cn/RRiadYN');
        }

        $url = self::genInviteUrl($uid);

        $nums = $total_bcv_num.' BCV<br>'.$total_dog_num.' DOGE<br>'.$total_btc_num.' BTC<br>'
            .$total_eth_num.' ETH<br>'.$total_eos_num.' EOS<br>'.$total_neo_num.' NEO';
        $ret_data = array(
            'retcode'   =>202,
            'data'      =>array(
                'num'               => $num,
                'total_bcv_num'     => $total_bcv_num,
                'total_doge_num'    => $total_dog_num,
                'bcv_num'           => $register_bcv_coin,
                'doge_num'          => $register_dog_coin,
                'btc_num'          => $register_btc_coin,
                'eth_num'          => $register_eth_coin,
                'eos_num'          => $register_eos_coin,
                'neo_num'          => $register_neo_coin,
                'url'               => $url,
                'uid'               => $uid,
                'nums'  => $nums,
            )
        );

        return $ret_data;
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
    public function inviteDbOpt($code, $address, $invite_uid) {
        $id = $this->upAddressById($address, $invite_uid);
        if (!$id) {
            return false;
        }

        if ($code) {
            $this->addNumById(self::decode($code));
            //奖励币(todo 这个是否需要写个事务)
            $bcv_coin   = rand(18, 58);
            $dog_coin   = rand(18, 58);
            $inviteReward = new InviteReward();
            $inviteReward->add(self::decode($code), $invite_uid, $bcv_coin, Constant::invite_reward_type_bcv);
            $inviteReward->add(self::decode($code), $invite_uid, $dog_coin, Constant::invite_reward_type_dog);
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
        //return $id ? self::encrypt($id) : '';
        return $id;
    }

    /**
     * id解密
     * @param $code
     * @return bool|string
     */
    public static function decode($code) {
        //return $code ? self::decrypt($code) : '';
        return $code;
    }
}