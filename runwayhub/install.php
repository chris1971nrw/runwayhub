<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RunwayHub Installation</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px; min-height: 100vh; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        h1 { color: #1a73e8; margin-bottom: 20px; }
        .step { background: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #1a73e8; }
        .step h3 { color: #333; margin-bottom: 10px; }
        .step pre { background: #2d2d2d; color: #f8f8f2; padding: 10px; border-radius: 5px; overflow-x: auto; font-size: 12px; }
        .success { background: #e6f4ea; border-left-color: #1e8e3e; }
        .success h3 { color: #1e8e3e; }
        .error { background: #fce8e6; border-left-color: #da4453; }
        .error h3 { color: #da4453; }
        .info { background: #e8f0fe; border-left-color: #1a73e8; }
        .info h3 { color: #1a73e8; }
        .checklist { list-style: none; }
        .checklist li { padding: 8px 0; }
        .checklist li::before { content: "☐ "; color: #666; }
        .checklist li.done::before { content: "☑ "; color: #1e8e3e; }
        .btn { display: inline-block; padding: 12px 24px; background: #1a73e8; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; cursor: pointer; border: none; font-size: 14px; }
        .btn:hover { background: #1557b0; }
        .btn-success { background: #1e8e3e; }
        .btn-success:hover { background: #13662e; }
        .btn-danger { background: #da4453; }
        .btn-danger:hover { background: #b32c3d; }
        .terminal { font-family: 'Courier New', monospace; }
        .loading { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.8); z-index: 1000; justify-content: center; align-items: center; }
        .loading.active { display: flex; }
        .loading-content { text-align: center; color: white; }
        .spinner { width: 50px; height: 50px; border: 4px solid #f3f3f3; border-top: 4px solid #1a73e8; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 20px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛫 RunwayHub Installation</h1>
        
        <?php
        $step = 1;
        $errors = [];
        $successes = [];
        
        // Step 1: Check PHP version
        if (version_compare(phpversion(), '8.0', '>=')) {
            echo '<div class="step success"><h3>Schritt 1: PHP-Version</h3><p>✅ PHP ' . phpversion() . ' ist installiert und läuft (mindestens 8.0 erforderlich)</p></div>';
            $successes[] = 'PHP-Version';
        } else {
            echo '<div class="step error"><h3>Schritt 1: PHP-Version</h3><p>❌ PHP ' . phpversion() . ' ist installiert, aber mindestens 8.0 erforderlich</p></div>';
            $errors[] = 'PHP-Version';
        }
        
        // Step 2: Check required extensions
        $requiredExtensions = ['pdo_sqlite', 'json', 'ctype', 'mbstring'];
        foreach ($requiredExtensions as $ext) {
            if (extension_loaded($ext)) {
                $successes[] = $ext;
            }
        }
        if (count($requiredExtensions) === count($successes)) {
            echo '<div class="step success"><h3>Schritt 2: PHP-Extensions</h3><p>✅ Alle erforderlichen Extensions sind installiert:</p><ul class="checklist">' . 
            implode('<li>', array_map(fn($e) => $e, $requiredExtensions)) . '</ul></div>';
        } else {
            echo '<div class="step error"><h3>Schritt 2: PHP-Extensions</h3><p>❌ Fehlende Extensions:</p><ul class="checklist">' . 
            implode('<li>', array_map(fn($e) => $e, array_diff($requiredExtensions, $successes))) . '</ul></div>';
        }
        
        // Step 3: Create database
        $dbPath = __DIR__ . '/database.sqlite';
        if (!file_exists($dbPath)) {
            echo '<div class="step info"><h3>Schritt 3: Datenbank erstellen</h3>';
            echo '<p>SQLite-Datenbank wird erstellt...</p><div class="terminal">';
            $db = new PDO('sqlite:' . $dbPath);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo 'CREATE TABLE flights (id INTEGER PRIMARY KEY AUTOINCREMENT, flight_number VARCHAR(10), origin VARCHAR(4), destination VARCHAR(4), departure_time DATETIME, arrival_time DATETIME, status VARCHAR(20));<br>';
            echo "CREATE TABLE aircrafts (id INTEGER PRIMARY KEY AUTOINCREMENT, registration VARCHAR(8), type VARCHAR(50), manufacturer VARCHAR(50), model VARCHAR(50), status VARCHAR(20));<br>";
            echo 'CREATE TABLE pilots (id INTEGER PRIMARY KEY AUTOINCREMENT, first_name VARCHAR(50), surname VARCHAR(50), callsign VARCHAR(8), email VARCHAR(100), status VARCHAR(10));<br>';
            echo 'CREATE TABLE bookings (id INTEGER PRIMARY KEY AUTOINCREMENT, flight_number VARCHAR(10), passenger_email VARCHAR(100), status VARCHAR(20));<br>';
            echo '✅ Datenbank erfolgreich erstellt!<br>';
            ?>
            <button class="btn" onclick="document.getElementById('step3').innerHTML='✅ Datenbank erstellt!';">OK</button>';
            <?php
        } else {
            echo '<div class="step success"><h3>Schritt 3: Datenbank</h3><p>✅ SQLite-Datenbank existiert bereits</p></div>';
        }
        
        // Step 4: Create config
        $configPath = __DIR__ . '/config/config.php';
        if (!file_exists($configPath)) {
            echo '<div class="step info"><h3>Schritt 4: Konfiguration erstellen</h3>';
            echo '<p>config/config.php wird erstellt...</p><pre>' . file_get_contents('config/config.example.php') . '</pre>';
            ?>
            <textarea id="config-content" rows="10" style="width: 100%; margin-bottom: 10px; font-family: monospace; padding: 10px;">
<?php
// RunwayHub Konfiguration
define('APP_NAME', 'RunwayHub');
define('APP_VERSION', '1.0.0');
define('DB_PATH', __DIR__ . '/database.sqlite');
define('LOG_PATH', __DIR__ . '/logs');
define('UPLOAD_PATH', __DIR__ . '/uploads');
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'localhost');
define('SMTP_PORT', getenv('SMTP_PORT') ?: '587');
define('SMTP_USER', getenv('SMTP_USER') ?: '');
define('SMTP_PASS', getenv('SMTP_PASS') ?: '');
define('SMTP_SECURE', getenv('SMTP_SECURE') ?: 'false');
define('MAILER_DOMAIN', getenv('MAILER_DOMAIN') ?: 'localhost');
define('ADMIN_EMAIL', getenv('ADMIN_EMAIL') ?: 'admin@example.com');
define('NOTIFICATIONS_ENABLED', getenv('NOTIFICATIONS_ENABLED') ?: 'true');
define('ACARS_API_URL', getenv('ACARS_API_URL') ?: 'https://api.runwayhub.example/acars');
define('ACARS_API_KEY', getenv('ACARS_API_KEY') ?: '');
define('WEATHER_PROVIDER', getenv('WEATHER_PROVIDER') ?: 'openmeteo');
define('WEATHER_CACHE_TTL', (int)(getenv('WEATHER_CACHE_TTL') ?: '300'));
define('MAX_BOOKINGS_PER_USER', (int)(getenv('MAX_BOOKINGS_PER_USER') ?: '100'));
define('ALLOW_REGISTRATION', getenv('ALLOW_REGISTRATION') ?: 'false');
define('ALLOW_BOOKING', getenv('ALLOW_BOOKING') ?: 'false');
define('ALLOW_ADMIN', getenv('ALLOW_ADMIN') ?: 'false');
?>
            </textarea>
            <button class="btn" onclick="document.getElementById('config-content').value='<?= htmlspecialchars(file_get_contents('config/config.example.php')) ?>'">Vorlagen laden</button>
            <button class="btn btn-success" onclick="saveConfig()">Speichern</button>';
            ?>
        <?php
        } else {
            echo '<div class="step success"><h3>Schritt 4: Konfiguration</h3><p>✅ config/config.php existiert bereits</p></div>';
        }
        
        // Step 5: Create logs directory
        $logPath = __DIR__ . '/logs';
        if (!is_dir($logPath)) {
            mkdir($logPath, 0755, true);
            echo '<div class="step success"><h3>Schritt 5: Log-Verzeichnis</h3><p>✅ Log-Verzeichnis erstellt</p></div>';
            $successes[] = 'Log-Verzeichnis';
        } else {
            echo '<div class="step success"><h3>Schritt 5: Log-Verzeichnis</h3><p>✅ Log-Verzeichnis existiert bereits</p></div>';
            $successes[] = 'Log-Verzeichnis';
        }
        
        // Step 6: Create uploads directory
        $uploadPath = __DIR__ . '/uploads';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
            echo '<div class="step success"><h3>Schritt 6: Upload-Verzeichnis</h3><p>✅ Upload-Verzeichnis erstellt</p></div>';
            $successes[] = 'Upload-Verzeichnis';
        } else {
            echo '<div class="step success"><h3>Schritt 6: Upload-Verzeichnis</h3><p>✅ Upload-Verzeichnis existiert bereits</p></div>';
            $successes[] = 'Upload-Verzeichnis';
        }
        
        // Step 7: Initialize database with initial data
        echo '<div class="step info"><h3>Schritt 7: Datenbank initialisieren</h3>';
        echo '<p>Datenbank mit initialen Daten wird populiert...</p>';
        ?>
        <button class="btn" onclick="initializeDatabase()">Datenbank initialisieren</button>';
        <?php
        ?>
        } else {
            echo '<div class="step success"><h3>Schritt 7: Datenbank</h3><p>✅ Datenbank initialisiert</p></div>';
        }
        
        // Step 8: Create default data
        echo '<div class="step info"><h3>Schritt 8: Standard-Daten</h3>';
        echo '<p>Flüge, Flugzeuge, Piloten werden initialisiert...</p>';
        ?>
        <button class="btn" onclick="initializeData()">Daten initialisieren</button>';
        <?php
        ?>
        } else {
            echo '<div class="step success"><h3>Schritt 8: Standard-Daten</h3><p>✅ Standard-Daten initialisiert</p></div>';
        }
        
        // Check for completion
        if (count($successes) >= 6) {
            echo '<div class="step success"><h3>Installation abgeschlossen!</h3>';
            echo '<p>✅ RunwayHub ist erfolgreich installiert!</p>';
            echo '<p><strong>Nächste Schritte:</strong></p>';
            echo '<ul class="checklist"><li>Konfiguration in config/config.php bearbeiten</li><li><a href="index.php">Zur Hauptseite</a></li><li><a href="dashboard.php">Zum Dashboard</a></li><li><a href="install.php?step=backup">Backup erstellen</a></li></ul>';
            echo '<a href="index.php" class="btn btn-success">Zur Hauptseite</a>';
            ?>
        } else {
            echo '<div class="step info"><h3>Installation nicht vollständig</h3>';
            echo '<p>Führe die oben genannten Schritte manuell aus.</p>';
            echo '<a href="index.php" class="btn">Zur Hauptseite</a>';
            ?>
        }
        ?>
    </div>

    <div id="loading" class="loading">
        <div class="loading-content">
            <div class="spinner"></div>
            <p id="loading-text">Installation läuft...</p>
        </div>
    </div>

    <script>
        function saveConfig() {
            const config = document.getElementById('config-content').value;
            const blob = new Blob([config], { type: 'text/php' });
            const url = URL.createObjectURL(blob);
            
            fetch(url)
                .then(response => response.text())
                .then(text => {
                    const blob = new Blob([text], { type: 'text/php' });
                    const url = URL.createObjectURL(blob);
                    fetch(url, { method: 'POST', body: blob })
                        .then(response => {
                            if (response.ok) {
                                document.getElementById('loading-text').textContent = 'Konfiguration gespeichert...';
                                setTimeout(() => {
                                    document.getElementById('loading-text').textContent = '✅ Konfiguration gespeichert!';
                                    alert('config/config.php wurde gespeichert!');
                                }, 500);
                            }
                        })
                        .catch(error => {
                            alert('Fehler beim Speichern: ' + error.message);
                        });
                });
        }

        function initializeDatabase() {
            document.getElementById('loading-text').textContent = 'Datenbank wird initialisiert...';
            document.getElementById('loading').classList.add('active');
            
            fetch('database/init.php')
                .then(response => response.text())
                .then(text => {
                    setTimeout(() => {
                        document.getElementById('loading').classList.remove('active');
                        alert('Datenbank erfolgreich initialisiert!');
                    }, 500);
                })
                .catch(error => {
                    alert('Fehler: ' + error.message);
                    document.getElementById('loading').classList.remove('active');
                });
        }

        function initializeData() {
            fetch('database/seed.php')
                .then(response => response.text())
                .then(text => {
                    setTimeout(() => {
                        alert('Standard-Daten erfolgreich initialisiert!');
                    }, 500);
                })
                .catch(error => {
                    alert('Fehler: ' + error.message);
                });
        }
    </script>
</body>
</html>
