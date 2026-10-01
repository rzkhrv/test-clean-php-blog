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

    public function index(Request $request): void
    {
        $request = FilterCategoryRequest::createFromRequest($request);
        $categoryWithPosts = $this->service->getCategoryWithPosts($request);

        $this->smarty->assign([
            'category' => $categoryWithPosts->category,
            'posts' => $categoryWithPosts->posts,
            'totalPages' => $categoryWithPosts->totalPages,
            'currentPage' => $request->page,
            'sortDirection' => $request->sortDirection->value,
            'sortBy' => $request->sortBy->value,
        ]);

        $this->smarty->display('pages/category.tpl');
    }
}