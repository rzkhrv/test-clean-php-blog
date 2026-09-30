<?php

namespace App\Repository;

use PDO;

abstract class BaseRepository
{
    public function __construct(protected readonly PDO $pdo) {}

    abstract protected function hydrate(array $row): mixed;
}