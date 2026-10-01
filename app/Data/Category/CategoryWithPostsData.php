<?php

namespace App\Data\Category;

use App\Data\Post\PostData;

class CategoryWithPostsData
{
    /**
     * @param PostData[] $posts
     */
    public function __construct(
        public CategoryData $category,
        public array $posts,
        public int $totalPosts,
        public int $totalPages,
    ) {}
}