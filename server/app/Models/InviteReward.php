<?php

namespace App\Models;

/**
 * 邀请奖励
 * Class InviteReward
 * @package App\Http\Models
 */
class InviteReward extends Base {

    private static $table;

    public function __construct() {
        self::$table = 'mod_invite_reward_'.app()->proj['proj_id'];
    }

    public function getAll() {
        return \DB::table(self::$table)->get()->all();
    }

    public function invite($uid, $invite_uid, $bcv_num, $doge_num) {
        $data = array(
            'uid'           => $uid,
            'invite_uid'    => $invite_uid,
            'bcv_num'       => $bcv_num,
            'doge_num'      => $doge_num,
            'action'        => Constant::invite_reward_action_invite,
            'ctime'         => date('Y-m-d H:i:s', time())
        );

        return \DB::table(self::$table)->insertGetId($data);
    }

    public function register($uid, $data) {
        $data['uid'] = $uid;
        $data['action'] = Constant::invite_reward_action_register;
        $data['ctime'] = date('Y-m-d H:i:s', time());

        return \DB::table(self::$table)->insertGetId($data);
    }
}