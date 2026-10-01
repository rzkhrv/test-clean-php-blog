<?php

namespace App\Data;

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