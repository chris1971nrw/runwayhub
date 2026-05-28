<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RunwayHub - Fluggesellschaft Management</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .header { background: #1a73e8; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1200px; margin: 0 auto; }
        .dashboard { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card h3 { color: #333; margin-bottom: 10px; }
        .card .value { font-size: 2em; font-weight: bold; color: #1a73e8; }
        .card .label { color: #666; margin-bottom: 5px; }
        .flights-list { list-style: none; }
        .flights-list li { padding: 10px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .flights-list li:hover { background: #f9f9f9; }
        .status { padding: 4px 8px; border-radius: 4px; font-size: 0.8em; }
        .status.scheduled { background: #e8f0fe; color: #1a73e8; }
        .status.active { background: #e6f4ea; color: #1e8e3e; }
        .status.boarding { background: #fce8e6; color: #c5221f; }
        .status.in-flight { background: #e8f0fe; color: #1a73e8; }
        .status.landed { background: #e6f4ea; color: #1e8e3e; }
        .status.delayed { background: #fce8e6; color: #c5221f; }
        .actions { margin-top: 20px; padding: 20px; text-align: center; }
        .btn { display: inline-block; padding: 10px 20px; background: #1a73e8; color: white; text-decoration: none; border-radius: 4px; margin: 5px; cursor: pointer; border: none; font-size: 14px; }
        .btn:hover { background: #1557b0; }
        .btn-secondary { background: #5f6368; }
        .btn-danger { background: #da4453; }
        .btn-success { background: #1e8e3e; }
        .notification { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .notification h4 { margin-bottom: 5px; }
        .notification .version { font-size: 1.5em; font-weight: bold; margin-bottom: 10px; }
        .notification .timestamp { font-size: 0.8em; opacity: 0.9; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🛫 RunwayHub - Fluggesellschaft Management</h1>
        <p>Verwalten Sie Ihre Flotte, Flüge und Buchungen</p>
    </div>

    <div class="container">
        <!-- Update Notification (nur Admin) -->
        <?php if (isset($update['isAdmin']) && $update['isAdmin'] && isset($update['updateAvailable']) && $update['updateAvailable']): ?>
        <?php 
            $latestVersion = $update['updateInfo']['latest_version'] ?? 'unknown';
        ?>
        <div class="notification">
            <h4>📦 Version-Update verfügbar</h4>
            <div class="version">Aktuell: APP_VERSION → Neu: <?php echo $latestVersion; ?></div>
            <p>Ein Update ist verfügbar. Klicken Sie auf "Jetzt Update durchführen", um das System zu aktualisieren.</p>
            <div style="margin-top: 10px;">
                <a href="update-release.php" class="btn">Jetzt Update durchführen</a>
                <a href="dashboard.php?clear_cache=1" class="btn btn-success">Cache leeren</a>
            </div>
            <div class="timestamp">Letzter Check: <?php echo $update['updateInfo']['last_check'] ?? 'unknown'; ?></div>
        </div>
        <?php endif; ?>

        <div class="dashboard">
            <div class="card">
                <h3>📊 Aktive Flüge</h3>
                <div class="value"><?php echo isset($stats['flights']['total']) ? $stats['flights']['total'] : 0; ?></div>
                <div class="label">Geplante Flüge heute</div>
            </div>

            <div class="card">
                <h3>✈️ Flotte</h3>
                <div class="value"><?php echo isset($stats['fleet']['total']) ? $stats['fleet']['total'] : 0; ?></div>
                <div class="label">Flugzeuge</div>
            </div>

            <div class="card">
                <h3>👨‍✈️ Piloten</h3>
                <div class="value"><?php echo isset($stats['pilots']['total']) ? $stats['pilots']['total'] : 0; ?></div>
                <div class="label">Aktive Piloten</div>
            </div>

            <div class="card">
                <h3>🎫 Buchungen</h3>
                <div class="value"><?php echo isset($stats['bookings']['today']) ? $stats['bookings']['today'] : 0; ?></div>
                <div class="label">Aktuelle Buchungen</div>
            </div>
        </div>

        <div class="card" style="margin-top: 20px;">
            <h3>🛫 Aktive Flüge</h3>
            <ul class="flights-list">
                <?php
                $flights = [
                    ['flight_number' => 'LH456', 'origin' => 'FRA', 'destination' => 'JFK', 'departure_time' => '14:30', 'status' => 'scheduled', 'aircraft' => 'D-AIMA'],
                    ['flight_number' => 'LH458', 'origin' => 'FRA', 'destination' => 'JFK', 'departure_time' => '18:30', 'status' => 'scheduled', 'aircraft' => 'D-AIMA2'],
                    ['flight_number' => 'BA123', 'origin' => 'LHR', 'destination' => 'FRA', 'departure_time' => '12:00', 'status' => 'scheduled', 'aircraft' => 'D-AIME'],
                    ['flight_number' => 'AF054', 'origin' => 'CDG', 'destination' => 'FRA', 'departure_time' => '13:00', 'status' => 'scheduled', 'aircraft' => 'D-AIMI'],
                ];
                foreach ($flights as $flight):
                    $statusClass = 'scheduled';
                    ?>
                    <li>
                        <strong><?php echo $flight['flight_number']; ?></strong>
                        <span>
                            <?php echo $flight['origin']; ?> → <?php echo $flight['destination']; ?>
                        </span>
                        <span class="status <?php echo $statusClass; ?>">
                            <?php echo $flight['status']; ?>
                        </span>
                    </li>
                    <?php
                endforeach;
                ?>
            </ul>
        </div>

        <div class="card" style="margin-top: 20px;">
            <h3>✈️ Flotte</h3>
            <table>
                <tr>
                    <th>Registrierung</th>
                    <th>Typ</th>
                    <th>Hersteller</th>
                    <th>Modell</th>
                    <th>Kapazität</th>
                    <th>Status</th>
                </tr>
                <?php
                $aircrafts = [
                    ['D-AIMA', 'Boeing 737-800', 'Boeing', '737-800', 162, 'active'],
                    ['D-AIMA2', 'Boeing 737-800', 'Boeing', '737-800', 162, 'active'],
                    ['D-AIME', 'Airbus A320', 'Airbus', 'A320', 180, 'active'],
                    ['D-AIMI', 'Airbus A319', 'Airbus', 'A319', 140, 'active'],
                ];
                foreach ($aircrafts as $aircraft):
                    ?>
                    <tr>
                        <td><?php echo $aircraft[0]; ?></td>
                        <td><?php echo $aircraft[1]; ?></td>
                        <td><?php echo $aircraft[2]; ?></td>
                        <td><?php echo $aircraft[3]; ?></td>
                        <td><?php echo $aircraft[4]; ?></td>
                        <td><?php echo $aircraft[5]; ?></td>
                    </tr>
                    <?php
                endforeach;
                ?>
            </table>
        </div>

        <div class="actions">
            <a href="flights.php" class="btn">🛫 Flüge verwalten</a>
            <a href="aircrafts.php" class="btn">✈️ Flotte verwalten</a>
            <a href="pilots.php" class="btn">👨‍✈️ Piloten verwalten</a>
            <a href="bookings.php" class="btn">🎫 Buchungen verwalten</a>
            <a href="maintenance.php" class="btn btn-secondary">🔧 Wartung verwalten</a>
            <a href="settings.php" class="btn btn-danger">⚙️ Einstellungen</a>
        </div>
    </div>
</body>
</html>
