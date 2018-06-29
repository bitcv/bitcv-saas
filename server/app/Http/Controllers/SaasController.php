<?php

namespace App\Http\Controllers;

use App\Models\Constant;
use App\Models\Module;
use Illuminate\Http\Request;
use App\Models\Saas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class SaasController extends Controller
{
    //登录显示
    public function loginView() {
        return view('saas.login');
    }

    //登录提交
    public function login(Request $req) {
        $uname = $req->input('uname');
        $pwd = $req->input('password');
        if (!$uname || !$pwd) {
            return false;
        }
        if ($uname == 'admin' && $pwd == env('ADMIN_PASS')) {
            session()->put('saas_admin', ['uid'=>1,'uname'=>'admin']);
        } else {
            // 登录五次密码错误后，禁止登录一天。
            $key = 'pass_err_'.$uname;
            if (Redis::get($key) >= 5) {
                return false;
            }
            Redis::incr($key);
            Redis::expire($key, 86400);
            return ['err' => 1, 'msg' => '密码错误'];
        }

        return ['err' => 0, 'data' => route('saas.admin')];
    }

    //退出
    public  function logout() {
        session()->pull('saas_admin');
        return view('saas.login');
    }

    //SaaS项目列表
    public function projs() {
        $data = (new Saas())->getProj();
        return view('saas.projs', ['data'=>$data]);
    }

    //SaaS模块详情
    public function module(Request $request) {
        $proj_id    = $request->projid;

        $data = (new Module())->getByProjId($proj_id);
        $proj = (new Saas())->getProById($proj_id);

        return view('saas.module', ['data'=>$data, 'proj_id'=>$proj_id, 'proj'=>$proj]);
    }
    
    //审核SaaS项目申请
    public function audit(Request $request) {
        $proj_id    = $request->input('proj_id');
        $status     = $request->input('status');
        $objSass    = new Saas();
        if ($status == Constant::proj_status_pass) {
            $objSass->apply($proj_id);
        } else if ($status == Constant::proj_status_refuse) {
            $objSass->refuse($proj_id);
        } else {
            return ['retcode'=>-1,'msg'=>'status error'];
        }

        return  ['retcode'=>200,'msg'=>'succ'];
    }

    /**
     * 模块审核
     * @param Request $request
     * @return array
     */
    public function auditMod(Request $request) {
        $proj_id    = $request->input('proj_id');
        $valid      = $request->input('valid');

        $result = (new Module())->audit($proj_id, $valid);
        if (!$result) {
            return ['retcode'=>-1,'msg'=>'修改模块失败！'];
        }

        return ['retcode'=>200,'msg'=>'succ'];
    }

    /**
     * 添加模块
     * @param Request $request
     * @return array
     */
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

    public function addPic (Request $request)
    {
        $projId = app()->proj['proj_id'];
        print_r($projId);
    }
}