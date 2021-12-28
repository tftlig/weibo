<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPagesController extends Controller
{

    // 主页
    // 3.3章
    public function home()
    {
        return view('static_pages/home');
    }

    // 帮助页
    // 3.3章
    public function help()
    {
        return view('static_pages/help');
    }

    // 关于页
    // 3.3章
    public function about()
    {
        return view('static_pages/about');
    }



}
