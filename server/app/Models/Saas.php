<?php

namespace App\Models;

use DB;

class Saas {

    private static $table = 'saas_proj';

    public function add($params = []) {
        $data = [];
        foreach ($params as $k => $v) {
            if (!empty($v)) {
                $data[$k] = $params[$k];
            }
        }
        $requried = ['org','username','email','mobile','subname'];
        foreach ($requried as $f) {
            if (!isset($data[$f]) || empty($data[$f])) {
                return false;
            }
        }

        $result = (array) DB::table('proj_info')->where('name_en',trim($data['subname']))->select('id')->first();
        if (isset($result) && $result) { // 如果 proj_info 表中存在，就获取 proj_id
            $data['proj_id'] = $result['id'];
        } else {
            $projinfo['name_en'] = $data['subname'];
            $projinfo['home_url'] = '';
            $id = DB::table('proj_info')->insertGetId($projinfo);
            $data['proj_id'] = $id;
        }
        $projid = \DB::table(self::$table)->insertGetId($data);
        return $projid;
        /*if ($projid) {
            $projinfo['name_en'] = $data['subname'];
            $projinfo['home_url'] = '';
            $id = DB::table('proj_info')->insertGetId($projinfo);
            return $id;
//            DB::table('project')->insert(['id'=>$projid]);
        }
//        return $projid;*/
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

    public function apply($proj_id) {
        $flag = [',','~','!','@','#','$','%','^','&','*','?'];
        $pro_info = (array)$this->getProById($proj_id);
        $data = [];

        if (!$pro_info['adminname']) {
            $data['adminname']  = $pro_info['subname'];
            $data['adminpass']  = $pro_info['subname'].$flag[rand(0, 10)].rand(1000, 9999);
        }

        $status = Constant::proj_status_pass;

        return $this->audit($proj_id, $status, $data);
    }

    public function refuse($proj_id) {
        return $this->audit($proj_id, Constant::proj_status_refuse);
    }

    public function audit($proj_id, $status, $params=[]) {
        $data   = array(
            'status'    => $status,
            'atime'     => date('Y-m-d H:i:s'),
        );

        $where  = array(
            'proj_id'   => $proj_id
        );

        return DB::table(self::$table)->where($where)->update(array_merge($data, $params));
    }

    public function getProById($id) {
        $where = array(
            'proj_id'   => $id
        );

        return (array)\DB::table(self::$table)->where($where)->first();
    }
}