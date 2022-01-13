<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $date = $this->faker->dateTime();
        return [
            'title' => $this->faker->sentence(rand(2, 7)),
            'content' => $this->faker->realTextBetween(200, 1000, 1),
            'img_path' => 'https://via.placeholder.com/640x360',
            'thumb_path' => 'https://via.placeholder.com/384x216',
            'view_count' => rand(1, 500),

            'like_count' => rand(0, 0),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
