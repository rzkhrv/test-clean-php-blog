<?php

namespace App\Foundation;

use App\Foundation\Data\VisitorData;

class Visitor
{
    private const string COOKIE_NAME = 'visitor';
    private const int COOKIE_LENGTH = 64;

    public static function create(array $cookies): VisitorData
    {
        $id = $cookies[self::COOKIE_NAME] ?? '';

        if (self::isValid($id) === false) {
            return new VisitorData($id);
        }

        $id = self::generate();
        self::setCookie($id);

        return new VisitorData($id);
    }

    private static function isValid(string $id): bool
    {
        return strlen($id) === self::COOKIE_LENGTH
            && ctype_xdigit($id);
    }

    private static function generate(): string
    {
        return bin2hex(random_bytes(self::COOKIE_LENGTH));
    }

    private static function setCookie(string $id): void
    {
        setcookie(self::COOKIE_NAME, $id, self::getCookieExpiration());
    }

    private static function getCookieExpiration(): int
    {
        return time() + (10 * 365 * 24 * 60 * 60);
    }
}