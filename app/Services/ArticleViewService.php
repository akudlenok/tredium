<?php


namespace App\Services;

use App\Models\ArticleView;

class ArticleViewService
{
    public function create(int $articleId, int $userId): ArticleView
    {
        return ArticleView::create(['article_id' => $articleId, 'user_id' => $userId]);
    }

    public function isExisting(int $articleId, int $userId): bool
    {
        return ArticleView::whereArticleId($articleId)->whereUserId($userId)->exists();
    }
}
