<?php

namespace App\Data\Category;

readonly class CategoryData
{
    public function __construct(
        public int $id,
        public string $name,
        public string $description,
    ) {}
}