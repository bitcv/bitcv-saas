<?php

namespace App\Models;

class Constant {

    const proj_status_default   = 0;
    const proj_status_pass      = 1;
    const proj_status_refuse    = 2;

    static $proj_status_ch = array(
        self::proj_status_default   => '待审核',
        self::proj_status_pass      => '审核通过',
        self::proj_status_refuse    => '审核拒绝',
    );

    const mod_valid_use         = 1;
    const mod_valid_unuse       = 2;

    static $mode_valid_ch   = array(
        self::mod_valid_use     => '允许使用',
        self::mod_valid_unuse   => '禁止使用',
    );

    //1 invite 2 代发
    const mod_id_invite         = 1;
    const mod_id_daifa          = 2;
    const mod_id_invite_reward  = 3;

    static $mod_id_ch           = array(
        self::mod_id_invite         => 'invite',
        self::mod_id_daifa          => '代发',
        self::mod_id_invite_reward  => '奖励',
    );

  /*  const invite_reward_type_bcv   = 1;
    const invite_reward_type_dog   = 2;

    static $invite_reward_type  = array(
        self::invite_reward_type_bcv    => 'BCV币',
        self::invite_reward_type_dog    => '狗狗币',
    );*/

    const invite_reward_action_register = 1;
    const invite_reward_action_invite   = 2;

    static $invite_reward_action   = array(
        self::invite_reward_action_register => '注册',
        self::invite_reward_action_invite   => '邀请',
    );
}