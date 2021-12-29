<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

class SessionsController extends Controller
{
    // 用 Auth 中间件提供的 guest 选项，指定一些只允许未登录用户访问的动作
    // 8.3章
    public function __construct(){
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

    // 返回登录视图
    // 7.2章
    public function create(){
        return view('sessions.create');
    }

    // 会话：登录验证、认证用户身份、记住我
    // 7.2章
    public function store(Request $request){
        $credentials = $this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials,$request->has('remember'))){
/*             //登录成功后的相关操作
            session()->flash('success','欢迎回来！');

/*             当一个未登录的用户尝试访问自己的资料编辑页面时，将会自动跳转到登录页面，
            这时候如果用户再进行登录，则会重定向到其个人中心页面上，这种方式的用户体验并不好。 */
            // return redirect()->route('users.show',[Auth::user()]);

/*             故采取下面这种新方法，redirect() 实例提供了一个 intended 方法，
            该方法可将页面重定向到上一次请求尝试访问的页面上，并接收一个默认跳转地址参数，
            当上一次请求记录为空时，跳转到默认地址上。 */
            // 8.3章
/*             $fallback = route('users.show',Auth::user());
            return redirect()->intended($fallback);  */

            // 9.2章，对之前的登录代码进行修改，当用户没有激活时，则视为认证失败，
            // 用户将会被重定向至首页，并显示消息提醒去引导用户查收邮件。
            if(Auth::user()->activated) {
               session()->flash('success', '欢迎回来！');
               $fallback = route('users.show', Auth::user());
               return redirect()->intended($fallback);
            } else {
               Auth::logout();
               session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
               return redirect('/');
            }

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

