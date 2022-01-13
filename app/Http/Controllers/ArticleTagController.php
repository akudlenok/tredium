<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Infrastructure\Response;
use App\Services\TagService;
use Illuminate\Http\JsonResponse;

class ArticleTagController extends Controller
{
    private TagService $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function show($id): JsonResponse
    {
        $tag = $this->tagService->find($id, ['articles']);
        if (!$tag) {
            return Response::error('Тэг не найден.', 404);
        }

        return response()->json(new TagResource($tag));
    }
}
