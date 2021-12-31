<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str; //引入creating方法，用于事件被创建前监听。9.2章
use Auth;

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
        // return $this->statuses()->orderBy('created_at','desc');

        // 11.6章调整：微博动态里，把自己的和关注人的微博动态显示出来
        $user_ids = $this->followings->pluck('id')->toArray();
        array_push($user_ids,$this->id);

        return Status::whereIn('user_id',$user_ids)->with('user')->orderBy('created_at','desc');
    }


    // 11.2章 一个用户可以有多个粉丝。belongsToMany方法里的第二个参数，是关联关系表。
    // 第三个参数（user_id） 和 第四个参数（follower_id），是关联模型在关联关系表中的外键
    public function followers(){
        return $this->belongsToMany(User::class,'followers','user_id','follower_id');
    }

    // 11.2章 一个粉丝，可以关注多个用户
    public function followings(){
        return $this->belongsToMany(User::class,'followers','follower_id','user_id');
    }


/*     11.2章 关注。is_array 用于判断参数是否为数组，如果已经是数组，
    则没有必要再使用 compact 方法。我们并没有给 sync 和 detach
    指定传递参数为用户的 id，这两个方法会自动获取数组中的 id。 */
    public function follow($user_ids){
        if(!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids,false);
    }

    // 11.2章 取消关注
    public function unfollow($user_ids){
        if(!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }


/*     11.2章 我们还需要一个方法用于判断当前登录的用户 A
    是否关注了用户 B，代码实现逻辑很简单，我们只需判断用户 B
    是否包含在用户 A 的关注人列表上即可。 */
    // 这里我们将用到 contains 方法来做判断。
    public function isFollowing($user_id){
        return $this->followings->contains($user_id);
    }

}
