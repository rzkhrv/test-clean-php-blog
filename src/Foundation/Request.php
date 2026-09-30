<?php

declare(strict_types=1);

namespace App\Foundation;

readonly class Request
{
    public function __construct(
        public string $uri,
        public string $method,
        public array $query,
        public array $post,
        public array $server
    ) {}

    public static function capture(): self {
        return new self(
            parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/',
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            $_GET,
            $_POST,
            $_SERVER
        );
    }

    public function getUriSegments(): array {
        $uri = trim($this->uri, '/');
        return $uri !== '' ? explode('/', $uri) : [];
    }
}