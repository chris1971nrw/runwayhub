# RunwayHub Deployment Guide

## Quick Deploy

```bash
git clone https://github.com/chris1971nrw/runwayhub.git
cd runwayhub
php -S localhost:8000 -t public
```

## Production Deployment

### Prerequisites

- **PHP:** 8.1 or higher
- **Database:** SQLite or MySQL/MariaDB
- **Web Server:** Apache, Nginx, or PHP SAPI
- **Composer:** For dependency management
- **Git:** For version control

### Step-by-Step Deployment

#### 1. Clone Repository

```bash
git clone https://github.com/chris1971nrw/runwayhub.git
cd runwayhub
```

#### 2. Install Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

#### 3. Configure Database

**SQLite (Recommended for beginners):**

```bash
# Create SQLite database
php code/database/migrate-all.php

# Or use seed file
php code/database/seed-sample-data.php
```

**MySQL/MariaDB:**

```bash
# Create database
CREATE DATABASE runwayhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Import schema
mysql -u your_user -p runwayhub < code/database/schema.sql

# Import data
mysql -u your_user -p runwayhub < code/database/seed-sample-data.sql
```

#### 4. Configure Environment

Create `config.php` or edit existing:

```php
<?php
// config.php
return [
    'database' => [
        'driver' => 'sqlite', // or 'mysql'
        'path' => 'database/database.sqlite',
        // 'host' => 'localhost',
        // 'database' => 'runwayhub',
        // 'username' => 'root',
        // 'password' => '',
    ],
    'app' => [
        'name' => 'RunwayHub',
        'version' => '2.0.3',
        'timezone' => 'Europe/Berlin',
    ],
    'api' => [
        'debug' => false,
        'rate_limit' => 100,
    ],
    'security' => [
        'password_cost' => 12,
        'session_lifetime' => 86400,
    ],
];
```

#### 5. Set Permissions

```bash
chmod 755 -R .
chmod 644 database/*.sqlite
chown -R www-data:www-data .
```

#### 6. Configure Web Server

**Apache (.htaccess):**

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
<IfModule mod_ssl.c>
    Header always set Strict-Transport-Security "max-age=31536000"
</IfModule>
```

**Nginx (nginx.conf snippet):**

```nginx
server {
    listen 80;
    server_name runwayhub.example.com;
    
    root /var/www/runwayhub/public;
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }
}
```

#### 7. Test Deployment

```bash
# Check PHP syntax
php -l public/index.php

# Run migrations
php code/database/migrate-all.php

# Test API endpoints
curl http://localhost:8000/api/health

# Check database connection
php code/tests/DatabaseTest.php
```

#### 8. SSL Setup (Production)

Use Let's Encrypt:

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```

#### 9. Performance Tuning

**Enable caching:**
```bash
# Install OPcache
php -m | grep opcache # Should be enabled

# Configure php.ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_writable_pages=1
```

**Enable GZIP compression:**
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css application/javascript
</IfModule>
```

---

## CI/CD Setup

### GitHub Actions

```yaml
name: Deploy to Production

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
          
      - name: Install dependencies
        run: composer install --no-dev --optimize-autoloader
          
      - name: Run tests
        run: php vendor/bin/phpunit
          
      - name: Deploy
        run: |
          rsync -avz --delete . user@server:/var/www/runwayhub/
          ssh user@server 'cd /var/www/runwayhub && php code/database/migrate-all.php'
```

---

## Environment Variables

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://runwayhub.example.com

DATABASE_URL=sqlite:database/database.sqlite
# or
DATABASE_URL=mysql:host=localhost;dbname=runwayhub

REDIS_URL=redis://localhost:6379

API_KEY=your_api_key_here
```

---

## Monitoring & Alerts

### Health Checks

```bash
# API health check
curl http://localhost/api/health

# Database status
php code/database/check-status.php

# Performance audit
php public/perf-audit.php
```

### Error Logging

```bash
# Check error logs
tail -f storage/logs/*.log

# Clear logs
rm storage/logs/*.log
```

---

## Backups

### Database Backup

```bash
# SQLite
sqlite3 database/database.sqlite ".dump" > backup-$(date +%Y%m%d).sql.gz
gzip database/database.sqlite

# Restore
gunzip -k backup-YYYYMMDD.sql.gz
sqlite3 database/database.sqlite < backup-YYYYMMDD.sql
```

### Full Backup

```bash
tar -czf backup-$(date +%Y%m%d).tar.gz \
    database/ \
    storage/ \
    config/
```

---

## Troubleshooting

### Database Connection Error

```bash
# Check file exists
ls -la database/database.sqlite

# Check permissions
chmod 644 database/database.sqlite

# Verify migrations
php code/database/migrate-all.php
```

### Permission Denied

```bash
chown -R www-data:www-data .
chmod -R 755 .
chmod -R 644 storage/
```

### Memory Limit Exceeded

```bash
# Increase in php.ini
memory_limit = 256M
```

---

## Security Checklist

- [x] HTTPS enabled
- [x] Database credentials secured
- [x] Regular updates applied
- [x] File permissions set correctly
- [x] CSRF protection enabled
- [x] XSS prevention active
- [x] Rate limiting configured
- [x] Error messages disabled in production

---

## Support

- **Documentation:** https://runwayhub.github.io/docs
- **Issues:** https://github.com/chris1971nrw/runwayhub/issues
- **Demo:** https://runwayhub.github.io
- **Email:** demo@airline.com

---

**Last Updated:** 2026-05-28  
**Version:** 2.0.3  
**Status:** Production Ready
