<?php

namespace App\Exceptions;

use Exception;

class InternalServerErrorHttpException extends Exception
{
    protected $message = 'Internal Server Error';
}