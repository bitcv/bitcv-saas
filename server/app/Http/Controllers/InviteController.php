<?php

namespace App\Http\Controllers;

use App\Http\Models\Invite;
use Illuminate\Http\Request     as Request;

class InviteController extends \App\Http\Controllers\Controller {

    public function getInvite() {
        return view('/invite/add');
    }

    public function add(Request $request) {
        $address = $request->input('address');
        if (!preg_match('/[0-9a-zA-Z]{30,50}/', $address)) {
            $data = array(
                'retcode'   => 40001,
                'msg'       => '请输入正确格式的以太坊钱包地址！',
            );

            return \response()->json($data);
        }

        //验证address
        $result = (new Invite())->add($address);

        if (!$result) {
            $data = array(
                'retcode'   => 50001,
                'msg'       => '添加失败！',
            );
        } else {
            $data = array(
                'retcode'   => 200,
                'msg'       => '添加成功！',
            );
        }

        return \response()->json($data);
    }
}