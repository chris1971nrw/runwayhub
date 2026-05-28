<?php

declare(strict_types=1);

/**
 * VA Admin Panel
 * 
 * Virtual Airline Management Interface
 * Pilot Management and Airline Configuration
 */

// Load environment
if (file_exists(__DIR__ . '/../runwayhub/.env')) {
    $env = parse_ini_file(__DIR__ . '/../runwayhub/.env');
    if (is_array($env)) {
        foreach ($env as $key => $value) {
            if (!getenv($key) && !defined($key)) {
                putenv("$key=$value");
            }
        }
    }
}

// Load main database schema
$db = new \SQLite3(__DIR__ . '/../runwayhub/database/runwayhub.sqlite');

// Enable prepared statements
$db->enableExceptions(true);

// Helper function to fetch all rows as arrays
function fetchAll($stmt) {
    $stmt->bindArray(['_' => 'array']);
    while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
        $row['_'] = null; // Remove bind parameter
        yield $row;
    }
}

// Get all airlines
$airlines = array_values($db->query('SELECT * FROM airlines ORDER BY name')->fetchAll());
// Get all VAs
$vAs = array_values($db->query('SELECT * FROM va ORDER BY name')->fetchAll());
// Get all pilot profiles
$profiles = array_values($db->query('SELECT p.*, a.name as airline_name FROM profiles p LEFT JOIN airlines a ON p.airline_id = a.id ORDER BY p.callsign')->fetchAll());

