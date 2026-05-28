<?php

declare(strict_types=1);

/**
 * VA Admin System
 * 
 * Admin Page zum Verwalten von Virtual Airlines
 */

// Session-Check
session_start();
if (!isset($_SESSION['user']['id'])) {
    header('Location: /login.php');
    exit;
}

$user = $_SESSION['user'];
$airlines = [];
$groups = [];

// Get VA list (if logged in as admin)
if ($user['role'] === 'admin') {
    try {
        $dbPath = '/home/christoph/.openclaw/workspace-runwayhub/runwayhub/database.sqlite';
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Get VA list
        $stmt = $pdo->query('SELECT * FROM va ORDER BY created_at DESC');
        $airlines = $stmt->fetchAll();
        
        // Get groups
        $stmt = $pdo->query('SELECT * FROM groups ORDER BY name ASC');
        $groups = $stmt->fetchAll();
        
        $pdo = null;
    } catch (Exception $e) {
        // No database, show placeholder
        $airlines = [];
        $groups = [];
    }
}

// Handle form submissions
$vaName = $_POST['vaName'] ?? '';
$airlineName = $_POST['airlineName'] ?? '';
$airlineUrl = $_POST['airlineUrl'] ?? '';
$logo = $_POST['logo'] ?? '';
$primaryColor = $_POST['primaryColor'] ?? '#000000';
$secondaryColor = $_POST['secondaryColor'] ?? '#ffffff';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save VA data (placeholder - will use API)
    $vaName = $airlineName . ' Virtual';
    $logo = 'https://via.placeholder.com/300x100.png?text=' . urlencode($airlineName);
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VA Verwalten - RunwayHub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Segoe UI", Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; color: white; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; padding: 20px 40px; background: rgba(255,255,255,0.1); border-radius: 10px; margin-bottom: 20px; }
        .user-info { font-size: 1.2em; }
        .nav a { display: inline-block; padding: 10px 20px; background: rgba(255,255,255,0.2); color: white; text-decoration: none; border-radius: 5px; margin-left: 10px; transition: background 0.3s; }
        .nav a:hover { background: rgba(255,255,255,0.3); }
        .logout { background: #ff6b6b; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { font-size: 2em; margin-bottom: 30px; color: white; }
        .card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card { background: rgba(255,255,255,0.95); padding: 30px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.2); }
        .card h3 { font-size: 1.5em; margin-bottom: 15px; color: #764ba2; }
        .card p { color: #555; margin-bottom: 10px; }
        .btn { padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; display: inline-block; cursor: pointer; transition: transform 0.3s; border: none; }
        .btn:hover { transform: translateY(-3px); }
        .btn-primary { background: #667eea; }
        .btn-secondary { background: #6bcb77; }
        .btn-danger { background: #ff6b6b; }
        .btn-info { background: #4facfe; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        input, select, textarea { width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 5px; font-size: 1em; transition: border-color 0.3s; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #667eea; }
        footer { text-align: center; padding: 20px; margin-top: 30px; color: #666; }
        .admin-badge { background: #ff6b6b; color: white; padding: 5px 15px; border-radius: 20px; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="header">
        <div class="user-info">
            <span class="admin-badge">Admin</span>
            <strong><?= htmlspecialchars($user['callsign']) ?></strong>
            <?= $user['role'] === 'admin' ? ' - Admin Mode' : '' ?>
        </div>
        <nav class="nav">
            <a href="/dashboard.php">Dashboard</a>
            <a href="/va-admin.php">VA Verwalten</a>
            <a href="/login.php" class="logout">Logout</a>
        </nav>
    </div>
    
    <div class="container">
        <h1>🛠️ VA Verwalten</h1>
        
        <?php if (empty($airlines)): ?>
            <div class="card" style="max-width: 800px; margin: 0 auto;">
                <h3>Neue Virtual Airline erstellen</h3>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="airlineName">Airline Name:</label>
                        <input type="text" id="airlineName" name="airlineName" 
                               placeholder="z.B. Deutsche Airline"
                               value="<?= htmlspecialchars($airlineName ?? '') ?>"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="airlineUrl">Airline Website:</label>
                        <input type="url" id="airlineUrl" name="airlineUrl" 
                               placeholder="https://www.deutsche-airline.de"
                               value="<?= htmlspecialchars($airlineUrl ?? '') ?>"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="logo">Logo URL:</label>
                        <input type="url" id="logo" name="logo" 
                               placeholder="https://example.com/logo.png"
                               value="<?= htmlspecialchars($logo ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Hauptfarbe:</label>
                        <input type="color" id="primaryColor" name="primaryColor" value="<?= htmlspecialchars($primaryColor) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Zusatzfarbe:</label>
                        <input type="color" id="secondaryColor" name="secondaryColor" value="<?= htmlspecialchars($secondaryColor) ?>">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">VA Erstellen</button>
                </form>
            </div>
        <?php else: ?>
            <div class="card-grid">
                <?php foreach ($airlines as $airline): ?>
                    <div class="card">
                        <h3><?= htmlspecialchars($airline['name'] ?? '') ?></h3>
                        <p><strong>Airline:</strong> <?= htmlspecialchars($airline['airline_name'] ?? '') ?></p>
                        <p><strong>Website:</strong> <a href="<?= htmlspecialchars($airline['website'] ?? '') ?>" target="_blank"><?= htmlspecialchars($airline['website'] ?? 'N/A') ?></a></p>
                        <p><strong>Status:</strong> <?= htmlspecialchars($airline['status'] ?? 'Active') ?></p>
                        <p><strong>Gegründet:</strong> <?= htmlspecialchars($airline['created_at'] ?? 'N/A') ?></p>
                        <p><strong>Owner:</strong> <?= htmlspecialchars($airline['owner'] ?? 'N/A') ?></p>
                        <hr>
                        <a href="/va-connect.php" class="btn btn-info">Verbinden</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($groups)): ?>
            <h2>👥 Piloten-Gruppen</h2>
            <div class="card-grid">
                <?php foreach ($groups as $group): ?>
                    <div class="card">
                        <h3><?= htmlspecialchars($group['name']) ?></h3>
                        <p><strong>Airline:</strong> <?= htmlspecialchars($group['airline']) ?></p>
                        <p><strong>Piloten:</strong> <?= htmlspecialchars($group['members'] ?? 0) ?></p>
                        <p><strong>Berechtigungen:</strong> <?= htmlspecialchars($group['permissions'] ?? 'Standard') ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <footer>
            <p><a href="/">← Zurück zur Startseite</a></p>
            <p>Built with ❤️ by @chris1971nrw | Powered by OpenAIP API</p>
        </footer>
    </div>
</body>
</html>
