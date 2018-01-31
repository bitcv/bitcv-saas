<?php

namespace App\Models;

use App\Utils\Crypt\Aes;

class Base {

    protected static $db = array();

    /**
     * @var array 参与加解密字段
     */
    protected static $encrypt_keys = array();

    public static function encrypt($data) {
        $keys = static::$encrypt_keys ? static::$encrypt_keys : self::$encrypt_keys;

        if (!is_array($data)) {
            return base64_encode(Aes::encrypt($data, 'db'));
        }
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $key => $value) {
                    if (in_array(strtolower($key), $keys) && $value) {
                        $data[$k][$key] = base64_encode(Aes::encrypt($value, 'db'));
                    }
                }
            } else {
                if (in_array(strtolower($k), $keys) && $v) {
                    $data[$k] = base64_encode(Aes::encrypt($v, 'db'));
                }
            }
        }

        return $data;
    }

    /**
     * 对特定db内容解密
     *
     * @param array|string $data 加密内容 如果是字符串或者数字直接解密;如果是数组则递归解密, 最多2层
     *
     * @return array|string 解密后内容
     */
    public static function decrypt($data) {
        $keys = static::$encrypt_keys ? static::$encrypt_keys : self::$encrypt_keys;

        if (!is_array($data)) {
            if (8 > strlen($data)) {
                return $data;
            }

            return Aes::decrypt(base64_decode($data), 'db');
        }

        foreach ($data as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $key => $value) {
                    if (in_array(strtolower($key), $keys) && $value) {
                        $data[$k][$key] = Aes::decrypt(base64_decode($value), 'db');
                    }
                }
            } else {
                if (in_array(strtolower($k), $keys) && $v) {
                    $data[$k] = Aes::decrypt(base64_decode($v), 'db');
                }
            }
        }

        return $data;
    }
}