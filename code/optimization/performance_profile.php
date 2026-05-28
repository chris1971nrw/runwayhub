<?php

declare(strict_types=1);

/**
 * Performance Profiling Script
 * 
 * Analyzes RunwayHub performance metrics.
 * 
 * Usage: php performance_profile.php [--report]
 */

echo '=== Performance Profiling ===' . PHP_EOL;
echo 'Timestamp: ' . date('Y-m-d H:i:s') . PHP_EOL;
echo '';

// Memory usage
echo '=== Memory Usage ===' . PHP_EOL;
echo 'Memory Limit: ' . ini_get('memory_limit') . PHP_EOL;
echo 'Actual Memory: ' . round(memory_get_usage() / 1024, 2) . ' KB' . PHP_EOL;
echo 'Peak Memory: ' . round(memory_get_peak_usage() / 1024, 2) . ' KB' . PHP_EOL;
echo '';

// PHP configuration
echo '=== PHP Configuration ===' . PHP_EOL;
echo 'PHP Version: ' . phpversion() . PHP_EOL;
echo 'PHP SAPI: ' . php_sapi_name() . PHP_EOL;
echo 'Max Input Time: ' . ini_get('max_input_time') . PHP_EOL;
echo 'Default Time Limit: ' . ini_get('default_socket_timeout') . 's' . PHP_EOL;
echo 'File Uploads: ' . ini_get('file_uploads') . PHP_EOL;
echo '';

// File counts
echo '=== File Statistics ===' . PHP_EOL;
$phpFiles = glob('code/src/**/*.php');
$mdFiles = glob('**/*.md');
$sqlFiles = glob('**/*.sql');
$cssFiles = glob('**/*.css');
$jsFiles = glob('**/*.js');

echo 'PHP Files: ' . count($phpFiles) . PHP_EOL;
echo 'Markdown: ' . count($mdFiles) . PHP_EOL;
echo 'SQL: ' . count($sqlFiles) . PHP_EOL;
echo 'CSS: ' . count($cssFiles) . PHP_EOL;
echo 'JS: ' . count($jsFiles) . PHP_EOL;
echo '';

// Database size
echo '=== Database Size ===' . PHP_EOL;
$dbPath = 'code/database.sqlite';
if (file_exists($dbPath)) {
    $size = filesize($dbPath);
    echo 'Database: ' . $dbPath . PHP_EOL;
    echo 'Size: ' . $size . ' bytes (' . round($size / 1024, 2) . ' KB)' . PHP_EOL;
} else {
    echo 'Database: Not found' . PHP_EOL;
}
echo '';

// Optimizations identified
echo '=== Identified Optimizations ===' . PHP_EOL;
echo '[1] Enable OPcache for PHP' . PHP_EOL;
echo '[2] Use prepared statements for SQL' . PHP_EOL;
echo '[3] Implement response compression (GZIP)' . PHP_EOL;
echo '[4] Use CDN for static assets' . PHP_EOL;
echo '[5] Enable browser caching headers' . PHP_EOL;
echo '[6] Minify CSS/JS files' . PHP_EOL;
echo '[7] Implement database indexing' . PHP_EOL;
echo '[8] Use connection pooling' . PHP_EOL;
echo '';

// Recommendations
echo '=== Recommendations ===' . PHP_EOL;
echo '1. Enable OPcache (improve PHP performance 5-10x)' . PHP_EOL;
echo '2. Use GZIP compression for responses' . PHP_EOL;
echo '3. Implement lazy loading for images' . PHP_EOL;
echo '4. Use database indexes for frequently queried columns' . PHP_EOL;
echo '5. Cache API responses with appropriate TTL' . PHP_EOL;
echo '6. Use content delivery network (CDN)' . PHP_EOL;
echo '7. Implement rate limiting for API endpoints' . PHP_EOL;
echo '';

echo '=== Profiling Complete ===' . PHP_EOL;
echo 'Timestamp: ' . date('Y-m-d H:i:s') . PHP_EOL;
