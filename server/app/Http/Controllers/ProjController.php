<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjController extends Controller
{
    
    //首页
    public function index() {
        return view('proj.index');
    }

    //申请SaaS
    public function apply() {
        return view('proj.apply');
    }

}
