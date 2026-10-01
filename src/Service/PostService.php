<?php

declare(strict_types=1);

namespace App\Service;

use App\Data\PostPageData;
use App\Exceptions\DbNotFoundException;
use App\Exceptions\InternalServerErrorHttpException;
use App\Exceptions\NotFoundHttpException;
use App\Foundation\Data\VisitorData;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Throwable;

readonly class PostService
{
    public function __construct(
        private PostRepository $postRepository,
        private CategoryRepository $categoryRepository,
    ) {}

    public function getPostPageData(int $postId, VisitorData $visitor): PostPageData
    {
        try {
            $post = $this->postRepository->get($postId);
        } catch (DbNotFoundException $e) {
            throw new NotFoundHttpException('Post not found');
        } catch (Throwable $e) {
            throw new InternalServerErrorHttpException(previous: $e);
        }

        $this->postRepository->addView($post->id, $visitor->id);

        $categories = $this->categoryRepository->findAllByPostId($postId);
        $related = $this->postRepository->getRelated($postId);

        return new PostPageData(
            post: $post,
            categories: $categories,
            related: $related,
        );
    }
}