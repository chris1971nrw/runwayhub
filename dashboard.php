<?php
/**
 * runwayhub - Dashboard Datei
 * 
 * Zeigt das Haupt-Dashboard für alle Rollen an.
 */

// Session prüfen
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user = $_SESSION['role']; // pilot, crew, ground_control, admin
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>runwayhub - Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #1a237e, #3949ab); color: #fff; min-height: 100vh; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        header { text-align: center; padding: 40px 20px; background: rgba(0,0,0,0.3); border-radius: 10px; margin-bottom: 30px; }
        h1 { font-size: 2.5em; margin-bottom: 10px; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); }
        .badges { display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; margin-top: 15px; }
        .badge { padding: 8px 15px; background: rgba(255,255,255,0.1); border-radius: 20px; font-size: 0.9em; }
        section { margin-bottom: 40px; background: rgba(255,255,255,0.05); padding: 30px; border-radius: 10px; backdrop-filter: blur(10px); }
        h2 { color: #81d4fa; margin-bottom: 20px; font-size: 1.8em; border-bottom: 2px solid #81d4fa; padding-bottom: 10px; }
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px; }
        .feature-card { background: rgba(255,255,255,0.08); padding: 20px; border-radius: 8px; transition: transform 0.3s; }
        .feature-card:hover { transform: translateY(-5px); background: rgba(255,255,255,0.12); }
        .feature-icon { font-size: 2em; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1); }
        th { color: #81d4fa; }
        .status-complete { color: #69f0ae; }
        footer { text-align: center; padding: 30px; background: rgba(0,0,0,0.3); margin-top: 40px; }
        footer a { color: #81d4fa; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🌉 runwayhub</h1>
            <p style="font-size: 1.3em; margin-bottom: 20px;">Willkommen, <?php echo htmlspecialchars($_SESSION['name']); ?>!</p>
            <div class="badges">
                <span class="badge">✅ MVP Phase 2: ABGESCHLOSSEN</span>
                <span class="badge">🧪 PHPUnit Tests: Active</span>
                <span class="badge">🔐 Security Scans: Active</span>
            </div>
        </header>

        <section>
            <h2>🎯 Dashboard</h2>
            <p>Du bist als <strong><?php echo htmlspecialchars($user); ?></strong> angemeldet.</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📅</div>
                    <h3>Schedule Management</h3>
                    <p>Flugpläne erstellen & verwalten mit ICAO-Routen und Aircraft-Zuweisung</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">✈️</div>
                    <h3>Flight Tracking</h3>
                    <p>Flugprotokolle hochladen & tracken mit MSFS/X-Plane Integration</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">📡</div>
                    <h3>ACARS Integration</h3>
                    <p>Automatisches Log Parsing für MSFS/X-Plane mit Telemetry Daten</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>Community Features</h3>
                    <p>Reviews, Leaderboards und Wetter Widget für bessere Flugplanung</p>
                </div>
            </div>
        </section>

        <section>
            <h2>📊 Datenbank Status</h2>
            <?php
            $pdo = new PDO("mysql:host=localhost;charset=utf8mb4", 'root', '');
            $pilots_count = $pdo->query("SELECT COUNT(*) as count FROM runwayhub_db.pilots")->fetch()['count'];
            $schedules_count = $pdo->query("SELECT COUNT(*) as count FROM runwayhub_db.flight_schedules")->fetch()['count'];
            $aircrafts_count = $pdo->query("SELECT COUNT(*) as count FROM runwayhub_db.aircrafts")->fetch()['count'];
            ?>
            <table>
                <tr><th>Tabelle</th><th>Anzahl Einträge</th></tr>
                <tr><td>Pilots</td><td class="status-complete"><?php echo $pilots_count; ?></td></tr>
                <tr><td>Flight Schedules</td><td class="status-complete"><?php echo $schedules_count; ?></td></tr>
                <tr><td>Aircrafts</td><td class="status-complete"><?php echo $aircrafts_count; ?></td></tr>
            </table>
        </section>

        <footer>
            <h3>🛫 Happy Flying! — runwayhub Team</h3>
            <p style="margin-top: 15px;">
                <a href="logout.php">Abmelden</a>
            </p>
            <p style="margin-top: 10px;">&copy; 2026 runwayhub. Alle Rechte vorbehalten.</p>
        </footer>
    </div>
</body>
</html>
