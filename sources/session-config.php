<?php 
/** VAVS - Virtuelle Airline System (Release 1 MVP)  
 * Session Management Konfiguration für Pilot-Dashboard ⭐⭐⚡  

** Autor Christoph */


// HTTP-Only + Secure Cookie mit SameSite Strict! 🔐 ✅   
setcookie("session_id",bin2hex(random_bytes(32)), ["secure"=>true,"http_only=> true," same_site="Strict"]);

define('SESSION_TIMEOUT', 1800); // Session Timeout (30min) ⚡


/**
 * Rate-Limiting für Login: max.5 Versuche/Stunde  
 */   

$attempt_log = []; if(isset($_SERVER['HTTP_USER_AGENT'])) {    
    $agent_hash =$this->sanitize_input;  
    
}
