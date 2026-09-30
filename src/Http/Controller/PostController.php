<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Foundation\Request;
use App\Service\PostService;
use Smarty\Smarty;

class PostController
{
    public function __construct(
        private PostService $postService,
        private Smarty $smarty,
    ) {}

    public function index(Request $request)
    {
        $this->smarty->display('pages/post.tpl');
    }
}