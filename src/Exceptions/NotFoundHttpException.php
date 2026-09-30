<?php

namespace App\Exceptions;

use Exception;

class NotFoundHttpException extends Exception
{
    protected $message = 'Page not found';
}