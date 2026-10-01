<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Foundation\Request;
use App\Http\Request\FilterCategoryRequest;
use App\Service\CategoryService;
use Smarty\Smarty;

class CategoryController
{
    public function __construct(
        private CategoryService $service,
        private Smarty $smarty,
    ) {}

    public function index(Request $request)
    {
        $categoryWithPosts = $this->service->getCategoryWithPosts(
            FilterCategoryRequest::createFromRequest($request)
        );

        $this->smarty->assign([
            'category' => $categoryWithPosts->category,
            'posts' => $categoryWithPosts->posts,
        ]);
        $this->smarty->display('pages/category.tpl');
    }
}