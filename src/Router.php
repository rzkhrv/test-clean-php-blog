<?php

namespace App;

use App\Exceptions\NotFoundHttpException;
use App\Http\Controller\CategoryController;
use App\Http\Controller\HomeController;
use App\Http\Controller\PostController;
use App\Http\Request;

readonly class Router
{
    public function __construct(
        private HomeController $homeController,
        private CategoryController $categoryController,
        private PostController $postController,
    ) {}

    public function dispatch(Request $request): void
    {
        $segments = $request->getUriSegments();

        if ($this->isHome($segments) === true) {
            $this->homeController->index($request);
            return;
        }

        if ($this->isValidPage($request) === false) {
            throw new NotFoundHttpException();
        }

        match ($segments[0]) {
            'category' => $this->categoryController->index($request),
            'post' => $this->postController->index($request),
            default => throw new NotFoundHttpException,
        };
    }

    private function isHome(array $segments): bool
    {
        return empty($segments) || $segments[0] === '';
    }

    private function isValidPage(Request $request): bool
    {
        $segments = $request->getUriSegments();
        if (count($segments) !== 2) {
            return false;
        }

        $id = $segments[1];

        return $id !== null && ctype_digit($id);
    }
}