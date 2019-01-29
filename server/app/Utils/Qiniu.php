<?php

namespace App\Utils;

class Qiniu {

    public static function getUrl($key) {
        return "http://file.ucai.net/{$key}";
    }

    //prefix，文件名是随机的，可以添加一个前缀区分，例如logo_
    public static function upload($localfile, $prefix = '') {
        if (!is_file($localfile) || filesize($localfile) < 100) {
            return ['code' => 101, 'msg' => 'not file'];
        }
        $auth = new \Qiniu\Auth(env('QINIU_AK'), env('QINIU_SK'));
        $bucket = 'bitcv';
        $token = $auth->uploadToken($bucket);
        $key = Service::genRandChars(15, true) . self::getPostfix($localfile);
        if ($prefix) {
            $key = "{$prefix}_{$key}";
        }
        $uploadMgr = new \Qiniu\Storage\UploadManager();
        list($ret, $err) = $uploadMgr->putFile($token, $key, $localfile);
        if ($err) {
            return ['code' => 102, 'msg' => 'upload err'];
        }
        return ['code' => 200, 'key' => $key];
    }

    //后缀，包含点号
    public static function getPostfix($file) {
        $p = strrpos($file, '.');
        if ($p) {
            $fix = substr($file, $p);
        } else {
            $fix = '';
        }
        return $fix;
    }

}