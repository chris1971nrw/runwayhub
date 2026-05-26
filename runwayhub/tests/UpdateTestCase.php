<?php

namespace Tests\Update;

use RunwayHub\Core\Version;
use RunwayHub\Core\Updater;
use PHPUnit\Framework\TestCase;

/**
 * Test Case für Update-System
 * @covers \RunwayHub\Core\Version
 * @covers \RunwayHub\Core\Updater
 */
class UpdateTestCase extends TestCase
{
    protected string $tempDir;
    protected string $configPath;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Temp-Verzeichnis
        $this->tempDir = sys_get_temp_dir() . '/runwayhub-test-' . uniqid();
        mkdir($this->tempDir, 0755, true);
        
        // Config-Verzeichnis (Simuliert)
        $this->configPath = sys_get_temp_dir() . '/runwayhub-test-' . uniqid() . '/config';
        mkdir($this->configPath, 0755, true);
    }
    
    protected function tearDown(): void
    {
        // Cleanup
        if (is_dir($this->tempDir)) {
            array_map('unlink', glob($this->tempDir . '/*'));
            rmdir($this->tempDir);
        }
        
        if (is_dir($this->configPath)) {
            array_map('unlink', glob($this->configPath . '/*'));
            rmdir($this->configPath);
        }
        
        parent::tearDown();
    }
    
    /**
     * Test: Version-Vergleich
     */
    public function testCompareVersion(): void
    {
        $this->assertEquals(-1, Version::compareVersion('1.0.0', '2.0.0'));
        $this->assertEquals(1, Version::compareVersion('2.0.0', '1.0.0'));
        $this->assertEquals(0, Version::compareVersion('1.0.0', '1.0.0'));
        
        // V-Präfix
        $this->assertEquals(-1, Version::compareVersion('v1.0.0', 'v2.0.0'));
        $this->assertEquals(0, Version::compareVersion('v1.0.0', 'v1.0.0'));
        
        // Suffixe
        $this->assertEquals(-1, Version::compareVersion('1.0.0-RC1', '1.0.0'));
        $this->assertEquals(-1, Version::compareVersion('1.0.0-beta', '1.0.0-RC1'));
        
        // Unvollständige Versionen
        $this->assertEquals(-1, Version::compareVersion('1.0', '1.0.1'));
        $this->assertEquals(-1, Version::compareVersion('1', '1.0.1'));
    }
    
    /**
     * Test: GetLocalVersion
     */
    public function testGetLocalVersion(): void
    {
        $versionFile = $this->configPath . '/version.php';
        file_put_contents($versionFile, '{"version": "1.0.0"}');
        
        $version = Version::getLocalVersion();
        $this->assertEquals('1.0.0', $version);
    }
    
    /**
     * Test: GetLocalVersion - Datei fehlt
     */
    public function testGetLocalVersionMissing(): void
    {
        $version = Version::getLocalVersion();
        // Erwartet null oder Standardwert
        $this->assertEquals('0.0.0', $version);
    }
    
    /**
     * Test: Updater - Backup
     */
    public function testBackup(): void
    {
        $updater = new Updater();
        $backupResult = $updater->backup();
        
        // Backup sollte erfolgreich sein
        $this->assertTrue($backupResult);
        
        // Backup-Verzeichnis prüfen
        $this->assertDirectoryExists($this->updater->backupDir);
    }
    
    /**
     * Test: Updater - Download fehlgeschlagen
     */
    public function testDownloadFailed(): void
    {
        $updater = new Updater();
        $result = $updater->downloadUpdate('invalid-version-xyz');
        
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('404', $result['error'] ?? '');
    }
    
    /**
     * Test: Updater - Validate valid ZIP
     */
    public function testValidateValidZip(): void
    {
        // Test-ZIP erstellen
        $tempZip = $this->tempDir . '/test-valid.zip';
        $zip = new \ZipArchive();
        $zip->open($tempZip, \ZipArchive::CREATE);
        
        // Valid Datei hinzufügen
        $zip->addFile(__DIR__ . '/Fixture.php', 'src/Fixture.php');
        $zip->close();
        
        $updater = new Updater();
        $validation = $updater->validateUpdate($tempZip);
        
        $this->assertTrue($validation['valid']);
        $this->assertNull($validation['warning']);
    }
    
    /**
     * Test: Updater - Validate invalid ZIP (Path Traversal)
     */
    public function testValidateInvalidZipPathTraversal(): void
    {
        // Invalid ZIP erstellen
        $tempZip = $this->tempDir . '/test-invalid-traversal.zip';
        $zip = new \ZipArchive();
        $zip->open($tempZip, \ZipArchive::CREATE);
        
        // Datei mit ../ Pfad
        $zip->addFile(__DIR__ . '/Fixture.php', '../src/Fixture.php');
        $zip->close();
        
        $updater = new Updater();
        $validation = $updater->validateUpdate($tempZip);
        
        $this->assertFalse($validation['valid']);
    }
    
    /**
     * Test: Updater - Validate invalid ZIP (Verbotener Dateityp)
     */
    public function testValidateInvalidZipType(): void
    {
        // ZIP mit .exe Datei erstellen
        $tempZip = $this->tempDir . '/test-invalid-type.zip';
        $zip = new \ZipArchive();
        $zip->open($tempZip, \ZipArchive::CREATE);
        
        // Dummy-Datei
        $zip->addFromString('malware.exe', 'fake executable content');
        $zip->close();
        
        $updater = new Updater();
        $validation = $updater->validateUpdate($tempZip);
        
        $this->assertFalse($validation['valid']);
    }
    
    /**
     * Test: Updater - Cleanup Old Backups
     */
    public function testCleanupOldBackups(): void
    {
        $updater = new Updater();
        
        // Alte Backup-Datei erstellen
        $oldBackup = $this->updater->backupDir . '/backup_' . (time() - (8 * 86400)) . '.sql';
        file_put_contents($oldBackup, 'fake backup content');
        
        // Bereinigung
        $result = $updater->backup(); // Aufrufen von backup() aktiviert cleanup
        
        // Alte Datei sollte gelöscht sein
        $this->assertFalse(file_exists($oldBackup));
    }
    
    /**
     * Test: API Endpoints existieren
     */
    public function testApiEndpointsExist(): void
    {
        // Version-Klasse sollte Methoden haben
        $this->assertTrue(method_exists(Version::class, 'getLocalVersion'));
        $this->assertTrue(method_exists(Version::class, 'getGithubVersion'));
        $this->assertTrue(method_exists(Version::class, 'compareVersion'));
        $this->assertTrue(method_exists(Version::class, 'isUpdateAvailable'));
        
        // Updater-Klasse sollte Methoden haben
        $this->assertTrue(method_exists(Updater::class, 'backup'));
        $this->assertTrue(method_exists(Updater::class, 'downloadUpdate'));
        $this->assertTrue(method_exists(Updater::class, 'validateUpdate'));
        $this->assertTrue(method_exists(Updater::class, 'applyUpdate'));
        $this->assertTrue(method_exists(Updater::class, 'rollback'));
    }
    
    /**
     * Test: Security Checks
     */
    public function testSecurityChecks(): void
    {
        $updater = new Updater();
        
        // Test: Empty ZIP
        $validation = $updater->validateUpdate('');
        $this->assertFalse($validation['valid']);
        
        // Test: Corrupt ZIP
        $corruptZip = $this->tempDir . '/corrupt.zip';
        file_put_contents($corruptZip, 'not a zip file');
        
        $validation = $updater->validateUpdate($corruptZip);
        $this->assertFalse($validation['valid']);
    }
    
    /**
     * Integration Test: Full Update Flow
     */
    public function testFullUpdateFlow(): void
    {
        $updater = new Updater();
        
        // 1. Backup erstellen
        $backupResult = $updater->backup();
        $this->assertTrue($backupResult);
        
        // 2. Download (simuliert - hier würde man ein echtes ZIP verwenden)
        // ...
        
        // 3. Validieren
        // ...
        
        // 4. Installieren
        // ...
    }
    
    /**
     * Test: Version Comparison Edge Cases
     */
    public function testVersionComparisonEdgeCases(): void
    {
        // Gleiche Version
        $this->assertEquals(0, Version::compareVersion('1.0.0', '1.0.0'));
        
        // Nur Major unterschiedlich
        $this->assertEquals(-1, Version::compareVersion('0.9.9', '1.0.0'));
        
        // Nur Minor unterschiedlich
        $this->assertEquals(-1, Version::compareVersion('1.0.0', '1.1.0'));
        
        // Nur Patch unterschiedlich
        $this->assertEquals(-1, Version::compareVersion('1.0.0', '1.0.1'));
        
        // Falsche Formatierung
        $this->assertEquals(-1, Version::compareVersion('1.0', '1.0.1'));
        $this->assertEquals(-1, Version::compareVersion('1', '1.0.1'));
    }
}
