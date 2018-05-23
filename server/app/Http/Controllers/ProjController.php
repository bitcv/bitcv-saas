<?php
/**
 * project前台页面
 */
namespace App\Http\Controllers;

use App\Models\Saas;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjController extends Controller {
    //首页
    public function index() {
        $projId = app()->proj['proj_id'];
        if (!$projId) {
            die('err');
        }
        $proj = (new Project())->getProjDetail($projId);
        var_dump($proj);die;
        return view('proj.index', compact('proj'));
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
        $fields = ['org','username','email','mobile','subname','domain','desc'];
        $data = [];
        foreach ($fields as $f) {
            $data[$f] = $request->input($f);
        }

        $pro_id     = (new Saas())->add($data);
        if (!$pro_id) {
            return ['retcode' => 5001, 'msg' => '申请失败，请重试！'];
        } else {
            return ['retcode' => 200, 'msg'  => '申请成功！'];
        }
    }

}