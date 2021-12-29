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

        Model::reguard();
    }
}
