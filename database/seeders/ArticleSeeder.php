<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        $tag = Tag::factory()->create();
        Article::factory(20)
            ->hasTags(5)
            ->create()->each(function (Article $article) use ($tag) {
                $article->tags()->attach($tag->id);
            });
    }
}
