<?php

namespace App\Models;
use Service;
use Redis;

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
        if (!isset($data['id'])) {
            return [];
        }
        $data['url'] = self::genInviteUrl($uid);
        $data['nums'] = $this->getShowCoin($data, '<br>');
        $data['nums2'] = $this->getShowCoin($data, ',');
        return $data;
    }

    public function getTotalToken() {
        $data = (array)\DB::select('select sum(bcv_num) as totalbcv,
            sum(doge_num) as totaldoge,
            sum(btc_num) as totalbtc,
            sum(eth_num) as totaleth,
            sum(eos_num) as totaleos,
            sum(neo_num) as totalneo,
            sum(pxc_num) as totalpxc,
            sum(icst_num) as totalicst,
            sum(kcash_num) as totalkcash
            from '.self::$table);
        return (array)$data[0];
    }

    private function getShowCoin($data, $s = '<br>') {
        $types = ['bcv', 'doge', 'btc', 'eth', 'eos', 'neo', 'pxc', 'icst', 'kcash'];
        $str = '';
        foreach ($types as $t) {
            if (isset($data[$t.'_num']) && ($s=='<br>'||$data[$t.'_num']>0)) {
                $num = $data[$t.'_num'];
                if ($t == 'btc') {
                    $num = $num/10000;
                } elseif ($t == 'eth') {
                    $num = $num/10000;
                } elseif ($t == 'neo') {
                    $num = $num/1000;
                } elseif ($t == 'eos') {
                    $num = $num/100;
                }
                if ($s == '<br>') {
                    $str .= strtoupper($t).' '.$num.$s;
                } else {
                    $str .= $num.' '.strtoupper($t).$s;
                }
            }
        }
        return $str;
    }

    public function getUidByMobile($mobile, $fromid = 0, $vip = 0, $nation = 86) {
        $types = ['bcv', 'doge', 'btc', 'eth', 'eos', 'neo', 'pxc', 'icst', 'kcash'];
        $data = \DB::table(self::$table)->where('mobile', $mobile)->first();
        if ($data) {
            $data   = (array)$data;
            $uid    = $data['id'];
            $num    = $data['num'];
        } else {
            $total = $this->getTotalToken();
            if ($total['totalbcv'] >= 1300000) { //1,500,000
                $bcv_num = 0;
                $invite_bcv_num = 0;
            } else {
                $bcv_num   = rand(10, 12);
                $invite_bcv_num = rand(5, 10);
                if ($vip) {
                    $sendcount = Redis::incr('vip_count');
                    Redis::expire("vip_count", 86400*7);
                    if ($sendcount < 500) {
                        $bcv_num = rand(1,3) * 100 + 88;
                    }
                }
            }
            if ($total['totaldoge'] >= 1000000) { //1,000,000
                $doge_num = 0;
                $invite_doge_num = 0;
            } else {
                $doge_num   = date('m-d')=='02-16'?rand(80,120):0;
                $invite_doge_num = date('m-d')=='02-16'?rand(50,100):0;
            }
            if ($total['totalbtc'] >= 20000) { //2,0.0001
                $btc_num = 0;
                $invite_btc_num = 0;
            } else {
                $btc_num = 0;
                $invite_btc_num = 0;
            }
            if ($total['totaleth'] >= 100000) { //10,0.0001
                $eth_num = 0;
                $invite_eth_num = 0;
            } else {
                $eth_num = date('m-d')=='02-19'?rand(5,8):0;
                $invite_eth_num = date('m-d')=='02-19'?rand(3,6):0;
            }
            if ($total['totaleos'] >= 100000) { //1000,0.01
                $eos_num = 0;
                $invite_eos_num = 0;
            } else {
                $eos_num = date('m-d')=='02-17'?rand(3,5):0;
                $invite_eos_num = date('m-d')=='02-17'?rand(2,4):0;
            }
            if ($total['totalneo'] >= 50000) { //50,0.001
                $neo_num = 0;
                $invite_neo_num = 0;
            } else {
                $neo_num = date('m-d')=='02-18'?rand(2,4):0;
                $invite_neo_num = date('m-d')=='02-18'?rand(1,3):0;
            }

            if ($total['totalpxc'] >= 1000000) { //1,000,000
                $pxc_num = 0;
                $invite_pxc_num = 0;
            } else {
                $pxc_num = date('m-d')=='02-20'?rand(50,80):0;
                $invite_pxc_num = date('m-d')=='02-20'?rand(30,60):0;
            }
            if ($total['totalicst'] >= 250000) { //250,000
                $icst_num = 0;
                $invite_icst_num = 0;
            } else {
                $icst_num = date('m-d')=='02-20'?rand(10,20):0;
                $invite_icst_num = date('m-d')=='02-20'?rand(8,15):0;
            }
            if ($total['totalkcash'] >= 40000) { //40,000
                $kcash_num = 0;
                $invite_kcash_num = 0;
            } else {
                $kcash_num = date('m-d')=='02-20'?rand(2,3):0;
                $invite_kcash_num = date('m-d')=='02-20'?rand(1,2):0;
            }

            $data = array(
                'mobile'    => $mobile,
                'fromid'    => $fromid,
            );
            foreach ($types as $t) {
                $f = $t.'_num';
                $data[$f] = $$f;
            }
            $uid = \DB::table(self::$table)->insertGetId($data);

            $inviteReward = new InviteReward();
            $coins = $data;
            unset($coins['mobile']);
            unset($coins['fromid']);
            $inviteReward->register($uid, $coins);

            if ($fromid) {
                $fromid_data = (array)$this->getByUid($fromid);
                $invitekey = 'invite_count_'.date('md').$fromid;
                $invitecount = Redis::incr($invitekey);
                Redis::expire($invitekey, 86400);
                $invitelimit = date('m-d') == '02-20' ? 70 : 60;
                if (isset($fromid_data['num']) && $invitecount <= 10 && $fromid_data['num'] < $invitelimit) {
                    $invite = [];
                    foreach ($types as $t) {
                        $f = 'invite_'.$t.'_num';
                        $invite[$t.'_num'] = $$f;
                    }
                    $inviteReward->invite($fromid, $uid, $invite);

                    //操作邀请表
                    $sql = 'update '.self::$table.' set ';
                    foreach ($types as $t) {
                        $sql .= "{$t}_num={$t}_num+?,";
                    }
                    $sql .= 'num=num+1 where id=?';
                    $invite['id'] = $fromid;

                    \DB::update($sql, array_values($invite));

                    //短信
                    $msg = "[BitCV] You've got additional ";
                    $msg .= $this->getShowCoin($invite, ',');
                    $msg .= ' invite detail: http://t.cn/RRiadYN';
                    if (strlen($fromid_data['mobile']) == 11) {
                        Service::sms($fromid_data['mobile'], $msg);
                    }
                }
            }

            $num = 0;
            $msg = "[BitCV] Congratulations, you've got ";
            $msg .= $this->getShowCoin($data, ',');
            $msg .= ' detail: http://t.cn/RRiadYN';
            if (strlen($mobile) == 11) {
                Service::sms($mobile, $msg);
            }
        }

        $url = self::genInviteUrl($uid);

        $ret_data = array(
            'retcode'   => 202,
            'data'      => array(
                'num'               => $num,
                'url'               => $url,
                'uid'               => $uid,
                'nums'  => $this->getShowCoin($data),
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