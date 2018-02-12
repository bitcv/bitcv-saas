<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use Illuminate\Http\Request;
use App\Utils\Service;
use App\Models\Module;
use App\Models\Project;

class InviteController extends \App\Http\Controllers\Controller {

    public function getInvite(Request $request) {
        if (!Module::check('invite')) {
            die('err url');
        }

        $uid        = Invite::decode(\Cookie::get('uid'));
        if ($uid) {
            $user = (new Invite())->getByUid($uid);
        } else {
            $user = [];
        }

        $code       = $request->input('code', '');
        $proj = Project::where('id', app()->proj['proj_id'])->first()->toArray();
        return view('invite.add', compact('code', 'proj', 'user'));
    }

    public function vcode($mobile) {
        $ret = Service::getVcode('reg', $mobile);
        if ($ret['err'] > 0) {
            return ['retcode'=>1, 'msg'=>$ret['msg']];
        }
        Service::sms($mobile, '【BitCV】您的验证码为'.$ret['data'].'，请在5分钟内输入');
        return ['retcode'=>200];
    }

    public function verifyCode(Request $request) {
        $vcode = $request->input('vcode');
        $mobile = $request->input('mobile');
        $code       = $request->input('code', '');
        $ret = Service::checkVCode('reg', $mobile, $vcode);
        if ($ret['err'] > 0) {
            return ['retcode' => 1, 'msg' => $ret['msg']];
        }

        $invite = new Invite();
        $fromid = $code ? Invite::decode($code) : 0;
        $ret    = $invite->getUidByMobile($mobile, $fromid);
        \Cookie::queue('uid', Invite::encode($ret['data']['uid']), 43200);//单位是分钟

        unset($ret['data']['uid']);

        return $ret;
    }

    public function add(Request $request) {
        return '';
        $uid        = Invite::decode(\Cookie::get('uid'));
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
            $result = (new Invite())->invites($code, $address, $uid);
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