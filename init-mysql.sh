#!/bin/bash

# MySQL Initialisierungskopieren
cp /home/christoph/.openclaw/workspace-runwayhub/runwayhub/projekt/create-database.sql /tmp/init-database.sql

# MySQL ausführen
sudo mysql -u root < /tmp/init-database.sql

echo "✅ MySQL-Datenbank erstellt!"
echo ""
echo "Datenbank: runwayhub"
echo "Benutzer: admin"
echo "Passwort: admin123"
echo ""
echo "Lade die MySQL-Datenbank unter: http://localhost:8080"
