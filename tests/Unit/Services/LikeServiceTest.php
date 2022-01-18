<?php

namespace Tests\Unit\Services;

use App\Models\Article;
use App\Models\Like;
use App\Models\User;
use App\Services\LikeService;
use Tests\TestCase;

class LikeServiceTest extends TestCase
{
    public function testCreate()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $service = app(LikeService::class);
        $this->assertDatabaseCount(Like::class, 0);
        $service->create($article->id, $user->id);
        $this->assertDatabaseCount(Like::class, 1);
    }

    public function testIsExisting()
    {
        $like = Like::factory()->create();
        $service = app(LikeService::class);
        $this->assertTrue($service->isExisting($like->article_id, $like->user_id));
        $this->assertFalse($service->isExisting($like->article_id + 1, $like->user_id + 1));
    }

    public function testLike()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $service = app(LikeService::class);

        $this->assertDatabaseCount(Like::class, 0);
        $like = $service->like($article->id, $user->id);
        $this->assertDatabaseCount(Like::class, 1);
        $this->assertFalse($like->trashed());

        $like = $service->like($article->id, $user->id);
        $this->assertTrue($like->trashed());

        $like = $service->like($article->id, $user->id);
        $this->assertFalse($like->trashed());
    }
}
