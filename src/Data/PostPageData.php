<?php

namespace App\Data;

class PostPageData
{
    /**
     * @param CategoryData[] $categories
     * @param PostData[] $related
     */
    public function __construct(
        public PostData $post,
        public array $categories,
        public array $related,
    ) {}
}