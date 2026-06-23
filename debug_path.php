<?php
echo "Aktueller Pfad: " . __FILE__ . "<br>";
echo "Basis-Pfad (BASE_PATH): " . (defined('BASE_PATH') ? BASE_PATH : 'Nicht definiert');
?>