<?php

declare(strict_types=1);

namespace App\Service;

use App\Data\PostWithCategoriesData;
use App\Exceptions\DbNotFoundException;
use App\Exceptions\InternalServerErrorHttpException;
use App\Exceptions\NotFoundHttpException;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Throwable;

readonly class PostService
{
    public function __construct(
        private PostRepository $postRepository,
        private CategoryRepository $categoryRepository,
    ) {}

    public function getPostWithCategories(int $postId): PostWithCategoriesData
    {
        try {
            $post = $this->postRepository->get($postId);
        } catch (DbNotFoundException $e) {
            throw new NotFoundHttpException('Post not found');
        } catch (Throwable $e) {
            throw new InternalServerErrorHttpException(previous: $e);
        }

        $categories = $this->categoryRepository->findAllByPostId($postId);

        return new PostWithCategoriesData(
            post: $post,
            categories: $categories,
        );
    }
}