<?php

declare(strict_types=1);

/**
 * VA Gründen Formular
 * 
 * Endpoint für Virtual Airline Gründung
 */

// Session-Check
if (!isset($_SESSION['user']['id'])) {
    header('Location: /login.php');
    exit;
}

$error = '';
$success = null;
$vaData = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $logo = trim($_POST['logo'] ?? '');
    $website = trim($_POST['website'] ?? '');
    $airlineName = trim($_POST['airlineName'] ?? '');
    $colors = isset($_POST['colors']) ? json_encode($_POST['colors']) : '{"primary":"#000000","secondary":"#ffffff"}';
    
    if ($name && $logo && $website && $airlineName) {
        try {
            // VA Create API call
            $apiUrl = 'http://localhost:8000/api/va-create.php';
            $data = json_encode([
                'name' => $name,
                'logo' => $logo,
                'colors' => json_decode($colors),
                'airlineName' => $airlineName,
                'website' => $website
            ]);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . ($_SESSION['user']['token'] ?? '')
            ]);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            $result = json_decode($response, true);
            
            if ($result['success'] ?? false) {
                $success = $result;
            } else {
                $error = $result['message'] ?? 'VA Erstellung fehlgeschlagen';
            }
            
        } catch (Exception $e) {
            $error = 'Fehler: ' . $e->getMessage();
        }
    } else {
        $error = 'Bitte alle Felder ausfüllen';
    }
}

// Get sample airlines
$airlines = [
    ['name' => 'Lufthansa', 'url' => 'https://www.lufthansa.com'],
    ['name' => 'Swiss', 'url' => 'https://www.swiss.com'],
    ['name' => 'Air France', 'url' => 'https://www.airfrance.com'],
    ['name' => 'British Airways', 'url' => 'https://www.britishairways.com'],
    ['name' => 'Emirates', 'url' => 'https://www.emirates.com'],
];
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VA Gründen - RunwayHub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Segoe UI", Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; color: white; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: rgba(255,255,255,0.95); padding: 40px; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); }
        h1 { font-size: 2em; margin-bottom: 30px; color: #764ba2; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        input, select, textarea { width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 5px; font-size: 1em; transition: border-color 0.3s; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #667eea; }
        .btn { padding: 15px 30px; background: #667eea; color: white; border: none; border-radius: 50px; font-size: 1.1em; cursor: pointer; transition: transform 0.3s, box-shadow 0.3s; }
        .btn:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(102,126,234,0.5); }
        .error { background: #ff6b6b; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .success { background: #6bcb77; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .result { background: #f8f9fa; padding: 20px; border-radius: 5px; margin-top: 20px; display: none; }
        .airline-selector { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px; }
        .airline-card { padding: 20px; border: 2px solid #ddd; border-radius: 8px; cursor: pointer; transition: all 0.3s; }
        .airline-card:hover { border-color: #667eea; transform: translateY(-3px); }
        .airline-card.selected { border-color: #667eea; background: rgba(102,126,234,0.1); }
        footer { text-align: center; padding: 20px; margin-top: 30px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Virtual Airline Gründen</h1>
        
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success">
                <h2>✅ Virtual Airline erstellt!</h2>
                <p><strong>Name:</strong> <?= htmlspecialchars($success['va']['name']) ?></p>
                <p><strong>Airline:</strong> <?= htmlspecialchars($success['va']['airline_name']) ?></p>
                <p><strong>Website:</strong> <?= htmlspecialchars($success['va']['airline_website']) ?></p>
                <p><strong>Owner Credentials:</strong></p>
                <p>Benutzername: <code><?= htmlspecialchars($success['owner_credentials']['username']) ?></code></p>
                <p>Passwort: <code><?= htmlspecialchars($success['owner_credentials']['password']) ?></code></p>
                <p><strong>Gültig bis:</strong> <?= htmlspecialchars($success['owner_credentials']['expires']) ?></p>
                <hr>
                <p><strong>Nächste Schritte:</strong></p>
                <ol style="margin-left: 20px;">
                    <li>Speichern Sie die Owner Credentials</li>
                    <li>Adden Sie Piloten-Callsigns zu Ihrem Airline Portal</li>
                    <li>Verwalten Sie Flüge und Piloten</li>
                </ol>
            </div>
        <?php endif; ?>
        
        <?php if (!$success): ?>
            <form method="POST">
                <div class="form-group">
                    <label>Airline wählen:</label>
                    <div class="airline-selector">
                        <?php foreach ($airlines as $airline): ?>
                            <div class="airline-card <?= isset($vaData['airline']) && $vaData['airline']['url'] === $airline['url'] ? 'selected' : '' ?>" 
                                 onclick="selectAirline(this, '<?= htmlspecialchars($airline['url']) ?>')">
                                <h4><?= htmlspecialchars($airline['name']) ?></h4>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="airlineName">Airline Name:</label>
                    <input type="text" id="airlineName" name="airlineName" 
                           placeholder="z.B. Deutsche Airline" 
                           value="<?= htmlspecialchars($vaData['airlineName'] ?? '') ?>"
                           required>
                </div>
                
                <div class="form-group">
                    <label for="website">Airline Website:</label>
                    <input type="url" id="website" name="website" 
                           placeholder="https://www.deutsche-airline.de" 
                           value="<?= htmlspecialchars($vaData['website'] ?? '') ?>"
                           required>
                </div>
                
                <div class="form-group">
                    <label for="name">VA Name:</label>
                    <input type="text" id="name" name="name" 
                           placeholder="z.B. Deutsche Airline Virtual" 
                           value="<?= htmlspecialchars($vaData['name'] ?? '') ?>"
                           required>
                </div>
                
                <div class="form-group">
                    <label for="logo">Logo URL:</label>
                    <input type="url" id="logo" name="logo" 
                           placeholder="https://example.com/logo.png" 
                           value="<?= htmlspecialchars($vaData['logo'] ?? '') ?>"
                           required>
                </div>
                
                <div class="form-group">
                    <label for="colors">Farben:</label>
                    <div style="display: flex; gap: 10px;">
                        <div style="flex: 1;">
                            <label>Hauptfarbe:</label>
                            <input type="color" id="primary" name="colors[primary]" value="<?= htmlspecialchars($vaData['colors']['primary'] ?? '#000000') ?>">
                        </div>
                        <div style="flex: 1;">
                            <label>Zusatzfarbe:</label>
                            <input type="color" id="secondary" name="colors[secondary]" value="<?= htmlspecialchars($vaData['colors']['secondary'] ?? '#ffffff') ?>">
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn">VA Gründen</button>
            </form>
        <?php endif; ?>
        
        <footer>
            <p>Built with ❤️ by @chris1971nrw | Powered by OpenAIP API</p>
        </footer>
    </div>
    
    <script>
        function selectAirline(card, url) {
            document.querySelectorAll('.airline-card').forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
        }
    </script>
</body>
</html>
