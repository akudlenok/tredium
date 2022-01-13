<?php


namespace App\Services;

use App\Models\Tag;

class TagService
{
    public function find(int $id, $with = []): ?Tag
    {
        return Tag::whereId($id)->with($with)->first();
    }
}
