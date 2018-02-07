<?php

namespace App\Http\Controllers;

use App\Models\Saas;
use Illuminate\Http\Request;

class ProjController extends Controller {
    //首页
    public function index() {
        return view('proj.index');
    }

    //申请SaaS
    public function apply() {
        return view('proj.apply');
    }

    public function add(Request $request) {
        $subname    = $request->input('subname');
        $domain     = $request->input('domain');
        $name       = $request->input('name');

        $pro_id     = (new Saas())->add($subname, $domain, $name);
        if (!$pro_id) {
            return ['retcode' => 5001, 'msg' => '申请失败，请重试！'];
        } else {
            return ['retcode' => 200, 'msg'  => '申请成功！'];
        }
    }
}