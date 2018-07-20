<?php

namespace App\Http\Controllers;

use App\Service\PacketStatService;
use App\Utils\Service;
use Illuminate\Http\Request;

class PacketStatController extends Controller
{
    //
    /*public function getPacketStatByMonth(Request $request)
    {
        $yearMonth = $request->get('month','2018-01');
        $beginDate = Service::getCurMonthFirstDay($yearMonth);
        $endDate = Service::getCurMonthLastDay($beginDate);
        $nowStamp = time();
        $endDate = strtotime($endDate) > $nowStamp ? date('Y-m-d',$nowStamp) : $endDate;
        $tokenId = $request->get('tokenId');
        \Log::info('getPacektStat$tokenId'.$tokenId);
        $data = PacketStatService::getStatDataByDay($beginDate,$endDate,$tokenId);
        \Log::info('getPacektStat'.var_export($data,true));
        foreach ($data as &$item) {
            $sentPacketRate = isset($item['numsOfSendPacketMember']) && $item['numsOfSendPacketMember'] > 0 ? bcdiv($item['numsOfPacketsSent'],$item['numsOfSendPacketMember'],2) : 0;
            $avgAmountOfPacket = isset($item['numsOfPacketsSent']) && $item['numsOfPacketsSent'] > 0 ?  bcdiv($item['amountOfCandySent'],$item['numsOfPacketsSent'],2) : 0;
            $item = array_add($item,'sentPacketRate',number_format($sentPacketRate,2,'.',','));
            $item = array_add($item,'avgAmountOfPacket',number_format($avgAmountOfPacket,2,'.',','));
            $item['amountOfCandyPicked'] = number_format($item['amountOfCandyPicked'],2,'.',',');
            $item['cnyValueOfTokenSent'] = number_format($item['cnyValueOfTokenSent'],2,'.',',');
        }
        $results = [];
        foreach ($data as $key => $value) {
            $results[] = $value;
        }
        return $this->output($results);
    }*/
    public function getPacketStatByMonth(Request $request)
    {
        $yearMonth = $request->get('month','2018-01');
        $beginDate = Service::getCurMonthFirstDay($yearMonth);
        $endDate = Service::getCurMonthLastDay($beginDate);
        $nowStamp = time();
        $endDate = strtotime($endDate) > $nowStamp ? date('Y-m-d',$nowStamp) : $endDate;
        $tokenId = (int)$request->get('tokenId',0);
        $tokenId = $tokenId >= 0 ? $tokenId : 0;
        $data = PacketStatService::getStatDataByDay($beginDate,$endDate,$tokenId);
        foreach ($data['dailyData'] as &$item) {
            $sentPacketRate = $item['numsOfSendPacketMember'] > 0 ? bcdiv($item['numsOfPacketsSent'],$item['numsOfSendPacketMember'],2) : 0;
            $avgAmountOfPacket = $item['numsOfPacketsSent'] > 0 ?  bcdiv($item['amountOfCandySent'],$item['numsOfPacketsSent'],2) : 0;
            $item = array_add($item,'sentPacketRate',number_format($sentPacketRate,2,'.',','));
            $item = array_add($item,'avgAmountOfPacket',number_format($avgAmountOfPacket,2,'.',','));
            $item['amountOfCandyPicked'] = number_format($item['amountOfCandyPicked'],2,'.',',');
            $item['cnyValueOfTokenSent'] = number_format($item['cnyValueOfTokenSent'],2,'.',',');
        }
        $results = [];
        foreach ($data['dailyData'] as $value) {
            $results[] = $value;
        }
        return $this->output($results);
        /*return $this->output([
            'dailyData' => $results,
            'numsOfPickPacketMemberTotal' => $data['numsOfPickPacketMemberTotal'],
            'numsOfSendPacketMemberTotal' => $data['numsOfSendPacketMemberTotal'],
        ]);*/
    }

}
