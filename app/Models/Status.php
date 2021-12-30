<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// 微博模型，用于操作数据表（statuses）
// 10.2章
class Status extends Model
{
    use HasFactory;

    // 10.4章 添加fillable属性中允许更新的字段
    protected $fillable = ['content'];

    // 如下，我们可在微博模型中，指明一条微博属于一个用户。
    // 10.2章
    public function user(){
        return $this->belongsTo(User::class);
    }
}
