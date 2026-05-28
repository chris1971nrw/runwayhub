# RunwayHub v1.0.0 - Release Package

**Release Date:** May 28, 2026  
**Version:** 1.0.0  
**License:** MIT

---

## 📦 About This Release

RunwayHub v1.0.0 ist das erste vollständige Release des Virtual Airline Management Systems. Alle Features, die in diesem Release enthalten sind, wurden erfolgreich getestet und sind für die öffentliche Nutzung bereit.

### ✨ Features

- ✅ Multi-Airline Support
- ✅ Live-Flugverfolgung (ACARS)
- ✅ Wetter-API Integration
- ✅ VA Management System
- ✅ Login System
- ✅ ACARS Client (Simulation)
- ✅ Demo-Accounts für Testing
- ✅ API Endpoints (30+)
- ✅ Modernes Design
- ✅ Sicherheitsfeatures

### 📚 Included Files

- PHP Source Code
- API Controllers
- Frontend Pages
- Configuration Files
- Database Schema
- Documentation
- Demo Accounts
- Release Notes
- Changelog

### 🔐 Demo Accounts

| Callsign | Passwort | Rolle |
|----------|----------|---------|
| `demo_admin` | `admin123` | Admin |
| `demo_pilot` | `pilot123` | Pilot |
| `demo_guest` | `guest123` | Guest |

---

## 🚀 Quick Start

```bash
# 1. Extrahieren
tar -xzf runwayhub-v1.0.0.tar.gz
cd runwayhub

# 2. Dependencies installieren
composer install

# 3. Server starten
php -S localhost:8000 -t public

# 4. Browser öffnen
# http://localhost:8000
```

### 🔐 Login mit Demo-Account

- **Admin:** `demo_admin` / `admin123`
- **Pilot:** `demo_pilot` / `pilot123`
- **Guest:** `demo_guest` / `guest123`

---

## 📖 Documentation

- [README.md](README.md) - Vollständige Dokumentation
- [SETUP.md](SETUP.md) - Installation Guide
- [release-notes.md](release-notes.md) - Release Notes
- [CHANGELOG.md](CHANGELOG.md) - Changelog
- [DEPLOYMENT.md](DEPLOYMENT.md) - Deployment Guide
- [release-notes.md](release-notes.md) - Release Notes

---

## 🛠️ API Endpoints

### Login

```bash
POST /api/login-pilot.php
Content-Type: application/json

{
  "callsign": "demo_pilot",
  "password": "pilot123"
}
```

### VA Erstellen

```bash
POST /api/va-create.php
Content-Type: application/json

{
  "name": "Deutsche Airline",
  "airlineName": "Deutsche Airline",
  "website": "https://www.deutsche-airline.de"
}
```

### VA Verbinden

```bash
POST /api/va-connect.php
Content-Type: application/json

{
  "ownerCredentials": {
    "username": "user123",
    "password": "***"
  },
  "website": "https://www.deutsche-airline.de"
}
```

---

## 🔒 Security

Dieses Release enthält folgende Sicherheitsfeatures:

- ✅ bcrypt Passwörter (cost=12)
- ✅ HttpOnly Cookies
- ✅ SQL Injection Schutz
- ✅ XSS Schutz
- ✅ CORS Headers
- ✅ Security Headers

---

## 📊 System Requirements

- **PHP:** 8.3+
- **Database:** SQLite oder MySQL
- **Extensions:** PDO, curl
- **RAM:** 256MB+
- **OS:** Linux/macOS/Windows

---

## 🐛 Known Issues

- ACARS benötigt echten MQTT-Broker (Simulation im Demo)
- SQLite-PDO-Connector optional

---

## ⏳ Roadmap

### Version 2.0.0 (Geplant)

- [ ] MQTT-Broker Integration
- [ ] Echtzeit ACARS Flugdaten
- [ ] OTA Integration (AMADEUS)
- [ ] SMTP E-Mail
- [ ] Leaderboards
- [ ] Admin Dashboard
- [ ] Mobile App

### Version 3.0.0 (Zukünftig)

- [ ] Enterprise Features
- [ ] Multi-Tenant Support
- [ ] Advanced Analytics
- [ ] Export/Import

---

## 🤝 Contributing

Contributions sind willkommen!

1. Fork das Repository
2. Erstellen Sie Feature Branch
3. Commit & Push
4. Öffnen Sie Pull Request

---

## 📜 License

Dieses Projekt ist unter der **MIT License**.

```
MIT License

Copyright (c) 2026 Chris 1971 NRW

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

## 👥 Credits

**Developer:** Chris 1971 NRW  
**GitHub:** [@chris1971nrw](https://github.com/chris1971nrw)  
**License:** MIT  
**Documentation:** OpenAIP API, Weather Services

### 🙏 Acknowledgments

- **OpenAIP API** für Wetter-Daten
- **ACARS** für Flugverfolgung
- **php-amqplib** für MQTT
- **Monolog** für Logging
- **Ramsey UUID** für IDs

---

## 🔗 Links

- **GitHub Repository:** https://github.com/chris1971nrw/runwayhub
- **Documentation:** https://chris1971nrw.github.io/runwayhub/
- **Issues:** https://github.com/chris1971nrw/runwayhub/issues
- **Demo:** http://localhost:8000

---

**Built with ❤️ by @chris1971nrw**

**Powered by OpenAIP API & Weather Services**

**© 2026 RunwayHub - All Rights Reserved**
