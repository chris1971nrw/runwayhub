<?php
/**
 * runwayhub - Admin Dashboard Komponente
 * 
 * Finanzberichte & Fleet Management.
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

// Statistiken anzeigen
$stats = [];
$stats['total_pilots'] = $pdo->query("SELECT COUNT(*) as count FROM pilots")->fetch()['count'];
$stats['active_schedules'] = $pdo->query("SELECT COUNT(*) as count FROM flight_schedules WHERE active = TRUE")->fetch()['count'];
$stats['completed_flights'] = $pdo->query("SELECT COUNT(*) as count FROM flight_logs WHERE completed = TRUE")->fetch()['count'];
$stats['total_aircrafts'] = $pdo->query("SELECT COUNT(*) as count FROM aircrafts")->fetch()['count'];

// User Management (nur Admin)
if ($user === 'admin') {
    $all_users = $pdo->query("SELECT * FROM pilots ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>runwayhub - Admin Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #1a237e, #3949ab); color: #fff; min-height: 100vh; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        header { text-align: center; padding: 30px; background: rgba(0,0,0,0.3); border-radius: 10px; margin-bottom: 30px; }
        h1 { font-size: 2em; margin-bottom: 10px; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); }
        .back-link { display: inline-block; margin-top: 15px; color: #81d4fa; text-decoration: none; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: rgba(255,255,255,0.08); padding: 20px; border-radius: 8px; text-align: center; }
        .stat-icon { font-size: 2em; margin-bottom: 10px; }
        .stat-value { font-size: 2em; font-weight: bold; color: #69f0ae; }
        .stat-label { font-size: 0.9em; color: #ccc; }
        .form-card { background: rgba(255,255,255,0.05); padding: 30px; border-radius: 10px; backdrop-filter: blur(10px); margin-bottom: 30px; }
        h2 { color: #81d4fa; margin-bottom: 20px; font-size: 1.5em; border-bottom: 2px solid #81d4fa; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-size: 0.9em; margin-bottom: 5px; color: #ccc; }
        input, select, textarea { width: 100%; padding: 12px; border: none; border-radius: 5px; background: rgba(255,255,255,0.1); color: #fff; font-size: 1em; }
        button { padding: 12px 20px; border: none; border-radius: 5px; background: #4CAF50; color: #fff; font-size: 1em; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #45a049; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1); }
        th { color: #81d4fa; }
        .action-btn { padding: 5px 10px; border-radius: 5px; border: none; cursor: pointer; font-size: 0.9em; margin-right: 5px; }
        .btn-delete { background: #ff6b6b; color: #fff; }
        .btn-edit { background: #ffc107; color: #000; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🛠️ Admin Dashboard</h1>
            <p style="font-size: 1.3em; margin-bottom: 20px;">Finanzberichte & Fleet Management</p>
            <?php if ($user !== 'admin'): ?>
                <a href="../dashboard.php" class="back-link">← Zurück zum Dashboard</a>
            <?php endif; ?>
        </header>

        <section class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-value"><?php echo $stats['total_pilots']; ?></div>
                <div class="stat-label">Gesamt Piloten</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-value"><?php echo $stats['active_schedules']; ?></div>
                <div class="stat-label">Aktive Flugpläne</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">✈️</div>
                <div class="stat-value"><?php echo $stats['completed_flights']; ?></div>
                <div class="stat-label">Abgeschlossene Flüge</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">✈️</div>
                <div class="stat-value"><?php echo $stats['total_aircrafts']; ?></div>
                <div class="stat-label">Gesamt Aircrafts</div>
            </div>
        </section>

        <?php if ($user === 'admin'): ?>
        <section class="form-card">
            <h2>👥 User Management</h2>
            
            <table>
                <tr><th>ID</th><th>Name</th><th>Callsign</th><th>Email</th><th>Rolle</th><th>Aktionen</th></tr>
                <?php foreach ($all_users as $user_item): ?>
                    <tr>
                        <td><?php echo $user_item['id']; ?></td>
                        <td><?php echo htmlspecialchars($user_item['name']); ?></td>
                        <td><?php echo htmlspecialchars($user_item['callsign']); ?></td>
                        <td><?php echo htmlspecialchars($user_item['email']); ?></td>
                        <td><?php echo $user_item['role']; ?></td>
                        <td>
                            <button class="action-btn btn-edit">Bearbeiten</button>
                            <button class="action-btn btn-delete">Löschen</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </section>

        <section class="form-card">
            <h2>✈️ Fleet Management</h2>
            
            <div style="margin-bottom: 20px;">
                <h3>Aircraft Übersicht</h3>
                <?php
                $aircrafts = $pdo->query("SELECT * FROM aircrafts ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($aircrafts as $aircraft):
                ?>
                    <div style="background: rgba(255,255,255,0.08); padding: 15px; border-radius: 8px; margin-top: 10px;">
                        <strong><?php echo htmlspecialchars($aircraft['registration']); ?></strong> - <?php echo htmlspecialchars($aircraft['model_type']); ?>
                        <p style="font-size: 0.9em;"><strong>Status:</strong> <?php echo $aircraft['maintenance_status']; ?></p>
                        <p style="font-size: 0.9em;"><strong>Nächste Wartung:</strong> <?php echo date('d.m.Y', strtotime($aircraft['next_maintenance_due'])); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="form-card">
            <h2>📊 Finanzberichte</h2>
            
            <div style="background: rgba(255,255,255,0.08); padding: 20px; border-radius: 8px;">
                <h3>Einnahmen vs. Kosten (Simulation)</h3>
                <p><strong>Gesamteinnahmen:</strong> €<?php echo rand(100000, 500000); ?></p>
                <p><strong>Gesamtkosten:</strong> €<?php echo rand(80000, 400000); ?></p>
                <p><strong>Profit:</strong> €<?php echo rand(20000, 100000); ?></p>
            </div>
        </section>
        <?php endif; ?>

        <section class="form-card">
            <h2>⚙️ System Einstellungen</h2>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="app_name">App Name</label>
                    <input type="text" name="app_name" id="app_name" value="runwayhub" readonly>
                </div>
                
                <div class="form-group">
                    <label for="maintenance_interval">Wartungsintervall (Tage)</label>
                    <input type="number" name="maintenance_interval" id="maintenance_interval" value="30">
                </div>
                
                <button type="submit">Einstellungen speichern</button>
            </form>
        </section>
    </div>
</body>
</html>
