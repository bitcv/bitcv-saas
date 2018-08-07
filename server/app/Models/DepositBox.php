<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class DepositBox extends Model
{
    protected $table = 'depo_box';
    protected $guarded = [];
    public static $depoDict = [];

    public function getDepoList () {
        $tokenModel = new Token();

        // 获取余币宝信息
        $depoList = self::where('status', '!=', 0)
            ->orderBy('status', 'desc')
            ->orderBy('interest_rate', 'desc')
            ->orderBy('lock_time')
            ->select('id as depo_id', 'name', 'token_id', 'lock_time', 'min_amount', 'interest_rate', DB::raw('remain_amount > min_amount as status'))
            ->get()->toArray();
        // 获取通证信息
        $tokenIdArr = array_unique(array_column($depoList, 'token_id'));
        $tokenDict = $tokenModel->getTokenDict($tokenIdArr);

        foreach ($depoList as &$depoData) {
            $tokenData = $tokenDict[$depoData['token_id']];
            $depoData['token_symbol'] = $tokenData['symbol'];
        }

        return $depoList;
    }

    public function getDepoData ($depoId) {
        $tokenModel = new Token();

        $depoData = self::select('name', 'token_id', 'lock_time', 'min_amount', 'remain_amount', 'interest_rate', 'max_amount', 'unit_amount')
            ->find($depoId);
        if (!$depoData) {
            return false;
        }
        $tokenId = $depoData['token_id'];
        $tokenDict = $tokenModel->getTokenDict([$tokenId]);
        $tokenData = $tokenDict[$tokenId];
        $depoData['token_symbol'] = $tokenData['symbol'];

        return $depoData;
    }

    public function getDepoDict ($depoIdArr) {
        $staticDepoIdArr = array_keys(self::$depoDict);
        $diffDepoIdArr = array_diff($depoIdArr, $staticDepoIdArr);
        if ($diffDepoIdArr) {
            $diffDepoList = self::whereIn('id', $diffDepoIdArr)->get()->toArray();
            foreach ($diffDepoList as $diffDepoData) {
                self::$depoDict[$diffDepoData['id']] = $diffDepoData;
            }
        }

        return self::$depoDict;
    }
}
