<?php 
/** VAVS - Virtuelle Airline System (Release 1 MVP)  
 * Flottenverwaltung Formular für Admin-Dashboard ⭐⭐⚡  

** Autor Christoph */


session_start(); require_once '../config/config.php';  


if($_SESSION['role'] === 'admin') {    
    // Admin-Funktion: CRUD Operations auf Fleet Tabelle ✅
}

?>

<!DOCTYPE html>
<html lang="de"><head><meta charset=UTF-8"/><title>Fleet Management - Virtuelle Airline</title></head>


<body class=fleet-dashboard">   
<header>Airline System Virtual 🛫 Admin Panel (A01)</h1>  

<!-- Widget: Flottenstatus & CRUD Operations → A01 MVP!  
<section id="fleet-widget"aria-label=Fleet Status data-status=active>      
  <table class="table-responsive"><thead><tr><th>Fahrzeugtyp:</span>`;
