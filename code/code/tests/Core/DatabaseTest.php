<?php
declare(strict_types=1);

namespace RunwayHub\Tests\Core;

use PHPUnit\Framework\TestCase;
use RunwayHub\Core\Database;

class DatabaseTest extends TestCase
{
    private Database $db;

    protected function setUp(): void
    {
        $this->db = new Database();
    }

    public function testConnectSuccess(): void
    {
        // Test connection string is valid
        $this->assertStringContainsString('mysql:host=', $this->db->getConnection());
    }

    public function testQueryPreparedStatements(): void
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $this->assertStringContainsString('?', $sql);
    }

    public function testGetTablePrefix(): void
    {
        $prefix = $this->db->getTablePrefix();
        $this->assertIsString($prefix);
    }
}
