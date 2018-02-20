<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Service;
use App\Models\Invite;
use Redis;

class SMSTips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:tips {seq}';

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
        //Service::sms('13810055038', "[BitCV] http://t.cn/RRiadYN The last day invite friends to get BCV、KCASH、PXC、ICST、BTC、DOGE、EOS、NEO、ETH together!");
        //exit;
        //
        $seq = $this->argument('seq');
        $start = 0;
        $num = 199;
        $msg = '[BitCV] http://t.cn/RRiadYN The last day invite friends to get BCV、KCASH、PXC、ICST、BTC、DOGE、EOS、NEO、ETH together!';
        while (true) {
            $rows = DB::table('mod_invite_2')->where('id','<',40000)->orderBy('id')->offset($start)->limit($num)->get()->toArray();
            if (empty($rows)) {
                break;
            }
            $start += $num;
            $mobs = [];
            foreach ($rows as $row) {
                $u = (array)$row;
                $id = $u['id'];
                $mobile = $u['mobile'];
                if (strlen($mobile) == 11) {
                    $mobs[] = $mobile;
                }
            }
            $m = implode(",", $mobs);
            echo "$m \n $msg \n";
            $ret = Service::sms($m, $msg);
            if ($ret['err'] > 0) {
                echo $m;
                var_dump($ret);
                exit;
            }
            sleep(1);

                /*
                if ($id % 10 != $seq) {
                    continue;
                }
                if (Redis::get('sms_tip_'.$mobile)) {
                    continue;
                }
                if (strlen($mobile) == 11) {
                    $msg = "[BitCV] You've got ";
                    $msg .= $this->getShowCoin($u, ',');
                    $msg .= " visit bitcv.com in march 1-10 to get your token to wallet, invite friends to get more, detail: http://t.cn/RRiadYN";
                    $ret = Service::sms($mobile, $msg);
                    if ($ret['err'] > 0) {
                        echo $mobile;
                        var_dump($ret);
                        exit;
                    }
                    Redis::set('sms_tip_'.$mobile, 1);
                    Redis::expire('sms_tip_'.$mobile, 86400);
                    echo "{$u['id']}\t{$u['mobile']}\n";
                    sleep(1);
                }
            }
                */
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
