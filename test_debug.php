<?php
echo "Testing config inclusion...<br>";
require_once 'auth_config.php';
echo "Auth Config loaded.<br>";

try {
    $db = getDB();
    echo "Database connection successful! Method: " . get_class($db) . "<br>";
    $stmt = $db->query("SELECT 1");
    if ($stmt) echo "Query success.<br>";
} catch (Exception $e) {
    echo "Database Error: " . $e->getMessage() . "<br>";
}
?>