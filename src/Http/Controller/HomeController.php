<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Http\Request;

class HomeController
{
    public function index(Request $request)
    {
        echo 'home';
    }
}