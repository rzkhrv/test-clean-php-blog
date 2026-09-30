<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\PostService;

readonly class PostController
{
    public function __construct(
        private PostService $postService,
    ) {}
}