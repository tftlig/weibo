<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

// 11.5章 加上store 和 destroy 动作，分别用于处理「关注」和「取消关注」用户的请求。
class FollowersController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function store(User $user){
        $this->authorize('follow',$user);

        if(!Auth::user()->isFollowing($user->id)){
            Auth::user()->follow($user->id);
        }

        return redirect()->route('users.show',$user->id);
    }

    public function destroy(User $user){
        $this->authorize('follow',$user);

        if(Auth::user()->isFollowing($user->id)){
            Auth::user()->unfollow($user->id);
        }

        return redirect()->route('users.show',$user->id);
    }


}

