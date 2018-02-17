<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Service;
use App\Models\Invite;

class SMSTips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:tips';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //test
        //Service::sms('13810055038', "[BitCV] You've got 8 BCV,0.0003 BTC, visit bitcv.com in march 1-10 to get your token to wallet, invite friends to get more, detail: http://t.cn/RRiadYN");
        //exit;
        //
        $start = 0;
        $num = 100;
        while (true) {
            $rows = DB::table('mod_invite_2')->where('num',0)->orWhere('id','<',1000)->orderBy('id')->offset($start)->limit($num)->get()->toArray();
            if (empty($rows)) {
                break;
            }
            $start += $num;
            foreach ($rows as $row) {
                $u = (array)$row;
                $mobile = $u['mobile'];
                if (strlen($mobile) == 11) {
                    $msg = "[BitCV] You've got ";
                    $msg .= $this->getShowCoin($u, ',');
                    $msg .= " visit bitcv.com in march 1-10 to get your token to wallet, invite friends to get more, detail: http://t.cn/RRiadYN";
                    Service::sms($mobile, $msg);
                    //echo $mobile.': '.$msg . "\n";
                    echo "{$u['id']}\t{$u['mobile']}\n";
                }
            }
            sleep(1);
        }
    }
    private function getShowCoin($data, $s = '<br>') {
        $types = ['bcv', 'doge', 'btc', 'eth', 'eos', 'neo'];
        $str = '';
        foreach ($types as $t) {
            if (isset($data[$t.'_num']) && ($s=='<br>'||$data[$t.'_num']>0)) {
                $num = $data[$t.'_num'];
                if ($t == 'btc') {
                    $num = $num/10000;
                } elseif ($t == 'eth') {
                    $num = $num/1000;
                } elseif ($t == 'neo') {
                    $num = $num/100;
                } elseif ($t == 'eos') {
                    $num = $num/100;
                }
                if ($s == '<br>') {
                    $str .= strtoupper($t).' '.$num.$s;
                } else {
                    $str .= $num.' '.strtoupper($t).$s;
                }
            }
        }
        return $str;
    }

}
