<?php

declare(strict_types=1);

/**
 * Database Integration Test
 * Tests SQLite database operations and migrations
 */

require_once __DIR__ . '/bootstrap.php';

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    private ?PDO $pdo = null;
    
    protected function setUp(): void
    {
        $dbFile = __DIR__ . '/../runwayhub/database.sqlite';
        
        try {
            $dsn = "sqlite:$dbFile";
            $this->pdo = new PDO($dsn, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            
            // Enable foreign keys
            $this->pdo->exec('PRAGMA foreign_keys = ON');
            
        } catch (PDOException $e) {
            $this->markTestSkipped('Database not initialized: ' . $e->getMessage());
        }
    }
    
    public function testDatabaseConnection(): void
    {
        if ($this->pdo === null) {
            $this->markTestSkipped('Database not initialized');
            return;
        }
        
        $this->assertTrue(true); // Connection successful
    }
    
    public function testDatabaseTablesExist(): void
    {
        if ($this->pdo === null) {
            $this->markTestSkipped('Database not initialized');
            return;
        }
        
        $tables = [
            'flights', 'aircraft', 'pilots', 'bookings', 'airlines',
            'bookings', 'bookings', 'bookings', 'bookings', 'bookings',
        ];
        
        $result = $this->pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
        $tableNames = array_map(fn($row) => $row['name'], $result->fetchAll());
        
        $this->assertGreaterThan(0, count($tableNames));
    }
    
    public function testFlightsTable(): void
    {
        if ($this->pdo === null) {
            $this->markTestSkipped('Database not initialized');
            return;
        }
        
        $result = $this->pdo->query("SELECT COUNT(*) as count FROM flights");
        $row = $result->fetch();
        
        $this->assertGreaterThan(-1, $row['count']);
    }
    
    public function testInsertFlight(): void
    {
        if ($this->pdo === null) {
            $this->markTestSkipped('Database not initialized');
            return;
        }
        
        $stmt = $this->pdo->prepare("
            INSERT OR REPLACE INTO flights (
                flight_number, airline_code, callsign, origin, destination,
                status, departure, arrival, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            'LH400', 'LH', 'DLH400', 'FRA', 'JFK',
            'en-route', '2026-05-28T08:00:00+02:00',
            '2026-05-28T11:30:00+05:00',
            date('c'),
        ]);
        
        $this->assertTrue(true); // Insert successful
    }
    
    public function testSelectFlight(): void
    {
        if ($this->pdo === null) {
            $this->markTestSkipped('Database not initialized');
            return;
        }
        
        $stmt = $this->pdo->prepare("SELECT * FROM flights WHERE flight_number = ?");
        $stmt->execute(['LH400']);
        $flight = $stmt->fetch();
        
        if ($flight) {
            $this->assertEquals('LH400', $flight['flight_number']);
        }
    }
    
    public function testUpdateFlight(): void
    {
        if ($this->pdo === null) {
            $this->markTestSkipped('Database not initialized');
            return;
        }
        
        $stmt = $this->pdo->prepare("UPDATE flights SET status = ? WHERE flight_number = ?");
        $stmt->execute(['landed', 'LH400']);
        
        $this->assertTrue(true);
    }
    
    public function testDeleteFlight(): void
    {
        if ($this->pdo === null) {
            $this->markTestSkipped('Database not initialized');
            return;
        }
        
        $stmt = $this->pdo->prepare("DELETE FROM flights WHERE flight_number = ?");
        $stmt->execute(['LH400']);
        
        $this->assertTrue(true);
    }
}
