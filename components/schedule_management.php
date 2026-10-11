<?php
/**
 * runwayhub - Schedule Management Komponente
 * 
 * Flugpläne erstellen & verwalten mit ICAO-Routen und Aircraft-Zuweisung.
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

// Flugpläne anzeigen
$schedules = $pdo->query("SELECT * FROM flight_schedules ORDER BY departure_date DESC")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>runwayhub - Schedule Management</title>
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
        .schedule-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px; }
        .schedule-card { background: rgba(255,255,255,0.08); padding: 20px; border-radius: 8px; transition: transform 0.3s; }
        .schedule-card:hover { transform: translateY(-5px); background: rgba(255,255,255,0.12); }
        .schedule-icon { font-size: 2em; margin-bottom: 10px; }
        .route-route { display: flex; align-items: center; gap: 10px; margin: 10px 0; }
        .airport-code { background: rgba(255,255,255,0.2); padding: 8px 12px; border-radius: 5px; font-weight: bold; }
        .status-active { color: #69f0ae; }
        .status-inactive { color: #ff6b6b; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1); }
        th { color: #81d4fa; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>📅 Schedule Management</h1>
            <p style="font-size: 1.3em; margin-bottom: 20px;">Flugpläne erstellen & verwalten mit ICAO-Routen und Aircraft-Zuweisung</p>
            <a href="../dashboard.php" class="back-link">← Zurück zum Dashboard</a>
        </header>

        <?php if ($user === 'admin' || $user === 'ground_control'): ?>
        <section class="form-card">
            <h2>➕ Neuer Flugplan erstellen</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="callsign">Callsign (z.B. RWH-001)</label>
                    <input type="text" name="callsign" id="callsign" required placeholder="RWH-001">
                </div>
                
                <div class="form-group">
                    <label for="departure_date">Abflugdatum</label>
                    <input type="date" name="departure_date" id="departure_date" required>
                </div>
                
                <div class="form-group">
                    <label for="from_airport">Abflug Flughafen (ICAO)</label>
                    <input type="text" name="from_airport" id="from_airport" required placeholder="EGKK">
                </div>
                
                <div class="form-group">
                    <label for="to_airport">Ziel Flughafen (ICAO)</label>
                    <input type="text" name="to_airport" id="to_airport" required placeholder="LHBB">
                </div>
                
                <div class="form-group">
                    <label for="estimated_off">Abflugzeit (ETA)</label>
                    <input type="datetime-local" name="estimated_off" id="estimated_off" required>
                </div>
                
                <div class="form-group">
                    <label for="estimated_arrival">Ankunftszeit (ETA)</label>
                    <input type="datetime-local" name="estimated_arrival" id="estimated_arrival" required>
                </div>
                
                <div class="form-group">
                    <label for="aircraft_type">Aircraft Typ</label>
                    <select name="aircraft_type" id="aircraft_type" required>
                        <option value="A320">Airbus A320</option>
                        <option value="B738">Boeing 737-800</option>
                        <option value="A319">Airbus A319</option>
                        <option value="B752">Boeing 757-200</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="distance_nm">Distanz (NM)</label>
                    <input type="number" name="distance_nm" id="distance_nm" placeholder="450">
                </div>
                
                <div class="form-group">
                    <label for="route_notes">Route Notes (optional)</label>
                    <textarea name="route_notes" id="route_notes" rows="3" placeholder="Zusätzliche Hinweise zur Route..."></textarea>
                </div>
                
                <button type="submit">Flugplan erstellen</button>
            </form>
        </section>
        <?php endif; ?>

        <section>
            <h2>📊 Aktuelle Flugpläne</h2>
            
            <?php if (empty($schedules)): ?>
                <p>Keine Flugpläne vorhanden.</p>
            <?php else: ?>
                <div class="schedule-grid">
                    <?php foreach ($schedules as $schedule): ?>
                        <div class="schedule-card">
                            <div class="schedule-icon">✈️</div>
                            <h3><?php echo htmlspecialchars($schedule['callsign']); ?></h3>
                            
                            <div class="route-route">
                                <span class="airport-code"><?php echo htmlspecialchars($schedule['from_airport_icao']); ?></span>
                                →
                                <span class="airport-code"><?php echo htmlspecialchars($schedule['to_airport_icao']); ?></span>
                            </div>
                            
                            <p><strong>📅 Datum:</strong> <?php echo date('d.m.Y', strtotime($schedule['departure_date'])); ?></p>
                            <p><strong>⏰ Abflug:</strong> <?php echo date('H:i', strtotime($schedule['estimated_off'])); ?></p>
                            <p><strong>⏰ Ankunft:</strong> <?php echo date('H:i', strtotime($schedule['estimated_arrival'])); ?></p>
                            
                            <?php if ($schedule['aircraft_type']): ?>
                                <p><strong>✈️ Aircraft:</strong> <?php echo htmlspecialchars($schedule['aircraft_type']); ?></p>
                            <?php endif; ?>
                            
                            <?php if ($schedule['distance_nm']): ?>
                                <p><strong>📏 Distanz:</strong> <?php echo $schedule['distance_nm']; ?> NM</p>
                            <?php endif; ?>
                            
                            <?php if ($schedule['route_notes']): ?>
                                <p style="font-size: 0.9em; margin-top: 10px;"><strong>📝 Notes:</strong><br><?php echo nl2br(htmlspecialchars($schedule['route_notes'])); ?></p>
                            <?php endif; ?>
                            
                            <div style="margin-top: 15px;">
                                <span class="<?php echo $schedule['active'] ? 'status-active' : 'status-inactive'; ?>">
                                    <?php echo $schedule['active'] ? '✅ Aktiv' : '❌ Inaktiv'; ?>
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
