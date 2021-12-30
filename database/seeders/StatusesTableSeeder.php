<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

// 对微博假数据进行批量生成。
// 10.3章
class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::factory()->count(100)->create();
    }
}
