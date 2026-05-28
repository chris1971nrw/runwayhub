<?php

declare(strict_types=1);

namespace RunwayHub\Core;

/**
 * SimpleDatabase - Simulates SQLite operations without actual SQLite driver
 * Falls pdo_sqlite nicht verfügbar, verwendet in-memory SQLite oder MySQL-Simulation
 */
class SimpleDatabase
{
    private ?\PDO $pdo = null;
    private string $charset = 'utf8mb4';
    private array $schema = [];
    private bool $inMemory = false;
    
    public function __construct(array $config)
    {
        $driver = strtolower($config['driver'] ?? 'sqlite');
        
        if ($driver === 'sqlite' && !extension_loaded('pdo_sqlite')) {
            // Fallback: Use in-memory SQLite if file sqlite not available
            $this->pdo = new \PDO('sqlite::memory:', null, null, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            $this->inMemory = true;
            
            echo "[SimpleDatabase] Using in-memory SQLite (file driver not available)\\n";
        } else {
            // Normal PDO setup
            $dsn = $driver === 'sqlite' 
                ? sprintf('sqlite:%s', $config['path'] ?? $config['database'] ?? ':memory:')
                : sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s',
                    $config['host'],
                    $config['port'] ?? 3306,
                    $config['database'] ?? '',
                    $this->charset);
            
            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            try {
                $this->pdo = new \PDO($dsn, $config['username'] ?? '', $config['password'] ?? '', $options);
            } catch (\PDOException $e) {
                throw new \RuntimeException(sprintf('Database connection failed: %s', $e->getMessage()));
            }
        }
    }
    
    public function query(string $sql, array $params = []): \PDOStatement
    {
        return $this->pdo->query($sql, \PDO::FETCH_ASSOC, ...$params);
    }
    
    public function prepare(string $sql): \PDOStatement
    {
        return $this->pdo->prepare($sql);
    }
    
    public function insert(string $table, array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        
        return $this->pdo->lastInsertId();
    }
    
    public function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $setParts = [];
        foreach (array_keys($data) as $column) {
            $setParts[] = "{$column} = ?";
        }
        $sql = "UPDATE {$table} SET " . implode(', ', $setParts) . " WHERE {$where}";
        
        $params = array_merge(array_values($data), $whereParams);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->rowCount();
    }
    
    public function delete(string $table, string $where, array $params = []): int
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$table} WHERE {$where}");
        $stmt->execute($params);
        return $stmt->rowCount();
    }
    
    public function fetch(string $sql, array $params = []): ?array
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    public function exists(string $table, string $where, array $params = []): bool
    {
        $row = $this->fetch("SELECT 1 FROM {$table} WHERE {$where}", $params);
        return $row !== false;
    }
    
    public function createTable(string $table, string $columns): void
    {
        // Extract columns (simple format: id TEXT PRIMARY KEY, name TEXT NOT NULL)
        $createSql = "CREATE TABLE IF NOT EXISTS {$table} ({$columns})";
        $this->query($createSql);
    }
    
    public function exec(string $sql): bool
    {
        return $this->pdo->exec($sql) !== false;
    }
    
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }
    
    public function lastError(): string
    {
        return $this->pdo->errorInfo()[2] ?? 'Unknown error';
    }
}
