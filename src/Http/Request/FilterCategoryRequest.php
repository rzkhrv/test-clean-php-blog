<?php

namespace App\Http\Request;

use App\Enums\PostSortByEnum;
use App\Enums\SortDirectionEnum;
use App\Foundation\Request;

class FilterCategoryRequest
{
    public function __construct(
        public int               $id,

        public PostSortByEnum    $sortBy,
        public SortDirectionEnum $sortDirection,

        public int               $limit,
        public int               $offset,
    ) {}

    public static function createFromRequest(Request $request): self
    {
        $sortBy = (string)$request->getQuery('sortBy');
        $sortDirection = (string)$request->getQuery('sortDirection');

        return new self(
            id: (int)$request->getUriSegment(1),
            sortBy: PostSortByEnum::tryFrom($sortBy) ?? PostSortByEnum::Date,
            sortDirection: SortDirectionEnum::tryFrom($sortDirection) ?? SortDirectionEnum::Desc,
            limit: (int)$request->getQuery('limit', 10),
            offset: (int)$request->getQuery('offset', 0),
        );
    }
}