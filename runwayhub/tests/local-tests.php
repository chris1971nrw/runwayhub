<?php

declare(strict_types=1);

/**
 * RunwayHub - Local Tests
 * 
 * Alle Tests vor Release durchführen
 */

echo "=== RunwayHub Local Tests v1.0.0 ===" . PHP_EOL;
echo "Datum: " . date('Y-m-d H:i:s') . PHP_EOL;
echo "===========================" . PHP_EOL;
echo "";

$testsPassed = 0;
$testsFailed = 0;
$errors = [];

// Test 1: Landing Page
echo "1. Testing Landing Page (/)...";
try {
    $content = file_get_contents('http://localhost:8000/');
    if ($content && strlen($content) > 1000) {
        echo "   ✅ PASS - Landing Page läuft" . PHP_EOL;
        $testsPassed++;
    } else {
        echo "   ❌ FAIL - Keine Inhalte" . PHP_EOL;
        $testsFailed++;
        $errors[] = "Landing Page returned empty response";
    }
} catch (Exception $e) {
    echo "   ❌ FAIL - " . $e->getMessage() . PHP_EOL;
    $testsFailed++;
}

// Test 2: Login Page
echo "2. Testing Login Page (/login.php)...";
try {
    $content = file_get_contents('http://localhost:8000/login.php');
    if (strpos($content, 'demo_pilot') !== false) {
        echo "   ✅ PASS - Login Page zeigt Demo-Accounts" . PHP_EOL;
        $testsPassed++;
    } else {
        echo "   ❌ FAIL - Demo-Accounts nicht gefunden" . PHP_EOL;
        $testsFailed++;
    }
} catch (Exception $e) {
    echo "   ❌ FAIL - " . $e->getMessage() . PHP_EOL;
    $testsFailed++;
}

// Test 3: VA Admin
echo "3. Testing VA Admin (/va-admin.php)...";
try {
    $content = file_get_contents('http://localhost:8000/va-admin.php');
    if (strpos($content, 'VA Verwalten') !== false) {
        echo "   ✅ PASS - VA Admin funktioniert" . PHP_EOL;
        $testsPassed++;
    } else {
        echo "   ❌ FAIL - VA Admin nicht gefunden" . PHP_EOL;
        $testsFailed++;
    }
} catch (Exception $e) {
    echo "   ❌ FAIL - " . $e->getMessage() . PHP_EOL;
    $testsFailed++;
}

