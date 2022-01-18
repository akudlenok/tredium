<?php

namespace Tests\Unit\Services;

use App\Models\Article;
use App\Models\ArticleView;
use App\Models\User;
use App\Services\ArticleViewService;
use Tests\TestCase;

class ArticleViewServiceTest extends TestCase
{
    public function testCreate()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $service = app(ArticleViewService::class);
        $this->assertDatabaseCount(ArticleView::class, 0);
        $service->create($article->id, $user->id);
        $this->assertDatabaseCount(ArticleView::class, 1);
    }

    public function testIsExisting()
    {
        $articleView = ArticleView::factory()->create();
        $service = app(ArticleViewService::class);
        $this->assertTrue($service->isExisting($articleView->article_id, $articleView->user_id));
        $this->assertFalse($service->isExisting($articleView->article_id + 1, $articleView->user_id + 1));
    }
}
