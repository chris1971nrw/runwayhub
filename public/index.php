<?php

declare(strict_types=1);

/**
 * RunwayHub - Main Entry Point
 * SEO-Optimized Landing Page for Visual Air Traffic Controllers
 */

// Load the application bootstrap
require_once dirname(dirname(dirname(__DIR__))).'/runwayhub/src/core/Bootstrap.php';

// Bootstrap the application
$bootstrap = new \RunwayHub\Core\Bootstrap();
$bootstrap->boot();

// Route to appropriate handler
try {
    // Check for route parameters
    $route = $bootstrap->router->getRoute();
    
    // Default: Show landing page
    if (empty($route) || $route === '') {
        echo $bootstrap->view->render('pages/landing');
    } elseif ($route === 'login') {
        echo $bootstrap->view->render('pages/login');
    } elseif ($route === 'dashboard') {
        echo $bootstrap->view->render('pages/dashboard');
    } elseif ($route === 'va-admin') {
        echo $bootstrap->view->render('pages/va-admin');
    } elseif ($route === 'va-connect') {
        echo $bootstrap->view->render('pages/va-connect');
    } elseif ($route === 'va-gruenden') {
        echo $bootstrap->view->render('pages/va-gruenden');
    } else {
        echo $bootstrap->view->render('pages/404');
    }
} catch (\Exception $e) {
    // Fallback to landing page
    echo 'Error loading application. Showing landing page.';
    echo $bootstrap->view->render('pages/landing');
}