<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use Illuminate\Http\Request;
use App\Utils\Service;
use App\Models\Module;

class InviteController extends \App\Http\Controllers\Controller {

    public function getInvite(Request $request) {
        if (!Module::check('invite')) {
            die('err url');
        }
        $code       = $request->input('code', '');
        return view('invite.add', ['code'=>$code]);
    }

    public function vcode($mobile) {
        $ret = Service::getVcode('reg', $mobile);
        if ($ret['err'] > 0) {
            return ['retcode'=>1, 'msg'=>$ret['msg']];
        }
        Service::sms($mobile, '【BitCV】您的验证码为'.$ret['data'].'，请在5分钟内输入');
        return ['retcode'=>200];
    }

    public function add(Request $request) {
        $vcode = $request->input('vcode');
        $mobile = $request->input('mobile');
        $ret = Service::checkVCode('reg', $mobile, $vcode);
        if ($ret['err'] > 0) {
            return ['retcode' => 1, 'msg' => $ret['msg']];
        }

        $address    = $request->input('address');
        $code       = $request->input('code', '');
        if (!preg_match('/[0-9a-zA-Z]{30,50}/', $address)) {
            $data = array(
                'retcode'   => 40001,
                'msg'       => '请输入正确格式的以太坊钱包地址！',
            );

            return $data;
        }

        //验证address
        try {
            $result = (new Invite())->invites($code, $address, $mobile);
        } catch(\Exception $e) {
            $data = array(
                'retcode'   => $e->getCode(),
                'msg'       => $e->getMessage()
            );

            return $data;
        }


        return $result;
    }
}