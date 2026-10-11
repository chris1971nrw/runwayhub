<?php
/**
 * runwayhub - Haupt-Index Datei
 * 
 * Diese Datei leitet zur install.php oder dashboard.php weiter.
 */

// Session starten
session_start();

// Security: HTTP Only Cookies
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

// Datenbank Connection
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=runwayhub_db;charset=utf8mb4",
        getenv('DB_USER') ?: 'root',
        getenv('DB_PASS') ?: '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    // Datenbank noch nicht existiert - erstelle sie
    try {
        $pdo = new PDO("mysql:host=localhost;charset=utf8mb4", 'root', '');
    } catch (PDOException $e) {
        die('<h1>❌ Fehler</h1><p>Konnte keine Verbindung zur MySQL Datenbank herstellen.</p>');
    }
}

// Erstelle Datenbank (falls nicht existiert)
try {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS runwayhub_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
} catch (PDOException $e) {
    // Fehler ignorieren wenn DB existiert
}

// Login Form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT * FROM pilots WHERE email = ?");
        $stmt->execute([$email]);
        $pilot = $stmt->fetch();
        
        if ($pilot && password_verify($password, $pilot['password'])) {
            $_SESSION['user_id'] = $pilot['id'];
            $_SESSION['name'] = $pilot['name'];
            $_SESSION['role'] = $pilot['role'];
            
            // Umleiten zur Dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            $error = 'Ungültige Anmeldeinformationen';
        }
    }
}

// User ist eingeloggt
if (isset($_SESSION['user_id'])) {
    $user = $pdo->query("SELECT * FROM pilots WHERE id = " . $_SESSION['user_id'])->fetch();
    
    // Dashboard anzeigen
    include 'dashboard.php';
    exit;
}

// Login Form (Standard-Ansicht)
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>runwayhub - Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #1a237e, #3949ab); color: #fff; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: rgba(255,255,255,0.1); padding: 40px; border-radius: 10px; backdrop-filter: blur(10px); width: 100%; max-width: 400px; }
        h1 { font-size: 2em; margin-bottom: 10px; text-align: center; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); }
        .badges { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-bottom: 30px; }
        .badge { padding: 5px 10px; background: rgba(255,255,255,0.1); border-radius: 15px; font-size: 0.8em; }
        form { display: flex; flex-direction: column; gap: 15px; }
        label { font-size: 0.9em; margin-bottom: -5px; }
        input, textarea { padding: 12px; border: none; border-radius: 5px; background: rgba(255,255,255,0.1); color: #fff; font-size: 1em; }
        button { padding: 12px; border: none; border-radius: 5px; background: #4CAF50; color: #fff; font-size: 1em; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #45a049; }
        .error { color: #ff6b6b; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>🌉 runwayhub</h1>
        <p style="text-align: center; margin-bottom: 20px;">Login zum Dashboard</p>
        
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        
        <form method="POST">
            <div>
                <label for="email">E-Mail Adresse</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required autofocus>
            </div>
            
            <div>
                <label for="password">Passwort</label>
                <input type="password" name="password" id="password" required>
            </div>
            
            <button type="submit">Anmelden</button>
        </form>
        
        <div class="badges">
            <span class="badge">✅ MVP Phase 2: ABGESCHLOSSEN</span>
            <span class="badge">🧪 PHPUnit Tests: Active</span>
            <span class="badge">🔐 Security Scans: Active</span>
        </div>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="dashboard.php" style="color: #81d4fa; text-decoration: none;">Dashboard (Demo)</a>
        </p>
    </div>
</body>
</html>
