<?php
namespace App\Utils;
use Cookie;
use Illuminate\Support\Facades\Redis;

class AuthUser {
    //角色
    const R_ADMIN_PERMISSION = 1;
    const R_FINANCE = 2;
    const R_ASSEST = 3;
    const R_PERSONNEL = 4;
    const R_MEDIA = 5;

    //角色
    public static $AUTHROLES = array (
        self:: R_ADMIN_PERMISSION  => '系统管理员',
        self:: R_FINANCE           => '财务',
        self:: R_ASSEST            => '行政',
        self:: R_PERSONNEL         => '人事',
        self:: R_MEDIA             => '运营'
    );

    public static $menu = array(
        /*array ('icon' => 'el-icon-menu', 'p' => 99, 'url' => '/admin/project', 'text' => '项目管理', 'child' => array(
            array ('icon' => 'el-icon-menu', 'p' => 99, 'url' => '/admin/project', 'text' => '项目管理'),
            array ('icon' => 'el-icon-menu', 'p' => 99, 'url' => '/admin/projdata', 'text' => '项目更新'),
            array ('icon' => 'el-icon-menu', 'p' => 99, 'url' => '/admin/mediareport', 'text' => '项目动态'),
        )),*/
        array ('icon' => 'el-icon-menu', 'p' => 99, 'url' => '/admin/setting', 'text' => '个人中心' ,'child' => array (
            array ('icon' => 'el-icon-menu', 'p' => 99, 'url' => '/admin/setting', 'text' => '个人中心')
        )),
        array ('icon' => 'el-icon-menu', 'p' => 99, 'url' => '/admin/upload', 'text' => '上传图片' ,'child' => array (
            array ('icon' => 'el-icon-menu', 'p' => 99, 'url' => '/admin/upload', 'text' => '上传图片')
        )),
    );

    public static $uid = 0;
    public static $authuser = [];

    public static function setLogin($user) {
        $token = self::genToken($user['uid']);
        if (!$token) {
            return array('err' => 1, 'msg' => 'generate token failed');
        }
        $user['token'] = $token;
        Cookie::queue('authtoken', $token, 30*24*60); //cookie分钟
        self::$authuser = $user;
        self::$uid = $user['uid'];
        session(['authuinfo' => $user]);
        return array('err' => 0, 'data' => ['token'=>$token]);
    }

    public static function genToken($uid) {
        $token = substr(md5($uid.'BitCVAuthUser'.'_'.rand()), 0, 16);
        Redis::set("authtoken:$token", json_encode(['uid'=>$uid,'t'=>time()]));
        Redis::expire("authtoken:$token", 86400*30);
        return $token;
    }

    public static function setLogout() {
        Cookie::queue('authtoken', '', 0);
        return true;
    }
}