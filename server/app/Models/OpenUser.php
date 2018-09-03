<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class openUser extends Model
{
    //
    protected $table = 'open_user';
    protected $guarded = [];

    /**
     * 用户可用状态
     */
    const USER_AVAILABLE_STATUS = 2;

    /**
     * 用户禁用状态
     */
    const USER_DISABLE_STATUS = 1;


    /**
     * 通过uid查询或创建openUser
     * @param int $uid
     * @param int $appid
     * @return bool
     * @throws \Exception
     */
    public static function getOrCreateOpenUserByUid($uid = 0, $appid = 0)
    {
        if($uid <= 0 || $appid <= 0)
            return false;
        $openUser = self::where('user_id',$uid)->where('app_id',$appid)
            ->where('status',self::USER_AVAILABLE_STATUS)
            ->first();
        if(empty($openUser)){
            try {
                $openid = self::generateOpenId($uid,$appid);
                if($openid == false){
                    throw new \PDOException('Try to generate openid failed!');
                }
                $openUser = openUser::create([
                    'user_id' => $uid,
                    'app_id'  => $appid,
                    'open_id' => $openid
                ]);
                \Log::info('App\Models\openUser getOpenUserByUid $openUser:'.var_export($openUser,true));
            } catch (\PDOException $e) {
                \Log::info(__FILE__.' '.__LINE__.' '.var_export($e->getMessage()));
                \App\Utils\Alarm::send($e->getMessage(),'dev');
                throw new \Exception('',$e->getCode());
            }
        }
        return empty($openUser) ? false :$openUser;
    }

    /**
     * 通过openid获取用户信息
     * @param $openid
     * @return mixed
     * isSen:是否包含用户敏感信息
     */
    public static function getUserInfoByOpenId($openid,$isSen = 0)
    {
        try{
            $openUser = null;
            if ($isSen == 0) {
                $openUser = OpenUser::from('open_user AS o')
                    ->select('u.avatar_url','u.nickname','o.open_id','o.user_id','u.has_paywd','u.invite_code')
                    ->leftJoin('base_user AS u','u.id','=','o.user_id')
                    ->where('o.open_id',$openid)
                    ->first();
            }else{
                //包含用户手机号信息
                $openUser = OpenUser::from('open_user AS o')
                    ->select('u.avatar_url','u.nickname','o.open_id','o.user_id','u.has_paywd','u.invite_code','u.mobile')
                    ->leftJoin('base_user AS u','u.id','=','o.user_id')
                    ->where('o.open_id',$openid)
                    ->first();
            }

            if(isset($openUser->avatar_url) && $openUser->avatar_url == ''){
                $openUser->avatar_url = env('APP_URL').'prize_static/imgs/common/avatar_default.png';
            }
            if(isset($openUser->nickname) && $openUser->nickname == ''){
                $openUser->nickname = User::getUserDefaultNickname($openUser->user_id);
            }
        } catch (\PDOException $e) {
            \Log::info('App\Models\openUser getUserInfoByOpenId:'.var_export($e->getMessage()));
            \App\Utils\Alarm::send($e->getMessage(),'dev');
        }
        return empty($openUser) ? false : $openUser->toArray();
    }

    /**
     * 通过user_id获取用户信息
     * @param $userId
     * @return mixed
     */
    public static function getUserInfoByUserId($userId)
    {
        try{
            $openUser = OpenUser::from('open_user AS o')
                ->select('u.avatar_url','u.nickname','o.open_id','o.user_id','u.has_paywd','u.invite_code','u.ignore_paywd')
                ->leftJoin('base_user AS u','u.id','=','o.user_id')
                ->where('o.user_id',$userId)
                ->first();
            if(isset($openUser->avatar_url) && $openUser->avatar_url == ''){
                $openUser->avatar_url = env('APP_URL').'prize_static/imgs/common/avatar_default.png';
            }
            if(isset($openUser->nickname) && $openUser->nickname == ''){
                $openUser->nickname = User::getUserDefaultNickname($openUser->user_id);
            }
        } catch (\PDOException $e) {
            \Log::info('App\Models\openUser getUserInfoByUserId:'.var_export($e->getMessage()));
            \App\Utils\Alarm::send($e->getMessage(),'dev');
        }
        return empty($openUser) ? false : $openUser->toArray();
    }

    /**
     * 生成OpenId
     * @param $uid
     * @param $appid
     * @return string
     */
    private static function generateOpenId($uid, $appid)
    {
        if(!defined('MAX_TRY_TIMES')){
            define('MAX_TRY_TIMES', 5);
        }
        $key = str_random(16);
        $openid = md5($uid . $key . $appid . LARAVEL_START);
        $openOwner = self::where('open_id',$openid)->first();
        $tryTimes = 0;
        while (!empty($openOwner) && $tryTimes < MAX_TRY_TIMES){
            $openid = self::generateOpenId($uid,$appid);
            $openOwner = self::where('open_id',$openid)->first();
            $tryTimes++;
        }
        \Log::info('App\Models\openUser getOpenUserByUid $tryTimes:'.var_export($tryTimes,true));
        return $tryTimes == MAX_TRY_TIMES ? false : $openid;
    }

}
