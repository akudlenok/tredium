<?php

namespace App\Jobs;

use App\Services\ArticleCommentService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateArticleCommentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $data;
    private int $userId;
    public int $tries = 25;
    public int $maxExceptions = 3;

    public function __construct(array $data, int $userId)
    {
        $this->data = $data;
        $this->userId = $userId;
    }

    public function handle()
    {
        app(ArticleCommentService::class)->create($this->data, $this->userId);
    }
}
