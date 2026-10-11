#!/bin/bash

# runwayhub - Ein-Klick-Installation für PHP + MySQL Server
# Läuft auf jedem Server mit PHP 8.1+ und MySQL/MariaDB

set -e

echo "=========================================="
echo "🌉 runwayhub - Installation"
echo "=========================================="
echo ""

# Farbeinstellungen für Ausgabe
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Prüfe ob PHP installiert ist
if ! command -v php &> /dev/null; then
    echo -e "${RED}❌ Fehler: PHP ist nicht installiert!${NC}"
    echo "Bitte installiere PHP 8.1+ zuerst:"
    echo "  Ubuntu/Debian: apt-get update && apt-get install php8.1"
    echo "  CentOS/RHEL: yum install php"
    exit 1
fi

echo -e "${GREEN}✅ PHP ist installiert${NC}"
php --version

# Prüfe ob MySQL/MariaDB installiert ist
if ! command -v mysql &> /dev/null; then
    echo -e "${RED}❌ Fehler: MySQL/MariaDB ist nicht installiert!${NC}"
    echo "Bitte installiere MySQL/MariaDB zuerst:"
    echo "  Ubuntu/Debian: apt-get install mariadb-server"
    echo "  CentOS/RHEL: yum install mariadb-server"
    exit 1
fi

echo -e "${GREEN}✅ MySQL/MariaDB ist installiert${NC}"
mysql --version

# Installiere benötigte PHP Extensions (falls nicht vorhanden)
echo ""
echo "📦 Installiere benötigte PHP Extensions..."

php_packages="mbstring xml zip gd curl pdo_mysql"

for package in $php_packages; do
    if ! php -m | grep -q "$package"; then
        echo -e "${YELLOW}⚠️  ${package} nicht gefunden, installiere...${NC}"
        
        # Prüfe Paketmanager
        if command -v apt-get &> /dev/null; then
            sudo apt-get update && sudo apt-get install -y php8.1-${package}
        elif command -v yum &> /dev/null; then
            sudo yum install -y php-${package}
        elif command -v dnf &> /dev/null; then
            sudo dnf install -y php-${package}
        fi
    else
        echo -e "${GREEN}✅ ${package} ist vorhanden${NC}"
    fi
done

echo ""
echo "📦 Installiere Composer..."

if [ ! -d "/tmp/composer" ]; then
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
fi

echo -e "${GREEN}✅ Composer ist installiert${NC}"
composer --version

# Erstelle Datenbank
echo ""
echo "🗄️  Konfiguriere MySQL Datenbank..."

mysql -u root -e "CREATE DATABASE IF NOT EXISTS runwayhub_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo -e "${GREEN}✅ Datenbank 'runwayhub_db' erstellt${NC}"

# Kopiere .env Datei
echo ""
echo "📝 Konfiguriere Environment..."

if [ ! -f ".env" ]; then
    cp .env.example .env
    
    # Generiere Laravel APP_KEY
    php artisan key:generate --force
    
    echo -e "${GREEN}✅ .env Datei erstellt${NC}"
else
    echo -e "${YELLOW}⚠️  .env Datei existiert bereits${NC}"
fi

# Installiere Composer Dependencies
echo ""
echo "📦 Installiere Laravel Dependencies..."

if [ -d "vendor" ]; then
    echo -e "${GREEN}✅ Dependencies sind bereits installiert${NC}"
else
    composer install --no-dev --optimize-autoloader
    echo -e "${GREEN}✅ Dependencies installiert${NC}"
fi

# Ausführe Migrations
echo ""
echo "🗄️  Führe Datenbank-Migrations aus..."

php artisan migrate --force
echo -e "${GREEN}✅ Migrations erfolgreich ausgeführt${NC}"

# Erstelle Storage Verzeichnisse mit richtigen Permissions
echo ""
echo "📁 Konfiguriere Storage Verzeichnisse..."

mkdir -p storage/app/public
chmod -R 775 storage/ bootstrap/cache/
echo -e "${GREEN}✅ Storage Verzeichnisse konfiguriert${NC}"

# Starte Web Server (optional)
echo ""
echo "🚀 Laravel Server starten..."

php artisan serve --host=0.0.0.0 --port=8000 &
SERVER_PID=$!

echo -e "${GREEN}✅ Laravel Server gestartet auf http://localhost:8000${NC}"
echo ""
echo "=========================================="
echo "🎉 Installation abgeschlossen!"
echo "=========================================="
echo ""
echo "Öffne http://localhost:8000 im Browser"
echo ""
echo "Um den Server zu stoppen:"
echo "  kill \$SERVER_PID"
echo ""
echo "Für Production verwenden:"
echo "  php artisan serve --host=0.0.0.0 --port=80"
echo ""
