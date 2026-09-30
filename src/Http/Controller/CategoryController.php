<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Http\Request;
use App\Service\CategoryService;

readonly class CategoryController
{
    public function __construct(
        private CategoryService $service,
    ) {}

    public function index(Request $request)
    {
        echo 'category';
    }
}