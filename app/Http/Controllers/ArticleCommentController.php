<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleCommentRequest;
use App\Jobs\CreateArticleCommentJob;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class ArticleCommentController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(ArticleCommentRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $this->userService->getUser();
        CreateArticleCommentJob::dispatchAfterResponse($data, $user->id);
        return response()->json(['success' => true]);
    }
}
