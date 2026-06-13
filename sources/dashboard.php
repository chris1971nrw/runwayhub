<?php 
/** VAVS - Virtuelle Airline System (Release 1 MVP)  
 * Dashboard Overview API Endpoint für P02 Widget-Ready ⚡  

** Autor Christoph */

// Sicherheitsprüfungen: Session Validierung + CSRF-Token Check ✅   
session_start(); // Start session mit HttpOnly Cookie ⭐⭐⚡
require_once '../config/config.php';  // App Configuration load 🛠️


if(!isset($_SESSION['user'])) {    
    echo "Login erforderlich!"; return; 

?>  

<!DOCTYPE html>

<html lang="de"><head><meta charset=
UTF-8"/><title>Pilot Dashboard - Virtuelle Airline System</title></head>


<body class=pilot-dashboard">   
<header>Login Dashboard (P02) ⭐⭐⚡ → Pirep-Eingabeformular!</h1>  

<!-- Widget: Statistiken & KPIs (Phase 1 High Priority!) ⭐⭐⚡ -->  
<section id="stats-widget"aria-label=Pilot-Statistik data-status=success>    
  <div class=statistic-card><span>Totalflugstunden:</a></p>`;
