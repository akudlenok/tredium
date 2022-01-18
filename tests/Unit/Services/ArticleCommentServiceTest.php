<?php

namespace Tests\Unit\Services;

use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\User;
use App\Services\ArticleCommentService;
use Tests\TestCase;

class ArticleCommentServiceTest extends TestCase
{
    public function testCreate()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $service = app(ArticleCommentService::class);
        $this->assertDatabaseCount(ArticleComment::class, 0);
        $service->create([
            'article_id' => $article->id,
            'subject' => \Str::random(10),
            'body' => \Str::random(10)
        ], $user->id);
        $this->assertDatabaseCount(ArticleComment::class, 1);
    }
}
