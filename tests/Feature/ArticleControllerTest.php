<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleView;
use App\Models\Like;
use App\Models\User;
use App\Services\ArticleService;
use App\Services\ArticleViewService;
use App\Services\LikeService;
use App\Services\UserService;
use Mockery;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    public function testIndex()
    {
        $perPage = 2;
        $defaultPerPage = 10;

        Article::factory($defaultPerPage)->create();

        $articleServiceMock = Mockery::mock(ArticleService::class);
        $articleServiceMock->shouldReceive('getAll')
            ->andReturns(Article::paginate($defaultPerPage), Article::paginate($perPage));

        $this->app->instance(ArticleService::class, $articleServiceMock);

        $this->get('api/articles')
            ->assertStatus(200)
            ->assertJsonCount($defaultPerPage, 'data');

        $this->get('api/articles?per_page=' . $perPage)
            ->assertStatus(200)
            ->assertJsonCount($perPage, 'data');
    }

    public function testShow()
    {
        $article = Article::factory()->create();
        $user = User::factory()->create();

        $exists = [false, true];
        $notFoundArticleId = $article->id + 1;

        $articleServiceMock = Mockery::mock(ArticleService::class);
        $articleServiceMock->shouldReceive('find')->with($notFoundArticleId, ['tags'])->andReturnNull();
        $articleServiceMock->shouldReceive('find')->with($article->id, ['tags'])->andReturn($article);

        $userServiceMock = Mockery::mock(UserService::class);
        $userServiceMock->shouldReceive('getUser')->andReturn($user);

        $likeServiceMock = Mockery::mock(LikeService::class);
        $likeServiceMock->shouldReceive('isExisting')->andReturns(...$exists);

        $this->app->instance(ArticleService::class, $articleServiceMock);
        $this->app->instance(UserService::class, $userServiceMock);
        $this->app->instance(LikeService::class, $likeServiceMock);

        $this->get('api/articles/' . $notFoundArticleId)
            ->assertStatus(404)
            ->assertJson(['message' => 'Статья не найдена.']);

        foreach ($exists as $exist) {
            $this->get('api/articles/' . $article->id)
                ->assertStatus(200)
                ->assertJson([
                    'is_liked' => $exist
                ]);
        }
    }

    public function testLike()
    {
        $like = Like::factory()->create();
        $notFoundArticleId = $like->article_id + 1;

        $articleServiceMock = Mockery::mock(ArticleService::class);
        $articleServiceMock->shouldReceive('find')->with($notFoundArticleId)->andReturnNull();
        $articleServiceMock->shouldReceive('find')->with($like->article_id)->andReturn($like->article);

        $userServiceMock = Mockery::mock(UserService::class);
        $userServiceMock->shouldReceive('getUser')->andReturn($like->user);

        $likeServiceMock = Mockery::mock(LikeService::class);
        $likeServiceMock->shouldReceive('like')->andReturn($like);

        $this->app->instance(ArticleService::class, $articleServiceMock);
        $this->app->instance(UserService::class, $userServiceMock);
        $this->app->instance(LikeService::class, $likeServiceMock);

        $this->post('api/articles/' . $notFoundArticleId . '/like')
            ->assertStatus(404)
            ->assertJson(['message' => 'Статья не найдена.']);

        $this->post('api/articles/' . $like->article_id . '/like')
            ->assertStatus(200)
            ->assertJson([
                'like_count' => 1,
                'is_can_liked' => false
            ]);

        $like->delete();
        $this->post('api/articles/' . $like->article_id . '/like')
            ->assertStatus(200)
            ->assertJson([
                'like_count' => 0,
                'is_can_liked' => true
            ]);
    }

    public function testView()
    {
        $article = Article::factory()->create();
        $user = User::factory()->create();

        $exists = [false, true];
        $notFoundArticleId = $article->id + 1;

        $articleServiceMock = Mockery::mock(ArticleService::class);
        $articleServiceMock->shouldReceive('find')->with($notFoundArticleId)->andReturnNull();
        $articleServiceMock->shouldReceive('find')->with($article->id)->andReturn($article);

        $userServiceMock = Mockery::mock(UserService::class);
        $userServiceMock->shouldReceive('getUser')->andReturn($user);

        $articleViewService = Mockery::mock(ArticleViewService::class);
        $articleViewService->shouldReceive('isExisting')->andReturns(...$exists);
        $articleViewService->shouldReceive('create')->once()->andReturn(new ArticleView());

        $this->app->instance(ArticleService::class, $articleServiceMock);
        $this->app->instance(UserService::class, $userServiceMock);
        $this->app->instance(ArticleViewService::class, $articleViewService);

        $this->post('api/articles/' . $notFoundArticleId . '/viewed')
            ->assertStatus(404)
            ->assertJson(['message' => 'Статья не найдена.']);

        foreach ($exists as $exist) {
            $this->post('api/articles/' . $article->id . '/viewed')
                ->assertStatus(200)
                ->assertJson([
                    'view_count' => $article->view_count + 1
                ]);
        }
        Mockery::close();
    }
}
