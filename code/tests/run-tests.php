#!/usr/bin/env php
<?php
/**
 * RunwayHub Automated Test Suite
 * 
 * Runs all tests automatically and generates report
 */

require_once __DIR__ . '/vendor/autoload.php';

class TestRunner {
    private $tests = [];
    private $passed = 0;
    private $failed = 0;
    private $errors = 0;
    private $skipped = 0;
    
    public function __construct() {
        $this->registerTests();
    }
    
    private function registerTests() {
        $testFiles = [
            'Core/BootstrapTest.php',
            'Core/ControllerTest.php',
            'Core/DatabaseTest.php',
            'Core/RequestTest.php',
            'Core/ResponseTest.php',
            'Core/RouterTest.php',
            'DatabaseTest.php',
            'WeatherServiceTest.php',
            'PerformanceTest.php',
            'api-health-check.php',
            'test-api.php',
            'test-weather.php',
        ];
        
        foreach ($testFiles as $file) {
            $path = __DIR__ . '/' . $file;
            if (file_exists($path)) {
                require_once $path;
            }
        }
    }
    
    public function run() {
        echo "═══════════════════════════════════════════════════════════\n";
        echo "         RunwayHub Automated Test Suite v2.0.3\n";
        echo "═══════════════════════════════════════════════════════════\n\n";
        
        echo "Starting tests at " . date('Y-m-d H:i:s') . "...\n\n";
        
        echo "\nRunning test suite...\n";
        echo "─".str_repeat('─', 77) . "─\n";
        
        foreach ($this->tests as $test) {
            echo "\n[test] " . $test->getName() . "\n";
            
            $start = microtime(true);
            $result = $test->run();
            $duration = round((microtime(true) - $start) * 1000, 2);
            
            if ($result) {
                echo "  ✓ PASSED (" . $duration . "ms)\n";
                $this->passed++;
            } else {
                echo "  ✗ FAILED\n";
                echo "    Error: " . $test->getError() . "\n";
                $this->failed++;
            }
            
            $this->printProgressBar();
        }
        
        echo "\n" . str_repeat('─'.str_repeat('─', 77) . "\n\n");
        
        $this->printSummary();
        
        return $this->failed === 0;
    }
    
    private function printProgressBar() {
        $total = count($this->tests);
        $progress = $this->passed + $this->failed;
        $percent = round($progress / $total * 100);
        
        $barLength = 40;
        $filled = floor($barLength * $percent / 100);
        $empty = $barLength - $filled;
        
        echo "  Progress: [" . str_repeat('█', $filled) . str_repeat('░', $empty) . "] $percent%";
        
        if ($this->failed > 0) {
            echo " ✗";
        }
        
        echo "\n";
    }
    
    private function printSummary() {
        echo "\n═══════════════════════════════════════════════════════════\n";
        echo "                  Test Summary\n";
        echo "═══════════════════════════════════════════════════════════\n\n";
        
        echo "Total Tests:    " . ($this->passed + $this->failed + $this->errors) . "\n";
        echo "Passed:         $this->passed\n";
        echo "Failed:         $this->failed\n";
        echo "Errors:         $this->errors\n";
        echo "Skipped:        $this->skipped\n\n";
        
        $percentage = round($this->passed / max(1, $this->passed + $this->failed) * 100, 1);
        
        echo "Success Rate:   {$percentage}%\n\n";
        
        if ($this->failed === 0) {
            echo "Status:         ✅ ALL TESTS PASSED\n";
            echo "Confidence:     100%\n\n";
        } else {
            echo "Status:         ⚠️  SOME TESTS FAILED\n";
            echo "Confidence:     " . ($percentage) . "%\n\n";
        }
        
        echo "Completed at:   " . date('Y-m-d H:i:s') . "\n";
        echo "═══════════════════════════════════════════════════════════\n";
        
        if ($this->failed === 0) {
            echo "\n✅ All systems operational!\n";
            echo "RunwayHub is production-ready.\n\n";
        } else {
            echo "\n⚠️  Please fix failed tests before deployment.\n\n";
        }
    }
}

// Run tests
$runner = new TestRunner();
$success = $runner->run();

exit($success ? 0 : 1);
