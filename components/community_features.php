<?php
/**
 * runwayhub - Community Features Komponente
 * 
 * Reviews, Leaderboards und Wetter Widget für bessere Flugplanung.
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

// Alle Piloten für Leaderboard anzeigen
$all_pilots = $pdo->query("SELECT * FROM pilots ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Wetter Widget (Open-Meteo API)
function get_weather($airport_icao) {
    // Simulierter Wetter-Daten-Abfrage
    return [
        'temperature' => 15 + rand(-5, 10),
        'condition' => ['Sunny', 'Cloudy', 'Rain'][rand(0, 2)],
        'wind_speed' => rand(5, 30)
    ];
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>runwayhub - Community Features</title>
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
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px; }
        .feature-card { background: rgba(255,255,255,0.08); padding: 20px; border-radius: 8px; transition: transform 0.3s; }
        .feature-card:hover { transform: translateY(-5px); background: rgba(255,255,255,0.12); }
        .feature-icon { font-size: 2em; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1); }
        th { color: #81d4fa; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>👥 Community Features</h1>
            <p style="font-size: 1.3em; margin-bottom: 20px;">Reviews, Leaderboards und Wetter Widget für bessere Flugplanung</p>
            <a href="../dashboard.php" class="back-link">← Zurück zum Dashboard</a>
        </header>

        <?php if ($user === 'admin' || $user === 'ground_control'): ?>
        <section class="form-card">
            <h2>⭐ Flug Review schreiben</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="callsign">Flugplan (Callsign)</label>
                    <input type="text" name="callsign" id="callsign" placeholder="RWH-001">
                </div>
                
                <div class="form-group">
                    <label for="rating">Bewertung (1-5 Sterne)</label>
                    <select name="rating" id="rating" required>
                        <option value="5">⭐⭐⭐⭐⭐ Perfekt</option>
                        <option value="4">⭐⭐⭐⭐ Sehr gut</option>
                        <option value="3">⭐⭐⭐ Gut</option>
                        <option value="2">⭐⭐ Okay</option>
                        <option value="1">⭐ Schlecht</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="review">Review schreiben</label>
                    <textarea name="review" id="review" rows="4" placeholder="Wie war der Flug?"></textarea>
                </div>
                
                <button type="submit">Review veröffentlichen</button>
            </form>
        </section>
        <?php endif; ?>

        <section>
            <h2>🏆 Leaderboard (Top Piloten)</h2>
            
            <?php if (empty($all_pilots)): ?>
                <p>Noch keine Piloten.</p>
            <?php else: ?>
                <table>
                    <tr><th>#</th><th>Pilot</th><th>Callsign</th><th>Flüge</th><th>Bewertung</th></tr>
                    <?php foreach ($all_pilots as $index => $pilot): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($pilot['name']); ?></td>
                            <td><?php echo htmlspecialchars($pilot['callsign']); ?></td>
                            <td>0</td>
                            <td>⭐⭐⭐⭐⭐</td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </section>

        <section style="margin-top: 40px;">
            <h2>🌤️ Wetter Widget</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🌤️</div>
                    <h3>München (EDDM)</h3>
                    <p><strong>Temperatur:</strong> <?php echo get_weather('EDDM')['temperature']; ?>°C</p>
                    <p><strong>Wetter:</strong> <?php echo get_weather('EDDM')['condition']; ?></p>
                    <p><strong>Wind:</strong> <?php echo get_weather('EDDM')['wind_speed']; ?> km/h</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">☀️</div>
                    <h3>München (EDDM)</h3>
                    <p><strong>Temperatur:</strong> <?php echo get_weather('EDDM')['temperature']; ?>°C</p>
                    <p><strong>Wetter:</strong> <?php echo get_weather('EDDM')['condition']; ?></p>
                    <p><strong>Wind:</strong> <?php echo get_weather('EDDM')['wind_speed']; ?> km/h</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🌧️</div>
                    <h3>München (EDDM)</h3>
                    <p><strong>Temperatur:</strong> <?php echo get_weather('EDDM')['temperature']; ?>°C</p>
                    <p><strong>Wetter:</strong> <?php echo get_weather('EDDM')['condition']; ?></p>
                    <p><strong>Wind:</strong> <?php echo get_weather('EDDM')['wind_speed']; ?> km/h</p>
                </div>
            </div>
        </section>

        <section style="margin-top: 40px;">
            <h2>💬 Community Diskussionen</h2>
            <p>Noch keine Diskussionen. Sei der Erste, der ein Thema startet!</p>
        </section>
    </div>
</body>
</html>
