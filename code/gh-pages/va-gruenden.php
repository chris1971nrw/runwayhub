<?php

declare(strict_types=1);

/**
 * RunwayHub - VA gründen
 * Formular für neue Virtual Airline
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('Europe/Berlin');

// Output HTML
echo '<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VA gründen - RunwayHub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Segoe UI", Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; color: white; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .form-container { max-width: 500px; width: 100%; background: rgba(255,255,255,0.1); padding: 40px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        h1 { text-align: center; margin-bottom: 30px; font-size: 2em; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="password"], textarea { width: 100%; padding: 12px; border: none; border-radius: 5px; background: rgba(255,255,255,0.9); font-size: 1em; }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus, textarea:focus { outline: 2px solid rgba(255,255,255,0.5); }
        .btn-submit { width: 100%; padding: 15px; background: white; color: #764ba2; border: none; border-radius: 5px; font-size: 1.1em; font-weight: bold; cursor: pointer; transition: transform 0.3s; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255,255,255,0.3); }
        .links { margin-top: 20px; text-align: center; }
        .links a { display: inline-block; margin: 5px; padding: 8px 15px; background: rgba(255,255,255,0.2); color: white; text-decoration: none; border-radius: 5px; font-size: 0.9em; }
        .links a:hover { background: rgba(255,255,255,0.3); }
        footer { text-align: center; margin-top: 30px; font-size: 0.8em; opacity: 0.7; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>🚀 VA gründen</h1>
        
        <form method="POST" action="/api/va-create.php">
            <div class="form-group">
                <label for="va_name">🏢 Airline-Name:</label>
                <input type="text" id="va_name" name="va_name" placeholder="Deutsche Airline" required>
            </div>
            
            <div class="form-group">
                <label for="va_callsign">✈️ Callsign:</label>
                <input type="text" id="va_callsign" name="va_callsign" placeholder="DL" required>
            </div>
            
            <div class="form-group">
                <label for="va_owner_callsign">👤 Owner Callsign:</label>
                <input type="text" id="va_owner_callsign" name="va_owner_callsign" placeholder="SW001" required>
            </div>
            
            <div class="form-group">
                <label for="va_owner_password">🔑 Owner Passwort:</label>
                <input type="password" id="va_owner_password" name="va_owner_password" placeholder="Ihr Passwort" required>
            </div>
            
            <div class="form-group">
                <label for="va_logo">🖼️ Logo (optional):</label>
                <input type="text" id="va_logo" name="va_logo" placeholder="URL zum Logo">
            </div>
            
            <div class="form-group">
                <label for="va_colors">🎨 Farben (optional):</label>
                <textarea id="va_colors" name="va_colors" rows="3" placeholder="Farben: Haupt #ff0000, Sekundär #00ff00"></textarea>
            </div>
            
            <button type="submit" class="btn-submit">VA gründen</button>
        </form>
        
        <div class="links">
            <a href="/">Home</a>
            <a href="/login.php">Login</a>
            <a href="/va-connect.php">VA anschließen</a>
        </div>
        
        <footer>
            <p>Powered by OpenAIP API & Weather Services</p>
        </footer>
    </div>
</body>
</html>';

exit;
