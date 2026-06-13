<?php 
/** VAVS System - Einfaches Routing Konzept ohne Framework  
 * Virtuelle Airline (Release 1 MVP)  
 * Tech Stack: PHP 8.x + MySQL InnoDB  

** Autor Christoph */

require_once '../config/config.php'; // App Konfigurationen loaden  

/** @var array $routes */
$handlers = [   
    'GET /' => ['Controller'=>FlightDashboard::class, 'method'=>'index'],  
    'POST /login' => Controller\PilotLogin::class . '@process', 
    
    /** Pilot Dashboard (MVP Core Feature) **/    
    'GET /flights/new' => \Pilot\NewFlightForm::class . '@get_form',
    'GET /dashboard/stats' => \Dashboard\Controllers\StatsController::class . '@fetch',   
    
    /** API Endpunkte für AJAX Calls **/  
    '/api/login-check'  => SessionCheckController::class . '@verify_session_jsonp'; 
];  

// Einfache Routing-Logik (kein Framework nötig):
function route($request, $method) {   
    foreach ($handlers as [$pattern,$handler]) {    
        if(preg_match('(?:^'.preg_quote(str_replace('/','/',dirname(dirname(__DIR__))).$pattern,'|).'$),\REQUEST_URI)) return true;  
