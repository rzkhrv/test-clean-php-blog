<?php

namespace App\Repository;

use PDO;

class BaseRepository
{
    public function __construct(protected readonly PDO $pdo) {}
}