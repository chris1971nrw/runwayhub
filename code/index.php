<?php
/**
 * RunwayHub - Management System
 * 
 * Haupt-Entry-Point für die API und Frontend
 */

require_once __DIR__ . '/src/core/Bootstrap.php';

// API Routing
if ($route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) {
    // API-Endpoints
    if (strpos($route, '/api/') === 0) {
        $controller = __DIR__ . '/api' . str_replace('/', DIRECTORY_SEPARATOR, $route);
        if (file_exists($controller) && str_ends_with($controller, '.php')) {
            require_once $controller;
            exit;
        }
    }
    
    // API Controller
    if (strpos($route, '/api/') === 0) {
        require_once __DIR__ . '/src/core/Database.php';
        require_once __DIR__ . '/api/LoginController.php';
        $db = new RunwayHub\Core\Database(
            [
                'driver' => getenv('DB_CONNECTION') === 'mysql' ? 'mysql' : 'sqlite',
                'host' => getenv('DB_HOST'),
                'port' => getenv('DB_PORT'),
                'database' => getenv('DB_DATABASE'),
                'username' => getenv('DB_USERNAME'),
                'password' => getenv('DB_PASSWORD'),
                'path' => __DIR__ . '/database.sqlite'
            ]
        );
        
        $request = new RunwayHub\Core\Request($_SERVER['REQUEST_METHOD'], $_SERVER, $_GET, $_POST, $_FILES);
        $response = new RunwayHub\Core\Response();
        
        // LoginController instanziiieren
        $controller = new RunwayHub\Api\LoginController($db, $request, $response);
        
        // Route action
        if ($route === '/api/login' || $route === '/api/login-pilot.php' || strpos($route, '/api/login') === 0) {
            $controller->login();
        } elseif ($route === '/api/logout' || strpos($route, '/api/logout') === 0) {
            $controller->logout();
        }
        exit;
    }
}

// Frontend Routing (Router)
if (strpos($_SERVER['REQUEST_URI'], '/router/') === 0) {
    $routerPath = __DIR__ . '/src/modules/Home/Views/' . $_SERVER['REQUEST_URI'];
    $routerPath = substr($routerPath, strlen('/router/'));
    
    if (file_exists($routerPath)) {
        header('Content-Type: text/html; charset=utf-8');
        readfile($routerPath);
        exit;
    }
}

// Default: Dashboard
header('Content-Type: text/html; charset=utf-8');
readfile(__DIR__ . '/src/modules/Home/Views/dashboard.php');
