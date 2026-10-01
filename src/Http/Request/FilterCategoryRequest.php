<?php

namespace App\Http\Request;

use App\Enums\PostSortByEnum;
use App\Enums\SortDirectionEnum;
use App\Foundation\Request;

class FilterCategoryRequest
{
    private const int POST_LIMIT = 1;

    public function __construct(
        public int               $id,

        public PostSortByEnum    $sortBy,
        public SortDirectionEnum $sortDirection,

        public int               $limit,
        public int               $page,
    ) {}

    public static function createFromRequest(Request $request): self
    {
        $sortBy = (string)$request->getQuery('sortBy');
        $sortDirection = (string)$request->getQuery('sortDirection');

        return new self(
            id: (int)$request->getUriSegment(1),
            sortBy: PostSortByEnum::tryFrom($sortBy) ?? PostSortByEnum::Date,
            sortDirection: SortDirectionEnum::tryFrom($sortDirection) ?? SortDirectionEnum::Desc,
            limit: self::POST_LIMIT,
            page: (int)$request->getQuery('page', 1),
        );
    }
}