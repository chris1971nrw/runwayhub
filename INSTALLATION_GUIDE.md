# runwayhub — Selbstinstallation Guide 📦

## 🎯 Ziel: MVP Installation ohne Docker auf jedem PHP+MySQL Server!

### ✅ Features:
- ✅ **Zero-Dependency**: Läuft ohne Composer/Laravel-Skeleton für Pure PHP MVP  
- ✅ **Auto-Diagnosis**: Prüft PHP version, MySQL extension availability, writable/storage  
- ✅ **DB Migrations**: database_schema.sql automatisch importiert (pilots/schedules/logs/aircrafts)  
- ✅ **REDACTED Security**: .env.example mit placeholders, auto-chmod 0600  

---

## 🚀 Installation:

```bash
curl -LO https://runwayhub.run/install.php || wget install.php 
sudo mv install.php /usr/local/sbin/runwayhub-install  
sudo chmod +x /usr/local/sbin/runwayhub-install  
sudo /usr/local/sbin/runwayhub-install
http://www.example.com/install.php
```

---

## 📋 Voraussetzungen:  

### Minimal Requirements (MVP):
- PHP >= 8.1 (Laravel 10 compatibility)
- MySQL/MariaDB Extension (php-mysql)  
- Writeable Storage Directory /storage/logs/  
- Database credentials in .env (REDACTED placeholders filled!)  

### Optional:
- Composer (für Laravel Skeleton, optional skip)  
- Apache/Nginx Mod_rewrite (Standard PHP Stack)  

---

## ⚙️ Deployment:

1. Upload `/opt/data/runwayhub/` zum Webserver Root oder Alias-Pfad!
2. Öffne `http://www.example.com/install.php` im Browser oder via CLI!
3. .env wird mit REDACTED DB credentials automatisch erstellt  
4. Database Schema importiert (pilots, schedules, logs, aircrafts)  

---

## 📖 Post-Install:

### Testing:
```bash
# Laravel Skeleton optional (pure PHP MVP):
php -S 0.0.0.0:8000 > /dev/null &
curl localhost:8000/pilots/dashboard  # Alpine.js UI  
curl localhost:8000/admin/finance    # Chart.js analytics  

# ACARS Worker (optional Laravel cron job):
crontab -e → */5 * * * * php artifact/process-flight-logs  

# GitHub PR Policy (nur Admin Update Click!):
git clone https://github.com/runwayhub/runwayhub.git  
git checkout main && git pull origin main  # Nur Admin allowed!  
```

---

## 🛑 Security Notes:

1. 🔑 **REDACTED**: .env nie commiten — production deploy DB credentials manuell ersetzen!
2. ⏳ **Admin Only**: Update Click Dashboard — GitHub Webhook check für Versions-Tagging  
3. 🔄 **Time-Lock**: Bookings auto-expires nach 24h (abteilung!)  

---

## 📂 Dateisystem:

```bash
/opt/data/runwayhub/          # Development Root  
├── install.php               ✅ Auto Installer  
├── .env.example              ⚠️  NEVER commit — DB credentials placeholders  
├── database_schema.sql       ✅ MVP Schema (4 Tabellen)  
└── views/                    ✈️  Alpine.js UI Templates  
    ├── pilots/dashboard.blade.php  
    └── admin/finance.blade.php  

/usr/local/www/example.com/   # Webserver Deployment Ziel!  
```

---

## 🧪 Test-Suite:

```bash
# Health Check Post-Install:
curl -I http://localhost/pilots/dashboard  # 200 Alpine.js loaded?  
grep REDACTED /opt/data/runwayhub/.env    # Credentials redacted!  

# DB Connection Test:
php -r "print_r(mysqli_connect('localhost', 'user', 'pass'));"  

# Laravel Skeleton optional (skip für pure PHP MVP):
composer create-project --prefer-dist laravel/laravel .  # Optional!  
```

---

## 🔧 Troubleshooting:

### 🚫 "PHP >= 8.1 not installed":
```bash
docker pull php:8.2-fpm  # Alternative mit Docker  
apt-get install -y php8.2-cli  
```

### 🦈 "Database connection failed":
```bash
env | grep DB_           # Check REDACTED credentials in .env  
vi /opt/data/runwayhub/.env   # Fix placeholders!  
```

---

## ✨ MVP Roadmap:

- ✅ **Install**: install.php ready (0 Dependencies!)  
- ✅ **MVP Core**: Database, Models, Controllers, UI Templates  
- ⏳ **ACARS Worker**: Laravel Cron Job (Optional Phase 2)  
- ⏳ **OAuth API**: Flight tracking end points (Future OAuth expansion)  

---

## 📞 Support:

🖋️ Fehlerbericht → `UserReportController.php` endpoint!  
🔄 Git Issue → Nur Admin darf pushen!  
📚 Dokumentationen: /opt/data/runwayhub/*.md  
