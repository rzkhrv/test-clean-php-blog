<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Foundation\Request;
use App\Service\CategoryService;
use Smarty\Smarty;

class HomeController
{
    public function __construct(
        private CategoryService $categoryService,
        private Smarty $smarty,
    ) {}

    public function index(Request $request)
    {
        $categories = $this->categoryService->getForHomePage();

        $this->smarty->assign(['categories' => $categories]);
        $this->smarty->display('pages/index.tpl');
    }
}