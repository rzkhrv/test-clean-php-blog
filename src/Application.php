<?php

declare(strict_types=1);

namespace App;

use App\Controller\CategoryController;
use App\Controller\HomeController;
use App\Controller\PostController;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Service\CategoryService;
use App\Service\PostService;
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

    private CategoryRepository $categoryRepository;
    private PostRepository $postRepository;

    private CategoryService $categoryService;
    private PostService $postService;

    private HomeController $homeController;
    private CategoryController $categoryController;
    private PostController $postController;

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
        $this->injectDependencies();

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

    private function injectDependencies(): void
    {
        $this->initRepositories();
        $this->initServices();
        $this->initControllers();
    }

    private function initRepositories(): void
    {
        $this->categoryRepository = new CategoryRepository($this->pdo);
        $this->postRepository = new PostRepository($this->pdo);
    }

    private function initServices(): void
    {
        $this->categoryService = new CategoryService($this->categoryRepository);
        $this->postService = new PostService($this->postRepository);
    }

    private function initControllers(): void
    {
        $this->categoryController = new CategoryController($this->categoryService);
        $this->postController = new PostController($this->postService);
        $this->homeController = new HomeController();
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