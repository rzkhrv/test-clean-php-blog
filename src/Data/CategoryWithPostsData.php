<?php

namespace App\Data;

class CategoryWithPostsData
{
    /**
     * @param CategoryData[] $categories
     * @param PostData[] $posts
     */
    public function __construct(
        public array $categories,
        public array $posts,
    ) {}
}