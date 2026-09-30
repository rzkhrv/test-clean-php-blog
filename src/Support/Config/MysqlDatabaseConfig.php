<?php

declare(strict_types=1);

namespace App\Support\Config;

use PDO;
use SensitiveParameter;

readonly class MysqlDatabaseConfig
{
    private function __construct(
        private string $host,
        private int $port,
        private string $database,
        private string $user,

        #[SensitiveParameter]
        private string $password,

        private array $options = [],
    ) {}

    public static function createFromEnv(): self
    {
        return new self(
            host:$_ENV['DB_HOST'] ?? 'localhost',
            port: (int)($_ENV['DB_PORT'] ?? 3306),
            database: $_ENV['DB_NAME'] ?? 'db',
            user: $_ENV['DB_USER'] ?? 'user',
            password: $_ENV['DB_PASS'] ?? 'password',
            options: self::getDefaultDatabaseOptions(),
        );
    }

    public function getDsn(): string
    {
        return sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $this->host,
            $this->port,
            $this->database
        );
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    private static function getDefaultDatabaseOptions(): array
    {
        return [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
    }
}