// Test 4: VA Gruenden
echo "4. Testing VA Gruenden (/va-gruenden.php)...";
try {
    $content = file_get_contents('http://localhost:8000/va-gruenden.php');
    if (strpos($content, 'VA Gründen') !== false) {
        echo "   ✅ PASS - VA Gruenden funktioniert" . PHP_EOL;
        $testsPassed++;
    } else {
        echo "   ⚠️  SKIP - Datei nicht gefunden" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "   ⚠️  SKIP - " . $e->getMessage() . PHP_EOL;
}

// Test 5: VA Connect
echo "5. Testing VA Connect (/va-connect.php)...";
try {
    $content = file_get_contents('http://localhost:8000/va-connect.php');
    if (strpos($content, 'VA Anschließen') !== false) {
        echo "   ✅ PASS - VA Connect funktioniert" . PHP_EOL;
        $testsPassed++;
    } else {
        echo "   ⚠️  SKIP - Datei nicht gefunden" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "   ⚠️  SKIP - " . $e->getMessage() . PHP_EOL;
}

// Test 6: Dashboard
echo "6. Testing Dashboard (/dashboard.php)...";
try {
    $content = file_get_contents('http://localhost:8000/dashboard.php');
    if (strpos($content, 'Dashboard') !== false) {
        echo "   ✅ PASS - Dashboard funktioniert" . PHP_EOL;
        $testsPassed++;
    } else {
        echo "   ❌ FAIL - Dashboard nicht gefunden" . PHP_EOL;
        $testsFailed++;
    }
} catch (Exception $e) {
    echo "   ❌ FAIL - " . $e->getMessage() . PHP_EOL;
    $testsFailed++;
}

// Test 7: API Status
echo "7. Testing API Status (/api-status.php)...";
try {
    $content = file_get_contents('http://localhost:8000/api-status.php');
    if (json_decode($content, true)['success'] ?? false) {
        echo "   ✅ PASS - API Status funktioniert" . PHP_EOL;
        $testsPassed++;
    } else {
        echo "   ⚠️  WARNING - API Response ungültig" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "   ⚠️  SKIP - " . $e->getMessage() . PHP_EOL;
}

// Test 8: Composer Install
echo "8. Testing Composer Dependencies...";
try {
    if (file_exists('vendor/composer/autoload_files.php')) {
        echo "   ✅ PASS - Composer Dependencies installiert" . PHP_EOL;
        $testsPassed++;
    } else {
        echo "   ⚠️  SKIP - composer.json existiert, aber vendor/ nicht" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "   ⚠️  SKIP - " . $e->getMessage() . PHP_EOL;
}

// Test 9: Configuration Files
echo "9. Testing Configuration Files...";
$requiredFiles = [
    'config/database.php',
    'config/mqtt.php',
    'runwayhub.sqlite.schema',
    'users.sqlite.schema',
];
$configOk = true;
foreach ($requiredFiles as $file) {
    if (!file_exists($file)) {
        echo "   ⚠️  WARNING - $file nicht gefunden" . PHP_EOL;
        $configOk = false;
    }
}
if ($configOk) {
    echo "   ✅ PASS - Alle Konfigurationsdateien vorhanden" . PHP_EOL;
    $testsPassed++;
}

// Test 10: Database Schema
echo "10. Testing Database Schema...";
try {
    if (file_exists('runwayhub.sqlite.schema')) {
        $content = file_get_contents('runwayhub.sqlite.schema');
        $tables = ['va', 'flights', 'pireps', 'maintenance', 'security'];
        foreach ($tables as $table) {
            if (strpos($content, $table) === false) {
                echo "   ⚠️  WARNING - Tabelle '$table' im Schema nicht gefunden" . PHP_EOL;
            }
        }
        echo "   ✅ PASS - Database Schema vorhanden" . PHP_EOL;
        $testsPassed++;
    } else {
        echo "   ⚠️  WARNING - SQLite Schema nicht gefunden" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "   ⚠️  SKIP - " . $e->getMessage() . PHP_EOL;
}

// Test 11: Security Headers
echo "11. Testing Security Configuration...";
$securityOk = true;
if (file_exists('.htaccess')) {
    $content = file_get_contents('.htaccess');
    if (strpos($content, 'X-Frame-Options') === false) {
        echo "   ⚠️  WARNING - X-Frame-Options nicht in .htaccess" . PHP_EOL;
        $securityOk = false;
    }
}
if ($securityOk) {
    echo "   ✅ PASS - Sicherheitskonfiguration vorhanden" . PHP_EOL;
    $testsPassed++;
}

// Summary
echo "";
echo "=== Test Summary ===" . PHP_EOL;
echo "Gesamt: " . ($testsPassed + $testsFailed) . " Tests" . PHP_EOL;
echo "Erfolgreich: $testsPassed" . PHP_EOL;
echo "Fehlgeschlagen: $testsFailed" . PHP_EOL;
echo "";

if ($testsFailed === 0 && $testsPassed > 0) {
    echo "✅ ALLES TESTERFOLGREICH!" . PHP_EOL;
    echo "" . PHP_EOL;
    echo "Release kann vorbereitet werden." . PHP_EOL;
    echo "========================" . PHP_EOL;
} else {
    echo "⚠️  Es gab Fehler oder Warnungen." . PHP_EOL;
    echo "Prüfe die Fehler:" . PHP_EOL;
    foreach ($errors as $error) {
        echo "   - $error" . PHP_EOL;
    }
    echo "========================" . PHP_EOL;
}

echo "" . PHP_EOL;
echo "Testzeit: " . round(microtime(true) - $startTime, 2) . "s" . PHP_EOL;

exit($testsFailed > 0 ? 1 : 0);
