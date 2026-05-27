# Database Documentation

Complete database schema and design documentation for RunwayHub.

## 🗄️ Database Overview

RunwayHub uses MySQL/MariaDB 8.0+ with the following core structure:

- **9 Core Tables** - Primary business data
- **5 OpenAIP Tables** - Aviation data
- **15+ Supporting Tables** - User, audit, configuration

## 📊 Core Tables

### 1. users

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','staff','pilot','guest') NOT NULL DEFAULT 'guest',
    avatar VARCHAR(255) DEFAULT NULL,
    timezone VARCHAR(50) DEFAULT 'Europe/Berlin',
    language VARCHAR(10) DEFAULT 'en',
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 2. airlines

```sql
CREATE TABLE airlines (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(10) UNIQUE NOT NULL,
    country VARCHAR(2) NOT NULL,
    city VARCHAR(100) NOT NULL,
    iata VARCHAR(10) DEFAULT NULL,
    icao VARCHAR(10) DEFAULT NULL,
    callsign VARCHAR(50) DEFAULT NULL,
    airline_type ENUM('low_cost','full_service','cargo','regional') DEFAULT 'full_service',
    founded_year INT DEFAULT NULL,
    logo VARCHAR(255) DEFAULT NULL,
    website VARCHAR(255) DEFAULT NULL,
    status ENUM('active','inactive','defunct') DEFAULT 'active',
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_code (code),
    INDEX idx_country (country),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3. fleet

```sql
CREATE TABLE fleet (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    airline_id BIGINT UNSIGNED NOT NULL,
    aircraft_type VARCHAR(50) NOT NULL,
    model VARCHAR(100) DEFAULT NULL,
    manufacturer VARCHAR(100) NOT NULL,
    registration VARCHAR(20) UNIQUE NOT NULL,
    serial_number VARCHAR(50) DEFAULT NULL,
    icao_type_code VARCHAR(10) DEFAULT NULL,
    capacity INT DEFAULT NULL,
    max_range_km INT DEFAULT NULL,
    max_speed_knots INT DEFAULT NULL,
    status ENUM('active','stored','maintenance','scrapped') DEFAULT 'active',
    purchase_date DATE DEFAULT NULL,
    purchase_price DECIMAL(12,2) DEFAULT NULL,
    hours_flight DECIMAL(10,2) DEFAULT 0,
    cycles_flight DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (airline_id) REFERENCES airlines(id) ON DELETE CASCADE,
    INDEX idx_registration (registration),
    INDEX idx_airline (airline_id),
    INDEX idx_type (aircraft_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 4. pilots

```sql
CREATE TABLE pilots (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    license_type ENUM('ATPL','CPL','FPL','commercial') NOT NULL,
    license_number VARCHAR(50) UNIQUE NOT NULL,
    license_country VARCHAR(2) NOT NULL,
    license_expiry DATE NOT NULL,
    type_rating TEXT DEFAULT NULL,
    ratings JSON DEFAULT NULL,
    total_flight_hours DECIMAL(10,2) DEFAULT 0,
    airline_id BIGINT UNSIGNED DEFAULT NULL,
    status ENUM('active','retired','medical') DEFAULT 'active',
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (airline_id) REFERENCES airlines(id) ON DELETE SET NULL,
    INDEX idx_license (license_number),
    INDEX idx_airline (airline_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 5. routes

```sql
CREATE TABLE routes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    airline_id BIGINT UNSIGNED NOT NULL,
    from_airport VARCHAR(10) NOT NULL,  -- IATA code
    to_airport VARCHAR(10) NOT NULL,
    distance_km DECIMAL(10,2) NOT NULL,
    flight_time_minutes INT NOT NULL,
    route_type ENUM('scheduled','charter','cargo','mixed') DEFAULT 'scheduled',
    status ENUM('active','inactive','planned') DEFAULT 'active',
    frequency VARCHAR(20) DEFAULT NULL,  -- e.g., "daily", "twice_daily"
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (airline_id) REFERENCES airlines(id) ON DELETE CASCADE,
    UNIQUE KEY unique_route (airline_id, from_airport, to_airport),
    INDEX idx_airport_from (from_airport),
    INDEX idx_airport_to (to_airport)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 6. flights

```sql
CREATE TABLE flights (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    flight_number VARCHAR(10) NOT NULL,
    airline_id BIGINT UNSIGNED NOT NULL,
    route_id BIGINT UNSIGNED NOT NULL,
    pilot_id BIGINT UNSIGNED DEFAULT NULL,
    aircraft_registration VARCHAR(20) DEFAULT NULL,
    aircraft_type VARCHAR(50) DEFAULT NULL,
    scheduled_departure DATETIME NOT NULL,
    scheduled_arrival DATETIME NOT NULL,
    actual_departure DATETIME DEFAULT NULL,
    actual_arrival DATETIME DEFAULT NULL,
    status ENUM('scheduled','in_air','arrived','cancelled','delayed') DEFAULT 'scheduled',
    delay_minutes INT DEFAULT 0,
    weather_conditions VARCHAR(100) DEFAULT NULL,
    fuel_burn_kg DECIMAL(10,2) DEFAULT NULL,
    distance_actual DECIMAL(10,2) DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    pirep_id BIGINT UNSIGNED DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (airline_id) REFERENCES airlines(id) ON DELETE CASCADE,
    FOREIGN KEY (route_id) REFERENCES routes(id) ON DELETE CASCADE,
    FOREIGN KEY (pilot_id) REFERENCES pilots(id) ON DELETE SET NULL,
    FOREIGN KEY (pirep_id) REFERENCES pireps(id) ON DELETE SET NULL,
    INDEX idx_flight_number (flight_number),
    INDEX idx_scheduled_departure (scheduled_departure),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 7. bookings

```sql
CREATE TABLE bookings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    flight_id BIGINT UNSIGNED NOT NULL,
    passenger_name VARCHAR(255) NOT NULL,
    passenger_email VARCHAR(255) NOT NULL,
    seat_number VARCHAR(5) DEFAULT NULL,
    fare_class ENUM('economy','premium','first') DEFAULT 'economy',
    status ENUM('confirmed','cancelled','completed','no_show') DEFAULT 'confirmed',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_method VARCHAR(50) DEFAULT 'credit_card',
    payment_status ENUM('pending','paid','refunded') DEFAULT 'pending',
    total_amount DECIMAL(10,2) NOT NULL,
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (flight_id) REFERENCES flights(id) ON DELETE CASCADE,
    INDEX idx_flight (flight_id),
    INDEX idx_email (passenger_email),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 8. pireps

```sql
CREATE TABLE pireps (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    flight_id BIGINT UNSIGNED NOT NULL,
    pilot_name VARCHAR(255) NOT NULL,
    pilot_icao VARCHAR(10) NOT NULL,
    timestamp TIMESTAMP NOT NULL,
    altitude_ft INT DEFAULT NULL,
    latitude DECIMAL(10,8) DEFAULT NULL,
    longitude DECIMAL(10,8) DEFAULT NULL,
    wind_direction INT DEFAULT NULL,
    wind_speed_knots INT DEFAULT NULL,
    temp_c DECIMAL(6,2) DEFAULT NULL,
    visibility_sm INT DEFAULT NULL,
    weather_phenomena VARCHAR(255) DEFAULT NULL,
    remarks TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (flight_id) REFERENCES flights(id) ON DELETE CASCADE,
    INDEX idx_timestamp (timestamp),
    INDEX idx_location (latitude, longitude)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 9. statistics

```sql
CREATE TABLE statistics (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    period_date DATE NOT NULL,
    metric_type VARCHAR(50) NOT NULL,
    airline_id BIGINT UNSIGNED DEFAULT NULL,
    value DECIMAL(12,2) NOT NULL,
    unit VARCHAR(20) DEFAULT 'count',
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (airline_id) REFERENCES airlines(id) ON DELETE SET NULL,
    UNIQUE KEY unique_stat (period_date, metric_type, airline_id),
    INDEX idx_period (period_date),
    INDEX idx_metric (metric_type),
    INDEX idx_airline (airline_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## 🌍 OpenAIP Tables

### airports_openaip, waypoints_openaip, airways_openaip, navaids_openaip

See [openaip.md](openaip.md) for detailed OpenAIP schema documentation.

## 🔑 Primary Keys

All tables use `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY` for performance with large datasets.

## 🔗 Foreign Keys

Referential integrity enforced with cascading deletes where appropriate.

## 📈 Indexes

Strategic indexes on frequently queried columns for performance optimization.

## 🔒 Security

- **Encrypted passwords** - bcrypt hashing
- **SSL/TLS** - Encrypted connections
- **Input validation** - Prevent injection attacks
- **Prepared statements** - PDO prepared statements only

## 📊 Migrations

Located in `database/migrations/`:

- `20260526000001_create_openaip_tables.sql`
- Pending migrations for new features

## 🧪 Testing

```bash
# Run database tests
vendor/bin/phpunit tests/Database/

# Check migrations
php artisan migrate:status

# Rollback last migration
php artisan migrate:rollback --step=1
```

---

**Last Updated:** 2026-05-26
**Version:** 2.0.0
**Status:** Active
