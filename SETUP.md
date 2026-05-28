# RunwayHub - Setup Guide

## 📋 Prerequisites

- **PHP:** 8.3.6+
- **Database:** SQLite or MySQL
- **Web Server:** Apache/Nginx
- **Composer:** For dependencies (optional)

---

## 🚀 Installation

### 1. Clone Repository

```bash
git clone https://github.com/chris1971nrw/runwayhub.git
cd runwayhub
```

### 2. Configure Database

```bash
# Copy database schema
cp runwayhub/database/migrations/* .

# Or use CLI tool
php runwayhub/cli/migrate.php install
```

### 3. Configure Application

```bash
# Copy config example
cp config.example.php config.php

# Edit config.php with your settings
nano config.php
```

### 4. Set Permissions

```bash
chmod 755 storage/
chmod 777 storage/cache/
chmod 777 storage/logs/
```

### 5. Start Server

```bash
php -S localhost:8000 -t public
```

---

## 🔧 Configuration

### Database

Edit `config.php`:

```php
'database' => [
    'driver' => 'sqlite',
    'database' => 'runwayhub.sqlite',
],
```

### Weather API

```php
'weather' => [
    'provider' => 'wttrin',
    'cache_ttl' => 300,
],
```

### SMTP (Optional)

```php
'smtp' => [
    'enabled' => true,
    'host' => 'smtp.example.com',
    'port' => 587,
],
```

---

## 📱 Demo Access

```
Admin:    demo_admin     / admin123
Pilot:    demo_pilot     / pilot123
Guest:    demo_guest     / guest123
```

---

## 🔐 Security

- Enable HTTPS in production
- Set secure cookies
- Configure CSP headers
- Enable rate limiting
- Regular updates

---

## 📚 Next Steps

1. Review [Documentation](README.md)
2. Configure your settings
3. Test with demo accounts
4. Deploy to production

---

**Version:** 2.0.3  
**Status:** ✅ Production Ready
