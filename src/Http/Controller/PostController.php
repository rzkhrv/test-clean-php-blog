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
        $postWithCategories = $this->postService->getPostWithCategories(
            (int)$request->getUriSegment(1)
        );

        $this->smarty->assign([
            'post' => $postWithCategories->post,
            'categories' => $postWithCategories->categories,
        ]);
        $this->smarty->display('pages/post.tpl');
    }
}