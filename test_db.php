<?php
require_once 'db_config.php';
try {
    $db = getDB();
    echo "Verbindung erfolgreich!";
} catch (Exception $e) {
    echo "Fehler: " . $e->getMessage();
}
?>