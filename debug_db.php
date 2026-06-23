<?php
require_once 'db_config.php';
try {
    $db = getDB();
    echo "Connection successful!<br>";
    $stmt = $db->query("SELECT count(*) FROM aircraft");
    $count = $stmt->fetchColumn();
    echo "Number of aircraft: " . $count;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>