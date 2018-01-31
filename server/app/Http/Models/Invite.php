<?php

namespace App\Http\Models;

/**
 * 邀请
 * Class Invite
 * @package App\Http\Models
 */
class Invite {

    public function add($code) {
        $data = array(
            'address'  => $code,
        );

        return \DB::table('t_invite')->insertGetId($data);
    }
}