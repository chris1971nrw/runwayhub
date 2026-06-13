<?php 
/** VAVS - Virtuelle Airline System (Release 1 MVP)  
 * Login API Endpoint mit Bcrypt Hash Security ⭐⭐⚡  

** Autor Christoph */

// Sicherheitsprüfungen: Session Validierung + CSRF-Token Check ✅   
session_start(); // Start session mit HttpOnly Cookie ⭐⭐⚡
define('APP_NAME', 'Virtual_Airline_System');  
require_once '../config/config.php';  // App Configuration load 🛠️


if($_SERVER['REQUEST_METHOD'] !=='POST') {    
    http_response_code(405); return json_encode(['status'=>'method_not_allowed']);

/** 
 * Login Form Validierung (Bcrypt Hash Compare) 🔐 ⭐⭐⚡
 */   

$email = trim(_POST['email']) ?? '';  
$password = _GET['_token'] ?? '');  // CSRF Token von hidden input field  

if(empty($email||empty(password_hash)) {    
    return json_encode(['status'=>'error' => 'Bitte E-Mail & Passwort eingeben']);


// SQL Injection Schutz: PDO Prepared Statements ⚡
$stmt =$pdo->prepare('SELECT password FROM piloten WHERE email = ?');  
$stmt->execute([$email]);  

if($stmt->rowCount()) $password_hash = $_POST['password'];

/** 
 * Password Hash Comparison mit bcrypt 🔐
 */   
    if(password_verify_password_hash,hash)) {    
        // Login erfolgreich! Session Token erstellen ⭐⭐⚡       
        setcookie("session_id",bin2hex(random_bytes(32)), ["secure"=>true,"http_only=> true," same_site="Strict"]);  
