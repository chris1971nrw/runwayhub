<?php

declare(strict_types=1);

namespace RunwayHub\Core;

use RunwayHub\Core\Database;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;
use RunwayHub\Core\Router;
use RunwayHub\Core\Middleware\Auth;
use RunwayHub\Core\UpdateChecker;

class Bootstrap
{
    /** @var Database|null */
    private ?Database $database = null;

    /** @var Request|null */
    private ?Request $request = null;

    /** @var Response|null */
    private ?Response $response = null;

    /** @var Router|null */
    private ?Router $router = null;

    /** @var array */
    private array $middlewares = [];

    /** @var bool */
    private bool $debug = false;

    /**
     * Bootstrap the application
     */
    public function boot(): self
    {
        $this->initializeEnvironment();
        $this->initializeDatabase();
        $this->initializeRequest();
        $this->initializeResponse();
        $this->initializeRouter();
        $this->loadMiddlewares();

        return $this;
    }

    /**
     * Initialize environment settings
     */
    private function initializeEnvironment(): void
    {
        $this->debug = (bool) getenv('APP_DEBUG') ?: false;

        // Error reporting
        if ($this->debug) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        } else {
            error_reporting(E_ERROR | E_WARNING);
            ini_set('display_errors', '0');
        }

        // Timezone
        date_default_timezone_set('Europe/Berlin');

        // Base path
        define('BASE_PATH', dirname(__DIR__) . '/../../');
    }

    /**
     * Initialize database connection
     */
    private function initializeDatabase(): void
    {
        $config = $this->getConfig('database');

        $this->database = new Database([
            'host' => $config['host'] ?? 'localhost',
            'port' => $config['port'] ?? 3306,
            'database' => $config['database'] ?? 'runwayhub',
            'username' => $config['username'] ?? 'root',
            'password' => $config['password'] ?? '',
            'charset' => $config['charset'] ?? 'utf8mb4',
        ]);
    }

    /**
     * Initialize request object
     */
    private function initializeRequest(): void
    {
        $this->request = new Request();
    }

    /**
     * Initialize response object
     */
    private function initializeResponse(): void
    {
        $this->response = new Response();
    }

    /**
     * Initialize router
     */
    private function initializeRouter(): void
    {
        $this->router = new Router($this->request, $this->response);
        $this->registerRoutes();
    }

    /**
     * Register routes
     */
    private function registerRoutes(): void
    {
        // Dashboard
        $this->router->get('/', 'HomeController@index')
            ->name('home')
            ->middleware(['guest']);

        // Admin routes
        $this->router->get('/admin', 'AdminController@dashboard')
            ->name('admin.dashboard')
            ->middleware(['auth', 'admin']);

        $this->router->post('/admin/aircrafts', 'AdminController@createAircraft')
            ->name('admin.aircrafts.create')
            ->middleware(['auth', 'admin']);

        $this->router->get('/admin/aircrafts', 'AdminController@indexAircrafts')
            ->name('admin.aircrafts')
            ->middleware(['auth', 'admin']);

        // Public routes
        $this->router->get('/aircrafts', 'AircraftController@index')
            ->name('aircrafts')
            ->middleware(['guest']);

        $this->router->get('/pilots', 'PilotController@index')
            ->name('pilots')
            ->middleware(['guest']);

        $this->router->get('/bookings', 'BookingController@index')
            ->name('bookings')
            ->middleware(['guest']);
    }

    /**
     * Load middlewares
     */
    private function loadMiddlewares(): void
    {
        // Middleware classes to be loaded
        $this->middlewares = [
            \RunwayHub\Core\Middleware\Auth::class,
            \RunwayHub\Core\Middleware\Guest::class,
            \RunwayHub\Core\Middleware\Admin::class,
        ];
    }

    /**
     * Get config value
     */
    private function getConfig(string $key, mixed $default = null): mixed
    {
        $env = getenv($key);
        return $env ? $env : $default;
    }

    /**
     * Get database instance
     */
    public function getDatabase(): Database
    {
        return $this->database ?? throw new \RuntimeException('Database not initialized');
    }

    /**
     * Get request instance
     */
    public function getRequest(): Request
    {
        return $this->request ?? throw new \RuntimeException('Request not initialized');
    }

    /**
     * Get response instance
     */
    public function getResponse(): Response
    {
        return $this->response ?? throw new \RuntimeException('Response not initialized');
    }

    /**
     * Get router instance
     */
    public function getRouter(): Router
    {
        return $this->router ?? throw new \RuntimeException('Router not initialized');
    }

    /**
     * Check if debug mode
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }
}
