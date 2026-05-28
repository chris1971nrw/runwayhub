<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class AddAdmins extends AbstractMigration
{
    public function change()
    {
        // Admins Tabelle erstellen
        $this->table('admins', [
            'id' => 'integer',
            'username' => 'string(50)',
            'password' => 'string(255)',
            'email' => 'string(100)',
            'role' => 'string(20)',
            'active' => 'boolean',
            'created_at' => 'datetime',
        ])
            ->addIndex(['username'])
            ->create();
        
        // Admin User erstellen
        $username = 'admin';
        $password = 'admin123';
        $email = 'admin@example.com';
        
        $adminHash = password_hash($password, PASSWORD_DEFAULT);
        
        $this->query("INSERT OR REPLACE INTO admins (username, password, email, role, created_at) 
                     VALUES (?, ?, ?, 'admin', datetime('now'));", 
                     [$username, $adminHash, $email]);
    }
}
