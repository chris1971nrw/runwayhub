<?php

declare(strict_types=1);

/**
 * Login Page
 * 
 * Pilot Login mit Callsign/Passwort
 */

// Start session
session_start();

// Demo account check
$demos = ['demo_pilot', 'demo_admin', 'demo_guest'];
if (isset($_POST['callsign'])) {
    if (in_array($_POST['callsign'], $demos)) {
        // Create session
        $_SESSION['user']['id'] = $_POST['callsign'];
        $_SESSION['user']['callsign'] = $_POST['callsign'];
        $_SESSION['user']['airline'] = '';
        $_SESSION['user']['token'] = bin2hex(random_bytes(32));
        
        // Redirect based on role
        if ($_POST['callsign'] === 'demo_admin') {
            header('Location: /va-admin.php');
        } else {
            header('Location: /dashboard.php');
        }
        exit;
    }
}

$error = '';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RunwayHub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Segoe UI", Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .container { width: 100%; max-width: 400px; background: rgba(255,255,255,0.95); padding: 40px; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); }
        h1 { font-size: 2em; margin-bottom: 30px; color: #764ba2; text-align: center; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        input { width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 5px; font-size: 1em; transition: border-color 0.3s; }
        input:focus { outline: none; border-color: #667eea; }
        .btn { width: 100%; padding: 15px; background: #667eea; color: white; border: none; border-radius: 50px; font-size: 1.1em; cursor: pointer; transition: transform 0.3s, box-shadow 0.3s; }
        .btn:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(102,126,234,0.5); }
        .error { background: #ff6b6b; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .demo { background: #e9ecef; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        footer { text-align: center; padding: 20px; margin-top: 30px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h1>👤 Pilot Login</h1>
        
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="callsign">Callsign:</label>
                <input type="text" id="callsign" name="callsign" 
                       placeholder="z.B. demo_pilot" 
                       value="<?= isset($_POST['callsign']) ? htmlspecialchars($_POST['callsign']) : '' ?>"
                       required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Passwort:</label>
                <input type="password" id="password" name="password" 
                       placeholder="Ihr Passwort" 
                       value="<?= isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '' ?>"
                       required>
            </div>
            
            <button type="submit" class="btn">Anmelden</button>
        </form>
        
        <div class="demo">
            <strong>Demo Accounts:</strong><br>
            <small>demo_pilot / pilot123</small><br>
            <small>demo_admin / admin123</small><br>
            <small>demo_guest / guest123</small>
        </div>
        
        <footer>
            <p><a href="/">← Zurück zur Startseite</a></p>
            <p>Built with ❤️ by @chris1971nrw | Powered by OpenAIP API</p>
        </footer>
    </div>
</body>
</html>
