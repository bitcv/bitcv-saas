<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SaasController extends Controller
{
    public function loginView() {
        return view('saas.login');
    }
    public function login(Request $req) {
        $uname = $req->input('uname');
        $pwd = $req->input('password');
        if ($uname == 'admin' && $pwd == 'abc') {
            session()->put('saas_admin', ['uid'=>1,'uname'=>'admin']);
        } else {
            return ['err' => 1, 'msg' => 'password error'];
        }
        return ['err' => 0, 'data' => route('saas.admin')];
    }

    //SaaS列表
    public function projs() {
        return view('saas.projs');
    }

    //SaaS详情
    public function proj() {

    }
    
    //审核SaaS申请
    public function audit() {
    }


}
