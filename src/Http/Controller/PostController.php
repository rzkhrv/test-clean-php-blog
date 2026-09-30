<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Foundation\Request;
use App\Service\PostService;

readonly class PostController
{
    public function __construct(
        private PostService $postService,
    ) {}

    public function index(Request $request)
    {
        echo 'post';
    }
}