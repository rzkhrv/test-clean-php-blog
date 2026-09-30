<?php

namespace App\Data;

readonly class CategoryData
{
    public function __construct(
        public int $id,
        public string $name,
        public string $description,
    ) {}
}