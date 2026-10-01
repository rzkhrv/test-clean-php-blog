<?php

namespace App\Data\Category;

use App\Data\Post\PostData;

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