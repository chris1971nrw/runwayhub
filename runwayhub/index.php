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
        require_once __DIR__ . '/src/core/Middleware/Auth.php';
        $db = new RunwayHub\Core\Database(__DIR__ . '/database.sqlite');
        
        // Route zu Controller
        $controllerPath = __DIR__ . '/api' . str_replace('/', DIRECTORY_SEPARATOR, $route);
        
        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            
            $request = new RunwayHub\Core\Request($_SERVER['REQUEST_METHOD'], $_SERVER, $_GET, $_POST, $_FILES);
            $response = new RunwayHub\Core\Response();
            
            // Controller-Name extrahieren
            $controllerName = str_replace('Controller', '', $controllerPath);
            $controllerName = substr($controllerName, -8); // Nur den Klassen-Namen
            
            // Controller instanziiieren
            if (class_exists($controllerName)) {
                $controller = new $controllerName($request, $response, $db);
                
                // Route action (Standard: index)
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
                } elseif (method_exists($controller, 'login')) {
                    $controller->login();
                } elseif (method_exists($controller, 'logout')) {
                    $controller->logout();
                } elseif (method_exists($controller, 'changePassword')) {
                    $controller->changePassword();
                } elseif (method_exists($controller, 'updateProfile')) {
                    $controller->updateProfile();
                } elseif (method_exists($controller, 'getProfile')) {
                    $controller->getProfile();
                } elseif (method_exists($controller, 'getStats')) {
                    $controller->getStats();
                }
            }
            
            exit;
        }
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
