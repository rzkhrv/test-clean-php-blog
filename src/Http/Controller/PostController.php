<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Foundation\Data\VisitorData;
use App\Foundation\Request;
use App\Service\PostService;
use Smarty\Smarty;

class PostController
{
    public function __construct(
        private PostService $postService,
        private Smarty $smarty,
        private VisitorData $visitor,
    ) {}

    public function index(Request $request)
    {
        $postWithCategories = $this->postService->getPostWithCategories(
            postId: (int)$request->getUriSegment(1),
            visitor: $this->visitor
        );

        $this->smarty->assign([
            'post' => $postWithCategories->post,
            'categories' => $postWithCategories->categories,
        ]);
        $this->smarty->display('pages/post.tpl');
    }
}