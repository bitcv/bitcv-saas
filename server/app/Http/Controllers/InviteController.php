<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use Illuminate\Http\Request;
use Service;

class InviteController extends \App\Http\Controllers\Controller {

    public function getInvite() {
        return view('invite.add');
    }

    public function vcode($mobile) {
        $vcode = Service::getVcode('reg', $mobile)['data'];
        Service::sms($mobile, '【BitCV】您的验证码为'.$vcode);
    }

    public function add(Request $request) {
        $vcode = $request->input('vcode');
        $mobile = $request->input('mobile');
        $ret = Service::checkVCode('reg', $mobile, $vcode);
        if (!$ret) {
            return ['retCode' => 1, 'msg' => $ret['msg']];
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
        $result = (new Invite())->invites($code, $address);

        if (!$result) {
            $data = array(
                'retcode'   => 50001,
                'msg'       => '添加失败！',
            );
        } else {
            $data = array(
                'retcode'   => 200,
                'msg'       => '添加成功！',
                'data'      => $result,
            );
        }

        return $data;
    }
}