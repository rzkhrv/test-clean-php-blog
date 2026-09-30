<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CategoryService;

readonly class CategoryController
{
    public function __construct(
        private CategoryService $service,
    ) {}
}