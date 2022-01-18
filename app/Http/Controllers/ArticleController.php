<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticlePaginationCollectionResource;
use App\Infrastructure\Response;
use App\Services\ArticleService;
use App\Services\ArticleViewService;
use App\Services\LikeService;
use App\Services\UserService;
use Cache;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    const CACHE_KEY_LIKE_COUNT = 'article_like_count_';
    const CACHE_KEY_VIEW_COUNT = 'article_view_count';

    private ArticleService $articleService;
    private LikeService $likeService;
    private UserService $userService;
    private ArticleViewService $articleViewService;

    public function __construct(
        ArticleService $articleService,
        LikeService $likeService,
        UserService $userService,
        ArticleViewService $articleViewService
    ) {
        $this->articleService = $articleService;
        $this->likeService = $likeService;
        $this->userService = $userService;
        $this->articleViewService = $articleViewService;
    }

    public function index(): JsonResponse
    {
        $perPage = request()->get('per_page', 10);
        $orderReverse = request()->get('order_reverse', true);
        $articles = $this->articleService->getAll($perPage, $orderReverse);
        return response()->json(new ArticlePaginationCollectionResource($articles));
    }

    public function show($id): JsonResponse
    {
        $article = $this->articleService->find($id, ['tags']);
        if (!$article) {
            return $this->articleNotFound();
        }

        $user = $this->userService->getUser();
        $isLiked = $this->likeService->isExisting($article->id, $user->id);
        $cacheLikeCount = $this->getCacheByKey(self::CACHE_KEY_LIKE_COUNT . $article->id);
        $cacheViewCount = $this->getCacheByKey(self::CACHE_KEY_VIEW_COUNT . $article->id);
        $article->view_count += $cacheViewCount;
        if ($cacheLikeCount + $article->like_count > 0) {
            $article->like_count += $cacheLikeCount;
        }

        return response()->json(['article' => $article, 'is_liked' => $isLiked]);
    }

    public function like($id): JsonResponse
    {
        $article = $this->articleService->find($id);
        if (!$article) {
            return $this->articleNotFound();
        }

        $user = $this->userService->getUser();
        $like = $this->likeService->like($article->id, $user->id);
        $likeCount = $article->like_count;
        $likeCountKey = self::CACHE_KEY_LIKE_COUNT . $article->id;
        $this->getCacheByKey($likeCountKey);
        if (is_null($like->deleted_at)) {
            Cache::increment($likeCountKey);
        } else {
            Cache::decrement($likeCountKey);
        }

        $likeCount += $this->getCacheByKey($likeCountKey);
        return response()->json([
            'like_count' => $likeCount > 0 ? $likeCount : 0,
            'is_can_liked' => $like->trashed()
        ]);
    }

    public function viewed($id): JsonResponse
    {
        $article = $this->articleService->find($id);
        if (!$article) {
            return $this->articleNotFound();
        }

        $user = $this->userService->getUser();
        $viewCountKey = self::CACHE_KEY_VIEW_COUNT . $article->id;
        if (!$this->articleViewService->isExisting($article->id, $user->id)) {
            $this->articleViewService->create($article->id, $user->id);
            Cache::increment($viewCountKey);
        }

        $viewCount = $article->view_count + $this->getCacheByKey($viewCountKey);
        return response()->json(['view_count' => $viewCount]);
    }

    private function articleNotFound()
    {
        return Response::error('Статья не найдена.', 404);
    }

    private function getCacheByKey($key): int
    {
        return (int)Cache::remember($key, now()->endOfDay(), function () {
            return 0;
        });
    }
}
