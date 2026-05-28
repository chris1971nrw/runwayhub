<?php

declare(strict_types=1);

/**
 * RunwayHub - Benutzerfreundliche Installation
 * 
 * Schritt-für-Schritt Installation mit Status-Anzeige
 */

// Schritt 1: Check
$step = 1;
$error = null;
$success = null;
$message = null;

// Schritt 1: Check
if ($step === 1) {
    // Check
    $phpVersion = phpversion();
    $sqliteVersion = dbversion();
    
    if ($phpVersion < '8.3.6') {
        $error = "❌ PHP-Version: {$phpVersion} (benötigt: 8.3.6+)";
    } elseif ($sqliteVersion < '3.39') {
        $error = "❌ SQLite-Version: {$sqliteVersion} (benötigt: 3.39+)";
    } else {
        $success = "✅ System-Check erfolgreich!";
        $message = "PHP: {$phpVersion}, SQLite: {$sqliteVersion}";
    }
}

// Schritt 2: Datenbank
if ($step === 2) {
    if (file_exists(DATABASE_PATH)) {
        $success = "✅ Datenbank bereits existiert!";
        $message = "Datenbank: " . basename(DATABASE_PATH);
    } else {
        $error = "⚠️ Datenbank nicht gefunden. Erstellung erforderlich!";
        $message = "Wird erstellt...";
        
        // Erstellen
        if (mkdir(DATABASE_DIR, 0755, true) && touch(DATABASE_PATH)) {
            $success = "✅ Datenbank erstellt!";
            $message = "Datenbank: " . basename(DATABASE_PATH);
        } else {
            $error = "❌ Datenbank-Erstellung fehlgeschlagen!";
        }
    }
}

// Schritt 3: Initialisierung
if ($step === 3) {
    $error = null;
    $success = null;
    $message = null;
    
    if (file_exists(DATABASE_PATH)) {
        require_once __DIR__ . '/database/init.php';
        require_once __DIR__ . '/database/init-admin.php';
        
        try {
            // Init
            $db = new \RunwayHub\Core\Database(DATABASE_PATH);
            $db->connect();
            $db->createTables();
            
            // Admin
            require_once __DIR__ . '/database/init-admin.php';
            $adminDb = new \RunwayHub\Core\Database(DATABASE_PATH);
            $adminDb->connect();
            $adminDb->createAdminUser();
            
            $success = "✅ Datenbank initialisiert!";
            $message = "Tabellen: flights, aircrafts, pilots, bookings, etc.";
        } catch (\Exception $e) {
            $error = "❌ Initialisierung fehlgeschlagen: " . $e->getMessage();
        }
    }
}

// Schritt 4: Konfiguration
if ($step === 4) {
    $error = null;
    $success = null;
    $message = null;
    
    // .env kopieren
    if (file_exists(DOTENV_PATH)) {
        if (!file_exists(DOTENV_REAL_PATH)) {
            copy(DOTENV_PATH, DOTENV_REAL_PATH);
            $success = "✅ .env Datei kopiert!";
            $message = "Umgebungsvariablen in .env kopiert.";
        } else {
            $success = "✅ .env Datei existiert!";
            $message = "Umgebungsvariablen bereits vorhanden.";
        }
    } else {
        $success = "✅ Umgebungsvariablen bereit!";
        $message = "Bitte .env.example konfigurieren.";
    }
}

// Schritt 5: Fertig
if ($step === 5) {
    $success = "✅ Installation abgeschlossen!";
    $message = "Zur Hauptseite: <a href='dashboard.php'>Dashboard</a>";
}

// Funktionen
function dbversion(): string
{
    global $DB_PATH;
    
    if (!file_exists($DB_PATH)) {
        return '0.0';
    }
    
    try {
        require_once $DB_PATH . '.sqlite3';
        $version = sqlite_version();
        return $version ?: '0.0';
    } catch (\Exception $e) {
        return '0.0';
    }
}

