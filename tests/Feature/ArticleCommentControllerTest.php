<?php

namespace Tests\Feature;

use App\Jobs\CreateArticleCommentJob;
use App\Models\Article;
use App\Models\User;
use App\Services\UserService;
use Bus;
use Mockery;
use Tests\TestCase;

class ArticleCommentControllerTest extends TestCase
{
    public function testStore()
    {
        $article = Article::factory()->create();
        $user = User::factory()->create();

        $userServiceMock = Mockery::mock(UserService::class);
        $userServiceMock->shouldReceive('getUser')->andReturn($user);

        $params = [];
        $message = 'The given data was invalid.';
        $errors = [
            'article_id' => [
                0 => 'Укажите статью.'
            ],
            'subject' => [
                0 => 'Укажите тему сообщения.'
            ],
            'body' => [
                0 => 'Укажите сообщение.'
            ]
        ];

        $this->postJson('/api/articles/comments', $params)
            ->assertStatus(422)
            ->assertJson([
                'message' => $message,
                'errors' => $errors
            ]);

        $params['article_id'] = $article->id + 1;
        $errors['article_id'][0] = 'Статья не найдена.';
        $this->postJson('/api/articles/comments', $params)
            ->assertStatus(422)
            ->assertJson([
                'message' => $message,
                'errors' => $errors
            ]);

        $params['article_id'] = $article->id;
        unset($errors['article_id']);
        $this->postJson('/api/articles/comments', $params)
            ->assertStatus(422)
            ->assertJson([
                'message' => $message,
                'errors' => $errors
            ]);

        $params['subject'] = \Str::random(256);
        $errors['subject'][0] = 'Тема сообщение не может быть больше 255 символов.';
        $this->postJson('/api/articles/comments', $params)
            ->assertStatus(422)
            ->assertJson([
                'message' => $message,
                'errors' => $errors
            ]);

        $params['subject'] = \Str::random(255);
        unset($errors['subject']);
        $this->postJson('/api/articles/comments', $params)
            ->assertStatus(422)
            ->assertJson([
                'message' => $message,
                'errors' => $errors
            ]);

        $params['body'] = \Str::random(100);
        unset($errors['body']);

        Bus::fake();
        $this->postJson('/api/articles/comments', $params)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
        Bus::assertDispatched(CreateArticleCommentJob::class);
    }
}
