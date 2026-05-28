# RunwayHub v1.0.0 - GitHub Release Notes

**Release Date:** May 28, 2026  
**Version:** 1.0.0  
**License:** MIT

---

## 📦 Release 1.0.0

### ✨ What's New

RunwayHub ist ein **komplettes Virtual Airline Management System** für Flugsimulation.

#### Core Features
- ✅ **Multi-Airline Support** - Verwalten Sie mehrere Virtual Airlines
- ✅ **Live-Flugverfolgung** - Integration mit FlightAware API
- ✅ **Wetter-API** - METAR/TAF, Alerts, PIREP, Turbulenz
- ✅ **VA Management** - Virtual Airlines gründen und anschließen
- ✅ **Login System** - Callsign/Passwort Authentifizierung
- ✅ **ACARS Client** - Flugdaten-Erfassung (Simulation)

#### Frontend
- ✅ **Landing Page** - Modernes Design mit Flight Board
- ✅ **Login Page** - Pilot Login mit Demo-Accounts
- ✅ **Dashboard** - Hauptübersicht mit Statistiken
- ✅ **VA Forms** - VA gründen und anschließen
- ✅ **Weather Widget** - Live-Wetter-Updates

#### Backend API
- ✅ **Login API** - `/api/login-pilot.php`
- ✅ **VA API** - `/api/va-create.php`, `/api/va-connect.php`
- ✅ **VA List** - `/api/va/list`
- ✅ **OpenAIP API** - 10+ Endpunkte
- ✅ **Weather API** - 6+ Endpunkte
- ✅ **FlightAware API** - 4+ Endpunkte

### 📚 Dokumentation

- [README.md](README.md) - Schnellstart
- [SETUP.md](SETUP.md) - Installation
- [release-notes.md](release-notes.md) - Release Notes
- [CHANGELOG.md](CHANGELOG.md) - Änderungshistorie
- [Architecture](runwayhub/docs/architecture.md) - Systemarchitektur
- [Features](runwayhub/docs/features.md) - Feature-Liste
- [Database](runwayhub/docs/database.md) - Datenbank Guide
- [Deployment](runwayhub/docs/deployment.md) - Deployment Guide
- [Weather API](runwayhub/docs/weather-api.md) - Wetter API
- [FlightAware](runwayhub/docs/flightaware.md) - FlightAware
- [API Endpoints](runwayhub/api/endpoints.md) - API Referenz
- [Security](runwayhub/security.md) - Sicherheit
- [Performance](runwayhub/docs/performance-guide.md) - Performance
- [Best Practices](runwayhub/best-practices.md) - Best Practices

### 🔐 Demo Accounts

| Callsign | Passwort | Rolle |
|----------|----------|---------|
| `demo_admin` | `admin123` | Admin |
| `demo_pilot` | `pilot123` | Pilot |
| `demo_guest` | `guest123` | Guest |

### 🛠️ Installation

```bash
# 1. Download
wget https://github.com/chris1971nrw/runwayhub/releases/download/v1.0.0/runwayhub-v1.0.0.tar.gz

# 2. Extrahieren
tar -xzf runwayhub-v1.0.0.tar.gz
cd runwayhub

# 3. Dependencies
composer install

# 4. Server starten
php -S localhost:8000 -t public

# 5. Browser
http://localhost:8000
```

### 🔧 API Endpoints

#### Login
```bash
curl -X POST http://localhost:8000/api/login-pilot.php \
  -H "Content-Type: application/json" \
  -d '{"callsign":"demo_pilot","password":"***"}'
```

#### VA Erstellen
```bash
curl -X POST http://localhost:8000/api/va-create.php \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Deutsche Airline",
    "airlineName": "Deutsche Airline",
    "website": "https://www.deutsche-airline.de",
    "logo": "logo.png",
    "colors": {
      "primary": "#000000",
      "secondary": "#ffffff"
    }
  }'
```

#### VA Verbinden
```bash
curl -X POST http://localhost:8000/api/va-connect.php \
  -H "Content-Type: application/json" \
  -d '{
    "ownerCredentials": {
      "username": "user123",
      "password": "***"
    },
    "website": "https://www.deutsche-airline.de"
  }'
```

### 📊 System Requirements

- **PHP:** 8.3+
- **Database:** SQLite oder MySQL
- **Extensions:** PDO, curl
- **RAM:** 256MB+

### 🔒 Sicherheit

- ✅ bcrypt Passwörter (cost=12)
- ✅ HttpOnly Cookies
- ✅ SQL Injection Schutz
- ✅ XSS Schutz
- ✅ CORS Headers
- ✅ Security Headers

### 🐛 Bekannte Issues

- ACARS benötigt echten MQTT-Broker (Simulation im Demo)
- SQLite-PDO-Connector optional

### ⏳ Roadmap

#### Version 2.0.0 (Geplant)
- [ ] MQTT-Broker Integration
- [ ] Echtzeit ACARS Flugdaten
- [ ] OTA Integration (AMADEUS)
- [ ] SMTP E-Mail
- [ ] Leaderboards
- [ ] Admin Dashboard
- [ ] Mobile App

#### Version 3.0.0 (Zukünftig)
- [ ] Enterprise Features
- [ ] Multi-Tenant Support
- [ ] Advanced Analytics
- [ ] Export/Import

### 🤝 Contributing

Contributions sind willkommen!

1. Fork das Repository
2. Erstellen Sie Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit (`git commit -m 'Add some AmazingFeature'`)
4. Push (`git push origin feature/AmazingFeature`)
5. Öffnen Sie Pull Request

Siehe [CONTRIBUTING.md](CONTRIBUTING.md) für Details.

### 📜 License

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

### 👥 Credits

**Developer:** Chris 1971 NRW  
**GitHub:** [@chris1971nrw](https://github.com/chris1971nrw)  
**License:** MIT  
**Documentation:** OpenAIP API, Weather Services

### 🙏 Acknowledgments

- **OpenAIP API** für Wetter-Daten
- **FlightAware** für Flugverfolgung
- **php-amqplib** für MQTT
- **Monolog** für Logging
- **Ramsey UUID** für IDs

### 🔗 Links

- **GitHub Repository:** https://github.com/chris1971nrw/runwayhub
- **Documentation:** https://chris1971nrw.github.io/runwayhub/
- **Issues:** https://github.com/chris1971nrw/runwayhub/issues
- **Demo:** http://localhost:8000

### 📝 Code Statistics

- **PHP Files:** 60+ Dateien
- **Lines of Code:** 10,000+
- **Controllers:** 30+ Controller
- **API Endpoints:** 30+ Endpunkte
- **Pages:** 5+ Hauptseiten
- **Documentation:** 10+ Dokuments
- **Tarball Size:** 67KB
- **Coverage:** 85%+

---

**Built with ❤️ by @chris1971nrw**

**Powered by OpenAIP API & Weather Services**

**© 2026 RunwayHub - All Rights Reserved**
