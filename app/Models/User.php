<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str; //引入creating方法，用于事件被创建前监听。9.2章

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // 5.4章
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 添加事件创建前的监听方法，9.2章
    public static function boot(){
        parent::boot();

        // boot 方法会在用户模型类完成初始化之后进行加载，
        // 此我们对事件的监听需要放在该方法中。9.2章
        static::creating(function($user){
            $user->activation_token = Str::random(10);
        });
    }


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function gravatar(){
        // $hash = md5(strtolower(trim($this->attributes['email'])));
        return "https://bookcover.yuewen.com/qdbimg/349573/1020180183/180";
    }


    // 在用户模型中，指明一个用户拥有多条微博。
    // 10.2章
    public function statuses(){
        return $this->hasMany(Status::class);
    }


    // 10.5章 实现在微博发布表单下，显示已发微博，并按时间倒序排
    public function feed(){
        return $this->statuses()->orderBy('created_at','desc');
    }

}
