<?php


namespace App\Services;

use App\Models\Article;
use App\Models\ArticleView;
use App\Models\Like;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ArticleService
{
    public function getAll(int $perPage = 50, bool $orderReverse = false): LengthAwarePaginator
    {
        $query = Article::query();
        if ($orderReverse) {
            $query->latest('id');
        }

        return $query->paginate($perPage);
    }

    public function find(int $id, array $with = []): ?Article
    {
        return Article::whereId($id)->with($with)->first();
    }

    public function findByIds(array $ids)
    {
        return Article::whereIn('id', $ids)->get();
    }

    public function recalculateCountLikes(Carbon $startPeriod, Carbon $endPeriod): void
    {
        $newCountLikes = $this->getCountLikesByPeriod($startPeriod, $endPeriod);
        $articleIds = $newCountLikes->keys()->toArray();
        $articles = $this->findByIds($articleIds);
        foreach ($articles as $article) {
            $article->like_count += $newCountLikes[$article->id]->count_likes;
            $article->save();
        }
    }

    private function getCountLikesByPeriod(Carbon $start, Carbon $end): Collection
    {
        return Like::select(['article_id'])
            ->selectRaw('sum(if(deleted_at is null, 1, -1)) as count_likes')
            ->whereBetween('updated_at', [$start->toDateTimeString(), $end->toDateTimeString()])
            ->groupBy('article_id')
            ->withTrashed()
            ->get()
            ->keyBy('article_id');
    }

    public function recalculateCountViews(Carbon $startPeriod, Carbon $endPeriod): void
    {
        $newCountViews = $this->getCountViewsByPeriod($startPeriod, $endPeriod);
        $articleIds = $newCountViews->keys()->toArray();
        $articles = $this->findByIds($articleIds);
        foreach ($articles as $article) {
            $article->view_count += $newCountViews[$article->id]->count_views;
            $article->save();
        }
    }

    private function getCountViewsByPeriod(Carbon $start, Carbon $end): Collection
    {
        return ArticleView::select(['article_id'])
            ->selectRaw('count(*) as count_views')
            ->whereBetween('created_at', [$start->toDateTimeString(), $end->toDateTimeString()])
            ->groupBy('article_id')
            ->get()
            ->keyBy('article_id');
    }
}