// Konstanten
define('DATABASE_PATH', __DIR__ . '/database.sqlite');
define('DATABASE_DIR', dirname(DATABASE_PATH));
define('DOTENV_PATH', __DIR__ . '.env.example');
define('DOTENV_REAL_PATH', __DIR__ . '.env');

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RunwayHub - Installation</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .header { background: #1a73e8; color: white; padding: 20px; text-align: center; }
        .container { max-width: 800px; margin: 0 auto; }
        .step { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .step h3 { color: #333; margin-bottom: 15px; }
        .status { padding: 10px; border-radius: 4px; margin-bottom: 10px; }
        .status.success { background: #e6f4ea; color: #1e8e3e; }
        .status.error { background: #fce8e6; color: #c5221f; }
        .status.info { background: #e8f0fe; color: #1a73e8; }
        .steps { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .step-icon { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; }
        .step-icon.completed { background: #1e8e3e; }
        .step-icon.current { background: #1a73e8; }
        .step-icon.pending { background: #5f6368; }
        .btn { display: inline-block; padding: 10px 20px; background: #1a73e8; color: white; text-decoration: none; border-radius: 4px; cursor: pointer; border: none; font-size: 14px; }
        .btn:hover { background: #1557b0; }
        .btn:disabled { background: #ccc; cursor: not-allowed; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🛫 RunwayHub - Installation</h1>
        <p>Benutzerfreundliche Installation mit Schritt-für-Schritt-Wizard</p>
    </div>

    <div class="container">
        <div class="steps">
            <div class="step-icon completed">1</div>
            <div class="step-icon <?php echo $step >= 2 ? 'current' : 'pending'; ?>">2</div>
            <div class="step-icon <?php echo $step >= 3 ? 'completed' : 'pending'; ?>">3</div>
            <div class="step-icon <?php echo $step >= 4 ? 'completed' : 'pending'; ?>">4</div>
            <div class="step-icon <?php echo $step >= 5 ? 'completed' : 'pending'; ?>">5</div>
        </div>

        <?php foreach ([1, 2, 3, 4, 5] as $i): ?>
            <div class="step">
                <h3>
                    <span style="color: #1a73e8; font-size: 1.5em; margin-right: 10px;">
                        <?php 
                        switch ($i) {
                            case 1: echo '🔍'; break;
                            case 2: echo '💾'; break;
                            case 3: echo '⚙️'; break;
                            case 4: echo '🌐'; break;
                            case 5: echo '🎉'; break;
                        }
                        ?>
                    </span>
                    Schritt <?php echo $i; ?>
                </h3>

                <?php if ($i === 1): ?>
                    <div class="status <?php echo $error ? 'error' : 'success'; ?>">
                        <?php echo $success ?: $error ?: $message; ?>
                    </div>
                    
                    <?php if ($error === null): ?>
                        <div class="status info">
                            <?php echo $message; ?>
                        </div>
                        
                        <div style="margin-top: 15px;">
                            <a href="?step=2" class="btn">Weiter →</a>
                        </div>
                    <?php endif; ?>
                    
                <?php elseif ($i === 2): ?>
                    <div class="status <?php echo $error ? 'error' : 'success'; ?>">
                        <?php echo $success ?: $error ?: $message; ?>
                    </div>
                    
                    <?php if ($error === null): ?>
                        <div style="margin-top: 15px;">
                            <a href="?step=3" class="btn">Weiter →</a>
                        </div>
                    <?php endif; ?>
                    
                <?php elseif ($i === 3): ?>
                    <div class="status <?php echo $error ? 'error' : 'success'; ?>">
                        <?php echo $success ?: $error ?: $message; ?>
                    </div>
                    
                    <?php if ($error === null): ?>
                        <div style="margin-top: 15px;">
                            <a href="?step=4" class="btn">Weiter →</a>
                        </div>
                    <?php endif; ?>
                    
                <?php elseif ($i === 4): ?>
                    <div class="status <?php echo $error ? 'error' : 'success'; ?>">
                        <?php echo $success ?: $error ?: $message; ?>
                    </div>
                    
                    <?php if ($error === null): ?>
                        <div style="margin-top: 15px;">
                            <a href="?step=5" class="btn">Weiter →</a>
                        </div>
                    <?php endif; ?>
                    
                <?php elseif ($i === 5): ?>
                    <div class="status <?php echo $error ? 'error' : 'success'; ?>">
                        <?php echo $success ?: $error ?: $message; ?>
                    </div>
                    
                    <div style="margin-top: 15px;">
                        <a href="dashboard.php" class="btn">Zur Dashboard 🚀</a>
                        <a href="index.php" class="btn btn-secondary">Zur Hauptseite</a>
                    </div>
                    
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <div style="margin-top: 20px; padding: 15px; background: #e8f0fe; border-radius: 8px;">
            <strong>ℹ️ Informationen:</strong>
            <ul style="margin-left: 20px; margin-top: 10px;">
                <li><strong>PHP:</strong> <?php echo phpversion(); ?> (benötigt: 8.3.6+)</li>
                <li><strong>SQLite:</strong> <?php echo file_exists(DATABASE_PATH) ? 'erstellt' : 'noch nicht erstellt'; ?></li>
                <li><strong>Umgebungsvariablen:</strong> <?php echo file_exists(DOTENV_REAL_PATH) ? 'vorhanden' : 'notwendig'; ?></li>
            </ul>
        </div>

        <div style="margin-top: 20px; padding: 15px; background: #fce8e6; border-radius: 8px;">
            <strong>⚠️ Admin-Account:</strong>
            <ul style="margin-left: 20px; margin-top: 10px;">
                <li><strong>Benutzer:</strong> admin</li>
                <li><strong>Passwort:</strong> admin123</li>
                <li><strong>⚠️ Wichtig:</strong> Passwort nach dem ersten Login ändern!</li>
            </ul>
        </div>

    </div>
</body>
</html>
