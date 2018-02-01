<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function test() {

        $saas = new \App\Models\Saas();
        $ret = $saas->add(1, 'bcv');

        //$mod = new \App\Models\Module();
        //$ret = $mod->add(1, 1);
        
        var_dump($ret);
    }
}
