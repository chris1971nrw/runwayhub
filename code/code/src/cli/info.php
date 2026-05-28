<?php

declare(strict_types=1);

/**
 * RunwayHub - Information CLI Tool
 * 
 * Display system information, version, and status.
 */

// Display information
echo "=== RunwayHub Information ===" . PHP_EOL;
echo "Version: 2.0.3" . PHP_EOL;
echo "Build: 2026-05-28" . PHP_EOL;
echo "Status: Production Ready" . PHP_EOL;
echo "" . PHP_EOL;

// PHP version
echo "PHP Version: " . phpversion() . PHP_EOL;
echo "" . PHP_EOL;

// Composer dependencies
if (file_exists(dirname(__DIR__).'/'.'vendor/autoload.php')) {
    echo "Composer Dependencies:" . PHP_EOL;
} else {
    echo "Composer: Not installed yet" . PHP_EOL;
}

echo "" . PHP_EOL;

// File counts
$phpFiles = 0;
foreach (glob(dirname(__DIR__).'/**/*.php') as $file) {
    $phpFiles++;
}

$mdFiles = 0;
foreach (glob(dirname(__DIR__).'/**/*.md') as $file) {
    $mdFiles++;
}

$sqlFiles = 0;
foreach (glob(dirname(__DIR__).'/**/*.sql') as $file) {
    $sqlFiles++;
}

echo "Files Count:" . PHP_EOL;
echo "  PHP: $phpFiles" . PHP_EOL;
echo "  Markdown: $mdFiles" . PHP_EOL;
echo "  SQL: $sqlFiles" . PHP_EOL;
echo "  Total: " . ($phpFiles + $mdFiles + $sqlFiles) . PHP_EOL;

echo "" . PHP_EOL;

// Date and time
echo "Current Date: " . date('Y-m-d H:i:s') . PHP_EOL;
echo "Timezone: " . date_default_timezone_get() . PHP_EOL;

echo "" . PHP_EOL;

// Memory usage
echo "Memory Usage: " . round(memory_get_usage() / 1024, 2) . " KB" . PHP_EOL;
echo "Memory Peak: " . round(memory_get_peak_usage() / 1024, 2) . " KB" . PHP_EOL;

echo "" . PHP_EOL;

// Git status (if available)
if (file_exists(dirname(__DIR__).'/.git')) {
    echo "Git Status:" . PHP_EOL;
    exec('cd ' . dirname(__DIR__) . ' && git status --short 2>/dev/null', $output, $returnVar);
    if ($returnVar === 0 && !empty($output)) {
        echo "  Modified files:" . PHP_EOL;
        echo implode(PHP_EOL, $output) . PHP_EOL;
    }
}

echo "" . PHP_EOL;

// Exit cleanly
exit(0);