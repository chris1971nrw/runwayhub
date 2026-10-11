<?php
/**
 * runwayhub - Komponenten-Übersicht
 * 
 * Zeigt alle verfügbaren PHP-Komponenten an.
 */

// Session prüfen
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$user = $_SESSION['role']; // pilot, crew, ground_control, admin

// Komponenten Liste
$components = [
    'schedule_management' => '📅 Flugpläne erstellen & verwalten mit ICAO-Routen und Aircraft-Zuweisung',
    'flight_tracking' => '✈️ Flugprotokolle hochladen & tracken mit MSFS/X-Plane Integration',
    'acars_integration' => '📡 Automatisches Log Parsing für MSFS/X-Plane mit Telemetry Daten',
    'community_features' => '👥 Reviews, Leaderboards und Wetter Widget für bessere Flugplanung',
    'admin_dashboard' => '🛠️ Finanzberichte & Fleet Management',
];

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>runwayhub - Komponenten</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #1a237e, #3949ab); color: #fff; min-height: 100vh; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        header { text-align: center; padding: 30px; background: rgba(0,0,0,0.3); border-radius: 10px; margin-bottom: 30px; }
        h1 { font-size: 2em; margin-bottom: 10px; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); }
        .back-link { display: inline-block; margin-top: 15px; color: #81d4fa; text-decoration: none; }
        .components-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px; }
        .component-card { background: rgba(255,255,255,0.08); padding: 20px; border-radius: 8px; transition: transform 0.3s; cursor: pointer; text-decoration: none; color: #fff; }
        .component-card:hover { transform: translateY(-5px); background: rgba(255,255,255,0.12); }
        .component-icon { font-size: 2em; margin-bottom: 10px; }
        .component-title { font-size: 1.3em; margin-bottom: 10px; color: #81d4fa; }
        .component-desc { font-size: 0.9em; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🧩 Komponenten</h1>
            <p style="font-size: 1.3em; margin-bottom: 20px;">Alle verfügbaren PHP-Komponenten für runwayhub</p>
            <a href="../dashboard.php" class="back-link">← Zurück zum Dashboard</a>
        </header>

        <section class="components-grid">
            <?php foreach ($components as $component => $description): ?>
                <a href="<?php echo $component . '.php'; ?>" class="component-card">
                    <div class="component-icon">🧩</div>
                    <div class="component-title"><?php echo $component; ?></div>
                    <div class="component-desc"><?php echo $description; ?></div>
                </a>
            <?php endforeach; ?>
        </section>

        <section style="margin-top: 40px; text-align: center;">
            <h2>🎯 Komponenten-Status</h2>
            <p>Allerlei Funktionen in separaten PHP-Dateien!</p>
            <div style="display: flex; gap: 15px; justify-content: center; margin-top: 20px;">
                <span class="badge">✅ schedule_management</span>
                <span class="badge">✅ flight_tracking</span>
                <span class="badge">✅ acars_integration</span>
                <span class="badge">✅ community_features</span>
                <span class="badge">✅ admin_dashboard</span>
            </div>
        </section>
    </div>
</body>
</html>
