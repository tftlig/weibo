<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // 调用call方法来指定我们要运行的假数据填充的文件
        // 8.4章
        Model::unguard();

        $this->call(UsersTableSeeder::class);

        // 10.3章 指定调用微博数据填充文件。
        $this->call(StatusesTableSeeder::class);

        // 11.3章 指定调用 关注 填充文件
        $this->call(FollowersTableSeeder::class);

        Model::reguard();
    }
}
