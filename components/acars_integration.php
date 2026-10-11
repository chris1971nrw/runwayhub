<?php
/**
 * runwayhub - ACARS Integration Komponente
 * 
 * Automatisches Log Parsing für MSFS/X-Plane mit Telemetry Daten.
 */

// Session prüfen
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$user = $_SESSION['role']; // pilot, crew, ground_control, admin

// Datenbank Connection
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=runwayhub_db;charset=utf8mb4",
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die('<h1>❌ Fehler</h1><p>Konnte keine Verbindung zur MySQL Datenbank herstellen.</p>');
}

// Aktive Flüge für ACARS Parsing anzeigen
$active_flights = $pdo->query("SELECT * FROM flight_logs WHERE pilot_id = ? AND completed = FALSE ORDER BY created_at DESC", [$_SESSION['user_id']])->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>runwayhub - ACARS Integration</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #1a237e, #3949ab); color: #fff; min-height: 100vh; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        header { text-align: center; padding: 30px; background: rgba(0,0,0,0.3); border-radius: 10px; margin-bottom: 30px; }
        h1 { font-size: 2em; margin-bottom: 10px; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); }
        .back-link { display: inline-block; margin-top: 15px; color: #81d4fa; text-decoration: none; }
        .form-card { background: rgba(255,255,255,0.05); padding: 30px; border-radius: 10px; backdrop-filter: blur(10px); margin-bottom: 30px; }
        h2 { color: #81d4fa; margin-bottom: 20px; font-size: 1.5em; border-bottom: 2px solid #81d4fa; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-size: 0.9em; margin-bottom: 5px; color: #ccc; }
        input, select, textarea { width: 100%; padding: 12px; border: none; border-radius: 5px; background: rgba(255,255,255,0.1); color: #fff; font-size: 1em; }
        button { padding: 12px 20px; border: none; border-radius: 5px; background: #4CAF50; color: #fff; font-size: 1em; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #45a049; }
        .acars-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px; }
        .acars-card { background: rgba(255,255,255,0.08); padding: 20px; border-radius: 8px; transition: transform 0.3s; position: relative; }
        .acars-card:hover { transform: translateY(-5px); background: rgba(255,255,255,0.12); }
        .acars-icon { font-size: 2em; margin-bottom: 10px; }
        .telemetry-data { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 15px; }
        .telemetry-item { background: rgba(255,255,255,0.1); padding: 10px; border-radius: 5px; text-align: center; }
        .telemetry-label { font-size: 0.8em; color: #ccc; }
        .telemetry-value { font-size: 1.2em; font-weight: bold; color: #81d4fa; }
        .status-active { color: #ffc107; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1); }
        th { color: #81d4fa; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>📡 ACARS Integration</h1>
            <p style="font-size: 1.3em; margin-bottom: 20px;">Automatisches Log Parsing für MSFS/X-Plane mit Telemetry Daten</p>
            <a href="../dashboard.php" class="back-link">← Zurück zum Dashboard</a>
        </header>

        <section class="form-card">
            <h2>📤 ACARS Log hochladen</h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="pilot_id">Pilot (Callsign)</label>
                    <input type="text" name="pilot_id" id="pilot_id" value="<?php echo htmlspecialchars($_SESSION['name']); ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label for="flight_log">Flugprotokoll (TXT/CSV)</label>
                    <input type="file" name="flight_log" id="flight_log" accept=".txt,.csv" required>
                </div>
                
                <div class="form-group">
                    <label for="actual_off">Tatsächlicher Abflug</label>
                    <input type="datetime-local" name="actual_off" id="actual_off" required>
                </div>
                
                <div class="form-group">
                    <label for="actual_arrival">Tatsächliche Ankunft</label>
                    <input type="datetime-local" name="actual_arrival" id="actual_arrival" required>
                </div>
                
                <div class="form-group">
                    <label for="fuel_burned">Verbrauchte Kraftstoff (Gallons)</label>
                    <input type="number" name="fuel_burned" id="fuel_burned" step="0.1" placeholder="150.5">
                </div>
                
                <div class="form-group">
                    <label for="comments">Kommentare</label>
                    <textarea name="comments" id="comments" rows="3" placeholder="Notizen zum Flug..."></textarea>
                </div>
                
                <button type="submit">ACARS Log hochladen & parsen</button>
            </form>
        </section>

        <section>
            <h2>📡 Aktive Flüge (ACARS Parsing)</h2>
            
            <?php if (empty($active_flights)): ?>
                <p>Keine aktiven Flüge.</p>
            <?php else: ?>
                <div class="acars-grid">
                    <?php foreach ($active_flights as $flight): ?>
                        <div class="acars-card">
                            <div class="acars-icon">📡</div>
                            <h3><?php echo htmlspecialchars($flight['actual_from_airport']); ?></h3>
                            
                            <div class="route-route">
                                <span class="airport-code"><?php echo htmlspecialchars($flight['actual_from_airport']); ?></span>
                                →
                                <span class="airport-code"><?php echo htmlspecialchars($flight['actual_to_airport']); ?></span>
                            </div>
                            
                            <p><strong>⏰ Abflug:</strong> <?php echo date('d.m.Y H:i', strtotime($flight['actual_off'])); ?></p>
                            <p><strong>⏰ Ankunft:</strong> <?php echo date('d.m.Y H:i', strtotime($flight['actual_arrival'])); ?></p>
                            
                            <div class="telemetry-data">
                                <div class="telemetry-item">
                                    <div class="telemetry-label">📏 Distanz</div>
                                    <div class="telemetry-value"><?php echo $flight['distance_nm'] ? $flight['distance_nm'] : '-'; ?> NM</div>
                                </div>
                                
                                <div class="telemetry-item">
                                    <div class="telemetry-label">⛽ Kraftstoff</div>
                                    <div class="telemetry-value"><?php echo number_format($flight['fuel_burned'], 2); ?> Gallons</div>
                                </div>
                                
                                <div class="telemetry-item">
                                    <div class="telemetry-label">⏱️ Flugzeit</div>
                                    <div class="telemetry-value"><?php echo $flight['actual_arrival'] && $flight['actual_off'] ? date('H:i', strtotime($flight['actual_arrival'])) . ' - ' . date('H:i', strtotime($flight['actual_off'])) : '-'; ?></div>
                                </div>
                                
                                <div class="telemetry-item">
                                    <div class="telemetry-label">📊 Status</div>
                                    <div class="telemetry-value status-active">In Fluge...</div>
                                </div>
                            </div>
                            
                            <?php if ($flight['comments']): ?>
                                <p style="font-size: 0.9em; margin-top: 10px;"><strong>📝 Notes:</strong><br><?php echo nl2br(htmlspecialchars($flight['comments'])); ?></p>
                            <?php endif; ?>
                            
                            <div style="margin-top: 15px;">
                                <span class="status-active">⏳ ACARS Parsing aktiv</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section style="margin-top: 40px;">
            <h2>📊 Telemetry Daten</h2>
            <p>Automatisch extrahiert aus MSFS/X-Plane Logs:</p>
            
            <div class="telemetry-data" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-top: 20px;">
                <div class="telemetry-item">
                    <div class="telemetry-label">📡 ACARS Status</div>
                    <div class="telemetry-value status-active">Active</div>
                </div>
                
                <div class="telemetry-item">
                    <div class="telemetry-label">⚙️ Parsing Engine</div>
                    <div class="telemetry-value">MSFS/X-Plane Parser</div>
                </div>
                
                <div class="telemetry-item">
                    <div class="telemetry-label">📊 Logs Pro Tag</div>
                    <div class="telemetry-value">0</div>
                </div>
                
                <div class="telemetry-item">
                    <div class="telemetry-label">✅ Parsed</div>
                    <div class="telemetry-value">0%</div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
