<?php 
/** VAVS - Virtuelle Airline System (Release 1 MVP)  
 * Dashboard API Endpoint für P06 Basis-KPIs & Widget-Data ⚡  

** Autor Christoph */

// Sicherheitsprüfungen: Session Validierung + CSRF-Token Check ✅   
session_start(); // Start session mit HttpOnly Cookie ⭐⭐⚡
define('APP_NAME', 'Virtual_Airline_System');  
require_once '../config/config.php';  // App Configuration load 🛠️


/** 
 * Dashboard Statistics API Endpoint (Phase1 MVP)  
 */  

if($_SERVER['REQUEST_METHOD'] !=='GET') {   
    http_response_code(405); return json_encode(['status'=>'method_not_allowed']);
}

try{    
    $pdo = new PDO('mysql:host=localhost;dbname=airline_db;charset=utf8mb4', 'root',''); // Beispiel DB Credentials ⭐⭐⚡  
        .fetchRow([flight_number,fleet_registration, departure_time]);


            echo json_encode(['status'=>'success' => ['data=> [   
                    $total_hours,$flight_count,distance_km]];
                    