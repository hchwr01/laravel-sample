<?php

namespace Database\Factories;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // ランダムな3〜5語の文章をタイトルにする
            'title'       => $this->faker->sentence(3),
            'description' => $this->faker->realText(100),
            // 初期状態は「未完了（0）」にしておく
            'completed'   => Todo::STATUS_NOT_COMPLETED,
        ];
    }
}
