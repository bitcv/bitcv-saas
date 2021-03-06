<?php

namespace App\Http\Controllers;
use App\Utils\Admin;
use Illuminate\Http\Request;
use App\Models\Saas;
use Illuminate\Support\Facades\DB;
use App\Utils\AdminUtils;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->saasModel = new Saas();
    }

    public function getApplySaasList(Request $request)
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
        $query = DB::table('saas_proj');
        $query = $query->select('*');
        $dataList = $query->orderBy('ctime', 'desc')->offset($offset)
            ->limit($perpage)
            ->get()->toArray();
        foreach ($dataList as $key => $value) {
            $dataList[$key]->statusname = AdminUtils::CheckStatus[$value->status];
        }
        return $this->output([
           'datalist' => $dataList,
        ]);
    }

    //审核saas
    public function checkSaas (Request $request)
    {
        //获取请求参数
        $params = $this->validation($request, [
            'status' => 'required|numeric',
            'proj_id' => 'required|numeric'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $pid = intval($params['proj_id']);
        $status = intval($params['status']);
        $result = $this->saasModel->audit($pid,$status);
        if ($result) {
            return $this->output();
        }
    }

    //删除 saas
    public function deleteSaas (Request $request)
    {
        //获取请求参数
        $params = $this->validation($request, [
           'proj_id'    =>  'required|numeric',
        ]);

        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $pid = intval($params['proj_id']);
        $result = DB::table('saas_proj')->where('proj_id','=',$pid)->delete();
        if ($result) {
            $this->output();
        }
    }

    public function getProjBasicList (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'pageno' => 'required|numeric',
            'perpage' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $allparams = $request->all();
        $query = DB::table('proj_info');
        $query = $query->select('proj_info.*');
        if (array_key_exists('projname', $allparams) && $allparams['projname']) {
            $where = ['name_cn','like','%'.$allparams['projname'].'%'];
            $query = $query->where('proj_info.name_cn','like','%'.$allparams['projname'].'%');
        }
        $offset = $perpage * ($pageno - 1);
        $projList = $query->offset($offset)
            ->limit($perpage)
            ->get()->toArray();

        $dataCount = $query->count();
        // $projList = Model\Project::offset($offset)->limit($perpage)->get()->toArray();
        // $dataCount = Model\Project::count();

        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $projList
        ]);
    }
}