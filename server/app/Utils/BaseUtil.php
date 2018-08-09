<?php

namespace App\Utils;
use Redis;
use App\Utils\Service;

class BaseUtil 
{
    
    public static function curlGet($url, Array $dataArr = []) {
        if ($dataArr) {
            $url .= (strpos($url, '?')?'&':'?') . http_build_query($dataArr);
        }
        //var_dump($url);
        $curlObj = curl_init();
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlObj, CURLOPT_HEADER, 0);
        curl_setopt($curlObj, CURLOPT_POST, 0);
        curl_setopt($curlObj, CURLOPT_URL, $url);
        curl_setopt($curlObj, CURLOPT_HTTPHEADER, array(
            "Content-type: application/json; charset=utf-8",
        ));
        $result = curl_exec($curlObj);
        curl_close($curlObj);
        return $result;
    }

    public static function curlPost($url, Array $dataArr = [], $proxy = '') {
        $dataJson = json_encode($dataArr);
        $length = strlen($dataJson);
        $curlObj = curl_init();
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlObj, CURLOPT_HEADER, 0);
        curl_setopt($curlObj, CURLOPT_POST, 1);
        curl_setopt($curlObj, CURLOPT_URL, $url);
        curl_setopt($curlObj, CURLOPT_POSTFIELDS, $dataJson);
        curl_setopt($curlObj, CURLOPT_TIMEOUT,20);
        //设置指定IP访问
        if ('' != $proxy){
            curl_setopt($curlObj, CURLOPT_PROXY, $proxy);
        }
        curl_setopt($curlObj, CURLOPT_HTTPHEADER, array(
            "Content-type: application/json; charset=utf-8",
            "Content-length: $length"
        ));
        $result = curl_exec($curlObj);
        curl_close($curlObj);
        return $result;
    }

    public static function curlPostByArr($url, Array $dataArr = [], $proxy = '') {
        $logFileName = "zp_".date("Y-m-d",time());
        $t1 = gettimeofday();
        $curlObj = curl_init();
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlObj, CURLOPT_HEADER, 0);
        curl_setopt($curlObj, CURLOPT_POST, 1);
        curl_setopt($curlObj, CURLOPT_URL, $url);
        curl_setopt($curlObj, CURLOPT_POSTFIELDS, http_build_query($dataArr));
        //设置指定IP访问
        if ('' != $proxy){
            curl_setopt($curlObj, CURLOPT_PROXY, $proxy);
        }

        $result = curl_exec($curlObj);
        curl_close($curlObj);
        $t2 = gettimeofday();
        $time = ($t2['sec'] - $t1['sec']) * 1000 + ($t2['usec'] - $t1['usec']) / 1000;
        //计算耗时，毫秒
        $msgArr['curl_url'] = $url;
        $msgArr['curl_param'] = $dataArr;
        $msgArr['curl_cost'] = $time ;
        $msgArr['curl_returnData'] = $result;
        Service::log(__CLASS__.":".__METHOD__."网络请求情况:".var_export($msgArr,true),$logFileName);
        return $result;
    }
    public static function bigDiv($number, $decimal) {
        $number = strtolower($number);
        if (strpos($number, 'e') !== false) {
            // 解析科学计数法
            $numArr = explode('e', $number);
            $number = $numArr[0];
            $decimal = $decimal - $numArr[1];
        }
        if (strpos($number, '.') !== false) {
            // 如果$number是小数，则变为整数处理
            $decimal += @strlen(@explode('.', $number)[1]);
            $number = str_replace('.', '', $number);
        }
        if ($decimal > 0) {
            $number = str_pad('', $decimal, '0') . $number;
            $integerPart = substr($number, 0, $decimal * -1);
            $decimalPart = substr($number, $decimal * -1);
            $retStr = $integerPart . '.' . $decimalPart;
            // 去掉左边多余的0
            $retStr = ltrim($retStr, '0');
            if (strpos($retStr, '.') === 0) {
                $retStr = '0' . $retStr;
            }
            // 去掉右边多余的0
            $retStr = rtrim(rtrim($retStr, '0'), '.');
        } else {
            $retStr = $number . str_pad('', $decimal * -1, '0');
            $retStr = ltrim($retStr, '0');
        }

        return $retStr;
    }

    /**
     * 根据User-Agent 获取请求的操作系统名称
     * @param $userAgent
     * @return mixed|string
     */
    public static function getRequestViaOS($userAgent = '')
    {
        $unknowOS = 'another';
        if($userAgent == ''){
            return $unknowOS;
        }
        $lowerUserAgent = strtolower(trim($userAgent));
        $operatingSystems = [
            'ios'     => 'iOS',
            'android' => 'Android',
            'macos'   => 'Mac',
            'windows' => 'windows',
            'linux'   => 'Linux'
        ];
        foreach ($operatingSystems as $os => $osName){
            if(strstr($lowerUserAgent,$os) !== false){
                return $osName;
            }
        }
        return $unknowOS;
    }

    public static function getShortStr ($string, $leftNum = null, $rightNum = null, $dotNum = 4){
        if (!$leftNum && !$rightNum) {
            $leftNum = $rightNum = 5;
        } else if (!$leftNum || !$rightNum){
            $leftNum = $rightNum = (int)$leftNum + (int)$rightNum;
        }
        if (strlen($string) <= $leftNum + $rightNum) {
            return $string;
        }
        return substr($string, 0, $leftNum) . str_pad('', $dotNum, '*') . substr($string, "-$rightNum");
    }

    public static function formatDate($time){
        if (!is_numeric($time)) {
            $time = strtotime($time);
        }
        $t = time() - $time;
        $f = array(
            '31536000' => '年',
            '2592000' => '个月',
            '604800' => '星期',
            '86400' => '天',
            '3600' => '小时',
            '60' => '分钟',
            '1' => '秒'
        );
        foreach ($f as $k => $v)    {
            if (0 != $c = floor($t / (int)$k)) {
                return $c . $v.'前';
            }
        }
    }

    public static function formatFloat($float) {
        if (strpos($float, '.') === false) {
            return $float;
        }
        return rtrim(rtrim($float, '0'), '.');
    }

    public static function numberFormat($numStr, $decimal, $addSep = false) {
        if ($decimal === 'trim') {
            $numStr = rtrim(rtrim($numStr, '0'), '.');
        } else {
            $numStr = bcadd($numStr, 0, $decimal);
        }
        if ($addSep) {
            $intLen = strpos($numStr, '.') === false ? strlen($numStr) : strpos($numStr, '.');
            if (strpos($numStr, '-') === 0) {
                $numLen = $intLen - 1;
            } else {
                $numLen = $intLen;
            }
            $count = intval(($numLen - 1) / 3);
            for ($i = 1; $i <= $count; $i++) {
                $pos = $intLen - $i * 3;
                $numStr = substr($numStr, 0, $pos) . ',' . substr($numStr, $pos);
            }
        }

        return $numStr;
    }

    public static function charCodeAt($str, $index) {
        $char = mb_substr($str, $index, 1, 'UTF-8');
        if (mb_check_encoding($char, 'UTF-8')) {
            $ret = mb_convert_encoding($char, 'UTF-32BE', 'UTF-8');
            return hexdec(bin2hex($ret));
        } else {
            return null;
        }
    }

    /**
     * 接口参数签名生成
     * @param $param
     * @return string
     */
    public  static function  genParamSign($param)
    {
        if(isset($param['sign'])){
            $clientSign = $param['sign'];
            unset($param['sign']);
        }
        foreach ($param as $k => &$item){
            $item = $item == null ? '' : $item;
        }
        ksort($param);
        $salt = "***!@#!@#89~#BCV***!@#!@#89~**";
        $buildStr = http_build_query($param);
        $buildStr = str_replace("%3D","=",$buildStr);
        $buildStr = str_replace("%26","&",$buildStr);
        $buildStr = str_replace("%2A","*",$buildStr);
        $buildStr = str_replace("+","%20",$buildStr);
        $paramStr = md5($buildStr.$salt);
        $index = array(1,2,4,6,7,9,11,12,14,15);
        $inputStr = '';
        foreach ($index as $k=>$v){
            $inputStr =  $inputStr . $paramStr[$v];
        }
        $sign =  md5($inputStr);
        if(isset($clientSign) && $clientSign != $sign){
            \Log::info('genParamSign API $buildStr :'.$buildStr);
        }
        return  $sign;
    }

    /**
     * 判断请求是否来自BCV钱包客户端
     * @param $request
     * @return bool
     */
    public static function isRequestFromAppClient($request)
    {
        $appClient = $request->header('App-Client');
        if(in_array($appClient ,['bcvAndroid'])){
            return true;
        }
        $userAgent = $request->header('User-Agent');
        if($userAgent == 'iOS'){
            return true;
        }
        $deviceId = $request->get('deviceId','');
        if($deviceId != ''){
            return true;
        }
        return false;
    }

    public static function getOrderId ($orderTimestamp, $orderType) {
        // orderType: 1平台内转账 2平台外转出 3存入
        $date = date('Y-m-d', $orderTimestamp);
        $orderNo = Redis::incr("tran_order_id_$date");
        $orderNo = str_pad($orderNo, 6, 0, STR_PAD_LEFT);
        $orderType = str_pad($orderType, 2, 0, STR_PAD_LEFT);
        $orderId = date('Ymd') . $orderType . $orderNo;
        return $orderId;
    }



    /**
     * @param $str
     * @return int
     */
    public static  function hash($str)
    {
        for ($hash = strlen($str), $n = strlen($str), $i = 0; $i < $n; $i++) {
            $hash +=ord($str[$i]);
            $hash +=($hash<<10);
            $hash ^=($hash>>6);
        }
        return ($hash % 10000);
    }


    /**
    * 全部校验
    * @param unknown $request
    * @return boolean
    */
    public static function isSign($request){
        return true;
        $appClient = $request->header('App-Client') == 'bcvAndroid' ? 'android' : 'ios';
        if (!isset($request['deviceId'])) {
              return false;
        }
        $hash= self::hash($request['deviceId']);
        if ($hash < 1000) {
                return true;
        }
        return false;
    }

    public static function vcompare($version1, $version2) {
        $verArr1 = explode('.', $version1);
        $verArr2 = explode('.', $version2);
        if (count($verArr1) != count($verArr2)) {
            return false;
        }
        foreach ($verArr1 as $key => $value) {
            if ($verArr1[$key] != $verArr2[$key]) {
                return $verArr1[$key] > $verArr2[$key] ? 1 : -1;
            }
        }
        return 0;
    }

    /**
     * 多维数组排序
     * @param $multi_array
     * @param $sort_key
     * @param int $sort
     * @return array|bool|null
     */
    public static function multiArraySort($multi_array, $sort_key, $sort = SORT_ASC)
    {
        if (is_array($multi_array)) {
            foreach ($multi_array as $row_array) {
                if (is_array($row_array)) {
                    $key_array[] = $row_array[$sort_key];
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        if (empty($key_array)) {
            return array();
        }
        array_multisort($key_array, $sort, $multi_array);
        return $multi_array;
    }

    public static function mkdirRecursively($path)
    {
        $path = str_replace(DIRECTORY_SEPARATOR, '/', rtrim($path, '/'));
        $pathArr = explode('/', $path);
        if ($pathArr[0] == "") {
            chdir('./');
            array_shift($pathArr);
        }
        try {
            foreach ($pathArr as $eachPath) {
                if (!is_dir($eachPath)) {
                    mkdir($eachPath);
                }
                chdir($eachPath);
            }
        } catch (Exception $e) {
            \Log::info($e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * 是否需要校验afs
     * @param $request
     * @return bool|mixed
     */
    public static function needAfsAppCheck($request)
    {
        $appClient = $request->header('App-Client','');
        $version = $request->get('version','');
        if(env('NEED_VAILD_FOR_SEND_SMS') == false){
            return false;
        }
        if($appClient == 'bcvAndroid') {
            return version_compare($version,'2.2.0','>');
        }

        if($appClient == 'ios') {
            return version_compare($version,'2.0.0','>');
        }
        return false;
    }

    /**
     * 校验支付密码
     * @param $uid
     * @param $paypwd
     * @throws \Exception
     */
    public static function validatePayPwd($uid, $paypwd)
    {
        try{
            $resJson = BaseUtil::curlPost(env('TX_API_URL') . '/api/checkPaywd', array(
                'userId' => $uid,
                'paywd' => Service::decryptPayPwd($paypwd),
            ));
            $resArr = json_decode($resJson, true);
            \Log::info(__FILE__.' '.__LINE__.' cost api/checkPaywd:'.var_export($resJson,true));
            if (!$resArr || $resArr['errcode'] !== 0){
                if ($resArr && $resArr['errcode'] == 216) {
                    // 支付密码错误
                    throw new \Exception('',216);
                } else if ($resArr && $resArr['errcode'] == 217) {
                    // 支付密码连续5次错误
                    throw new \Exception('',217);
                }
                throw new \Exception('',101);
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(),$e->getCode());
        }
    }
}
