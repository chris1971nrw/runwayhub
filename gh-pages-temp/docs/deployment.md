# Deployment Guide

Complete deployment instructions for RunwayHub.

## 🚀 Quick Start

### Deployment Checklist

- [ ] Install PHP 8.2+
- [ ] Set up MySQL/MariaDB 8.0+
- [ ] Copy project files
- [ ] Configure .env
- [ ] Install Composer dependencies
- [ ] Run migrations
- [ ] Seed demo data (optional)
- [ ] Configure OpenAIP API
- [ ] Set up HTTPS
- [ ] Configure firewall
- [ ] Enable maintenance mode
- [ ] Disable maintenance mode

### Installation Commands

```bash
# Clone repository
git clone https://github.com/chris1971nrw/runwayhub.git
cd runwayhub

# Copy environment file
cp runwayhub/.env.example runwayhub/.env

# Install dependencies
cd runwayhub
composer install

# Configure .env
nano .env
# Edit database credentials, OpenAIP API key

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed demo users (optional)
php artisan db:seed --class=DemoUser

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 📦 Deployment Options

### Option 1: Manual Deployment

1. **Clone or upload files**
   ```bash
   git clone https://github.com/chris1971nrw/runwayhub.git /path/to/runwayhub
   ```

2. **Install Composer**
   ```bash
   cd runwayhub
   composer install
   ```

3. **Configure**
   ```bash
   cp .env.example .env
   nano .env
   ```

4. **Migrate & Seed**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

### Option 2: CI/CD Deployment

Use GitHub Actions or GitLab CI with the following workflow:

```yaml
# .github/workflows/deploy.yml
name: Deploy

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
      - run: composer install
      - run: php artisan migrate
      - run: php artisan config:clear
      - name: Deploy to server
        uses: easingthemes/ssh-deploy@v2
        with:
          SSH_PRIVATE_KEY: ${{ secrets.SSH_KEY }}
          ARGS: "-rvz"
          SOURCE: "."
          DEST: ${{ secrets.SERVER_PATH }}
          EXCLUDE: ".git", "vendor/composer/*"
```

### Option 3: Docker Deployment

```yaml
# docker-compose.yml
version: '3.8'

services:
  app:
    build: .
    ports:
      - "80:80"
    volumes:
      - ./runwayhub:/var/www
    depends_on:
      - mysql

  mysql:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: ${DB_PASSWORD}
      MYSQL_DATABASE: ${DB_NAME}
    volumes:
      - mysql_data:/var/lib/mysql

  redis:
    image: redis:alpine

volumes:
  mysql_data:
```

## 🔒 Security Configuration

### Environment Variables

```env
APP_NAME=RunwayHub
APP_ENV=production
APP_DEBUG=false
APP_URL=https://runwayhub.example.com
LOG_CHANNEL=single

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=runwayhub_production
DB_USERNAME=runwayhub
DB_PASSWORD=secure_password

# OpenAIP
OPENAIP_API_KEY=your_api_key
OPENAIP_CACHE_TTL=300

# Security
APP_KEY=base64:your_generated_key_here
SESSION_SECURE=true
SESSION_ENCRYPT=true
```

### Apache/Nginx Configuration

#### Apache (.htaccess)

```apache
# Security headers
<IfModule mod_headers.c>
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Enable compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css
    AddOutputFilterByType DEFLATE application/javascript application/json
</IfModule>

# Disable access to sensitive files
<FilesMatch "\.(env|sql|log|bak|swp)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

#### Nginx Configuration

```nginx
server {
    listen 80;
    server_name runwayhub.example.com;

    # Redirect to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name runwayhub.example.com;

    ssl_certificate /etc/letsencrypt/live/runwayhub.example.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/runwayhub.example.com/privkey.pem;

    root /var/www/runwayhub/public;
    index index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass php-fpm:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## 📊 Monitoring

### Setup Monitoring Tools

```bash
# Install monitoring
composer require --dev phpstan/phpstan
composer require --dev phpunit/phpunit

# Set up logging
php artisan tinker --execute="Log::channel('stack')->push(new Monolog\Handler\StreamHandler(storage/logs, Monolog\LogLevel::DEBUG));"
```

### Health Check Endpoint

```php
// routes/api.php
Router::get('/health', function() {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toIso8601String(),
        'version' => '2.0.0',
    ]);
});
```

## 🔄 Backup Strategy

### Database Backups

```bash
#!/bin/bash
# backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR=/backups/runwayhub
mysql -u $DB_USER -p$DB_PASS $DB_NAME \
    | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Keep last 30 days
find $BACKUP_DIR -name "*.sql.gz" -mtime +30 -delete
```

### File Backups

```bash
tar -czf /backups/runwayhub_$DATE.tar.gz \
    /var/www/runwayhub/public \
    /var/www/runwayhub/database
```

## 🔧 Maintenance Tasks

### Daily Tasks

```bash
# Run migrations if pending
php artisan migrate --force

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Sync OpenAIP
php src/artisan openaip:sync
```

### Weekly Tasks

- Review logs
- Check disk space
- Review user activity
- Update dependencies

### Monthly Tasks

- Security audit
- Performance testing
- Backup verification
- User permission review

## 📝 Troubleshooting

### Common Issues

**Issue:** "Class not found"
```bash
composer dump-autoload
```

**Issue:** "Database connection failed"
```bash
# Check credentials in .env
nano .env
# Ensure DB_HOST, DB_DATABASE, etc. are correct
```

**Issue:** "Route not found"
```bash
php artisan route:clear
```

**Issue:** "Permission denied"
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 🌐 Domain Setup

### DNS Configuration

```
runwayhub.example.com.    IN  A   192.0.2.1
www.runwayhub.example.com. IN  A   192.0.2.1
```

### SSL Certificate

```bash
# Using Let's Encrypt
certbot certificates
certbot renew --dry-run
```

## 📚 References

- [Laravel Deployment](https://laravel.com/docs/deployment)
- [PHP Security](https://php.org/security)
- [GDPR Compliance](https://gdpr.eu/)

---

**Last Updated:** 2026-05-26
**Version:** 2.0.0
**Status:** Active
