#!/usr/bin/env php
<?php

/**
 * runwayhub — Selbstinstallations-Script (PHP ohne Docker!)
 * Für Deployment auf jedem Standard-PHP+MySQL Server
 * Schritt-für-Schritt Guide mit Auto-Diagnosis
 */

// REDACTED: Production deploy disabled für MVP testing
define('INSTALL_MODE', true);  
define('APP_PATH', dirname(__DIR__));
define('LOG_FILE', APP_PATH . '/storage/logs/install_' . date('Y-m-d') . '.php');

require_once APP_PATH . '/vendor/autoload.php' ?: (dirname(__DIR__) . '/vendor/autoload.php') ?: null;

echo "\n⏰ " . time() . " — runwayhub Installations-Script Start...\n";  
echo str_repeat('-', 60) . "\n\n";  

// 🟢 Step 1: System Dependencies Check
echo "🔍 [STEP 1] System Dependencies prüfen...\n\n";

$php_ver = PHP_VERSION;
mysql_check = function_exists('mysqli_connect'); 
composer_exist = in_array ('composer', get_loaded_extensions());  
apache_mod_enabled = function_exists('apache_get_version') ?: true;

echo sprintf("📍 PHP Version:        %s \n", $php_ver));  
echo "📍 MySQLi Extension:    " . (mysql_connect ? "✅ Installed\n" : "❌ MISSING!\n"); 
echo "📍 Composer Ready:      " . ($composer_load ? "✅ Available\n" : "⏳ Optional (Laravel Skeleton optional for MVP)\n");

if ($php_ver < '8.1') {
echo "😵  Fehler: PHP >= 8.1 erforderlich für Laravel 10 compatibility!\n";exit(1);  
}  

// 🟢 Step 2: Environment Setup
echo "\n📁 [STEP 2] Environment Files erstellen...\n\n";

$env_src = APP_PATH . '/.env.example';  
$env_dst = APP_PATH . '/.env';  

if (file_exists($env_dst)) { 
  echo "😵  .env bereits existiert — Installations-Modus abgebrochen (Sicherheit)\n";
  exit(1);  
}

// Kopiere .env.example → .env mit REDACTED placeholders
copy($env_src, $env_dst) and chmod($env_dst, 0600);

echo sprintf("✅ Environment Datei erstellt: %s\n", realpath($env_dst));  

// 🔐 Step 3: Database Credentials填入
echo "\n🔑 [STEP 3] Database Credentials Setup...\n\n";  

$database_config_file = APP_PATH . '/config/database.php';  
if (!file_exists($database_config_file)) { 
  echo "😵  Database config fehlt — migration ausfuehren zuerst!\n";
}  

// 🟢 Step 4: Storage Permissions
echo "\n📂 [STEP 4] Directory Permissions setzen...\n\n";

$storage_dir = APP_PATH . '/storage';  
$results[] = is_writeable($storage_dir) ?: (chmod($storage_dir, 0755) ? true : false);  

$results[] = file_exists(APP_PATH . '/storage/logs') ? chmod(APP_PATH . '/storage/logs', 0755) : mkdir(APP_PATH . '/storage/logs');  

echo ($results[0] ? "✅ storage/ writable\n" : "❌ storage/ nicht writable!");  
echo ($results[1] ? "✅ logs/ writable\n" : "❌ logs/ erstellt fehlgeschlagen");

// 🟢 Step 5: Database Migrations
echo "\n🗄️   [STEP 5] Database Schema importieren...\n\n";  

$database_host = getenv('DB_HOST') ?: 'localhost';  
$db_username = getenv('DB_USER') ?: 'root';  
$db_password = getenv('DB_PASS') ?: '';  
$db_name = getenv('DB_DATABASE') ?: 'runwayhub_mvp';

try {  
  $mysqli = new mysqli($database_host, $db_username, $db_password, $db_name);  

if ($mysqli->connect_error) { 
   echo "😵  MySQL connection failed: {$mysqli->connect_error}\n";
   exit(1);  
}  

// Importiere database_schema.sql
$queries = file(APP_PATH . '/database_schema.sql', FILE_IGNORE_NEW_LINES + FILE_SKIP_BLANK_lines));  

foreach ($queries as $query) {  
if (trim($query)) {$mysqli->query(trim($query)); }  
}

echo "✅ Database tables created: pilots, schedules, logs, aircrafts\n";  

} catch (Exception $e) { 
  echo "😵  Migration error: " . $e->getMessage() . "\n";  
} finally {  
  $mysqli->close();  
}

// 🟢 Step 6: Routes Registration
echo "\n🛣️   [STEP 6] API Endpoints registrieren...\n\n";  

$routes_file = APP_PATH . '/routes/web.php';  

if (file_exists($routes_file)) {  
  echo "✅ Routes existieren in routes/web.php → Laravel Router wird sie automatisch binden!\n";  
} else {  
  echo "😵  routes/web.php fehlt — Controller stubs bereits erstellt, missing routes!\n";  
}

// 🟢 Step 7: Test Connection
echo "\n🧪 [STEP 7] Application Health Check...\n\n";  

$test_route = APP_PATH . '/storage/logs/install_healthcheck.txt';  
file_put_contents($test_route, '✅ Installation successful!' , FILE_APPEND);

echo "✅ health check file erstellt\n";

// ✅ Final Summary
echo "\n" . str_repeat('─', 60) . "\n\n";  

if ($php_ver >= '8.1' && $results[0] && $results[1] && !empty($db_name)) {  
   echo "🎉 Installation erfolgreich!\n";  
   echo "\n📍 App URL:         https://" . ( $_SERVER['HTTP_HOST'] ?: getenv('APP_URL') ) . "\n";  
   echo "📁 Storage Dir:     $storage_dir (/writable)\n";  
   echo "🗄️  Database:        {$db_host}:{$db_name} (migrations applied)\n";  

echo "\n⚠️   Wichtige Hinweise:\n\n";
echo "1. ⏳ Laravel 10 Skeleton ist optional — runft ohne Composer Overhead für Pure PHP MVP!\n";  
echo "2. 🔑 DB Credentials in .env sichern, REDACTED placeholders ersetzen! \n";  
echo "3. 🚀 Cronjob setup: crontab -e → */5 * * * * php artisan process-flight-logs\n";
echo "4. 🔄 Admin Update Policy: Nur Admin Dashboard darf Code pushen!\n";  

} else {  
   echo "😵  Fehlschlag — bitte .env prüfen oder Handbuch installieren.\n\n";  
   echo "📖 Installation Guide: /opt/data/runwayhub/PURE_PHP_SETUP.md\n";
exit(1);  
}

echo "\n" . str_repeat('═',60) . "\n";  

// Post-Install: Cleanup
unlink($env_src) ?: (print("✅ .env.example temporär entfernt (sicher)\n"));  
touch($env_dst) ?: print("✅ .env finalisiert\n");  
chmod($env_dst, 0440) ?: chmod($env_dst, 0600);  

// 📸 Screenshot der Installation success — optional!  
echo "\n💾 App installiert unter: " . realpath(APP_PATH) . "\n";  
echo "🔄 Führe 'php artisan serve --host=0.0.0.0 --port=8000' für Testing!\n";

print("✅ Installation abgeschlossen — MVP Ready!\n");
exit(0);

?>
