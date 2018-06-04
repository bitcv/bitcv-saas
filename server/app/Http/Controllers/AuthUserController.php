<?php
namespace App\Http\Controllers;
use App\Utils\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Utils\Service;
use App\Utils\AuthUser;
use Cookie;
use Illuminate\Support\Facades\Redis;

class AuthUserController extends Controller
{
    public function addAuthUser(Request $request)
    {
        //获取请求参数
        $params = $this->validation($request, [
            'uname' => 'required',
//            'umobile' => 'required|numeric',
            'uemail' => 'required',
        ]);

        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $allparams = $request->all();
        if (array_key_exists('roles', $allparams) && $allparams['roles']) {
            $data['roles'] = trim(implode(',', array_filter($allparams['roles'])));
        }

        $data['uname'] = trim($allparams['uname']);
        $data['email'] = trim($allparams['uemail']);
        $data['mobile'] = trim($allparams['umobile']);
        $data['is_active'] = (int) $allparams['uactive'];
        $data['gender'] = (int) $allparams['gender'];
        if ($allparams['passwd']) {
            $data['passwd'] = Service::getPwd($allparams['passwd']);
        }
        if (array_key_exists('uid', $allparams) && $allparams['uid']) {
            $result = DB::table('base_authuser')->where('uid',$request->all()['uid'])->update($data);
            if ($result !== false) {
                return $this->output();
            }
        } else {
            $result = DB::table('base_authuser')->insertGetId($data);
            if ($result) {
                return $this->output();
            }
        }
    }

