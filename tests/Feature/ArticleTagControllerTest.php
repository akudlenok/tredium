<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Tag;
use App\Services\TagService;
use Mockery;
use Tests\TestCase;

class ArticleTagControllerTest extends TestCase
{
    public function testShow()
    {
        $articleCount = 5;
        $tag = Tag::factory()->has(Article::factory($articleCount))->create();
        $notFoundTagId = $tag->id + 1;
        $with = ['articles'];

        $tagServiceMock = Mockery::mock(TagService::class);
        $tagServiceMock->shouldReceive('find')->with($notFoundTagId, $with)->andReturnNull();
        $tagServiceMock->shouldReceive('find')->with($tag->id, $with)->andReturn($tag->load('articles'));
        $this->app->instance(TagService::class, $tagServiceMock);

        $this->get('/api/articles/tags/' . $notFoundTagId)
            ->assertStatus(404)
            ->assertJson(['message' => 'Тэг не найден.']);

        $this->get('/api/articles/tags/' . $tag->id)
            ->assertStatus(200)
            ->assertJson([
                'id' => $tag->id,
                'name' => $tag->name,
                'created_at' => $tag->created_at->toISOString(),
                'updated_at' => $tag->updated_at->toISOString()
            ])->assertJsonCount($articleCount, 'articles');
    }
}