// Statistics
$stats = array_values($db->query('SELECT 
    SUM(value) as total_flights,
    COUNT(*) as total_pireps,
    SUM(CASE WHEN status = "open" THEN value ELSE 0 END) as open_maintenance,
    COUNT(DISTINCT callsign) as active_pilots
    FROM statistics
    WHERE metric = "flights"
')->fetchAll());

$page = $_GET['page'] ?? 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Pagination for profiles
$profilesTotal = count($profiles);
$profiles = array_slice($profiles, $offset, $perPage);
$pageInfo = [
    'current' => $page,
    'total' => ceil($profilesTotal / $perPage),
    'count' => $perPage
];

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VA Admin Panel - RunwayHub</title>
    <link rel="stylesheet" href="/assets/admin.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Segoe UI", Arial, sans-serif; background: #f5f5f5; color: #333; }
        header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; color: white; }
        .logo { font-size: 1.8em; font-weight: bold; }
        nav a { display: inline-block; padding: 10px 20px; background: rgba(255,255,255,0.2); color: white; text-decoration: none; border-radius: 5px; margin-left: 10px; }
        nav a:hover { background: rgba(255,255,255,0.3); }
        .container { max-width: 1400px; margin: 40px auto; padding: 0 20px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        .stat-card h3 { font-size: 2em; margin-bottom: 10px; color: #764ba2; }
        .stat-card p { color: #666; }
        .section { background: white; padding: 30px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .section h2 { margin-bottom: 20px; color: #333; border-bottom: 2px solid #764ba2; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f8f8; font-weight: 600; color: #555; }
        tr:hover { background: #f8f8f8; }
        .btn { display: inline-block; padding: 10px 20px; background: #764ba2; color: white; text-decoration: none; border-radius: 5px; border: none; cursor: pointer; }
        .btn:hover { background: #667eea; }
        .btn-secondary { background: #6c757d; }
        .btn-danger { background: #dc3545; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 0.85em; }
        .badge-active { background: #d4edda; color: #155724; }
        .badge-inactive { background: #f8d7da; color: #721c24; }
        .alert { padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .card { background: #f8f8f8; padding: 20px; border-radius: 10px; border-left: 4px solid #764ba2; }
        .card h3 { margin-bottom: 10px; }
        .card p { color: #666; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .pagination { display: flex; justify-content: center; gap: 10px; margin-top: 20px; }
        .pagination a { padding: 8px 15px; background: #764ba2; color: white; text-decoration: none; border-radius: 5px; }
        .pagination a:hover { background: #667eea; }
        .toolbar { display: flex; gap: 10px; margin-bottom: 20px; }
        .toolbar input { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        footer { background: #333; color: white; text-align: center; padding: 20px; margin-top: 60px; }
    </style>
</head>
<body>
    <header>
        <div class="logo">✈️ RunwayHub Admin</div>
        <nav>
            <a href="/">Zurück zur Hauptseite</a>
            <a href="/va-gruenden.php">VA Gründen</a>
            <a href="/va-connect.php">VA Anschließen</a>
            <a href="/weather-widget.html">Wetter Widget</a>
        </nav>
    </header>

    <div class="container">
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3><?= isset($stats[0]['total_flights']) ? $stats[0]['total_flights'] : 0 ?></h3>
                <p>Gesamte Flüge</p>
            </div>
            <div class="stat-card">
                <h3><?= isset($stats[0]['total_pireps']) ? $stats[0]['total_pireps'] : 0 ?></h3>
                <p>PIREPs (Wetterberichte)</p>
            </div>
            <div class="stat-card">
                <h3><?= isset($stats[0]['open_maintenance']) ? $stats[0]['open_maintenance'] : 0 ?></h3>
                <p>Offene Wartungen</p>
            </div>
            <div class="stat-card">
                <h3><?= isset($stats[0]['active_pilots']) ? $stats[0]['active_pilots'] : 0 ?></h3>
                <p>Aktive Piloten</p>
            </div>
        </div>

        <!-- Airlines Management -->
        <div class="section">
            <h2>🛫 Airlines Management</h2>
            <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
                <?php foreach ($airlines as $airline): ?>
                    <div class="card">
                        <h3><?= htmlspecialchars($airline['name']) ?></h3>
                        <p><strong>IATA:</strong> <?= htmlspecialchars($airline['iata']) ?></p>
                        <p><strong>ICAO:</strong> <?= htmlspecialchars($airline['icao']) ?></p>
                        <p><strong>Website:</strong> <a href="<?= htmlspecialchars($airline['website']) ?>" target="_blank"><?= htmlspecialchars($airline['website']) ?></a></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Virtual Airlines -->
        <div class="section">
            <h2>🚀 Virtual Airlines</h2>
            <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));">
                <?php foreach ($vAs as $va): ?>
                    <div class="card" style="border-left-color: #764ba2;">
                        <h3><?= htmlspecialchars($va['name']) ?></h3>
                        <p><strong>Logo:</strong> <img src="<?= htmlspecialchars($va['logo']) ?>" alt="VA Logo" style="max-width: 100px; border-radius: 5px;"> (falls vorhanden)</p>
                        <p><strong>Erstellt:</strong> <?= htmlspecialchars($va['created_at']) ?></p>
                        <p><strong>Website:</strong> <?= htmlspecialchars($va['airline_website']) ?></p>
                        <a href="/va-gruenden.php?va_id=<?= urlencode($va['id']) ?>" class="btn btn-secondary">Verwalten</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Pilot Management -->
        <div class="section">
            <h2>👥 Piloten Management</h2>
            <div class="toolbar">
                <input type="text" placeholder="Piloten nachsuchen..." id="searchInput" onkeyup="filterPilots()">
                <span style="color: #666;"><?= count($profiles) ?> von <?= $profilesTotal ?></span>
            </div>
            
            <table id="pilotsTable">
                <thead>
                    <tr>
                        <th>Callsign</th>
                        <th>Airline</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody id="pilotsBody">
                    <?php foreach ($profiles as $profile): ?>
                        <tr>
                            <td><?= htmlspecialchars($profile['callsign']) ?></td>
                            <td><?= htmlspecialchars($profile['airline_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($profile['email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($profile['display_name'] ?? $profile['callsign']) ?></td>
                            <td><span class="badge badge-active">aktiv</span></td>
                            <td>
                                <a href="/admin/pilot/edit.php?id=<?= urlencode($profile['id']) ?>" class="btn btn-secondary" style="padding: 5px 10px;">Bearbeiten</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="pagination">
                <?php for ($i = 1; $i <= $pageInfo['total']; $i++): ?>
                    <a href="?page=<?= $i ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Reports & Statistics Section -->
        <div class="section">
            <h2>📊 Reports & Statistiken</h2>
            <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
                <div class="card">
                    <h3>Flugstatistiken</h3>
                    <p>Detaillierte Berichte über alle Flüge</p>
                    <a href="/admin/reports/flights.php" class="btn">Ansehen</a>
                </div>
                <div class="card">
                    <h3>Wetterberichte</h3>
                    <p>Alle PIREPs und Wetterdaten</p>
                    <a href="/admin/reports/pireps.php" class="btn">Ansehen</a>
                </div>
                <div class="card">
                    <h3>Leaderboard</h3>
                    <p>Top Piloten nach Punkten</p>
                    <a href="/admin/reports/leaderboard.php" class="btn">Ansehen</a>
                </div>
                <div class="card">
                    <h3>Wartungsberichte</h3>
                    <p>Offene und geschlossene Maintenance</p>
                    <a href="/admin/reports/maintenance.php" class="btn">Ansehen</a>
                </div>
            </div>
        </div>

    </div>

    <footer>
        <p>&copy; <?= date('Y') ?> RunwayHub - Virtual Airline Management System</p>
        <p>Generated: <?= date('Y-m-d H:i:s', time()) ?></p>
    </footer>

    <script>
        // Pilot filter function
        function filterPilots() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const tbody = document.getElementById('pilotsBody');
            const tr = tbody.getElementsByTagName('tr');
            
            for (let i = 0; i < tr.length; i++) {
                const td = tr[i].getElementsByTagName('td')[0];
                if (td) {
                    const txtValue = td.textContent || td.innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
</body>
</html>
