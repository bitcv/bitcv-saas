<?php
namespace App\Utils\Crypt;

use \Exception;

/**
 * Class Des
 * ***默认加密秘钥会定期更换，涉及到存储的时候，请不要使用默认秘钥***
 *
 * Des加密算法为对称加密算法
 * DES加密已被破解 对安全性需求高的请使用aes加密
 *
 * 配置文件为/conf/security/../des.php
 * 配置
 * <code>
 * 'common'  => array(
 *      'method'    => 'DES-ECB',
 *      'password'  => '12asld12lkjsdf1s',
 *      'options'   => 0,
 * )
 * </code>
 * 说明
 * @method
 *      DES-ECB 直接使用密钥对明文进行加密 加密程度弱 加密速度快
 * @password 密钥
 *           DES加密方法使用64位密钥 其中后8位为校验位 实际有效密钥长度为56位
 *           当密钥超过64位时 截取前64位,少于64位时用0补位
 * @options  加密的配置 对同一数据的加解密需要使用一样的配置
 *      0: 默认值 对密文使用base64进行编码后输出 方便存储加密后的数据
 *      OPENSSL_RAW_DATA: 密文使用原编码输出
 *      OPENSSL_ZERO_PADDING: 数据补齐方式 待加密数据按8字节分组，最后一组不足8字节数据右补0x00至8字节
 *
 * 以下的Des算法实现时默认使用pkcs5方法来对明文数据进行补齐
 * 即待加密数据按8字节分组，最后一组不足8字数据按补位长度分别右补1-7个字节的相应数据
 * 加解密时都需要使用pkcs5方法来处理明文/密文数据
 */
class Des implements CryptInterface{

	const DEFAULT_KEY   = "common";

	public static function encrypt($plain, $key=self::DEFAULT_KEY) {
		$config = Config('des.'.$key);
		if(!$config){
			throw new Exception("des $key config not find");
		}

		if ($config['pkcs5pad'] !== false) {
			$plain = self::pkcs5Pad($plain, 8);
		}
		return openssl_encrypt($plain, $config['method'], $config['password'], $config['options']);
	}

	public static function decrypt($enplain, $key=self::DEFAULT_KEY) {
		$config = Config('des.'.$key);
		if(!$config){
			throw new Exception("des $key config not find");
		}
		$plain = openssl_decrypt($enplain, $config['method'], $config['password'], $config['options']);
		return $config['pkcs5pad'] !== false ? self::pkcs5Unpad($plain) : $plain;
	}

	public static function pkcs5Pad($plain, $blocksize) {
		$pad = $blocksize - (strlen($plain) % $blocksize);
		return $plain . str_repeat(chr($pad), $pad);
	}

	public static function pkcs5Unpad($plain) {
		$pad = ord($plain{strlen($plain) - 1});
		if ($pad > strlen($plain)) {
			return false;
		}
		if (strspn($plain, chr($pad), strlen($plain) - $pad) != $pad) {
			return false;
		}
		return substr($plain, 0, -1 * $pad);
	}
}