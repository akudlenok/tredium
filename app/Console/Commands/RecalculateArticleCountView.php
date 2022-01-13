<?php

namespace App\Console\Commands;

use App\Services\ArticleService;
use Illuminate\Console\Command;

class RecalculateArticleCountView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:recalculate-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->service->recalculateCountViews($startDate, $endDate);
        return 0;
    }
}
