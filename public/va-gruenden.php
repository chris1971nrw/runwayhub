<?php

declare(strict_types=1);

/**
 * RunwayHub - VA Gründen
 * Create a new Visual Air Traffic Controller
 */

// Set headers
header('Content-Type: text/html; charset=utf-8');
header('X-Content-Type-Options: nosniff');

// German localization for VA Gründen
$germanLabels = [
    'title' => '🛫 VA Gründen',
    'subtitle' => 'Erstellen Sie einen neuen Visuellen Luftverkehrsleiter',
    'vaIdentifier' => 'VA-Identifikator',
    'email' => 'E-Mail',
    'organization' => 'Organisation',
    'password' => 'Passwort',
    'confirmPassword' => 'Passwort bestätigen',
    'firstName' => 'Vorname',
    'lastName' => 'Nachname',
    'submit' => 'VA Erstellen',
    'alreadyHave' => 'Bereits einen VA?',
    'login' => 'Hier anmelden',
    'info' => 'Informationshinweis',
    'step1' => 'Schritt 1: VA-Identität',
    'step2' => 'Schritt 2: Zugangsdaten',
    'step3' => 'Schritt 3: Bestätigen',
    'description' => 'Hier können Sie neue Visuelle Luftverkehrsleiter für Ihren Betrieb erstellen.',
];

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $germanLabels['title']; ?> - RunwayHub</title>
    <meta name="description" content="<?php echo $germanLabels['subtitle']; ?>">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .container { background: white; border-radius: 16px; padding: 40px; max-width: 500px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { font-size: 28px; color: #1a365d; margin-bottom: 8px; }
        .header p { color: #6b7280; font-size: 14px; }
        .info { background: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 15px; margin-bottom: 20px; font-size: 13px; color: #92400e; }
        .form { display: flex; flex-direction: column; gap: 15px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group label { font-size: 14px; color: #374151; font-weight: 500; }
        .form-group input { padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; background: #f9fafb; }
        .form-group input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .btn { padding: 14px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-primary:hover { background: #1d4ed8; }
        .links { text-align: center; margin-top: 20px; }
        .links a { color: #6b7280; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo $germanLabels['title']; ?></h1>
            <p><?php echo $germanLabels['subtitle']; ?></p>
        </div>
        
        <div class="info">
            <strong><?php echo $germanLabels['info']; ?></strong><br>
            Füllen Sie das Formular aus, um einen neuen Visuellen Luftverkehrsleiter zu erstellen.
            <br><br>
            <?php echo $germanLabels['step1']; ?><br>
            <?php echo $germanLabels['step2']; ?><br>
            <?php echo $germanLabels['step3']; ?>
        </div>
        
        <div class="info">
            <strong><?php echo $germanLabels['description']; ?></strong>
        </div>
        
        <form class="form" action="/va-gruenden.php" method="post">
            <div class="form-group">
                <label for="va-id"><?php echo $germanLabels['vaIdentifier']; ?></label>
                <input type="text" id="va-id" name="va_id" placeholder="VA-001" required>
            </div>
            
            <div class="form-group">
                <label for="email"><?php echo $germanLabels['email']; ?></label>
                <input type="email" id="email" name="email" placeholder="va@beispiel.de" required>
            </div>
            
            <div class="form-group">
                <label for="organization"><?php echo $germanLabels['organization']; ?></label>
                <input type="text" id="organization" name="organization" placeholder="Flughafen XYZ FBO">
            </div>
            
            <div class="form-group">
                <label for="password"><?php echo $germanLabels['password']; ?></label>
                <input type="password" id="password" name="password" placeholder="Mindestens 8 Zeichen" required>
            </div>
            
            <div class="form-group">
                <label for="confirmPassword"><?php echo $germanLabels['confirmPassword']; ?></label>
                <input type="password" id="confirmPassword" name="confirm_password" placeholder="Passwort bestätigen" required>
            </div>
            
            <div class="form-group">
                <label for="firstName"><?php echo $germanLabels['firstName']; ?></label>
                <input type="text" id="firstName" name="first_name" placeholder="Max">
            </div>
            
            <div class="form-group">
                <label for="lastName"><?php echo $germanLabels['lastName']; ?></label>
                <input type="text" id="lastName" name="last_name" placeholder="Mustermann">
            </div>
            
            <button type="submit" class="btn btn-primary" style="align-self: center;"><?php echo $germanLabels['submit']; ?></button>
        </form>
        
        <div class="links">
            <a href="/"><?php echo $germanLabels['alreadyHave']; ?> <br><strong><?php echo $germanLabels['login']; ?></strong></a>
        </div>
    </div>
</body>
</html>