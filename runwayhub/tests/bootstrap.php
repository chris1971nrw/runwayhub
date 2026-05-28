<?php
declare(strict_types=1);

// Bootstrap PHPUnit test environment
require_once dirname(__DIR__) . '/vendor/autoload.php';

use PHPUnit\Framework\Result\TestResult;

// Enable error reporting for tests
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Set default test directory
if (!defined('TESTS_DIR')) {
    define('TESTS_DIR', __DIR__);
}

// Load PHPUnit configuration
if (file_exists(__DIR__ . '/../phpunit.xml')) {
    ini_set('default_memory_limit', '512M');
}
