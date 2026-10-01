<?php

namespace App\Data;

use DateTimeImmutable;

readonly class PostData
{
    public function __construct(
        public int               $id,
        public string            $name,
        public string            $description,
        public string            $text,
        public string            $imagePath,
        public int               $viewsCount,
        public DateTimeImmutable $createdAt,
    ) {}
}