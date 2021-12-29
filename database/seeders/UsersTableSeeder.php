<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;


// 用户数据填充文件，用于填充用户相关的假数据的Seeder类，
// 8.4章
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(50)->create();

        $user = User::find(1);
        $user->name = 'zhixin';
        $user->email = '173612205@qq.com';
        $user->is_admin = true;  // 添加is_admin字段 8.5章
        $user->save();

        $user = User::find(2);
        $user->name = '李之心';
        $user->email = 'tftlig@sina.com';
        $user->save();
    }
}
