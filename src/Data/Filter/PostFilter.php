<?php

namespace App\Data\Filter;

use App\Enums\PostSortByEnum;
use App\Enums\SortDirectionEnum;

class PostFilter
{
    public function __construct(
        public int               $categoryId,

        public PostSortByEnum    $sortBy,
        public SortDirectionEnum $sortDirection,

        public int               $limit = 10,
        public int               $offset = 0,
    ) {}
}