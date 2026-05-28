<?php
/**
 * RunwayHub - Fluggesellschaft Management
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
    if (strpos($route, '/Controller/') === 0) {
        require_once __DIR__ . '/src/core/Database.php';
        $db = new RunwayHub\Core\Database(__DIR__ . '/database.sqlite');
        
        // Route zu Controller
        $controllerPath = __DIR__ . '/api' . str_replace('/', DIRECTORY_SEPARATOR, $route);
        
        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            
            $request = new RunwayHub\Core\Request($_SERVER['REQUEST_METHOD'], $_SERVER, $_GET, $_POST, $_FILES);
            $response = new RunwayHub\Core\Response();
            
            // Create controller instance
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass($request, $response, $db);
                
                // Route action
                if (method_exists($controller, 'index')) {
                    $controller->index();
                } elseif (method_exists($controller, 'create')) {
                    $controller->create();
                } elseif (method_exists($controller, 'show')) {
                    $controller->show();
                } elseif (method_exists($controller, 'update')) {
                    $controller->update();
                } elseif (method_exists($controller, 'delete')) {
                    $controller->delete();
                }
            }
            exit;
        }
    }
}

// Frontend Routing (Router)
if (strpos($_SERVER['REQUEST_URI'], '/router/') === 0) {
    require_once __DIR__ . '/src/modules/Home/Views/dashboard.php';
    exit;
}

// Default: Dashboard
header('Content-Type: text/html; charset=utf-8');
readfile(__DIR__ . '/src/modules/Home/Views/dashboard.php');
