<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use App\Models\Saas;

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
        $data = (new Saas())->getProj();
        return view('saas.projs', ['data'=>$data]);
    }

    //SaaS详情
    public function module(Request $request) {
        /* todo 排查完页面的问题恢复回来
         $proj_id    = $request->projid;
        $data = (new Module())->getByProjId($proj_id);
*/
        return view('saas.module', ['data'=>[]]);
    }
    
    //审核SaaS申请
    public function audit(Request $request) {
        $proj_id    = $request->input('proj_id');
        $status     = $request->input('status');

        (new Saas())->audit($proj_id, $status);

        return  ['retcode'=>200,'msg'=>'succ'];
    }

    public function auditMod(Request $request) {
        $proj_id    = $request->proj_id;
        $valid      = $request->valid;

        (new Module())->audit($proj_id, $valid);
    }

    public function add(Request $request) {
        $proj_id    = $request->proj_id;
        $mod_id     = $request->mod_id;

        (new Module())->add($proj_id, $mod_id);
    }
}