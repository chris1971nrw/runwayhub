<?php

declare(strict_types=1);

/**
 * Dashboard Page
 * 
 * Hauptübersicht für Piloten
 */

// Session-Check
session_start();
if (!isset($_SESSION['user']['id'])) {
    header('Location: /login.php');
    exit;
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RunwayHub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Segoe UI", Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; color: white; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; padding: 20px 40px; background: rgba(255,255,255,0.1); border-radius: 10px; margin-bottom: 20px; }
        .user-info { font-size: 1.2em; }
        .nav a { display: inline-block; padding: 10px 20px; background: rgba(255,255,255,0.2); color: white; text-decoration: none; border-radius: 5px; margin-left: 10px; transition: background 0.3s; }
        .nav a:hover { background: rgba(255,255,255,0.3); }
        .dashboard { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .card { background: rgba(255,255,255,0.95); padding: 30px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.2); }
        .card h2 { font-size: 1.8em; margin-bottom: 15px; color: #764ba2; }
        .card p { color: #555; margin-bottom: 10px; }
        .btn { padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; display: inline-block; cursor: pointer; transition: transform 0.3s; }
        .btn:hover { transform: translateY(-3px); }
        .logout { background: #ff6b6b; }
        footer { text-align: center; padding: 20px; margin-top: 30px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <div class="user-info">
            <span>👤 <?= htmlspecialchars($user['callsign']) ?> - <?= htmlspecialchars($user['airline'] ?? 'Guest') ?></span>
        </div>
        <nav class="nav">
            <a href="/dashboard.php">Dashboard</a>
            <a href="/va-admin.php">VA Verwalten</a>
            <a href="/login.php" class="logout">Logout</a>
        </nav>
    </div>
    
    <div class="dashboard">
        <div class="card">
            <h2>👋 Willkommen</h2>
            <p>Begrüßung, <?= htmlspecialchars($user['callsign']) ?>!</p>
            <p>Ihr Dashboard ist bereit.</p>
        </div>
        
        <div class="card">
            <h2>🛫 Meine Flüge</h2>
            <p>Kommende Flüge:</p>
            <ul style="margin-left: 20px; color: #555;">
                <li>Durchsuche nach Ihren Piloten-Flügen...</li>
            </ul>
            <br>
            <a href="/flights.php" class="btn">Flüge anzeigen</a>
        </div>
        
        <div class="card">
            <h2>🌤️ Wetter</h2>
            <p>Lokale Wetter-Updates:</p>
            <ul style="margin-left: 20px; color: #555;">
                <li>EDDF: 20°C, Wind 270°, 10kt</li>
                <li>Wetterwarnungen: Keine aktiv</li>
            </ul>
            <br>
            <a href="/weather.php" class="btn">Wetter ansehen</a>
        </div>
        
        <div class="card">
            <h2>📊 Statistiken</h2>
            <p>Ihre Flugstatistiken:</p>
            <ul style="margin-left: 20px; color: #555;">
                <li>Flüge: 0</li>
                <li>Stunden: 0h</li>
                <li>Ranking: #0</li>
            </ul>
            <br>
            <a href="/statistics.php" class="btn">Statistiken</a>
        </div>
        
        <div class="card">
            <h2>✈️ Airline Portal</h2>
            <p><strong><?= htmlspecialchars($user['airline'] ?? 'Nicht verbunden') ?></strong></p>
            <p>Verbinden Sie sich mit Ihrer Airline:</p>
            <br>
            <a href="/va-admin.php" class="btn">VA Verwalten</a>
        </div>
        
        <div class="card">
            <h2>📚 Hilfe</h2>
            <p>Hilfe und Support:</p>
            <ul style="margin-left: 20px; color: #555;">
                <li>Dokumentation lesen</li>
                <li>FAQ durchsuchen</li>
                <li>Support kontaktieren</li>
            </ul>
        </div>
    </div>
    
    <footer>
        <p>Built with ❤️ by @chris1971nrw | Powered by OpenAIP API</p>
    </footer>
</body>
</html>
