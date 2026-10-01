<?php

declare(strict_types=1);

namespace App\Service;

use App\Data\CategoryData;
use App\Data\CategoryWithPostsData;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;

readonly class CategoryService
{
    public function __construct(
        private CategoryRepository $repository,
        private PostRepository $postRepository,
    ) {}

    public function getForHomePage(int $postLimit): CategoryWithPostsData
    {
        $categories = $this->repository->findAllWhereHasPosts();
        $categoryIds = array_map(fn(CategoryData $c): int => $c->id, $categories);

        $posts = $this->postRepository->findLatestByCategoryIds($categoryIds, $postLimit);

        return new CategoryWithPostsData(
            categories: $categories,
            posts: $posts
        );
    }
}