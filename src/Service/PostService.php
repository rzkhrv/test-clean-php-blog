<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\PostRepository;

readonly class PostService
{
    public function __construct(
        private PostRepository $postRepository,
    ) {}
}