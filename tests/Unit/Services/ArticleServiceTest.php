<?php

namespace Tests\Unit\Services;

use App\Models\Article;
use App\Models\ArticleView;
use App\Models\Like;
use App\Models\User;
use App\Services\ArticleService;
use Tests\TestCase;

class ArticleServiceTest extends TestCase
{

    public function testGetAll()
    {
        $perPage = 5;
        $countArticle = 10;
        $articles = Article::factory($countArticle)->create();
        $service = app(ArticleService::class);

        $foundArticles = $service->getAll();
        $this->assertCount($countArticle, $foundArticles->items());

        $foundArticleIds = array_column($foundArticles->items(), 'id');
        $this->assertEquals($foundArticleIds, $articles->pluck('id')->all());

        $foundArticles = $service->getAll($perPage, true);
        $foundArticleIds = array_column($foundArticles->items(), 'id');
        $this->assertEquals(
            $foundArticleIds,
            $articles->reverse()
                ->slice(0, $perPage)
                ->pluck('id')
                ->all());
    }

    public function testFind()
    {
        $article = Article::factory()->create();
        $service = app(ArticleService::class);
        $foundArticle = $service->find($article->id);
        $this->assertNotNull($foundArticle);

        $notFoundArticle = $service->find($article->id + 1);
        $this->assertNull($notFoundArticle);
    }

    public function testFindByIds()
    {
        $articles = Article::factory(10)->create();
        $service = app(ArticleService::class);
        $articleIds = $articles->pluck('id')->toArray();
        $foundArticles = $service->findByIds($articleIds);
        $this->assertEquals($foundArticles->count(), $articles->count());

        $notFoundArticles = $service->findByIds([]);
        $this->assertCount(0, $notFoundArticles);
    }

    public function testRecalculateCountLikes()
    {
        $user = User::factory()->create();
        $admin = User::factory()->create();
        $article = Article::factory()->create();
        $this->assertEquals(0, $article->like_count);
        $service = app(ArticleService::class);

        $yesterday = now()->subDay();
        $userLike = Like::factory()->create([
            'article_id' => $article->id,
            'user_id' => $user->id,
            'updated_at' => $yesterday
        ]);
        $adminLike = Like::factory()->create([
            'article_id' => $article->id,
            'user_id' => $admin->id,
            'updated_at' => $yesterday
        ]);

        $service->recalculateCountLikes($yesterday, $yesterday);
        $article->refresh();
        $this->assertEquals(2, $article->like_count);

        $adminLike->delete();

        $service->recalculateCountLikes(now(), now());
        $article->refresh();
        $this->assertEquals(1, $article->like_count);
    }

    public function testRecalculateCountViews()
    {
        $user = User::factory()->create();
        $admin = User::factory()->create();
        $startViewCount = 50;
        $article = Article::factory()->create(['view_count' => $startViewCount]);
        $this->assertEquals($startViewCount, $article->view_count);
        $service = app(ArticleService::class);

        $userView = ArticleView::factory()->create([
            'article_id' => $article->id,
            'user_id' => $user->id,
            'created_at' => now()
        ]);
        $adminView = ArticleView::factory()->create([
            'article_id' => $article->id,
            'user_id' => $admin->id,
            'created_at' => now()
        ]);

        $service->recalculateCountViews(now()->startOfDay(), now()->endOfDay());
        $article->refresh();
        $this->assertEquals($startViewCount + 2, $article->view_count);
    }
}
