<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class UsersController extends Controller
{

    // 使用身份验证（Auth）中间件，过滤未登录用户，执行编辑、更新个人中心动作
    // __construct 是PHP的构造器方法，当一个类对象被创建之前，该方法将会被调用
    // except方法，指定动作不使用Auth中间件过滤
    // 8.3章
    public function __construct(){
        $this->middleware('auth',[
            'except' => ['show','create','store']
        ]);

        // 用 Auth 中间件提供的 guest 选项，指定一些只允许未登录用户访问的动作
        // 8.3章
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

    // 展示注册页面
    // 4.7章，6.2章
    public function create(){
        return view('users.create');
    }

    // 展示用户个人中心
    // 6.2章
    public function show(User $user){
        return view('users.show',compact('user'));
    }

    // 验证注册信息，保存注册信息到数据库
    // 注册后自动登录,显示欢迎信息，和重新到个人中心
    // 6.6章
    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|unique:users|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]);
    }

    // 编辑用户个人信息页面
    // 8.2章
    public function edit(User $user){

        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    //处理编辑个人信息后，用户提交的数据
    //8.2章
    public function update(User $user,Request $request){

        $this->authorize('update',$user);
        $this->validate($request,[
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        // 第一种认证用户身份的方法
/*         $user->update([
            'name' => $request->name,
            'password' => bcrypt($request->password)
        ]); */

        // 第二种认证用户身份的方法
        $data = [];
        $data['name'] = $request->name;
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success','个人资料更新成功');
        return redirect()->route('users.show',$user->id);

    }

}


