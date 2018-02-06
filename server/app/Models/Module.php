<?php

namespace App\Models;

use DB;
use Storage;


class Module {

    private static $table = 'proj_mod';

    private static $mods = [
        1 => [
            'name' => 'invite',
            'table' => 'mod_invite_PROJID',
        ],
    ];

    public static function check($modname, $proj_id = 0) {
        if (!$proj_id && isset(app()->proj['proj_id'])) {
            $proj_id = app()->proj['proj_id'];
        }
        $mod_id = 0;
        foreach (self::$mods as $mid => $mod) {
            if ($mod['name'] == $modname) {
                $mod_id = $mid;
                break;
            }
        }
        if (!$proj_id || !$mod_id) {
            return false;
        }
        $ret = (array)DB::table(self::$table)->where([['proj_id',$proj_id],['mod_id',$mod_id]])->first();
        if (!$ret || $ret['valid'] != 1) {
            return false;
        }
        return true;
    }

    public function add($proj_id, $mod_id) {
        if (!$proj_id || !$mod_id) {
            return ['err' => 1, 'msg' => 'para error'];
        }
        $data = [
            'proj_id' => $proj_id,
            'mod_id' => $mod_id,
            'valid' => Constant::mod_valid_use,
        ];
        try {
            $ret = DB::table(self::$table)->insert($data);
        } catch (\Exception $e) {
            return ['err' => 2, 'msg' => 'add module error'];
        }
        $mtable = self::$mods[$mod_id]['table'];
        $sql = Storage::get("mtables/{$mtable}.sql");
        if (strlen($sql)) {
            $sql = str_replace('_PROJID', "_{$proj_id}", $sql);
            $ret = DB::statement($sql);
            if (!$ret) {
                return ['err' => 3, 'msg' => 'add table error'];
            }
        }
        return ['err' => 0];
    }

    public function getByProjId($proj_id) {
        $where = array(
            'proj_id'   => $proj_id
        );

        return DB::table(self::$table)->where($where)->get();
    }

    public function audit($proj_id, $valid) {
        $where  = array(
            'proj_id'   => $proj_id,
        );

        $data   = array(
            'valid'     => $valid
        );

        return DB::update(self::$table)->where($where)->update($data);
    }
}
