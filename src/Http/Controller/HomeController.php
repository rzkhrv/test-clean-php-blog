<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Foundation\Request;
use App\Service\CategoryService;
use Smarty\Smarty;

class HomeController
{
    private const int LATEST_POST_COUNT = 3;

    public function __construct(
        private CategoryService $categoryService,
        private Smarty $smarty,
    ) {}

    public function index(Request $request): void
    {
        $categoryWithPosts = $this->categoryService->getForHomePage(self::LATEST_POST_COUNT);

        $this->smarty->assign([
            'categories' => $categoryWithPosts->categories,
            'posts' => $categoryWithPosts->posts
        ]);

        $this->smarty->display('pages/index.tpl');
    }
}