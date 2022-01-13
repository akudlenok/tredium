<?php

namespace App\Console\Commands;

use App\Services\ArticleService;
use Illuminate\Console\Command;

class RecalculateArticleCountLike extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:recalculate-like';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculating the number of likes for an article';

    private ArticleService $service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ArticleService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function handle(): int
    {
        $startDate = now()->subDay()->startOfDay();
        $endDate = now()->subDay()->endOfDay();
        $this->service->recalculateCountLikes($startDate, $endDate);
        return 0;
    }
}
