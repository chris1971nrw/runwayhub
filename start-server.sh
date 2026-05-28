#!/bin/bash

# Start-Skript für RunwayHub Manager mit MySQL

echo "🚀 RunwayHub Manager - System-Start"
echo "======================================"

# Verzeichnis wechseln
cd "$(dirname "$0")/code"

echo ""
echo "📊 Datenbank-Status:"
docker ps | grep mysql && echo "   ✓ MySQL läuft auf Port 3306" || echo "   ✗ MySQL nicht gefunden"

echo ""
echo "📝 Datenbank-Konfiguration:"
cat .env | grep -E "^DB_"

echo ""
echo "🔐 PHP-Version:"
php -v | head -1

echo ""
echo "📁 Verzeichnis-Struktur:"
ls -la | head -10

echo ""
echo "✅ System bereit!"
echo ""
echo "📡 Lokale URLs:"
echo "   - Management-Dashboard: http://localhost:8080"
echo "   - API-Endpoint: http://localhost:8080/api/"
echo ""
echo "📝 Beachte:"
echo "   - Der MySQL-Container ist bereits im Docker-Netzwerk"
echo "   - Falls Fehler auftreten, prüfe die .env-Konfiguration"
echo "   - Für production: APP_DEBUG=false und korrekte DB-Passwort"
