<?php

namespace App\Exceptions;

use Exception;

class HttpException extends Exception
{
    protected int $httpStatusCode = 500;

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}