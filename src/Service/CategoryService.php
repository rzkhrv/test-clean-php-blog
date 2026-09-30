<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\CategoryRepository;

readonly class CategoryService
{
    public function __construct(
        private CategoryRepository $repository,
    ) {}

    public function getForHomePage(): array
    {
        return $this->repository->findAllWithPosts();
    }
}