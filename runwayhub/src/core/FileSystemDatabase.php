<?php

declare(strict_types=1);

namespace RunwayHub\Core;

/**
 * FileSystemDatabase - Database abstraction using JSON file storage
 * Alternative to SQLite for environments without PDO drivers
 */
class FileSystemDatabase
{
    private string $storagePath;
    private string $indexPath;
    
    public function __construct(string $basePath)
    {
        $this->storagePath = $basePath . '/data.db.json';
        $this->indexPath = $basePath . '/index.db.json';
    }
    
    private function getStorage(): array
    {
        if (!file_exists($this->storagePath)) {
            return [];
        }
        return json_decode(file_get_contents($this->storagePath), true) ?: [];
    }
    
    private function setStorage(array $data): void
    {
        file_put_contents($this->storagePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
    
    private function getIndex(): array
    {
        if (!file_exists($this->indexPath)) {
            return [];
        }
        return json_decode(file_get_contents($this->indexPath), true) ?: [];
    }
    
    private function setIndex(array $data): void
    {
        file_put_contents($this->indexPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
    
    public function get(string $table, string $primaryKey = 'id'): ?array
    {
        $data = $this->getStorage();
        if (!isset($data[$table])) {
            return null;
        }
        $records = $data[$table];
        
        foreach ($records as $record) {
            if ($record[$primaryKey] === $primaryKey) {
                return $record;
            }
        }
        return null;
    }
    
    public function getAll(string $table): array
    {
        $data = $this->getStorage();
        return $data[$table] ?? [];
    }
    
    public function insert(string $table, array $data): string
    {
        $tableData = $this->getAll($table);
        
        // Auto-increment ID
        if (!isset($data['id'])) {
            $data['id'] = $tableData['id'] ?? (count($tableData) + 1);
        }
        
        $tableData[$data['id']] = $data;
        $this->setStorage($this->getStorage());
        
        // Update index
        $idx = $this->getIndex();
        $idx[$table][] = $data['id'];
        $this->setIndex($idx);
        
        return $data['id'];
    }
    
    public function update(string $table, string $primaryKey, array $data): bool
    {
        $tableData = &$this->getStorage();
        if (!isset($tableData[$table])) {
            return false;
        }
        
        if (!isset($tableData[$table][$primaryKey])) {
            return false;
        }
        
        $tableData[$table][$primaryKey] = array_merge($tableData[$table][$primaryKey], $data);
        $this->setStorage($tableData);
        
        return true;
    }
    
    public function delete(string $table, string $primaryKey): bool
    {
        $tableData = &$this->getStorage();
        if (!isset($tableData[$table])) {
            return false;
        }
        
        unset($tableData[$table][$primaryKey]);
        $this->setStorage($tableData);
        
        // Update index
        $idx = &$this->getIndex();
        if (isset($idx[$table])) {
            $idx[$table] = array_filter($idx[$table], fn($id) => $id !== $primaryKey);
            $this->setIndex($idx);
        }
        
        return true;
    }
    
    public function where(string $table, string $conditions, array $params = []): array
    {
        $records = $this->getAll($table);
        $results = [];
        
        foreach ($records as $record) {
            if (preg_match($conditions, $record, $matches, 0, $params)) {
                $results[] = $record;
            }
        }
        
        return $results;
    }
    
    public function count(string $table): int
    {
        return count($this->getAll($table));
    }
    
    public function clear(): void
    {
        $this->setStorage([]);
        $this->setIndex([]);
        if (file_exists($this->storagePath)) {
            unlink($this->storagePath);
        }
        if (file_exists($this->indexPath)) {
            unlink($this->indexPath);
        }
    }
}