    //后台用户列表
    public function getAuthUserList(Request $request)
    {
        //获取请求参数
        $params = $this->validation($request, [
            'pageno' => 'required|numeric',
            'perpage' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $offset = $perpage * ($pageno - 1);
        $query = DB::table('base_authuser');
        $query = $query->select('*');
        $userList = $query->orderBy('uid', 'desc')->where('is_active','=','0')->offset($offset)
            ->limit($perpage)
            ->get()->toArray();
        foreach ($userList as $key => $user) {
            $userList[$key]->gendername = $user->gender ? '女' : '男';
        }
        return $this->output([
            'authuserlist' => $userList,
            'roles' => AuthUser::$AUTHROLES,
        ]);
    }

    //删除用户
    public function deleteAuthUser (Request $request)
    {
        //获取请求参数
        $params = $this->validation($request, [
            'uid' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $data['is_active'] = 1;//禁用用户
        $result = DB::table('base_authuser')->where('uid', '=', $params['uid'])->update($data);
        if ($result)
        {
            return $this->output();
        }
    }

    //后台登录
    public function doSignin(Request $request)
    {
        //获取请求参数
        $params = $this->validation($request, [
            'email' => 'required',
            'passwd' => 'required',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $user = DB::table('base_authuser')->where([['email','=',$params['email']],['is_active','=',0]])->get()->toArray();
        if (!$user) {
            return $this->error(203);
        }
        $user = $user[0];
        $hash = $user->passwd;
        $key = 'pass_err_'.$user->email;
        //1分钟内密码尝试3次
        if (Redis::get($key) > 3) {
            return $this->error(207);
        }
        if(!Service::checkPwd($params['passwd'],$hash)) {
            Redis::incr($key);
            Redis::expire($key, 60);
            return $this->error(202);
        }
        $tempuser = array();
        foreach ($user as $key => $item) {
            $tempuser[$key] = $item;
        }

        AuthUser::setLogin($tempuser);

        $data['uid'] = $tempuser['uid'];
        $data['uname'] = $tempuser['uname'];
        $data['email'] = $tempuser['email'];

        return $this->output([
            'userinfo' => $data,
        ]);
    }

    public function getAuthUser (Request $request)
    {
        $uinfo = session()->get('authuinfo');
        $menus = AuthUser::$menu;
        $adminmenu = array();
        $uid = $uinfo['uid'];
        $roles = DB::table('base_authuser')->select('roles')->where('uid',$uid)->get()->toArray();
        if (!empty($roles)) {
            $roles = $roles[0]->roles;
        }
        if (!empty($uinfo)) {
            $roles = explode(',', $roles);
            //超级管理员拥有所有菜单权限
            if (in_array(AuthUser::R_ADMIN_PERMISSION, $roles)) {
                $adminmenu = $menus;
            } else {
                foreach ($menus as $key => $menu) {
                    // $menu['p'] == 99 普通员工，个人中心
                    if (in_array($menu['p'], $roles) || $menu['p'] == 99) {
                        $adminmenu[] = $menu;
                    }
                }
            }
        }
        return $this->output([
            'uinfo' => $uinfo,
            'menu'  => $adminmenu,
        ]);
    }

    //退出登录
    public function doSignout (Request $request)
    {
        \App\Utils\AuthUser::setLogout();
        $request->session()->flush();
        return $this->output();
    }

    public function getSimpleAuthUser(Request $request)
    {
        $uinfo = session()->get('authuinfo');
        $uid = $uinfo['uid'];
        $user = DB::table('base_authuser')->select('*')->where('uid',$uid)->get()->toArray();
        if (!empty($user)) {
            $userinfo = array();
            $userinfo['uname'] = $user[0]->uname;
            $userinfo['mobile'] = $user[0]->mobile;
            $userinfo['email'] = $user[0]->email;
            return $this->output([
               'uinfo' => $userinfo,
            ]);
        }
    }

    public function updateAuthUser (Request $request)
    {
        //获取请求参数
        $params = $this->validation($request, [
            'uemail' => 'required',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $allparams = $request->all();
        $uinfo = session()->get('authuinfo');
        $uid = $uinfo['uid'];
        $data['email'] = trim($allparams['uemail']);
        if ($allparams['passwd']) {
            $data['passwd'] = Service::getPwd($allparams['passwd']);
        }

        $result = DB::table('base_authuser')->where('uid', $uid)->update($data);
        if ($result !== false) {
            return $this->output();
        }
    }

    public function captcha($type = 'sms', $nation = '', $mobile = '', Request $request)
    {
        $appClient = $request->header('appClient','default');

        return view('captcha',[
            'ip'        => $request->ip(),
            'type'      => $type,
            'nation'    => $nation,
            'mobile'    => $mobile,
            'appClient' => $appClient,
        ]);
    }

    // 项目方上传发红包的封面图片
    public function addPacketPic (Request $request)
    {
        //获取请求参数
        $params = $this->validation($request, [
            'pid' => 'required|numeric',
        ]);
        $allparams = $request->all();
        if ($params === false) {
            return $this->error(100);
        }
        $data = array();
        $data['packet_cover'] = $allparams['pic'];
//        $data['packet_cover'] = 'http://p8k1ocuzy.bkt.clouddn.com/saasPacketPic_810039961619194,http://p8k1ocuzy.bkt.clouddn.com/saasPacketPic_524009732861674';
        $result = DB::table('base_token')->where('id', $params['pid'])->update($data);
        if ($result !== false) {
            $this->output();
        }
    }

    // 获取项目方发红包的封面图片
    public function getPacketPic (Request $request)
    {
        //获取请求参数
        $params = $this->validation($request, [
            'pid' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        $picture = DB::table('base_token')->where('id',$params['pid'])->select('packet_cover','id')->get()->toArray();
        if ($picture) {
            return $this->output([
                'pic' => $picture
            ]);
        }
    }

    // 获取项目方 tokenid
    public function getPid (Request $request)
    {
        $projectid = app()->proj['proj_id'];
//        $projectid = 1; // 测试使用
        // 获取当前项目的 tokenid
        $project = DB::table('proj_info')->where('id',$projectid)->select('token_id')->get()->toArray();
        if ($project) {
            $result = array();
            foreach ($project as $key => $value) {
                $result['tokenid'] = $value->token_id;
            }
        }

        return $this->output([
            'tokenId' => $result
        ]);
    }
}