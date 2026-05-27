# Datenbank‑Migrations

## Struktur

```
database/migrations/
├── README.md              # Diese Datei
├── 001_create_users.php   # Benutzer‑Tabelle
├── 002_create_airlines.php # Airlines‑Tabelle
├── 003_create_roles.php   # Rollen‑Tabelle
└── 004_create_aircrafts.php # Flugzeug‑Tabelle
```

## Schema

### users
- id (PK, auto_increment)
- username (unique, varchar 50)
- email (unique, varchar 100)
- password_hash (varchar 255)
- role_id (FK zu roles)
- first_name (varchar 100)
- last_name (varchar 100)
- avatar (varchar 255)
- status (enum: active,banned,deleted)
- created_at (timestamp)
- updated_at (timestamp)

### airlines
- id (PK, auto_increment)
- name (unique, varchar 100)
- callsign (unique, varchar 20)
- iata_code (varchar 3, unique)
- icao_code (varchar 4, unique)
- logo (varchar 255)
- description (text)
- country (varchar 100)
- founded (date)
- status (enum: active,inactive)

### roles
- id (PK, auto_increment)
- name (unique, varchar 50)
- permissions (json)

### aircrafts
- id (PK, auto_increment)
- callsign (unique, varchar 20)
- registration (unique, varchar 20)
- airline_id (FK zu airlines)
- type (varchar 100)
- capacity (integer)
- status (enum: active,maintenance,stored)
- created_at (timestamp)
- updated_at (timestamp)

### flights
- id (PK, auto_increment)
- flight_number (varchar 10, unique)
- origin (varchar 100)
- destination (varchar 100)
- departure_time (datetime)
- arrival_time (datetime)
- aircraft_id (FK zu aircrafts)
- pilot_id (FK zu users)
- status (enum: scheduled,boarding,active,landed,canceled)
- created_at (timestamp)
- updated_at (timestamp)

### bookings
- id (PK, auto_increment)
- flight_id (FK zu flights)
- user_id (FK zu users)
- passenger_name (varchar 100)
- passenger_email (varchar 100)
- price (decimal)
- booking_date (datetime)
- status (enum: confirmed,canceled,completed)

###pireps
- id (PK, auto_increment)
- flight_id (FK zu flights)
- pilot_id (FK zu users)
- report_text (text)
- weather_conditions (text)
- created_at (timestamp)

### maintenance
- id (PK, auto_increment)
- aircraft_id (FK zu aircrafts)
- type (enum:scheduled,unscheduled)
- description (text)
- scheduled_date (datetime)
- actual_date (datetime)
- cost (decimal)
- status (enum:scheduled,completed,overdue)

## Migration‑Beispiel

```php
<?php

use RunwayHub\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->table('users')
            ->addColumn('username', 'string', ['limit' => 50])
            ->addColumn('email', 'string', ['limit' => 100])
            ->addColumn('password_hash', 'string', ['limit' => 255])
            ->addColumn('role_id', 'integer', ['null' => false])
            ->addColumn('first_name', 'string', ['limit' => 100])
            ->addColumn('last_name', 'string', ['limit' => 100])
            ->addColumn('avatar', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('status', 'enum', ['values' => ['active', 'banned', 'deleted']])
            ->addColumn('created_at', 'datetime', ['default' => 'NOW()'])
            ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'])
            ->create();
    }

    public function down()
    {
        $this->table('users')->drop();
    }
}
```

## Ausführen

```bash
php bin/migrate create 001_create_users.php
php bin/migrate up
php bin/migrate down --step=1
```

---

_Dokument automatisch gepflegt von DatabaseAgent._
