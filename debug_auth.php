<?php
require_once 'db_config.php';

echo "--- Login-Diagnose ---\n";

try {
    $db = getDB();
    echo "1. Datenbankverbindung erfolgreich.\n";

    // Test 1: Überprüfung des Benutzers "admin"
    $test_user = 'admin';
    $stmt = $db->prepare("SELECT id, username, role, password FROM users WHERE username = ?");
    $stmt->execute([$test_user]);
    $user = $stmt->fetch();

    if ($user) {
        echo "2. Benutzer '$test_user' gefunden!\n";
        echo "   Details: ID={$user['id']}, Rolle={$user['role']}\n";
        
        // Test 2: Passwort-Vergleich (manuelle Prüfung des Hashes)
        // Wir testen hier, ob das System den Hash korrekt verarbeiten kann.
        // Da wir das Passwort des Nutzers nicht kennen, geben wir nur an, 
        // ob der Hash gültig ist für eine beliebige Eingabe oder einfach vorhanden ist.
        if (!empty($user['password'])) {
            echo "3. Passwort-Hash im System vorhanden.\n";
        } else {
            echo "3. WARNUNG: Kein Passwort-Hash gefunden!\n";
        }
    } else {
        echo "2. Fehler: Benutzer '$test_user' wurde NICHT in der Datenbank gefunden.\n";
    }

} catch (Exception $e) {
    echo "Fehler während der Diagnose: " . $e->getMessage() . "\n";
}
?>