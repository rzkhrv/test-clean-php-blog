<?php

namespace App\Data;

class CategoriesWithPostsData
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