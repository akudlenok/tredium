<?php


namespace App\Services;

use App\Models\Like;

class LikeService
{
    public function create(int $articleId, int $userId): Like
    {
        return Like::create(['article_id' => $articleId, 'user_id' => $userId]);
    }

    public function isExisting(int $articleId, int $userId): bool
    {
        return Like::whereArticleId($articleId)->whereUserId($userId)->exists();
    }

    private function findByArticleIdAndUserId(int $articleId, int $userId): ?Like
    {
        return Like::whereArticleId($articleId)->whereUserId($userId)->withTrashed()->first();
    }

    public function like(int $articleId, int $userId): Like
    {
        $like = $this->findByArticleIdAndUserId($articleId, $userId);
        if (!$like) {
            return $this->create($articleId, $userId);
        }

        $like->trashed() ? $like->restore() : $like->delete();
        return $like;
    }
}
