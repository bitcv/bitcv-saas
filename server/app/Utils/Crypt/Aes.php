<?php
namespace App\Utils\Crypt;

use \Exception;

/**
 * ***默认加密秘钥会定期更换，涉及到存储的时候，请不要使用默认秘钥***
 *
 * Aes加密算法为对称加密算法
 * 当不需要暴露密钥时，对数据的加密推荐使用此算法
 *
 * Aes加密采用块加密形式, 明文会被分割成16字节逐个进行加密。
 * 最后一块若不足16字节, 则会被补齐到16字节 默认使用PKCS#7的方式补齐
 *
 * 配置文件为/conf/security/../aes.php
 * 配置
 * <code>
 * 'common'  => array(
 *      'method'    => 'aes128',
 *      'password'  => 'jk23jbsdfi23hhj1',
 *      'iv'        => 'j12hj34jhg123khj',
 *      'options'   =>  0,
 * )
 * </code>
 * 说明
 * @method
 *      aes128 使用128位密钥进行加密(128位密钥已可以满足一般的加密需求)
 *      aes192 使用192位密钥进行加密
 *      aes256 使用256位密钥进行加密
 * @password 密钥
 *           aes加密算法使用128/192/256位密钥 长度越长 安全性越高 所需时间也越长
 *           当密钥长度不够时使用0进行补位，长度过长时则截取需要的位数使用
 * @iv       向量值
 *           参与加解密过程 长度固定为16字节
 * @options  加密的配置 对同一数据的加解密需要使用一样的配置
 *      0: 默认值 对密文使用base64进行编码后输出 方便存储加密后的数据
 *      OPENSSL_RAW_DATA: 密文使用原编码输出
 *      OPENSSL_ZERO_PADDING: 不采用默认的数据补齐方式 即需要由我们来实现数据的补齐 会对密文使用base64进行编码后输出
 */
class Aes implements CryptInterface {

    const ARRAY_PREFIX  = "`ARRAY`\n";
    const DEFAULT_KEY   = "common";

    /**
     * @param string $plain 明文  支持数组形式
     * @param string $key   指定加密配置 默认采用common的配置加密
     * @return string       密文
     * @throws Exception
     */
    public static function encrypt($plain, $key=self::DEFAULT_KEY) {
        $config = Config('aes.'.$key);
        if(!$config){
            throw new Exception("aes $key config not find");
        }

        if (is_array($plain)) {
            $plain = self::ARRAY_PREFIX.self::serialize($plain);
        }

        return openssl_encrypt($plain, $config['method'], $config['password'], $config['options'], $config['iv']);
    }

    /**
     * @param string $enplain  密文
     * @param string $key      指定解密的配置 默认采用common的配置解密
     * @return array|string    明文
     * @throws Exception
     */
    public static function decrypt($enplain, $key=self::DEFAULT_KEY) {

        $config = Config('aes.'.$key);
        if(!$config){
            throw new Exception("aes $key config not find");
        }

        $plain = openssl_decrypt($enplain, $config['method'], $config['password'], $config['options'], $config['iv']);
        if (strncmp(self::ARRAY_PREFIX, $plain, $len = strlen(self::ARRAY_PREFIX)) === 0) {
            return self::unserialize(substr($plain, $len));
        }
        return $plain;
    }

    /**
     * 序列化
     * @param array $data
     * @return string
     */
    protected static function serialize(array $data) {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 反序列化
     * @param $string
     * @return array
     */
    protected static function unserialize($string) {
        return json_decode($string, true);
    }

}