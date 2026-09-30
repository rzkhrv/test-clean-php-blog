<?php

declare(strict_types=1);

namespace App;

use App\Support\Config\MysqlDatabaseConfig;
use Dotenv\Dotenv;
use InvalidArgumentException;
use PDO;
use Throwable;

final class Application
{
    private static ?self $instance = null;

    private bool $initialized = false;

    private PDO $pdo;

    private function __construct(){}
    private function __clone(){}

    public static function getInstance(string $basePath): self
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        return self::createInstance($basePath);
    }

    private static function createInstance(string $basePath): self
    {
        self::validateBasePath($basePath);

        self::$instance = new self();
        self::$instance->bootstrap($basePath);

        return self::$instance;
    }

    private static function validateBasePath(string $basePath): void
    {
        if ($basePath === '') {
            throw new InvalidArgumentException('Base path cannot be empty.');
        }

        if (realpath($basePath) === false) {
            throw new InvalidArgumentException('Base path does not exist.');
        }
    }

    private function bootstrap(string $basePath): void
    {
        if ($this->initialized === true) {
            return;
        }

        $this->loadEnvironment($basePath);
        $this->initDatabase();

        $this->initialized = true;
    }

    private function loadEnvironment(string $basePath): void
    {
        $dotenv = Dotenv::createImmutable($basePath);
        $dotenv->safeLoad();
    }

    private function initDatabase(): void {
        $config = MysqlDatabaseConfig::createFromEnv();

        $this->pdo = new PDO(
            dsn: $config->getDsn(),
            username: $config->getUser(),
            password: $config->getPassword(),
            options: $config->getOptions()
        );
    }

    public function run(): void
    {
        try {
            echo 'OK';
        } catch (Throwable $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }
}