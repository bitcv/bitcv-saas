<?php
namespace App\Utils\Crypt;

use \Exception;

/**
 * Class Rsa
 * ***默认加密秘钥会定期更换，涉及到存储的时候，请不要使用默认秘钥***
 *
 * Rsa加密为非对称加密 拥有两个不同的密钥 其加密速度相比于对称加密慢几百倍
 * 在需要暴露数据的加密密钥时, 使用Rsa来对数据进行加密
 * Example：在前端使用公钥对用户的输入加密 防止直接以明文来进行传输 后端再使用私钥对密文解密
 *
 * 配置文件为/conf/security/../aes.php
 * 配置
 * <code>
 * 'common'  => array(
 *      'public_key'   => '',
 *      'private_key'  => ''
 * )
 * </code>
 * @public_key  公钥
 * @private_key 私钥
 * Rsa算法主流的密钥长度为1024位以上，1024位以下的密钥长度因安全问题不推荐使用(此密钥长度指Rsa算法中的模长度)
 * 公钥和私钥一一对应,长度可不等,一般私钥长度大于公钥
 *
 * 加密解密参数
 * @padding Rsa加解密时的填充模式,下面是几种常用的填充模式介绍
 *      OPENSSL_PKCS1_PADDING:  默认值 最常用的填充模式 一次加密中明文长度需要比模长度短至少11个字节
 *      OPENSSL_NO_PADDING：    不填充
 *      RSA_PKCS1_OAEP_PADDING: 一次加密中明文长度需要比模长度短至少41个字节
 * 对数据对加解密需要约定一样的填充方式
 */
class Rsa implements CryptInterface {

    const DEFAULT_KEY   = "common";
    /**
     * 加密
     * @param $plain
     * @param $key
     * @param int $padding
     *
     * @return string
     * @throws Exception
     */
    public static function encrypt($plain, $key=self::DEFAULT_KEY, $padding=OPENSSL_PKCS1_PADDING){
        $rsa_content = Config('rsa.'.$key);
        if (!$rsa_content) {
            throw new Exception("The rsa $key is not find");
        }

        $enplain = '';
        $errmsg = '';
        $public_key = openssl_pkey_get_public($rsa_content['public_key']);
        if (!openssl_public_encrypt($plain, $enplain, $public_key, $padding)) {
            while ($msg = openssl_error_string()) {
                $errmsg .= $msg. "\n";
            }
            throw new Exception($errmsg);
        }
        return $enplain;
    }

    /**
     * 解密
     * @param $enplain
     * @param $key
     * @param int $padding
     *
     * @return string
     * @throws Exception
     */
    public static function decrypt($enplain, $key=self::DEFAULT_KEY, $padding=OPENSSL_PKCS1_PADDING){
        $rsa_content = Config('rsa.'.$key);
        if (!$rsa_content) {
            throw new Exception("The rsa $key is not find");
        }

        $plain = '';
        $errmsg = "";
        $private_key = openssl_pkey_get_private($rsa_content['private_key']);
        if (!openssl_private_decrypt($enplain, $plain, $private_key, $padding)) {
            while ($msg = openssl_error_string()) {
                $errmsg .= $msg. "\n";
            }
            throw new Exception($errmsg);
        }
        return $plain;
    }

    /**s
     * 对js加密的内容解密
     * @param $enplain
     * @param $key
     * @param int $padding
     *
     * @return string
     * @throws Exception
     */
    public static function decryptForJs($enplain, $key=self::DEFAULT_KEY, $padding=OPENSSL_PKCS1_PADDING) {
        $enplain = pack("H*", $enplain);
        $plain = self::decrypt($enplain, $key);
        if ($padding == OPENSSL_NO_PADDING) {
            return rtrim(strrev($plain), "/0");
        } else {
            return urldecode($plain);
        }
    }

}