<?php

namespace Tests\Unit\Services;

use App\Models\Tag;
use App\Services\TagService;
use Tests\TestCase;

class TagServiceTest extends TestCase
{
    public function testFind()
    {
        $tag = Tag::factory()->create();
        $service = app(TagService::class);
        $foundTag = $service->find($tag->id);
        $this->assertNotNull($foundTag);

        $notFoundTag = $service->find($tag->id + 1);
        $this->assertNull($notFoundTag);
    }
}
