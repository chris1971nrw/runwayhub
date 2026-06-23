<?php
require_once 'db_config.php';

try {
    $db = getDB();
    // Das Passwort für den Admin neu setzen (Passwort: admin123)
    $newPassword = password_hash('admin123', PASSWORD_DEFAULT);
    
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->execute([$newPassword, 'admin']);
    
    echo "Passwort für User 'admin' erfolgreich auf 'admin123' aktualisiert.\n";
} catch (Exception $e) {
    echo "Fehler beim Zurücksetzen des Passworts: " . $e->getMessage() . "\n";
}
?>