<?php
/**
 * project前台页面
 */
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
        //总站默认申请页面，子站显示自己的主页
        if (isset(app()->proj['proj_id'])) {
            return $this->index();
        }
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