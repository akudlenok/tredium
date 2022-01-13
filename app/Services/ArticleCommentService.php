<?php


namespace App\Services;

use App\Models\ArticleComment;

class ArticleCommentService
{
    public function create(array $data, int $userId): ArticleComment
    {
        $comment = new ArticleComment();
        $comment->fill($data);
        $comment->user_id = $userId;
        $comment->save();
        return $comment;
    }
}
