<?php

namespace App\Models;

use DB;

class Saas {

    private static $table = 'saas_proj';

    public function add($subname, $domain, $data = []) {
        if (!$domain || !$subname) {
            return false;
        }

        $data['subname']    = $subname;
        $data['domain']     = $domain;

        return \DB::table(self::$table)->insertGetId($data);
    }

    public function getBySubname($subname) {
        $where = array(
            'subname'   => $subname,
            'status'    => Constant::proj_status_pass,
        );

        return (array)DB::table(self::$table)->where($where)->first();
    }

    public function getByDomain($domain) {
        $where = array(
            'domain'    => $domain,
            'status'    => Constant::proj_status_pass,
        );

        return (array)DB::table(self::$table)->where($where)->first();
    }

    public function getProj() {
        return DB::table(self::$table)->get();
    }

    public function audit($proj_id, $status) {
        $data   = array(
            'status'    => $status,
            'atime'     => date('Y-m-d H:i:s'),
        );

        $where  = array(
            'proj_id'   => $proj_id
        );

        return DB::table(self::$table)->where($where)->update($data);
    }
}