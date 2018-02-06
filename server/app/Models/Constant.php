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
}