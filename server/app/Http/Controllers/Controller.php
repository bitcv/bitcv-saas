<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Utils\Alarm;
use App\Utils\Service;
use Illuminate\Http\Request;
use Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const ERROR = [
        '0' => ['errcode' => 0, 'errmsg' => '成功执行'],
        // 通用错误码
        '100' => ['errcode' => 100, 'errmsg' => '参数错误'],
        '101' => ['errcode' => 101, 'errmsg' => '未知错误'],
        '110' => ['errcode' => 110, 'errmsg' => '文件名称错误'],
        // 用户错误码
        '202' => ['errcode' => 202, 'errmsg' => '用户名或密码错误'],
        '203' => ['errcode' => 203, 'errmsg' => '用户不存在'],
        '207' => ['errcode' => 207, 'errmsg' => '登录次数过多请稍候重试'],
        // 项目错误码
        '301' => ['errcode' => 301, 'errmsg' => '项目不存在'],
        '302' => ['errcode' => 302, 'errmsg' => '社交ID不存在'],
        '303' => ['errcode' => 303, 'errmsg' => '媒体ID不存在'],
        //
        '400' => ['errcode' => 400, 'errmsg' => '需要登录'],
        '401' => ['errcode' => 401, 'errmsg' => '密码错误'],
    ];

    public function validation(Request $request, Array $rules)
    {
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return false;
        } else {
            $params = $request->only(array_keys($rules));
            foreach ($params as $key => &$value) {
                if (strpos($rules[$key], 'numeric') !== false) {
                    $value = intval($value);
                }
            }
            return $params;
        }
    }

    public function output($data = [])
    {
        if (is_object($data)) {
            $data = $data->toArray();
        }
        if ($data == null) {
            return json_encode(self::ERROR[0], JSON_UNESCAPED_UNICODE);
        }
        $result = $this->arrayKeyToCamel($data);
        $rtnArr = self::ERROR[0];
        $rtnArr['data'] = $result;
        return json_encode($rtnArr, JSON_UNESCAPED_UNICODE);
    }

    /*public function error($errcode)
    {
        if (array_key_exists($errcode, self::ERROR)) {
            return json_encode(self::ERROR[$errcode], JSON_UNESCAPED_UNICODE);
        }
        return json_encode(self::ERROR[101], JSON_UNESCAPED_UNICODE);
    }*/

     public function error($errcode = 101, $errmsg = '', $getJson = false)
     {
         Service::log("errcode:$errcode " . $_SERVER['REQUEST_URI'] . ' => ' . json_encode($_POST), 'debug');
         \Log::info('appenv'.env('APP_ENV'));
         $err = array_key_exists($errcode, self::ERROR) ? self::ERROR[$errcode] : self::ERROR[101];
         if ($errmsg) {
             $err['errmsg'] = $errmsg;
         }
         $errorArr = [202, 207, 301, 302, 303, 400, 401];
         if (env('APP_ENV') != 'local' && !in_array($errcode, $errorArr)) {
             Alarm::send("SaaS 站：errcode: $errcode, errmsg: ".$err['errmsg'] . ', ' . $_SERVER['REQUEST_URI'] . ': ' . json_encode($_POST));
         }

         if ($getJson) {
             return json_encode($err, JSON_UNESCAPED_UNICODE);
         } else {
             return response()->json($err);
         }
     }

    public function arrayKeyToCamel (Array $array)
    {
        $newArray = array();
        foreach ($array as $key => $value) {
            $newKey = preg_replace_callback('/([-_]+([a-z]{1}))/i', function($matches){
                return strtoupper($matches[2]);
            }, $key);
            $newArray[$newKey] = is_array($value) ? $this->arrayKeyToCamel($value) : $value;
        }
        return $newArray;
    }

    public function arrayKeyToLine (Array $array)
    {
        $newArray = array();
        foreach ($array as $key => $value) {
            $newKey = preg_replace_callback('/([A-Z]{1})/', function($matches){
                return '_'.strtolower($matches[0]);
            }, $key);
            $newArray[$newKey] = is_array($value) ? $this->arrayKeyToCamel($value) : $value;
        }
        return $newArray;
    }

}
