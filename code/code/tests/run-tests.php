#!/usr/bin/env php
<?php
declare(strict_types=1);

/**
 * Test Runner for RunwayHub
 * 
 * Usage: php tests/run-tests.php [options]
 * Options:
 *   --verbose    Show detailed output
 *   --coverage   Generate coverage report
 *   --filter     Filter tests by name
 */

$verbose = false;
$coverage = false;
$filter = '';

$argv = $_SERVER['argv'];
$argc = count($argv);

for ($i = 1; $i < $argc; $i++) {
    $arg = $argv[$i];
    if ($arg === '--verbose') {
        $verbose = true;
    } elseif ($arg === '--coverage') {
        $coverage = true;
    } elseif ($arg === '--filter') {
        $i++;
        $filter = $argv[$i] ?? '';
    }
}

echo "RunwayHub Test Runner v1.0.0\n";
echo "=============================\n\n";

echo "PHP Version: " . PHP_VERSION . "\n";
echo "Composer Dependencies: " . (file_exists('vendor/composer/autoload_files.php') ? 'Installed' : 'Not Installed') . "\n";
echo "Test Files: " . count(glob('tests/**/*.php', GLOB_BRACE)) . "\n\n";

// Run PHPUnit if available
if (file_exists('vendor/bin/phpunit')) {
    $command = 'php vendor/bin/phpunit';
    
    if ($filter !== '') {
        $command .= ' --filter "' . $filter . '"';
    }
    
    if ($coverage) {
        $command .= ' --coverage-text';
    }
    
    if ($verbose) {
        $command .= ' --verbose';
    }
    
    $command .= ' --colors=always';
    
    exec($command . ' 2>&1', $output, $returnCode);
    echo "\n";
    echo "=============================================\n";
    echo "Test Execution Complete\n";
    echo "=============================================\n";
    echo "Return Code: " . ($returnCode === 0 ? 'SUCCESS (0)' : 'FAILED (' . $returnCode . ')') . "\n";
    echo "=============================================\n\n";
    
    if ($returnCode === 0) {
        echo "✅ All tests passed!\n";
    } else {
        echo "❌ Some tests failed.\n";
    }
} else {
    echo "⚠️  PHPUnit not installed. Run: composer install\n\n";
    
    // List available tests
    echo "Available Test Files:\n";
    foreach (glob('tests/**/*.php', GLOB_BRACE) as $file) {
        echo "  - " . basename($file) . "\n";
    }
}

echo "\nTest Runner Complete\n";
exit($returnCode ?? 0);
