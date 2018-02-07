<?php

namespace App\Modules;

use App\Utils\BlockCypherConfig;
use App\Utils\BlockCypher;


class Transfer {
    
    public function tran() {
        //
        $balance = BlockCypher::getBalance('eth', '0x7906C635689B3A94b3F270411dfE347c6462995C');
        var_dump($balance);exit;
    }

}