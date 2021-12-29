<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Mail;

class UsersController extends Controller
{

    // 使用身份验证（Auth）中间件，过滤未登录用户，执行编辑、更新个人中心动作
    // __construct 是PHP的构造器方法，当一个类对象被创建之前，该方法将会被调用
    // except方法，指定动作不使用Auth中间件过滤
    // 8.3章
    public function __construct(){
        $this->middleware('auth',[
            'except' => ['show','create','store','index','confirmEmail']
        ]);

        // 用 Auth 中间件提供的 guest 选项，指定一些只允许未登录用户访问的动作
        // 8.3章
        $this->middleware('guest',[
            'only' => ['create']
        ]);

        // 9.6章，注册限流，60分钟10次
        $this->middleware('throttle:10,60', [
            'only' => ['store']
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

/*         Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]); */

        // 9.2章，注释之前的登录显示代码，修改用户注册成功之后调用该方法来发送激活邮件
        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');

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

    // 展示用户列表
    // 8.4章
    public function index(){
        // 获取全部用户数据
        // $users = User::all();
        // 分页获取用户数据，分页器paginate()
        $users = User::paginate(6);
        return view('users.index',compact('users'));
    }

    // 为用户列表删除按钮添加删除动作  8.5章
    public function destroy(User $user){
        $this->authorize('destroy',$user); //添加授权，只有管理员才能操作
        $user->delete();
        session()->flash('success','成功删除用户！');
        return back();
    }

    // 9.2章 定制发送邮件给指定用户的方法
    protected function sendEmailConfirmationTo($user){
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'summer@example.com';
        $name = 'Summer';
        $to = $user->email;
        $subject = "感谢注册 Weibo 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    //完成用户的激活操作。 9.2章
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }

}


