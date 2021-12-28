<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

class SessionsController extends Controller
{
    // 返回登录视图
    // 7.2章
    public function create()
    {
        return view('sessions.create');
    }

    // 会话：登录验证、认证用户身份、记住我
    // 7.2章
    public function store(Request $request)
    {
        $credentials = $this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials,$request->has('remember'))){
            //登录成功后的相关操作
            session()->flash('success','欢迎回来！');
            return redirect()->route('users.show',[Auth::user()]);
        }else{
            //登录失败后的相关操作
            session()->flash('danger','很抱歉，你的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
    }

    // 用户退出功能
    // 7.4章
    public function destroy(){
        Auth::logout();
        session()->flash('success','您已成功退出！');
        return redirect('login');
    }
}

