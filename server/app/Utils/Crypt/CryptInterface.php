<?php
namespace App\Utils\Crypt;

interface CryptInterface {
    /**
     * 统一加密
     * @param $plain
     * @param $key
     *
     * @return mixed
     */
    public static function encrypt($plain, $key);

    /**
     * 统一解密
     * @param $enplain
     * @param $key
     *
     * @return mixed
     */
    public static function decrypt($enplain, $key);
}