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
        if ($uname == 'admin' && $pwd == env('ADMIN_PASS')) {
            session()->put('saas_admin', ['uid'=>1,'uname'=>'admin']);
        } else {
            return ['err' => 1, 'msg' => 'password error'];
        }

        return ['err' => 0, 'data' => route('saas.admin')];
    }

    public  function logout() {
        session()->pull('saas_admin');
        return view('saas.login');
    }

    //SaaS列表
    public function projs() {
        $data = (new Saas())->getProj();
        return view('saas.projs', ['data'=>$data]);
    }

    //SaaS详情
    public function module(Request $request) {
        $proj_id    = $request->projid;
        $data = (new Module())->getByProjId($proj_id);
        $proj = (new Saas())->getProById($proj_id);

        return view('saas.module', ['data'=>$data, 'proj_id'=>$proj_id, 'proj'=>$proj]);
    }
    
    //审核SaaS申请
    public function audit(Request $request) {
        $proj_id    = $request->input('proj_id');
        $status     = $request->input('status');

        (new Saas())->audit($proj_id, $status);

        return  ['retcode'=>200,'msg'=>'succ'];
    }

    public function auditMod(Request $request) {
        $proj_id    = $request->input('proj_id');
        $valid      = $request->input('valid');

        $result = (new Module())->audit($proj_id, $valid);
        if (!$result) {
            return ['retcode'=>-1,'msg'=>'修改模块失败！'];
        }

        return ['retcode'=>200,'msg'=>'succ'];
    }

    public function add(Request $request) {
        $proj_id    = $request->input('proj_id');
        $mod_id     = $request->input('mod_id');
        $data = (new Module())->add($proj_id, $mod_id);
        if ($data['err'] == 0) {
            return ['retcode'=>200,'msg'=>'succ'];
        } else {
            return ['retcode'=>$data['err'],'msg'=>$data['msg']];
        }
    }
}