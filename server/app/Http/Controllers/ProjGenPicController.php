<?php
/**
 * Created by PhpStorm.
 * User: zxf1001
 * Date: 2019/1/11
 * Time: 15:03
 */
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProjGenPic;
use App\Models\lunar;

class ProjGenPicController extends Controller
{
    public function __construct()
    {
        $this->genPicM = new ProjGenPic();
    }

    public function gen_picture($newsinfo)
    {
        $lunar = new lunar();
        $title_len = mb_strlen($newsinfo['title']);
        $title_lines = array();
        for($i=0; $i<$title_len/14; $i++)
        {$title_lines[] = mb_substr($newsinfo['title'], $i*14, 14);
        }
        $newsinfo['title'] = $title_lines;
        $content_len = mb_strlen($newsinfo['content']);
        $content_lines = array();
        for($i=0; $i<$content_len/16; $i++)
        {
            $content_lines[] = mb_substr($newsinfo['content'], $i*16, 16);
        }
        $newsinfo['content'] = $content_lines;
        $month = $lunar->convertSolarToLunarSimple($newsinfo['date']);
        $month_lunar = $lunar->getLunarMonthName($month[3],$month[4]);
        $newsinfo['lunar_month'] = $month_lunar;
        $day_lunar = $lunar->getLunarDayName($newsinfo['date']);
        $newsinfo['lunar_day'] = $day_lunar;

        $basePicUrl = base_path();
        $picName = $this->genPicM->doit($basePicUrl.'/storage/app/public/image/lianxun/bk.png', $newsinfo);
        return $picName;
    }
    public function genLianXunPic(Request $request)
    {
        $params = $this->validation($request, [
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        $allParams = $request->all();
        $newInfo = [
            'no' => intval($allParams['no']),
            'title' => $allParams['title'],
            'content' => $allParams['content'],
            'date' => $allParams['oldTime'],
            'proj_id' => $allParams['projId'],
            'created_at' => date('Y-m-d H:i:s', time()),
        ];
        $result = $this->gen_picture($newInfo);
        if ($result) {
            $newInfo['pic_url'] = $newInfo['no'].'_'.$result.'.png';
            unset($newInfo['date']);
            $newInfo['time'] = $allParams['oldTime'];
            $insertId = $this->genPicM->addPic($newInfo);
            if ($insertId) {
                return $this->output();
            }
        }
    }

    // 获取列表
    public function getLianXunPicList (Request $request)
    {
        $params = $this->validation($request, [
            'pageno' => 'required|numeric',
            'perpage' => 'required|numeric',
            'projId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }

        $offset = $params['perpage'] * ($params['pageno'] - 1);
        $dataList = $this->genPicM->getLianXunPicList($offset, $params['perpage'], $params['projId']);
        return $this->output([
           'dataList' => $dataList,
        ]);
    }

    // 下载
    public function downPic (Request $request)
    {
        $params = $this->validation($request, [
            'id' => 'required|numeric',
        ]);
        $picInfo = $this->genPicM->getPicInfo ($params['id']);
        header('Content-type: image/png');
        header("Content-Disposition: attachment; filename=".$picInfo->pic_url);
    }
}