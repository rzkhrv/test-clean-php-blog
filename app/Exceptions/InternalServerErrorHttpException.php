<?php

namespace App\Exceptions;

class InternalServerErrorHttpException extends HttpException
{
    protected $message = 'Internal Server Error';
}