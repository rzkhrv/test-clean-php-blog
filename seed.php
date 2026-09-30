<?php

declare(strict_types=1);

$basePath = realpath(__DIR__);

require_once $basePath . '/vendor/autoload.php';

\App\Foundation\Application::getInstance($basePath)->runSeed();