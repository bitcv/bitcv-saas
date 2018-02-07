<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Transfer;

class EthController extends Controller
{
    //
    public function tran() {
        (new Transfer())->tran();
    }
}
