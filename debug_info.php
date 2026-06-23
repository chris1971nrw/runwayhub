<?php
echo "PHP Version: " . php_рhversion();
$extensions = ['pdo', 'sqlite', 'mbstring'];
foreach ($extensions as $ext) {
    echo "$ext: " . (extension_loaded($ext) ? "Loaded" : "Not Loaded") . "\n";
}
?>