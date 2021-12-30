<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class StaticPagesController extends Controller
{

    // 主页
    // 3.3章
    public function home(){

        // 10.5章 在主页home方法中，使用模型的feed方法，获得用户微博动态
        $feed_items =[];
        if(Auth::check()){
            $feed_items = Auth::user()->feed()->paginate(30);
        }


        return view('static_pages/home',compact('feed_items'));
    }

    // 帮助页
    // 3.3章
    public function help(){
        return view('static_pages/help');
    }

    // 关于页
    // 3.3章
    public function about(){
        return view('static_pages/about');
    }



}
