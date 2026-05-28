#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * RunwayHub Update Script
 * 
 * Aktualisiert das System auf die neueste Version.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use RunwayHub\Core\Database;
use RunwayHub\Core\GitHubRelease;

// Database Path
$dbPath = __DIR__ . '/../database.sqlite';

if (!file_exists($dbPath)) {
    die('❌ Datenbank nicht gefunden! Bitte installiere RunwayHub zuerst.');
}

echo "🛫 RunwayHub Update Script\n";
echo "==========================\n\n";

// Database
$db = new Database($dbPath);

echo "📊 Datenbank-Pfad: {$dbPath}\n\n";

// Check for updates
$release = new GitHubRelease();

echo "🔍 GitHub Releases prüfen...\n";
echo "   Repository: {$release->repository}\n\n";

// Update available
$hasUpdate = $release->checkForUpdates();

$info = $release->getUpdateInfo();

echo "📋 Versions-Info:\n";
echo "   Aktuelle Version: {$info['current_version']}\n";
echo "   Neueste Version: {$info['latest_version']}\n";
echo "   Update verfügbar: " . ($hasUpdate ? 'Ja' : 'Nein') . "\n";
echo "   Letzter Check: {$info['last_check']}\n\n";

if (!$hasUpdate) {
    echo "✅ System ist auf dem neuesten Stand!\n\n";
    exit(0);
}

// Download update
if ($info['download_url']) {
    echo "⬇️  Update-Download...\n";
    echo "   Download-URL: {$info['download_url']}\n\n";
    
    $downloadFile = __DIR__ . '/../runwayhub.zip';
    
    try {
        $fileHandle = fopen($info['download_url'], 'r');
        if ($fileHandle) {
            $content = '';
            while (!feof($fileHandle)) {
                $content .= fread($fileHandle, 8192);
            }
            fclose($fileHandle);
            
            // Write to temporary file
            $tempFile = sys_get_temp_dir() . '/runwayhub-update.zip';
            file_put_contents($tempFile, $content);
            
            echo "   Download erfolgreich!\n\n";
        }
    } catch (\Exception $e) {
        die("❌ Download-Fehler: {$e->getMessage()}\n");
    }
} else {
    die("❌ Keine Download-URL verfügbar!\n");
}

// Extract and replace files
echo "📦 Update-Paket extrahieren...\n\n";

// Extract zip to temporary directory
$extractDir = sys_get_temp_dir() . '/runwayhub-update';
if (file_exists($extractDir)) {
    shell_exec("rm -rf " . escapeshellarg($extractDir));
}

if (!exec("unzip -o '{$downloadFile}' -d '{$extractDir}' > /dev/null 2>&1")) {
    die("❌ Extraktionsfehler!\n");
}

// Copy new files (skip database, logs, uploads)
$skipDirs = ['/database.sqlite', '/logs', '/uploads'];

foreach (scandir($extractDir) as $file) {
    if (in_array($file, $skipDirs)) {
        continue;
    }
    
    $src = $extractDir . '/' . $file;
    $dst = $dbPath . '/' . basename($file);
    
    if (file_exists($dst)) {
        copy($src, $dst);
    }
}

// Cleanup
shell_exec("rm -rf {$extractDir}");

echo "✅ Update abgeschlossen!\n\n";
echo "📊 Statistiken:\n";
echo "   Dateien aktualisiert: " . count(scandir($extractDir)) . "\n\n";

echo "📝 Next Steps:\n";
echo "   1. Dashboard aktualisieren\n";
echo "   2. Neue Version prüfen\n";
echo "   3. Logs überprüfen\n\n";

echo "✅ Update erfolgreich!\n";
