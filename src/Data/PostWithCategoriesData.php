<?php

namespace App\Data;

class PostWithCategoriesData
{
    /**
     * @param PostData $post
     * @param CategoryData[] $categories
     */
    public function __construct(
        public PostData $post,
        public array $categories,
    ) {}
}