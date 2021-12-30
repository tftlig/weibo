<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Status;

// 10.3章 为微博模型生成假数据的模型工厂
class StatusFactory extends Factory
{
    protected $model = Status::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date_time = $this->faker->date . ' ' . $this->faker->time;
        return [
            'user_id'    => $this->faker->randomElement(['1','2','3']),
            'content'    => $this->faker->text(),
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
    }
}
