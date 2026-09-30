<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Foundation\Request;
use Smarty\Smarty;

class HomeController
{
    public function __construct(
        private Smarty $smarty,
    ) {}

    public function index(Request $request)
    {
        $this->smarty->display('pages/index.tpl');
    }
}