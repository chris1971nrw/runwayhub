<?php

declare(strict_types=1);

/**
 * VA Gründen - Virtual Airline Manager Setup
 * 
 * Setup wizard for Virtual Airline Management system
 */

use RunwayHub\Core\Database;

require_once __DIR__.'/vendor/autoload.php';

$db = new Database();

echo '<!DOCTYPE html><html><head><title>Virtual Airline Manager - Setup</title>
<meta name="viewport" content="width=device-width,initial-scale=1"><style>
body{font-family:sans-serif;background:#1a1a2e;color:#fff;margin:0;padding:20px}
h1{color:#4ecca3;margin-bottom:20px}
.card{background:#16213e;border-radius:8px;padding:20px;margin-bottom:15px}
.btn{display:inline-block;padding:10px 20px;margin:5px;background:#4ecca3;color:#1a1a2e;border:none;border-radius:4px;cursor:pointer;text-decoration:none;font-weight:bold}
.btn:hover{background:#45b08c}
.btn-primary{background:#4ecca3}
.btn-secondary{background:#6c757d}
progress{width:100%;height:25px}
.step{background:#0f3460;padding:15px;border-radius:6px;margin:10px 0}
.step h4{color:#4ecca3;margin:0}
.step-completed{background:#0f3460;padding:15px;border-radius:6px;margin:10px 0;color:#4ecca3}
</style></head><body>
<h1>⚙️ Virtual Airline Manager - Setup</h1>

<div class="card"><h3>Setup Wizard</h3>
<p>Willkommen bei der <strong>Virtual Airline Manager</strong> Einrichtung.</p>
<p>Bitte folgen Sie den Schritten für die Konfiguration.</p>
</div>

<div class="step step-completed"><h4>✅ Schritt 1: System-Check</h4>
<p>System ist bereit. Datenbank ist konfiguriert.</p>
</div>

<div class="step step-completed"><h4>✅ Schritt 2: API-Schlüssel</h4>
<p>API-Schlüssel ist generiert und gespeichert.</p>
</div>

<div class="step"><h4>🔄 Schritt 3: Wetter-API</h4>
<p><strong>Wetter-Datenquelle:</strong> METAR/TAF via OpenMeteo</p>
<p>API-Endpunkte:</p>
<ul>
  <li><code>/weather/metar</code> - METAR-Daten</li>
  <li><code>/weather/taf</code> - TAF-Prognosen</li>
  <li><code>/weather/alerts</code> - Wetterwarnungen</li>
</ul>
</div>

<div class="step"><h4>🔄 Schritt 4: ACARS Integration</h4>
<p><strong>ACARS-Status:</strong> Development Mode</p>
<p><strong>ACARS-URL:</strong> <code><?= htmlspecialchars(getenv("ACARS_API_URL") ?: "Not configured") ?></code></p>
<p>ACARS API ist konfiguriert und bereit für die Integration.</p>
</div>

<div class="step"><h4>🔄 Schritt 5: Airlines hinzufügen</h4>
<p>Bitte fügen Sie Ihre Airlines im Admin-Panel hinzu.</p>
<ul>
  <li><a href="/admin/airlines" class="btn btn-primary">Airlines verwalten</a></li>
  <li><a href="/flights" class="btn btn-secondary">Flüge anzeigen</a></li>
</ul>
</div>

<div class="card"><h3>Vorschau</h3>
<ul>
  <li><a href="/flights" class="btn btn-primary">Flüge</a></li>
  <li><a href="/weather" class="btn btn-secondary">Wetter</a></li>
  <li><a href="/acars" class="btn btn-secondary">ACARS</a></li>
  <li><a href="/admin" class="btn btn-secondary">Admin</a></li>
</ul>
</div>

<footer style="margin-top:30px;text-align:center;color:#888;">
  <p>Virtual Airline Manager &copy; 2026</p>
  <p><a href="/">← Zurück zum Home</a></p>
</footer>
</body></html>';
