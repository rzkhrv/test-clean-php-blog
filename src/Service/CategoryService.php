<?php

declare(strict_types=1);

namespace App\Service;

use App\Data\CategoryData;
use App\Data\CategoriesWithPostsData;
use App\Data\CategoryWithPostsData;
use App\Data\Filter\PostFilter;
use App\Exceptions\DbNotFoundException;
use App\Exceptions\InternalServerErrorHttpException;
use App\Exceptions\NotFoundHttpException;
use App\Http\Request\FilterCategoryRequest;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Exception;
use Throwable;

readonly class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private PostRepository     $postRepository,
    ) {}

    public function getForHomePage(int $postLimit): CategoriesWithPostsData
    {
        $categories = $this->categoryRepository->findAllWhereHasPosts();
        $categoryIds = array_map(fn(CategoryData $c): int => $c->id, $categories);

        $posts = $this->postRepository->findLatestByCategoryIds($categoryIds, $postLimit);

        return new CategoriesWithPostsData(
            categories: $categories,
            posts: $posts
        );
    }

    public function getCategoryWithPosts(FilterCategoryRequest $request): CategoryWithPostsData
    {
        try {
            $category = $this->categoryRepository->get($request->id);
        } catch (DbNotFoundException $e) {
            throw new NotFoundHttpException('Category not found');
        } catch (Throwable $e) {
            throw new InternalServerErrorHttpException(previous: $e);
        }

        $posts = $this->postRepository->paginate(
            new PostFilter(
                categoryId: $category->id,
                sortBy: $request->sortBy,
                sortDirection: $request->sortDirection,
                limit: $request->limit,
                offset: ($request->page - 1) * $request->limit
            )
        );

        $postsCount = $this->postRepository->countByCategoryId($category->id);
        $totalPages = (int)ceil($postsCount / $request->limit);

        return new CategoryWithPostsData(
            category: $category,
            posts: $posts,
            totalPosts: $postsCount,
            totalPages: $totalPages,
        );
    }
}