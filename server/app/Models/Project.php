<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models as M;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    protected $table = 'project';
    protected $guarded = [];

    public function getProjDetail ($projId) {

        $params = ['projId' => $projId];
        extract($params);

        // 获取项目基本信息
        // $projData = M\Project::where('id', $projId)
        //     ->select('id', 'name_cn', 'name_en', 'logo_url', 'banner_url', 'abstract', 'white_paper_url', 'home_url', 'view_times', 'token_id', 'node_amount', 'cur_amount', 'plan_amount', 'fund_start_time', 'fund_end_time', 'status', 'admin_id', 'company_addr', 'company_email')
        //     ->first();
        $projData = DB::table('proj_info')->where('id',$projId)
            ->select('id', 'name_cn', 'name_en', 'logo_url', 'banner_url', 'abstract', 'white_paper_url', 'home_url', 'view_times', 'token_id', 'node_amount', 'cur_amount', 'plan_amount', 'fund_start_time', 'fund_end_time', 'status', 'admin_id', 'company_addr', 'company_email')
            ->get();
        if ($projData === null) {
            return $this->error(301);
        }
        $projData->toArray();

        // 获取项目关注数目
        //$projData['focusNum'] = M\UserFocus::where([['proj_id', $projId], ['status', 1]])->count();

        // 获取项目关注状态
        $userId = isset($_COOKIE['userId']) ? $_COOKIE['userId'] : null;
        if (!$userId) {
            $projData['focusStatus'] = 0;
        } else {
            $focusStatus = M\UserFocus::where([['user_id', $userId], ['proj_id', $projId]])->value('status');
            $projData['focusStatus'] = (int)$focusStatus;
        }
/*
        // 获取token信息
        $tokenId = $projData['token_id'];
        unset($projData['token_id']);
        $tokenData = M\Token::where('id', $tokenId)->first();
        if ($tokenData === null) {
            return $this->error(101);
        }
        $projData['tokenName'] = $tokenData->name;
        $projData['tokenSymbol'] = $tokenData->symbol;

        // 获取项目标签
        $projTagList = M\ProjTag::where('proj_id', $projId)
            ->pluck('tag')->toArray();
        $projData['tagList'] = $projTagList;

        // 获取项目优势
        $projAdvList = M\ProjAdvantage::where('proj_id', $projId)
            ->select('title', 'detail')
            ->get()->toArray();
        $projData['advangateList'] = $projAdvList;
*/
        // 获取项目成员信息
        $projMemberList = M\ProjMember::where('proj_id', $projId)
            ->select('photo_url', 'name', 'position', 'intro')
            ->get()->toArray();
        $projData['memberList'] = $projMemberList;

        // 获取项目事件
        $projEventList = M\ProjEvent::where('proj_id', $projId)
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
        $projAdvisorList = M\ProjAdvisor::where('proj_id', $projId)
            ->select('name', 'photo_url', 'company', 'intro')
            ->get()->toArray();
        $projData['advisorList'] = $projAdvisorList;

        // 获取合作伙伴信息
        $projPartnerList = M\ProjPartner::where('proj_id', $projId)
            ->select('name', 'logo_url', 'home_url')
            ->get()->toArray();
        $projData['partnerList'] = $projPartnerList;

        // 获取媒体报道信息
        $projReportList = M\ProjReport::join('proj_media', 'proj_report.media_id', '=', 'proj_media.id')
            ->where('proj_id', $projId)
            ->select('banner_url', 'link_url', 'proj_report.title')
            ->limit(4)->get()->toArray();
        $projData['reportList'] = $projReportList;

        // 获取社交链接信息
        $projSocialList = M\ProjSocial::join('proj_social', 'proj_social.social_id', '=', 'proj_social.id')
            ->where('proj_id', $projId)
            ->select('name', 'font_class', 'link_url')
            ->get()->toArray();
        $projData['socialList'] = $projSocialList;

        return $projData;
    }

}
