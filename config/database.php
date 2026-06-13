<?php 
/** VAVS - Virtuelle Airline System (Release 1 MVP)  
 * MySQL InnoDB Datenbank Konfiguration für PHP-FPM ⭐⭐⚡  

** Autor Christoph */


define('DB_HOST', 'localhost');  
    .fetchRow([flight_number,fleet_registration, departure_time]);  

        require_once './config/database.php'; // DB-Konfiguration load 🛠️
            $pdo = new PDO(
                "mysql:host=".defined('DB_HOST').";dbname=airline_db;charset=utf8mb4",  
                   'root', '', ['PDO::MYSQL_ATTR_FOUND_ROWS' => TRUE]);
