<?php
/**
 * runwayhub - Flight Tracking Komponente
 * 
 * Flugprotokolle hochladen & tracken mit MSFS/X-Plane Integration.
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

// Aktive Flüge für den Piloten anzeigen
$pilot_id = $_SESSION['user_id'];
$active_flights = $pdo->query("SELECT * FROM flight_logs WHERE pilot_id = ? AND completed = FALSE ORDER BY created_at DESC LIMIT 10", [$pilot_id])->fetchAll(PDO::FETCH_ASSOC);

// Alle Flüge des Piloten anzeigen
$all_flights = $pdo->query("SELECT * FROM flight_logs WHERE pilot_id = ? ORDER BY created_at DESC", [$pilot_id])->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>runwayhub - Flight Tracking</title>
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
        .flight-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px; }
        .flight-card { background: rgba(255,255,255,0.08); padding: 20px; border-radius: 8px; transition: transform 0.3s; position: relative; }
        .flight-card:hover { transform: translateY(-5px); background: rgba(255,255,255,0.12); }
        .flight-icon { font-size: 2em; margin-bottom: 10px; }
        .route-route { display: flex; align-items: center; gap: 10px; margin: 10px 0; }
        .airport-code { background: rgba(255,255,255,0.2); padding: 8px 12px; border-radius: 5px; font-weight: bold; }
        .status-active { color: #ffc107; }
        .status-completed { color: #69f0ae; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1); }
        th { color: #81d4fa; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>✈️ Flight Tracking</h1>
            <p style="font-size: 1.3em; margin-bottom: 20px;">Flugprotokolle hochladen & tracken mit MSFS/X-Plane Integration</p>
            <a href="../dashboard.php" class="back-link">← Zurück zum Dashboard</a>
        </header>

        <?php if ($user === 'pilot'): ?>
        <section class="form-card">
            <h2>📤 Flugprotokoll hochladen</h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="schedule_id">Flugplan (Callsign)</label>
                    <input type="text" name="schedule_id" id="schedule_id" required placeholder="RWH-001">
                </div>
                
                <div class="form-group">
                    <label for="actual_from_airport">Tatsächlicher Abflug (ICAO)</label>
                    <input type="text" name="actual_from_airport" id="actual_from_airport" required placeholder="EGKK">
                </div>
                
                <div class="form-group">
                    <label for="actual_to_airport">Tatsächliche Ankunft (ICAO)</label>
                    <input type="text" name="actual_to_airport" id="actual_to_airport" required placeholder="LHBB">
                </div>
                
                <div class="form-group">
                    <label for="actual_off">Tatsächlicher Abflug (ETA)</label>
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
                    <label for="weather_conditions">Wetterbedingungen</label>
                    <input type="text" name="weather_conditions" id="weather_conditions" placeholder="Clear, Cloudy, Rain...">
                </div>
                
                <div class="form-group">
                    <label for="comments">Kommentare</label>
                    <textarea name="comments" id="comments" rows="3" placeholder="Notizen zum Flug..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="rating">Flugbewertung (1-5)</label>
                    <input type="number" name="flight_rating" id="rating" min="1" max="5" step="0.1" placeholder="4.5">
                </div>
                
                <div class="form-group">
                    <label for="screenshots">Screenshots (optional)</label>
                    <input type="file" name="screenshots[]" id="screenshots" multiple accept="image/*">
                </div>
                
                <button type="submit">Flugprotokoll hochladen</button>
            </form>
        </section>
        <?php endif; ?>

        <section>
            <h2>📊 Meine aktiven Flüge</h2>
            
            <?php if (empty($active_flights)): ?>
                <p>Keine aktiven Flüge.</p>
            <?php else: ?>
                <div class="flight-grid">
                    <?php foreach ($active_flights as $flight): ?>
                        <div class="flight-card">
                            <div class="flight-icon">🛫</div>
                            <h3><?php echo htmlspecialchars($flight['actual_from_airport']); ?></h3>
                            
                            <div class="route-route">
                                <span class="airport-code"><?php echo htmlspecialchars($flight['actual_from_airport']); ?></span>
                                →
                                <span class="airport-code"><?php echo htmlspecialchars($flight['actual_to_airport']); ?></span>
                            </div>
                            
                            <p><strong>⏰ Abflug:</strong> <?php echo date('d.m.Y H:i', strtotime($flight['actual_off'])); ?></p>
                            <p><strong>⏰ Ankunft:</strong> <?php echo date('d.m.Y H:i', strtotime($flight['actual_arrival'])); ?></p>
                            
                            <?php if ($flight['fuel_burned']): ?>
                                <p><strong>⛽ Kraftstoff:</strong> <?php echo number_format($flight['fuel_burned'], 2); ?> Gallons</p>
                            <?php endif; ?>
                            
                            <?php if ($flight['comments']): ?>
                                <p style="font-size: 0.9em; margin-top: 10px;"><strong>📝 Notes:</strong><br><?php echo nl2br(htmlspecialchars($flight['comments'])); ?></p>
                            <?php endif; ?>
                            
                            <div style="margin-top: 15px;">
                                <span class="status-active">⏳ In Fluge...</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section style="margin-top: 40px;">
            <h2>📊 Historische Flüge</h2>
            
            <?php if (empty($all_flights)): ?>
                <p>Noch keine Flugprotokolle.</p>
            <?php else: ?>
                <div class="flight-grid">
                    <?php foreach ($all_flights as $flight): ?>
                        <div class="flight-card <?php echo $flight['completed'] ? 'status-completed' : ''; ?>">
                            <div class="flight-icon">✈️</div>
                            <h3><?php echo htmlspecialchars($flight['actual_from_airport']); ?></h3>
                            
                            <div class="route-route">
                                <span class="airport-code"><?php echo htmlspecialchars($flight['actual_from_airport']); ?></span>
                                →
                                <span class="airport-code"><?php echo htmlspecialchars($flight['actual_to_airport']); ?></span>
                            </div>
                            
                            <p><strong>⏰ Abflug:</strong> <?php echo date('d.m.Y H:i', strtotime($flight['actual_off'])); ?></p>
                            <p><strong>⏰ Ankunft:</strong> <?php echo date('d.m.Y H:i', strtotime($flight['actual_arrival'])); ?></p>
                            
                            <?php if ($flight['fuel_burned']): ?>
                                <p><strong>⛽ Kraftstoff:</strong> <?php echo number_format($flight['fuel_burned'], 2); ?> Gallons</p>
                            <?php endif; ?>
                            
                            <?php if ($flight['weather_conditions']): ?>
                                <p><strong>🌤️ Wetter:</strong> <?php echo htmlspecialchars($flight['weather_conditions']); ?></p>
                            <?php endif; ?>
                            
                            <?php if ($flight['comments']): ?>
                                <p style="font-size: 0.9em; margin-top: 10px;"><strong>📝 Notes:</strong><br><?php echo nl2br(htmlspecialchars($flight['comments'])); ?></p>
                            <?php endif; ?>
                            
                            <?php if ($flight['flight_rating']): ?>
                                <p style="font-size: 0.9em; margin-top: 10px;"><strong>⭐ Bewertung:</strong> <?php echo number_format($flight['flight_rating'], 2); ?>/5</p>
                            <?php endif; ?>
                            
                            <div style="margin-top: 15px;">
                                <span class="<?php echo $flight['completed'] ? 'status-completed' : 'status-active'; ?>">
                                    <?php echo $flight['completed'] ? '✅ Abgeschlossen' : '⏳ In Fluge...'; ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>
