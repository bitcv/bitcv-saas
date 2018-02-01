<?php

namespace App\Models;

use DB;


class Saas {

    private static $table = 'saas_proj';

    public function add($proj_id, $subname, $data = []) {
        if (!$proj_id || !$subname) {
            return false;
        }
        $data['proj_id'] = $proj_id;
        $data['subname'] = $subname;
        $ret = DB::table(self::$table)->insert($data);
        return $ret;
    }

    public function getBySubname($subname) {
        return (array)DB::table(self::$table)->where('subname', $subname)->first();
    }

    public function getByDomain($domain) {
        return (array)DB::table(self::$table)->where('domain', $domain)->first();
    }

}