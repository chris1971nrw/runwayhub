<?php

declare(strict_types=1);

/**
 * VA Anschließen Formular
 * 
 * Endpoint für existierende Virtual Airline anzuschließen
 */

// Session-Check
if (!isset($_SESSION['user']['id'])) {
    header('Location: /login.php');
    exit;
}

$error = '';
$success = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $website = trim($_POST['website'] ?? '');
    
    if ($username && $password && $website) {
        try {
            // VA Connect API call
            $apiUrl = 'http://localhost:8000/api/va-connect.php';
            $data = json_encode([
                'ownerCredentials' => [
                    'username' => $username,
                    'password' => $password
                ],
                'website' => $website
            ]);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            $result = json_decode($response, true);
            
            if ($result['success'] ?? false) {
                $success = $result;
            } else {
                $error = $result['message'] ?? 'VA Verbindung fehlgeschlagen';
            }
            
        } catch (Exception $e) {
            $error = 'Fehler: ' . $e->getMessage();
        }
    } else {
        $error = 'Bitte alle Felder ausfüllen';
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VA Anschließen - RunwayHub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Segoe UI", Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; color: white; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: rgba(255,255,255,0.95); padding: 40px; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); }
        h1 { font-size: 2em; margin-bottom: 30px; color: #764ba2; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        input, select, textarea { width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 5px; font-size: 1em; transition: border-color 0.3s; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #667eea; }
        .btn { padding: 15px 30px; background: #667eea; color: white; border: none; border-radius: 50px; font-size: 1.1em; cursor: pointer; transition: transform 0.3s, box-shadow 0.3s; }
        .btn:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(102,126,234,0.5); }
        .error { background: #ff6b6b; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .success { background: #6bcb77; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        footer { text-align: center; padding: 20px; margin-top: 30px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔗 Virtual Airline Anschließen</h1>
        
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success">
                <h2>✅ Erfolgreich verbunden!</h2>
                <p><strong>Virtual Airline:</strong> <?= htmlspecialchars($success['va']['name']) ?></p>
                <p><strong>Airline:</strong> <?= htmlspecialchars($success['va']['airline_name']) ?></p>
                <p><strong>Website:</strong> <?= htmlspecialchars($success['va']['airline_website']) ?></p>
                <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                <p><strong>Nächste Schritte:</strong></p>
                <ol style="margin-left: 20px; color: #555;">
                    <li>Adden Sie Piloten-Callsigns zu Ihrem Airline Portal</li>
                    <li>Completieren Sie Ihre Profil-Einstellungen</li>
                    <li>Bereit für Flugdaten-Empfang</li>
                </ol>
            </div>
        <?php else: ?>
            <form method="POST">
                <div class="form-group">
                    <label for="username">Owner Credentials Benutzernamen:</label>
                    <input type="text" id="username" name="username" 
                           placeholder="Wie von der VA bereitgestellt" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="password">Owner Credentials Passwort:</label>
                    <input type="password" id="password" name="password" 
                           placeholder="Wie von der VA bereitgestellt" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="website">Airline Website:</label>
                    <input type="url" id="website" name="website" 
                           placeholder="https://www.airline-website.de" 
                           required>
                </div>
                
                <button type="submit" class="btn">VA Anschließen</button>
            </form>
        <?php endif; ?>
        
        <footer>
            <p>Built with ❤️ by @chris1971nrw | Powered by OpenAIP API</p>
        </footer>
    </div>
</body>
</html>
