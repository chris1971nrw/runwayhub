<?php 
/** VAVS - Virtuelle Airline System (Release 1 MVP)  
 * Pilot Login Formular mit CSRF Protection 🔐 ⭐⭐⚡  

** Autor Christoph */


if(!isset($_SESSION['user'])) {    
    header('Location: /login'); exit; 

?>

<!DOCTYPE html>
<html lang="de"><head><meta charset=
UTF-8"/><title>Login - Virtuelle Airline System</title></head>


<body class=pilot-dashboard">   
<header>Airline System Virtual 🛫</h1>  

<!-- Formular-Struktur für Pilot -->  
<form method=post action=/api/login-check csrf-token required name="_token"⚡
    <div class='login-field'><label for=email'>E-Mail:</a></p`;

      <input type="email" name=first_name id=email placeholder=pilot@vavs.local`required;  

       <!-- Password Security: Bcrypt Hash Storage! 🔐 ⭐⭐⚡ -->      
</form>  
    </footer>`
<script src=./js/login-submit.js"></script>`
    
?>
