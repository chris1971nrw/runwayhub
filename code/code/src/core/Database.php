<?php

declare(strict_types=1);

namespace RunwayHub\Core;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    /** @var PDO */
    private PDO $pdo;

    /** @var string */
    private string $charset = 'utf8mb4';

    /**
     * Database constructor
     */
    public function __construct(array $config)
    {
        // Support SQLite for local development
        if (isset($config['driver']) && strtolower($config['driver']) === 'sqlite') {
            $dsn = sprintf('sqlite:%s', $config['path'] ?? $config['database'] ?? '');
        } else {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $config['host'],
                $config['port'] ?? 3306,
                $config['database'] ?? '',
                $this->charset
            );
        }

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $config['username'] ?? '', $config['password'] ?? '', $options);
        } catch (PDOException $e) {
            throw new \RuntimeException(sprintf('Database connection failed: %s', $e->getMessage()));
        }
    }

    /**
     * Execute SQL query
     *
     * @param string $sql
     * @param array $params
     * @return PDOStatement
     * @throws PDOException
     */
    public function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Fetch single row
     *
     * @param string $sql
     * @param array $params
     * @return array|false
     */
    public function fetch(string $sql, array $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    /**
     * Fetch all rows
     *
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Fetch value
     *
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function fetchOne(string $sql, array $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchColumn();
    }

    /**
     * Insert record
     *
     * @param string $sql
     * @param array $params
     * @return int Last insert ID
     */
    public function insert(string $sql, array $params = []): int
    {
        $stmt = $this->query($sql, $params);
        return $this->pdo->lastInsertId();
    }

    /**
     * Update records
     *
     * @param string $sql
     * @param array $params
     * @return int Number of affected rows
     */
    public function update(string $sql, array $params = []): int
    {
        return $this->query($sql, $params)->rowCount();
    }

    /**
     * Delete records
     *
     * @param string $sql
     * @param array $params
     * @return int Number of deleted rows
     */
    public function delete(string $sql, array $params = []): int
    {
        return $this->query($sql, $params)->rowCount();
    }

    /**
     * Begin transaction
     */
    public function begin(): void
    {
        $this->pdo->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public function commit(): void
    {
        $this->pdo->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback(): void
    {
        $this->pdo->rollBack();
    }

    /**
     * Check if in transaction
     */
    public function inTransaction(): bool
    {
        return $this->pdo->inTransaction();
    }

    /**
     * Get PDO instance
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * Get last insert ID
     */
    public function lastInsertId(): int
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Get affected rows
     */
    public function affectedRows(): int
    {
        return $this->pdo->rowCount();
    }

    /**
     * Escape value for SQL
     *
     * @param mixed $value
     * @return string Escaped value
     */
    public function escape(mixed $value): string
    {
        return $this->pdo->quote($value);
    }

    /**
     * Get connection info
     *
     * @return array Connection info
     */
    public function getConnectionInfo(): array
    {
        return [
            'server_version' => $this->pdo->getAttribute(PDO::ATTR_SERVER_VERSION),
            'client_version' => $this->pdo->getAttribute(PDO::ATTR_CLIENT_VERSION),
            'server_info' => $this->pdo->getAttribute(PDO::ATTR_SERVER_INFO),
            'charset' => $this->charset,
        ];
    }
}
