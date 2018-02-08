<?php
/**
 * 后台管理接口
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models as Model;
use Illuminate\Support\Facades\DB;
use Service;

class ProjectController extends Controller
{
    //域名解析projid
    public function getProjId() {
        return app()->proj['proj_id'];
    }

    public function login(Request $req) {
        $username = $req->input('username');
        if ($username != 'admin') {
            return $this->error(401);
        }
        session()->put('proj_admin', ['uname'=>'admin']);
        return $this->output([]);
    }
    public function checkLogin() {
        return !empty(session()->get('proj_admin'));
    }

    public function admin() {
        return view('proj.admin');
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

        $offset = $perpage * ($pageno - 1);
        $projList = Model\Project::offset($offset)->limit($perpage)->get()->toArray();
        $dataCount = Model\Project::count();

        return $this->output([
            'dataCount' => $dataCount,
            'dataList' => $projList
        ]);
    }

    public function getProjTopList (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'type' => 'required|string',
            'count' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        if ($type === 'view') {
            $projList = Model\Project::orderBy('view_times', 'desc')
                ->select('id as proj_id', 'name_cn', 'view_times as count')->limit($count)
                ->get()->toArray();
            return $this->output($projList);
        }
        if ($type === 'focus') {
            $focusList = Model\UserFocus::select('proj_id', DB::raw('COUNT(proj_id) as count'))
                ->groupBy('proj_id')->orderBy('count', 'desc')->limit($count)
                ->get()->toArray();
            foreach ($focusList as &$focus) {
                $project = Model\Project::find($focus['proj_id']);
                $focus['name_cn'] = $project->name_cn;
            }
            return $this->output($focusList);
        }
    }

    public function getProjDetail (Request $request) {

        // 获取请求参数
        $params = ['projId' => $this->getProjId()];
        extract($params);

        // 获取项目基本信息
        $projData = Model\Project::where('id', $projId)
            ->select('id', 'name_cn', 'name_en', 'logo_url', 'banner_url', 'abstract', 'white_paper_url', 'home_url', 'view_times', 'token_id', 'node_amount', 'cur_amount', 'plan_amount', 'fund_start_time', 'fund_end_time', 'status', 'admin_id', 'company_addr', 'company_email')
            ->first();
        if ($projData === null) {
            return $this->error(301);
        }
        $projData->toArray();

        // 获取项目关注数目
        $projData['focusNum'] = Model\UserFocus::where([['proj_id', $projId], ['status', 1]])->count();

        // 获取项目关注状态
        $userId = isset($_COOKIE['userId']) ? $_COOKIE['userId'] : null;
        if (!$userId) {
            $projData['focusStatus'] = 0;
        } else {
            $focusStatus = Model\UserFocus::where([['user_id', $userId], ['proj_id', $projId]])->value('status');
            $projData['focusStatus'] = (int)$focusStatus;
        }

        // 获取token信息
        $tokenId = $projData['token_id'];
        unset($projData['token_id']);
        $tokenData = Model\Token::where('id', $tokenId)->first();
        if ($tokenData === null) {
            return $this->error(101);
        }
        $projData['tokenName'] = $tokenData->name;
        $projData['tokenSymbol'] = $tokenData->symbol;

        // 获取项目标签
        $projTagList = Model\ProjTag::where('proj_id', $projId)
            ->pluck('tag')->toArray();
        $projData['tagList'] = $projTagList;

        // 获取项目优势
        $projAdvList = Model\ProjAdvantage::where('proj_id', $projId)
            ->select('title', 'detail')
            ->get()->toArray();
        $projData['advangateList'] = $projAdvList;

        // 获取项目成员信息
        $projMemberList = Model\ProjMember::where('proj_id', $projId)
            ->select('photo_url', 'name', 'position', 'intro')
            ->get()->toArray();
        $projData['memberList'] = $projMemberList;

        // 获取项目事件
        $projEventList = Model\ProjEvent::where('proj_id', $projId)
            ->select('occur_time', 'title', 'detail')
            ->orderBy('occur_time', 'desc')->get()->toArray();
        $resultList = array();
        foreach ($projEventList as $event) {
            $timestamp = strtotime($event['occur_time']);
            $year = date('Y', $timestamp);
            $month = date('m', $timestamp);
            $season = ceil($month / 3);
            $key = $year . '年 第' . $season . '季度';
            $isExist = false;
            foreach ($resultList as &$eventItem) {
                if ($eventItem['eventKey'] === $key) {
                    $isExist = true;
                    $eventItem['eventNode'][] = array(
                        'time' => date('m月d日', $timestamp),
                        'title' => $event['title']
                    );
                }
            }
            if (!$isExist) {
                $resultList[] = array(
                    'eventKey' => $key,
                    'eventNode' => array(
                        array(
                            'time' => date('m月d日', $timestamp),
                            'title' => $event['title']
                        )
                    )
                );
            }
        }
        $projData['eventList'] = $resultList;

        // 获取项目顾问信息
        $projAdvisorList = Model\ProjAdvisor::where('proj_id', $projId)
            ->select('name', 'photo_url', 'company', 'intro')
            ->get()->toArray();
        $projData['advisorList'] = $projAdvisorList;

        // 获取合作伙伴信息
        $projPartnerList = Model\ProjPartner::where('proj_id', $projId)
            ->select('name', 'logo_url', 'home_url')
            ->get()->toArray();
        $projData['partnerList'] = $projPartnerList;

        // 获取媒体报道信息
        $projReportList = Model\ProjReport::join('media', 'proj_report.media_id', '=', 'media.id')
            ->where('proj_id', $projId)
            ->select('name', 'logo_url', 'link_url', 'title')
            ->limit(4)->get()->toArray();
        $projData['reportList'] = $projReportList;

        // 获取社交链接信息
        $projSocialList = Model\ProjSocial::join('social', 'proj_social.social_id', '=', 'social.id')
            ->where('proj_id', $projId)
            ->select('name', 'font_class', 'link_url')
            ->get()->toArray();
        $projData['socialList'] = $projSocialList;

        return $this->output($projData);
    }

    public function getProjBasicInfo (Request $request) {
        if (!$this->checkLogin()) {
            return $this->error(400);
        }

        $params = ['projId' => $this->getProjId()];
        extract($params);
        // 获取项目基本信息
        $projBasicInfo = Model\Project::where('id', $projId)->first();
        if ($projBasicInfo == null) {
            return $this->error(301);
        }

        // 获取项目token
        $tokenId = $projBasicInfo->token_id;
        if ($tokenId) {
            $tokenModel = Model\Token::where('id', $tokenId)->first();
            $projBasicInfo['tokenName'] = $tokenModel->name;
            $projBasicInfo['tokenSymbol'] = $tokenModel->symbol;
            $projBasicInfo['tokenPrice'] = $tokenModel->price;
        }

        return $this->output($projBasicInfo);
    }

    public function updProjBasicInfo (Request $request) {
        // 获取请求参数
        $params = $this->validation($request, [
            'nameCn' => 'required|string',
            'nameEn' => 'required|string',
            'foundDate' => 'string|nullable',
            'logoUrl' => 'string|nullable',
            'homeUrl' => 'string|nullable',
            'shortDesc' => 'string|nullable',
            'abstract' => 'string|nullable',
            'region' => 'numeric|nullable',
            'buzType' => 'numeric|nullable',
            'stage' => 'numeric|nullable',
            'fundStage' => 'numeric|nullable',
            'whitePaperUrl' => 'string|nullable',
            'fundStartTime' => 'string|nullable',
            'fundEndTime' => 'string|nullable',
            'companyEmail' => 'string|nullable',
            'companyAddr' => 'string|nullable',
            'bannerUrl' => 'string|nullable',
            'tokenName' => 'string|nullable',
            'tokenSymbol' => 'string|nullable',
            'tokenPrice' => 'string|nullable',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $projId = $this->getProjId();
        $projInfo = [];
        foreach ($params as $k => $v) {
            if ($v) {
                $k = Service::lineToHump($k);
                $projInfo[$k] = $v;
            }
        }

        if (isset($tokenName) && isset($tokenSymbol)) {
            $tokenModel = Model\Token::firstOrCreate([
                'name' => $tokenName,
                'symbol' => $tokenSymbol,
                'price' => $tokenPrice ?: 0,
            ]);
            $projInfo['token_id'] = $tokenModel->id;
        }
        

        Model\Project::where('id', $projId)->update($projInfo);
        /*
        foreach ($tagList as $tag) {
            Model\ProjTag::firstOrCreate(['proj_id' => $projId, 'tag' => $tag]);
        }
        */

        return $this->output();
    }

    public function addProjMember (Request $request) {
        $params = $this->validation($request, [
            'name' => 'required|string',
            'photoUrl' => 'required|string',
            'position' => 'required|string',
            'intro' => 'required|string',
        ]);
        $params['projId'] = $this->getProjId();
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjMember::firstOrCreate([
            'proj_id' => $projId,
            'name' => $name,
            'photo_url' => $photoUrl,
            'position' => $position,
            'intro' => $intro,
        ]);

        return $this->output();
    }

    public function getProjMemberList (Request $request) {
        $params = ['projId' => $this->getProjId()];
        extract($params);

        $projMemberList = Model\ProjMember::where('proj_id', $projId)->get()->toArray();

        return $this->output(['dataList' => $projMemberList]);
    }

    public function updProjMember (Request $request) {
        $params = $this->validation($request, [
            'memberId' => 'required|numeric',
            'name' => 'required|string',
            'photoUrl' => 'required|string',
            'position' => 'required|string',
            'intro' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjMember::where('id', $memberId)->update([
            'name' => $name,
            'photo_url' => $photoUrl,
            'position' => $position,
            'intro' => $intro,
        ]);

        return $this->output();
    }

    public function delProjMember (Request $request) {
        $params = $this->validation($request, [
            'memberId' => 'required|numeric'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjMember::where('id', $memberId)->delete();

        return $this->output();
    }

    public function addProjEvent (Request $request) {
        $params = $this->validation($request, [
            'occurTime' => 'required|string',
            'title' => 'required|string',
            'detail' => 'required|string',
        ]);
        $params['projId'] = $this->getProjId();
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjEvent::firstOrCreate([
            'proj_id' => $projId,
            'occur_time' => date('Y-m-d H:i:s', strtotime($occurTime)),
            'title' => $title,
            'detail' => $detail,
        ]);

        return $this->output();
    }

    public function getProjEventList (Request $request) {
        $params = ['projId' => $this->getProjId()];
        extract($params);

        $projEventList = Model\ProjEvent::where('proj_id', $projId)->get()->toArray();

        return $this->output(['dataList' => $projEventList]);
    }

    public function updProjEvent (Request $request) {
        $params = $this->validation($request, [
            'eventId' => 'required|numeric',
            'occurTime' => 'required|string',
            'title' => 'required|string',
            'detail' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjEvent::where('id', $eventId)->update([
            'occur_time' => date('Y-m-d H:i:s', strtotime($occurTime)),
            'title' => $title,
            'detail' => $detail,
        ]);

        return $this->output();
    }

    public function delProjEvent (Request $request) {
        $params = $this->validation($request, [
            'eventId' => 'required|numeric'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjEvent::where('id', $eventId)->delete();

        return $this->output();
    }

    public function addProjSocial (Request $request) {
        $params = $this->validation($request, [
            'socialId' => 'required|numeric',
            'linkUrl' => 'required|string',
        ]);
        $params['projId'] = $this->getProjId();
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $linkUrl = strpos($linkUrl, 'http') === 0 ? $linkUrl : 'http://' . $linkUrl;

        $isExist = Model\Social::where('id', $socialId)->count();
        if (!$isExist) {
            $this->error(302);
        }

        Model\ProjSocial::firstOrCreate([
            'proj_id' => $projId,
            'social_id' => $socialId,
            'link_url' => $linkUrl,
        ]);

        return $this->output();
    }

    public function getProjSocialList (Request $request) {
        $params = ['projId' => $this->getProjId()];
        extract($params);

        $projSocialList = Model\ProjSocial::where('proj_id', $projId)
            ->join('social', 'proj_social.social_id', '=', 'social.id')
            ->select('proj_social.id', 'name', 'social_id', 'font_class', 'link_url')
            ->get()->toArray();

        return $this->output(['dataList' => $projSocialList]);
    }

    public function updProjSocial (Request $request) {
        $params = $this->validation($request, [
            'projSocialId' => 'required|numeric',
            'socialId' => 'required|numeric',
            'linkUrl' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $linkUrl = strpos($linkUrl, 'http') === 0 ? $linkUrl : 'http://' . $linkUrl;

        $isExist = Model\Social::where('id', $socialId)->count();
        if (!$isExist) {
            $this->error(302);
        }

        Model\ProjSocial::where('id', $projSocialId)->update([
            'social_id' => $socialId,
            'link_url' => $linkUrl,
        ]);

        return $this->output();
    }

    public function delProjSocial (Request $request) {
        $params = $this->validation($request, [
            'projSocialId' => 'required|numeric'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjSocial::where('id', $projSocialId)->delete();

        return $this->output();
    }

    public function addProjPartner (Request $request) {
        $params = $this->validation($request, [
            'name' => 'required|string',
            'logoUrl' => 'required|string',
            'homeUrl' => 'required|string',
        ]);
        $params['projId'] = $this->getProjId();
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $homeUrl = strpos($homeUrl, 'http') === 0 ? $homeUrl : 'http://' . $homeUrl;

        Model\ProjPartner::firstOrCreate([
            'proj_id' => $projId,
            'name' => $name,
            'logo_url' => $logoUrl,
            'home_url' => $homeUrl,
        ]);

        return $this->output();
    }

    public function getProjPartnerList (Request $request) {
        $params = ['projId' => $this->getProjId()];
        extract($params);

        $projPartnerList = Model\ProjPartner::where('proj_id', $projId)->get()->toArray();

        return $this->output(['dataList' => $projPartnerList]);
    }

    public function updProjPartner (Request $request) {
        $params = $this->validation($request, [
            'partnerId' => 'required|numeric',
            'name' => 'required|string',
            'logoUrl' => 'required|string',
            'homeUrl' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $homeUrl = strpos($homeUrl, 'http') === 0 ? $homeUrl : 'http://' . $homeUrl;

        Model\ProjPartner::where('id', $partnerId)->update([
            'name' => $name,
            'logo_url' => $logoUrl,
            'home_url' => $homeUrl,
        ]);

        return $this->output();
    }

    public function delProjPartner (Request $request) {
        $params = $this->validation($request, [
            'partnerId' => 'required|numeric'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjPartner::where('id', $partnerId)->delete();

        return $this->output();
    }

    public function addProjReport (Request $request) {
        $params = $this->validation($request, [
            'mediaId' => 'required|numeric',
            'title' => 'required|string',
            'linkUrl' => 'required|string',
        ]);
        $params['projId'] = $this->getProjId();
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $linkUrl = strpos($linkUrl, 'http') === 0 ? $linkUrl : 'http://' . $linkUrl;

        $isExist = Model\Media::where('id', $mediaId)->count();
        if (!$isExist) {
            $this->error(303);
        }

        Model\ProjReport::firstOrCreate([
            'proj_id' => $projId,
            'media_id' => $mediaId,
            'title' => $title,
            'link_url' => $linkUrl,
        ]);

        return $this->output();
    }

    public function getProjReportList (Request $request) {
        $params = ['projId' => $this->getProjId()];
        extract($params);

        $projReportList = Model\ProjReport::where('proj_id', $projId)
            ->join('media', 'proj_report.media_id', '=', 'media.id')
            ->select('proj_report.id', 'media_id', 'name', 'title', 'logo_url', 'link_url')
            ->get()->toArray();

        return $this->output(['dataList' => $projReportList]);
    }

    public function updProjReport (Request $request) {
        $params = $this->validation($request, [
            'projReportId' => 'required|numeric',
            'mediaId' => 'required|numeric',
            'title' => 'required|string',
            'linkUrl' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $linkUrl = strpos($linkUrl, 'http') === 0 ? $linkUrl : 'http://' . $linkUrl;

        $isExist = Model\Media::where('id', $mediaId)->count();
        if (!$isExist) {
            $this->error(303);
        }

        Model\ProjReport::where('id', $projReportId)->update([
            'media_id' => $mediaId,
            'title' => $title,
            'link_url' => $linkUrl,
        ]);

        return $this->output();
    }

    public function delProjReport (Request $request) {
        $params = $this->validation($request, [
            'projReportId' => 'required|numeric'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjReport::where('id', $projReportId)->delete();

        return $this->output();
    }

    public function addProjAdvisor (Request $request) {
        $params = $this->validation($request, [
            'name' => 'required|string',
            'photoUrl' => 'required|string',
            'company' => 'required|string',
            'intro' => 'required|string',
        ]);
        $params['projId'] = $this->getProjId();
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjAdvisor::firstOrCreate([
            'proj_id' => $projId,
            'name' => $name,
            'photo_url' => $photoUrl,
            'company' => $company,
            'intro' => $intro,
        ]);

        return $this->output();
    }

    public function getProjAdvisorList (Request $request) {
        $params = ['projId' => $this->getProjId()];
        extract($params);

        $projAdvisorList = Model\ProjAdvisor::where('proj_id', $projId)->get()->toArray();

        return $this->output(['dataList' => $projAdvisorList]);
    }

    public function updProjAdvisor (Request $request) {
        $params = $this->validation($request, [
            'advisorId' => 'required|numeric',
            'name' => 'required|string',
            'photoUrl' => 'required|string',
            'company' => 'required|string',
            'intro' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjAdvisor::where('id', $advisorId)->update([
            'name' => $name,
            'photo_url' => $photoUrl,
            'company' => $company,
            'intro' => $intro,
        ]);

        return $this->output();
    }

    public function delProjAdvisor (Request $request) {
        $params = $this->validation($request, [
            'advisorId' => 'required|numeric'
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\ProjAdvisor::where('id', $advisorId)->delete();

        return $this->output();
    }

    public function addProject (Request $request) {

        // 获取请求参数
        $params = $this->validation($request, [
            'nameCn' => 'required|string',
            'nameEn' => 'required|string',
            'foundDate' => 'required|string',
            'logoUrl' => 'required|string',
            'homeUrl' => 'required|string',
            'shortDesc' => 'required|string',
            'whitePaperUrl' => 'required|string',
            'abstract' => 'required|string',
            'region' => 'required|numeric',
            'buzType' => 'required|numeric',
            'stage' => 'required|numeric',
            'fundStage' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $homeUrl = strpos($homeUrl, 'http') === 0 ? $homeUrl : 'http://' . $homeUrl;

        // 创建项目
        $projModel = Model\Project::firstOrCreate([
            'name_cn' => $nameCn,
            'name_en' => $nameEn,
            'found_date' => $foundDate,
            'logo_url' => $logoUrl,
            'home_url' => $homeUrl,
            'white_paper_url' => $whitePaperUrl,
            'region' => $region,
            'stage' => $stage,
            'buz_type' => $buzType,
            'abstract' => $abstract,
            'fund_stage' => $fundStage,
            'short_desc' => $shortDesc,
            'found_date' => date('Y-m-d H-i-s', strtotime($foundDate)),
        ]);

        return $this->output(['projId' => $projModel->id]);
    }

    public function delProject (Request $request) {
        $params = ['projId' => $this->getProjId()];
        extract($params);

        Model\ProjAdvantage::where('proj_id', $projId)->delete();
        Model\ProjAdvisor::where('proj_id', $projId)->delete();
        Model\ProjEvent::where('proj_id', $projId)->delete();
        Model\ProjMember::where('proj_id', $projId)->delete();
        Model\ProjPartner::where('proj_id', $projId)->delete();
        Model\ProjReport::where('proj_id', $projId)->delete();
        Model\ProjSocial::where('proj_id', $projId)->delete();
        Model\ProjTag::where('proj_id', $projId)->delete();
        Model\Project::where('id', $projId)->delete();

        return $this->output();
    }

    public function getProjTagList (Request $request) {
        $regionOptionList = [
            array('label' => '不限', 'value' => 0),
            array('label' => '国内', 'value' => 1),
            array('label' => '国外', 'value' => 2),
        ];
        $buzOptionList = [
            array('label' => '不限', 'value' => 0),
            array('label' => '金融', 'value' => 1),
            array('label' => '数字货币', 'value' => 2),
            array('label' => '娱乐', 'value' => 3),
            array('label' => '供应链管理', 'value' => 4),
            array('label' => '法律服务', 'value' => 5),
            array('label' => '医疗', 'value' => 6),
            array('label' => '能源服务', 'value' => 7),
            array('label' => '公益', 'value' => 8),
            array('label' => '物联网', 'value' => 9),
            array('label' => '农业', 'value' => 10),
            array('label' => '社交', 'value' => 11),
            array('label' => '其它', 'value' => 100),
        ];
        $stageOptionList = [
            array('label' => '不限', 'value' => 0),
            array('label' => '初创期', 'value' => 1),
            array('label' => '成长发展期', 'value' => 2),
            array('label' => '上市公司', 'value' => 3),
            array('label' => '成熟期', 'value' => 4),
        ];

        return $this->output([
            'region' => array('label' => '地区', 'default' => 0, 'optionList' => $regionOptionList),
            'buzType' => array('label' => '行业', 'default' => 0, 'optionList' => $buzOptionList),
            'stage' => array('label' => '阶段', 'default' => 0, 'optionList' => $stageOptionList),
        ]);
    }

    public function index(){
        $projectList = Model\Project::where('id',1)->get()->toArray();
        return Model\Project::all(); //bad
    }

    public function getPList(Request $request){

        $params = $this->validation($request, [
            'p' => 'required|numeric',
            'chid' => 'string|nullable',
        ]);

        if ($params === false) {
            return $this->error(100);
        }
        extract($params);
        $offset = 6 * ($p - 1);
        if($chid){
//            $projList = Model\Project::join('proj_tag','project.id','=','proj_tag.proj_id')->where('tag','like',"%$chid%")
//                ->offset($offset)->limit(6)->get()->toArray();
            $projList = Model\Project::where('buz_type',$chid)
                ->offset($offset)->limit(6)->get()->toArray();
        }else{
            $projList = Model\Project::offset($offset)->limit(6)->get()->toArray();
        }

        return response()->json($projList);
    }

    public function getProInfo(Request $request)
    {

        $params = $this->validation($request, [
            'id' => 'required|numeric',
        ]);
        extract($params);

        // 获取项目基本信息
        $projData = Model\Project::where('id', $id)
            ->select('name_cn','logo_url', 'name_en', 'short_desc', 'abstract', 'white_paper_url', 'home_url', 'view_times', 'token_id', 'node_amount', 'cur_amount', 'plan_amount', 'fund_start_time', 'fund_end_time', 'status', 'admin_id')
            ->first();

        $projData->toArray();

        // 获取项目优势
        $projAdvList = Model\ProjAdvantage::where('proj_id', $id)
            ->select('title', 'detail')
            ->get()->toArray();
        $projData['advangateList'] = $projAdvList;

        // 获取项目成员信息
        $projMemberList = Model\ProjMember::where('proj_id', $id)
            ->select('photo_url', 'name', 'position', 'intro')
            ->get()->toArray();
        $projData['memberList'] = $projMemberList;

        // 获取项目事件
        $projEventList = Model\ProjEvent::where('proj_id', $id)
            ->select('occur_time', 'title', 'detail')
            ->get()->toArray();
        $projData['eventList'] = $projEventList;

        // 获取合作伙伴信息
        $projPartnerList = Model\ProjPartner::where('proj_id', $id)
            ->select('name')
            ->get()->toArray();
        $projData['partnerList'] = $projPartnerList;

        // 获取媒体报道信息
//        $projMediaList = Model\ProjMedia::where('proj_id', $id)
//            ->get()->toArray();
//        $projData['mediaList'] = $projMediaList;

        // 获取社交链接信息
        $projSocialList = Model\ProjSocial::where('proj_id', $id)
            ->get()->toArray();
        $projData['socialList'] = $projSocialList;

        return response()->json($projData);
    }


    public function gerNewsList(){

    }


    public function getMediaList (Request $request) {
        $mediaList = Model\Media::select('id', 'name', 'logo_url', 'title_reg', 'release_time_reg', 'banner_url_reg', 'content_reg')->get()->toArray();

        return $this->output(['dataList' => $mediaList]);
    }

    public function addMedia (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'name' => 'required|string',
            'logoUrl' => 'required|string',
            'titleReg' => 'string|nullable',
            'releaseTimeReg' => 'string|nullable',
            'bannerUrlReg' => 'string|nullable',
            'contentReg' => 'string|nullable',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $mediaData = [
            'name' => $name,
            'logo_url' => $logoUrl,
        ];
        if ($titleReg) {
            $mediaData['title_reg'] = $titleReg;
        }
        if ($releaseTimeReg) {
            $mediaData['release_time_reg'] = $releaseTimeReg;
        }
        if ($bannerUrlReg) {
            $mediaData['banner_url_reg'] = $bannerUrlReg;
        }
        if ($contentReg) {
            $mediaData['content_reg'] = $contentReg;
        }
        Model\Media::firstOrCreate($mediaData);

        return $this->output();
    }

    public function updMedia (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'mediaId' => 'required|numeric',
            'name' => 'required|string',
            'logoUrl' => 'required|string',
            'titleReg' => 'string|nullable',
            'releaseTimeReg' => 'string|nullable',
            'bannerUrlReg' => 'string|nullable',
            'contentReg' => 'string|nullable',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        $mediaData = [
            'name' => $name,
            'logo_url' => $logoUrl,
        ];
        if ($titleReg) {
            $mediaData['title_reg'] = $titleReg;
        }
        if ($releaseTimeReg) {
            $mediaData['release_time_reg'] = $releaseTimeReg;
        }
        if ($bannerUrlReg) {
            $mediaData['banner_url_reg'] = $bannerUrlReg;
        }
        if ($contentReg) {
            $mediaData['content_reg'] = $contentReg;
        }

        Model\Media::where('id', $mediaId)->update($mediaData);

        return $this->output();
    }

    public function delMedia (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'mediaId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\Media::where('id', $mediaId)->delete();

        return $this->output();
    }

    public function getSocialList (Request $request) {
        $socialList = Model\Social::select('id', 'font_class', 'name')->get()->toArray();

        return $this->output(['dataList' => $socialList]);
    }

    public function addSocial (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'name' => 'required|string',
            'fontClass' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\Social::firstOrCreate([
            'name' => $name,
            'font_class' => $fontClass,
        ]);

        return $this->output();
    }

    public function updSocial (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'socialId' => 'required|numeric',
            'name' => 'required|string',
            'fontClass' => 'required|string',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\Social::where('id', $socialId)->update([
            'name' => $name,
            'font_class' => $fontClass,
        ]);

        return $this->output();
    }

    public function delSocial (Request $request) {
        //获取请求参数
        $params = $this->validation($request, [
            'socialId' => 'required|numeric',
        ]);
        if ($params === false) {
            return $this->error(100);
        }
        extract($params);

        Model\Social::where('id', $socialId)->delete();

        return $this->output();
    }

}
