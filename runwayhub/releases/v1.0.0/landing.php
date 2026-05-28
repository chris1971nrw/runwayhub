<?php

declare(strict_types=1);

/**
 * RunwayHub - Landing Page
 * Hauptseite für RunwayHub
 */

// Enable error display for debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Set timezone
date_default_timezone_set('Europe/Berlin');

// Load environment
if (file_exists(__DIR__ . '/../runwayhub/.env')) {
    $env = parse_ini_file(__DIR__ . '/../runwayhub/.env');
    if (is_array($env)) {
        foreach ($env as $key => $value) {
            if (!getenv($key) && !defined($key)) {
                putenv("$key=$value");
            }
        }
    }
}

// Output HTML
echo '<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RunwayHub - Virtual Airline Management System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Segoe UI", Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; color: white; }
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        h1 { font-size: 3em; margin: 0; }
        nav a { display: inline-block; padding: 10px 20px; background: rgba(255,255,255,0.2); color: white; text-decoration: none; border-radius: 5px; margin-left: 10px; transition: background 0.3s; }
        nav a:hover { background: rgba(255,255,255,0.3); }
        .hero { text-align: center; padding: 60px 20px; background: rgba(255,255,255,0.1); border-radius: 10px; margin-bottom: 40px; }
        .hero h2 { font-size: 2.5em; margin-bottom: 20px; }
        .hero p { font-size: 1.3em; margin-bottom: 30px; opacity: 0.9; }
        .features { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .feature { background: rgba(255,255,255,0.1); padding: 30px; border-radius: 10px; text-align: center; }
        .feature h3 { font-size: 1.5em; margin-bottom: 15px; }
        .feature p { font-size: 1em; opacity: 0.8; }
        .cta { text-align: center; padding: 40px 20px; }
        .btn { display: inline-block; padding: 15px 40px; background: white; color: #764ba2; text-decoration: none; border-radius: 50px; font-size: 1.2em; font-weight: bold; transition: transform 0.3s, box-shadow 0.3s; }
        .btn:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }
        footer { text-align: center; padding: 20px; opacity: 0.8; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🛫 RunwayHub</h1>
            <nav>
                <a href="/landing.php">Home</a>
                <a href="/api/">API</a>
                <a href="/dashboard-public.php">Dashboard</a>
                <a href="/weather-widget.html">Weather</a>
            </nav>
        </header>
        
        <div class="hero">
            <h2>Modernes Virtual Airline Management System</h2>
            <p>Verwalten Sie Ihre Virtual Airline mit modernsten Tools und Features</p>
        </div>
        
        <div class="features">
            <div class="feature">
                <h3>📊 Dashboard</h3>
                <p>Übersicht über alle Flüge, Statistiken und Reports</p>
            </div>
            <div class="feature">
                <h3>🌤️ Weather API</h3>
                <p>Live-Wetterdaten, METAR/TAF, Alerts</p>
            </div>
            <div class="feature">
                <h3>🛩️ Flight Tracking</h3>
                <p>Live-Flugverfolgung via FlightAware API</p>
            </div>
            <div class="feature">
                <h3>📈 Reports</h3>
                <p>Detaillierte Statistiken und Analysen</p>
            </div>
            <div class="feature">
                <h3>👥 Pilotenverwaltung</h3>
                <p>Pilotendatenbank mit Statistiken</p>
            </div>
            <div class="feature">
                <h3>🏆 Leaderboards</h3>
                <p>Podiums und Ranglisten</p>
            </div>
        </div>
        
        <div class="cta">
            <a href="/dashboard-public.php" class="btn">Zum Dashboard</a>
        </div>
    </div>
    
    <footer>
        <p>Built with ❤️ by @chris1971nrw | Powered by OpenAIP API & Weather Services</p>
    </footer>
</body>
</html>';

exit;
