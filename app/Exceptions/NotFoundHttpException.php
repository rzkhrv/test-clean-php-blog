<?php

namespace App\Exceptions;

class NotFoundHttpException extends HttpException
{
    protected $message = 'Page not found';

    protected int $httpStatusCode = 404;
}