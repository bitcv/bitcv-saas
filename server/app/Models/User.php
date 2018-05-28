<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Utils\Service;
use Illuminate\Support\Facades\Redis;

class User extends Model
{
    protected $table = 'base_user';
    protected $guarded = [];

    public function getUser($uid) {
        $userObj = self::where('id', $uid)->first();
        if (!$userObj) {
            return false;
        }
        $user = $userObj->toArray();
        $role = Admin::where('id', $uid)->first();
        if ($role) {
            $user['is_sys'] = $role['is_sys'];
            $user['proj_id'] = $role['proj_id'];
        } else {
            $user['is_sys'] = 0;
            $user['proj_id'] = 0;
        }
        return $user;
    }

    public function loginUser($mobile, $pass) {
        $user = self::where('mobile', $mobile)->first();
        if (!$user) {
            return false;
        }
        $key = 'pass_err_'.$mobile;
        //1分钟内密码尝试5次
        if (Redis::get($key) > 5) {
            return false;
        }
        if (md5($pass) == $user['passwd']) { //更新老的加密方式
            $passwd = Service::getPwd($pass);
            self::where('mobile', $mobile)->update(['passwd'=>$passwd]);
        } else {
            $hash = $user['passwd'];
            if (!Service::checkPwd($pass, $hash)) {
                Redis::incr($key);
                Redis::expire($key, 60);
                return false;
            }
        }
        return $user;
    }

    public function regUser($nation, $mobile, $passwd) {
        if (empty($mobile) || empty($passwd)) {
            return false;
        }
        if (strlen($mobile) == 11) {
            $nation = 86;
        }

        $userModel = self::where('mobile', $mobile)->first();
        if ($userModel) { // 经过了验证码校验，更新密码
            self::where('mobile', $mobile)->update([
                'nation' => $nation,
                'passwd' => Service::getPwd($passwd)
            ]);
        } else {
            $userModel = self::create([
                'nation' => $nation,
                'mobile' => $mobile,
                'passwd' => Service::getPwd($passwd)
            ]);
        }
        return $userModel;
    }

    /**
     * 根据微信id查找用户
     * @param $wxUserid
     * @return bool
     */
    public static function getUserByWxUserId($wxUserid = 0)
    {
        if($wxUserid <= 0){
            return false;
        }
        $userObj = self::from('base_user AS u')->join('base_wx_user AS wu','wu.unionid','=','u.unionid')
            ->where(['wu.id'=>$wxUserid])
            ->where('wu.unionid','!=','')
            ->select('u.id','u.nickname','u.avatar_url','u.unionid','u.has_paywd','u.mobile','u.nation','u.wx_user_id')
            ->first();
        if($userObj != false && count($userObj) > 0){
            return $userObj;
        }
        return false;
    }

    /**
     * 获取用户默认昵称
     * @param $uid
     * @return bool|string
     */
    public static function getUserDefaultNickname($uid = 0)
    {
        if($uid <= 0){
            return false;
        }
        $userObj = self::where('id','=',$uid)->first();
        if($userObj != false && count($userObj) > 0){
            $mobile = $userObj->mobile;
            return Service::mask_string($mobile,3,4);

        }
        return '默认昵称';
    }

    /**
     * 根据用户id获取用户
     * @param int $uid
     * @return bool
     */
    public static function getUserById($uid = 0)
    {
        if($uid <= 0){
            return false;
        }
        $userObj = self::where(['id'=>$uid])
            ->select('id','nickname','avatar_url','unionid','has_paywd','mobile','nation','wx_user_id')
            ->first();
        if($userObj != false && count($userObj) > 0){
            return $userObj;
        }
        return false;
    }

}
