<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        Article::factory(20)
            ->has(Tag::factory())
            ->hasTags(5)
            ->create();
    }
}
