<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class UsersController extends Controller
{
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
    public function store(Request $request)
    {
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
        return view('users.edit',compact('user'));
    }

    //处理编辑个人信息后，用户提交的数据
    //8.2章
    public function update(User $user,Request $request){

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
