<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use Auth;

// 10.4章  微博创建和删除控制器
class StatusesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    // 10.4章，创建微博
    public function store(Request $request){
        $this->validate($request,[
            'content' => 'required|max:140'
        ]);

        Auth::user()->statuses()->create(['content' => $request['content']
        ]);

        session()->flash('success','发布成功');

        return redirect()->back();
    }


    // 10.6章 给微博控制器加上destory动作来处理微博的删除
    public function destroy(Status $status){
        $this->authorize('destroy',$status);
        $status->delete();
        session()->flash('success','微博已被成功删除');
        return redirect()->back();
    }

}
