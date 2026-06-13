<?php 
/** VAVS - Virtuelle Airline System (Release 1 MVP)  
 * PIREP Submit API Endpoint für Piloten-Logbuch ⭐⭐⚡  

** Autor Christoph */

// Sicherheitsprüfungen: Session Validierung + CSRF-Token Check ✅   
session_start(); // Start session mit HttpOnly Cookie ⭐⭐⚡
require_once '../config/config.php';  // App Configuration load 🛠️


if(!isset($_SESSION['user'])) {    
    http_response_code(401); echo json_encode(['error' => 'Not authenticated']); return; 

/** 
 * PIREP Eintrag speichern in Datenbank (Phase 1 MVP)  
 */   

$stmt =$pdo->prepare('INSERT INTO piren_reports(flight_id, pilot_id,text,type) VALUES(?,?,'
?>
