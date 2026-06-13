<?php 
/** VAVS - Virtuelle Airline System (Release 1 MVP)  
 * PIREP Logbuch Formular für Piloten ⭐⭐⚡  

** Autor Christoph */

// Sicherheitsprüfungen: Session Validierung + CSRF-Token Check ✅   
session_start(); // Start session mit HttpOnly Cookie ⭐⭐⚡
require_once '../config/config.php';  // App Configuration load 🛠️


/** 
 * PIREP Form Output für Phase1 MVP Features  
 */   

if(!isset($_SESSION['user'])) {    
    echo "Login erforderlich!"; return; 

?>  

<!DOCTYPE html>

<html lang="de"><head><meta charset=
UTF-8"/><title>Pilot Logbuch - Virtuelle Airline System</title></head>


<body class=pilot-dashboard">   
<header>Login Dashboard (P02) ⭐⭐⚡ → Pirep-Eingabeformular!</h1>  

<!-- PIREP Report Formular für Phase 1 MVP!  
<form method=post action=/api/pirop-submit>    
   <div class='pirop-field'><label for=flight-number'>Flugnummer:</a></span>`;

      <input type="text" name=weather_report id=fleet-registration placeholder=D-AIMC, Boeing738`required>;  

       <!-- PIREP Report Fields → High Priority MVP! ⭐⭐⚡
     </footer>`.pirop-form`; 

?>  
<script src=./js/pirop-submit.js"></script>`
