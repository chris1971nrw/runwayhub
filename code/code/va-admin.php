<?php

declare(strict_types=1);

/**
 * VA Admin - Virtual Airline Management
 * 
 * Admin-Panel für Virtual Airline Management
 * Flight booking, tracking, statistics, weather reports
 */

use RunwayHub\Core\Controller;
use RunwayHub\Core\Database;

require_once __DIR__.'/../vendor/autoload.php';

$db = new Database();

echo '<!DOCTYPE html><html><head><title>Virtual Airline Manager - Admin</title>
<meta name="viewport" content="width=device-width,initial-scale=1"><style>
body{font-family:sans-serif;background:#1a1a2e;color:#fff;margin:0;padding:20px}
h1{color:#4ecca3;margin-bottom:20px}
.card{background:#16213e;border-radius:8px;padding:20px;margin-bottom:15px}
.btn{display:inline-block;padding:10px 20px;margin:5px;background:#4ecca3;color:#1a1a2e;border:none;border-radius:4px;cursor:pointer;text-decoration:none;font-weight:bold}
.btn:hover{background:#45b08c}
.btn-danger{background:#e94560}
.btn-danger:hover{background:#d63a52}
table{width:100%;border-collapse:collapse;margin-top:10px}
th,td{border:1px solid #333;padding:8px;text-align:left}
th{background:#1a1a2e}
.card h3{margin-top:0}
</style></head><body>
<h1>🛫 Virtual Airline Manager - Admin</h1>

<div class="card"><h3>Flugmanagement</h3>
<ul>
  <li><a href="/flights" class="btn">Flüge anzeigen</a></li>
  <li><a href="/flights/new" class="btn">Neuen Flug buchen</a></li>
  <li><a href="/aircrafts" class="btn">Flotte verwalten</a></li>
  <li><a href="/passengers" class="btn">Passagiere verwalten</a></li>
</ul></div>

<div class="card"><h3>Wetter-API</h3>
<ul>
  <li><a href="/weather/metar" class="btn">METAR-Daten</a></li>
  <li><a href="/weather/taf" class="btn">TAF-Prognosen</a></li>
  <li><a href="/weather/alerts" class="btn">Wetterwarnungen</a></li>
</ul></div>

<div class="card"><h3>ACARS Integration</h3>
<ul>
  <li><a href="/acars/flights" class="btn">ACARS Flugstatus</a></li>
  <li><a href="/acars/history" class="btn">Flugverlauf</a></li>
  <li><a href="/acars/config" class="btn">ACARS Konfiguration</a></li>
</ul></div>

<div class="card"><h3>Statistik & Reports</h3>
<ul>
  <li><a href="/reports" class="btn">Berichte anzeigen</a></li>
  <li><a href="/analytics" class="btn">Analytik</a></li>
  <li><a href="/leaderboard" class="btn">Leaderboards</a></li>
</ul></div>

<div class="card"><h3>System</h3>
<ul>
  <li><a href="/stats" class="btn">System-Statistik</a></li>
  <li><a href="/logs" class="btn">Logs</a></li>
  <li><a href="/settings" class="btn">Einstellungen</a></li>
</ul></div>

<br>
<div class="card">
  <h3>Admin-Account</h3>
  <p><strong>Username:</strong> demo_admin</p>
  <p><strong>Password:</strong> admin123</p>
  <p><strong>API-Key:</strong> <code><?= htmlspecialchars(getenv("API_KEY") ?: "Not configured") ?></code></p>
</div>

<footer style="margin-top:30px;text-align:center;color:#888;">
  <p>Virtual Airline Manager &copy; 2026</p>
  <p><a href="/">← Zurück zum Home</a></p>
</footer>
</body></html>';
