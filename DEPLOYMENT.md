# RunwayHub - Deployment Guide

**Version:** 1.0.0  
**Datum:** 2026-05-28  
**Status:** Production Ready

---

## 📋 Inhaltsverzeichnis

1. [Voraussetzungen](#voraussetzungen)
2. [Deployment Checklist](#deployment-checklist)
3. [Lokales Deployment](#lokales-deployment)
4. [Production Deployment](#production-deployment)
5. [Docker Deployment](#docker-deployment)
6. [Nginx Konfiguration](#nginx-konfiguration)
7. [SSL/TLS Setup](#ssl-tls-setup)
8. [Monitoring](#monitoring)
9. [Backup Strategie](#backup-strategie)

---

## Voraussetzungen

### System Requirements

- **OS:** Linux/macOS/Windows (WSL)
- **RAM:** 512MB+
- **CPU:** 2+ Cores
- **Storage:** 500MB+

### Dependencies

- **PHP:** 8.3+
- **Composer:** 2.0+
- **Database:** SQLite oder MySQL 8.0+
- **Web Server:** Apache/Nginx

### Tools

```bash
# Installieren
sudo apt-get update
sudo apt-get install -y \
    php \
    php-cli \
    php-curl \
    php-mbstring \
    php-xml \
    php-mysql \
    php-sqlite3 \
    composer

# Prüfen
php -v
composer --version
```

---

## Deployment Checklist

### Pre-Deployment

- [ ] Code Review abgeschlossen
- [ ] Unit Tests (PHPUnit) durchgelaufen
- [ ] Security Scan (Composer Audit)
- [ ] .env Datei bereinigt (Passwörter!)
- [ ] Database Schema migriert
- [ ] Static Assets optimiert

### Deployment

- [ ] Code deployen
- [ ] Dependencies installieren
- [ ] Migrationen ausführen
- [ ] Seed Daten einfügen
- [ ] Config prüfen
- [ ] Permissions setzen

### Post-Deployment

- [ ] Smoke Tests durchführen
- [ ] API Endpoints testen
- [ ] Logs prüfen
- [ ] Monitoring aktivieren
- [ ] Backup konfigurieren

---

## Lokales Deployment

### Schritt 1: Code Deployen

```bash
cd /home/christoph/.openclaw/workspace-runwayhub/runwayhub

# Copy code
cp -r /path/to/runwayhub/* .

# Dependencies
composer install --no-dev

# .env Datei
cp .env.example .env
nano .env
```

### Schritt 2: Configuration

```bash
# SQLite (Standard)
# Keine weitere Konfiguration nötig

# MySQL (optional)
DB_DRIVER=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=runwayhub
DB_USERNAME=root
DB_PASSWORD=***
```

### Schritt 3: Migrationen

```bash
php src/cli/migrate.php

# Seed Daten
php src/cli/seed.php
```

### Schritt 4: Starten

```bash
php -S localhost:8000 -t public
```

### Schritt 5: Testen

```bash
# Landing Page
curl http://localhost:8000/

# API Status
curl http://localhost:8000/api-status.php

# Login
curl -X POST http://localhost:8000/api/login-pilot.php \
  -H "Content-Type: application/json" \
  -d '{"callsign":"demo_pilot","password":"***"}'
```

---

## Production Deployment

### Schritt 1: Backup

```bash
# Database Backup
mysqldump -u root -p runwayhub > backup.sql

# File Backup
tar -czf runwayhub-backup.tar.gz /path/to/runwayhub/

# Clean Backup
rm -rf storage/*.cache
rm -rf storage/*.lock
```

### Schritt 2: Deployen

```bash
# Upload
rsync -avz --exclude='.env' --exclude='*.log' \
    /local/runwayhub/ user@server:/var/www/runwayhub/

# Dependencies
ssh user@server "cd /var/www/runwayhub && composer install --no-dev"

# Migrationen
ssh user@server "cd /var/www/runwayhub && php src/cli/migrate.php"

# Permissions
ssh user@server "cd /var/www/runwayhub && chmod -R 755 storage/ bootstrap/cache/"
```

### Schritt 3: Environment

```bash
# .env setzen
ssh user@server "cd /var/www/runwayhub && cp .env.example .env"

# Security
nano .env
# Production settings setzen
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### Schritt 4: Web Server Config

```bash
# Apache .htaccess
cat > /var/www/runwayhub/.htaccess << EOF
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteRule ^index\.html$ - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-l
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
EOF

# Nginx Config (siehe unten)
```

### Schritt 5: SSL/TLS

```bash
# Certbot
sudo apt-get install certbot python3-certbot-apache
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# Renewal
sudo certbot renew
```

### Schritt 6: Monitoring

```bash
# Installieren
sudo apt-get install -y htop iotop iostat

# Log Watch
tail -f /var/www/runwayhub/storage/logs/laravel.log

# Monitoring
curl http://localhost:8000/api/status
```

---

## Docker Deployment

### docker-compose.yml

```yaml
version: '3.8'

services:
  mysql:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: ${DB_PASSWORD}
      MYSQL_DATABASE: runwayhub
    volumes:
      - mysql-data:/var/lib/mysql
    ports:
      - "${DB_PORT:-3306}:3306"
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      interval: 10s
      timeout: 5s
      retries: 5

  runwayhub:
    build: .
    depends_on:
      mysql:
        condition: service_healthy
    environment:
      DB_DRIVER: mysql
      DB_HOST: mysql
      DB_DATABASE: runwayhub
      DB_USERNAME: ${DB_USERNAME}
      DB_PASSWORD: ${DB_PASSWORD}
    volumes:
      - ./runwayhub:/var/www/html
    ports:
      - "${PORT:-8000}:8000"
    command: php -S 0.0.0.0:8000 -t public

volumes:
  mysql-data:
```

### Build & Run

```bash
# Docker Build
docker-compose build

# Starten
docker-compose up -d

# Logs
docker-compose logs -f runwayhub

# Console
docker-compose exec runwayhub php artisan tinker
```

---

## Nginx Konfiguration

### /etc/nginx/sites-available/runwayhub

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    
    # Redirect HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    
    # SSL
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    include /etc/letsencrypt/options-ssl-nginx.conf;
    
    # Root
    root /var/www/runwayhub/public;
    index index.php;
    
    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    
    # PHP
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    # Static Files
    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
    
    # Logs
    access_log /var/log/nginx/runwayhub.access.log;
    error_log /var/log/nginx/runwayhub.error.log;
}
```

### Aktivieren

```bash
sudo ln -s /etc/nginx/sites-available/runwayhub /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## SSL/TLS Setup

### Certbot

```bash
# Installieren
sudo apt-get install certbot python3-certbot-nginx

# Certificate erstellen
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo certbot renew --dry-run
```

### Apache

```bash
# a2ensite
sudo a2ensite yourdomain.com.conf
sudo a2enconf ssl-ssl

# Restart
sudo systemctl restart apache2
```

---

## Monitoring

### Logging

```bash
# Laravel Logs
tail -f /var/www/runwayhub/storage/logs/laravel.log

# Nginx Logs
tail -f /var/log/nginx/*.log

# PHP Errors
tail -f /var/log/php-fpm/*.log
```

### Health Checks

```bash
# API Status
curl http://localhost:8000/api/status

# Database
mysql -u root -p -e "SHOW PROCESSLIST;"

# PHP-FPM
systemctl status php8.3-fpm
```

### Uptime Monitor

```bash
# Uptime Kuma
docker run -d --name uptime-kuma \
  -p 3001:3001 \
  mitmochi/uptime-kuma \
  --db-file ./config/config.db
```

---

## Backup Strategie

### Automatische Backups

```bash
# Cron für Backups
# /etc/cron.d/runwayhub-backup

0 2 * * * cd /var/www/runwayhub && mysqldump -u root -p runwayhub > /backup/runwayhub-$(date +%Y%m%d).sql

# Cleanup alte Backups
0 3 * * * find /backup -name '*.sql' -mtime +7 -delete
```

### Backup Script

```bash
#!/bin/bash
BACKUP_DIR=/backup
DATE=$(date +%Y%m%d_%H%M%S)
mysql -u root -p -D runwayhub | gzip > $BACKUP_DIR/runwayhub-$DATE.sql.gz

# Cleanup
find $BACKUP_DIR -name '*.sql.gz' -mtime +7 -delete
```

---

## Troubleshooting

### Fehler 404

```bash
# .htaccess prüfen
cat .htaccess

# Root dir prüfen
ls -la public/
```

### PHP Errors

```bash
# Debug aktivieren
nano .env
APP_DEBUG=true
LOG_CHANNEL=stack

# Logs prüfen
tail -f storage/logs/laravel.log
```

### Database Errors

```bash
# Migrationen
php src/cli/migrate.php

# Datenbank reconnect
php src/cli/migrate.php --force
```

### Performance Issues

```bash
# Opcache prüfen
php -i | grep opcache

# Memory Leaks
php -m | grep -i zend
```

---

## Best Practices

### Security

1. **Environment Variables**
   - Passwörter in .env
   - APP_DEBUG=false in Production
   - APP_ENV=production

2. **File Permissions**
   ```bash
   chmod -R 755 storage/ bootstrap/cache/
   chown -R www-data:www-data storage/ bootstrap/cache/
   ```

3. **Dependencies**
   ```bash
   composer audit
   composer outdated
   ```

4. **SSL/TLS**
   - Immer HTTPS
   - Certificate Renewal
   - HSTS Header

### Performance

1. **OpCache**
   ```ini
   opcache.enable=1
   opcache.memory_consumption=256
   opcache.max_accelerated_files=4000
   ```

2. **Database**
   - Indexes optimieren
   - Queries optimieren
   - Connection Pooling

3. **Caching**
   ```bash
   php artisan config:cache
   php artisan route:cache
   ```

---

## Support

- **GitHub Issues:** https://github.com/chris1971nrw/runwayhub/issues
- **Documentation:** https://chris1971nrw.github.io/runwayhub/
- **Demo:** http://localhost:8000

---

**Built with ❤️ by @chris1971nrw**

**Licensed under MIT**

**© 2026 RunwayHub**
