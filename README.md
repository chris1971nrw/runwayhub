# RunwayHub - Free Visual Air Traffic Controller Software

**Modern, Open Source, Multi-Airline Flight Management System**

![Version](https://img.shields.io/badge/version-2.0.3-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.3+-green.svg)
![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Security](https://img.shields.io/badge/security-hardened-brightgreen.svg)
![SEO](https://img.shields.io/badge/SEO-97.5%25-green.svg)

---

## 📋 Overview

RunwayHub is a **free, open-source** Visual Air Traffic Controller hub designed for FBOs, airports, and aviation professionals. Built with modern PHP 8.3+, it provides comprehensive flight management, weather integration, and visual air traffic controller (VA) management.

### Key Features

- ✅ **Multi-Airline Support** - Compatible with multiple airlines and systems
- ✅ **Live Flight Tracking** - Real-time flight status via FlightAware API
- ✅ **Weather API** - METAR/TAF weather data with caching
- ✅ **VA Management** - Create, manage, and connect Visual Air Traffic Controllers
- ✅ **Statistics & Reports** - Comprehensive flight analytics
- ✅ **PIREP System** - Pilot weather reports integration
- ✅ **Leaderboards** - Track top performers
- ✅ **Secure** - Industry-standard security (bcrypt, CSRF, XSS prevention)

---

## 🚀 Quick Start

### Installation

```bash
cd runwayhub
php -S localhost:8000 -t public
```

### Demo Access

```
Admin:    demo_admin     / admin123
Pilot:    demo_pilot     / pilot123
Guest:    demo_guest     / guest123
```

Visit <a href="https://runwayhub.github.io">https://runwayhub.github.io</a>

---

## 📖 Documentation

- [**Architecture**](runwayhub/docs/architecture.md) - System design
- [**Features**](runwayhub/docs/features.md) - Complete feature list
- [**Database**](runwayhub/docs/database.md) - Schema documentation
- [**Deployment**](runwayhub/docs/deployment.md) - Production setup
- [**Weather API**](runwayhub/docs/weather-api.md) - METAR/TAF integration
- [**FlightAware**](runwayhub/docs/flightaware.md) - Flight tracking
- [**Security**](runwayhub/docs/security.md) - Security hardening
- [**Performance**](runwayhub/docs/performance-guide.md) - Optimization

---

## 🎯 Features

### Core

- **Multi-Airline Support**
  - Compatible with multiple airlines
  - Unified data interface
  
- **Live Flight Tracking**
  - Real-time flight status
  - Flight history tracking
  - Arrival/departure boards
  
- **Weather Integration**
  - METAR weather reports
  - TAF forecasts
  - Weather alerts

### VA Management

- **VA Creation** - Create new controllers
- **Connection Management** - Connect VAs to your system
- **Admin Panel** - Full administration interface
- **Secure Sessions** - HttpOnly, Secure cookies

### API

- **40+ Endpoints** - RESTful API
- **32 Controllers** - Full CRUD operations
- **Rate Limiting** - Protect against abuse
- **Documentation** - Complete API docs

---

## 🔧 Technical Stack

- **PHP:** 8.3.6+
- **Database:** SQLite (15 tables)
- **Security:** bcrypt (cost=12), CSRF, XSS prevention
- **Caching:** TTL-based caching (5-300s)
- **API:** RESTful architecture
- **Static HTML:** Fast load times, SEO-optimized

---

## 🛡️ Security

RunwayHub includes enterprise-grade security:

- **Password Hashing:** bcrypt (cost=12)
- **CSRF Protection:** Token-based
- **XSS Prevention:** Output escaping
- **SQL Injection:** Prepared statements
- **Session Security:** HttpOnly, Secure, SameSite cookies
- **Rate Limiting:** DDoS protection
- **CSP:** Content Security Policy headers

See [Security Documentation](runwayhub/docs/security.md) for details.

---

## 📊 Statistics

- **PHP Files:** 144 (all syntax-valid)
- **API Endpoints:** 40+
- **Database Tables:** 15
- **Documentation:** 54 files
- **Lines of Code:** ~65,000

---

## 🎓 License

MIT License - Free and open source.

```
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software.
```

---

## 👥 Community

- **GitHub:** [@chris1971nrw](https://github.com/chris1971nrw)
- **Issues:** [Report Issues](https://github.com/chris1971nrw/runwayhub/issues)
- **Discussions:** [Start a Discussion](https://github.com/chris1971nrw/runwayhub/discussions)
- **Email:** demo@airline.com

---

## 📞 Support

Need help?

- **Email:** demo@airline.com
- **GitHub Issues:** [Open an Issue](https://github.com/chris1971nrw/runwayhub/issues)
- **Discussions:** [Join Discussions](https://github.com/chris1971nrw/runwayhub/discussions)

---

## 🚀 Why RunwayHub?

- **Free:** No licensing fees
- **Open Source:** Full source code available
- **Self-Hosted:** Complete control over your data
- **Privacy:** Data stays on your server
- **Multi-Airline:** Works with multiple airlines
- **Live Data:** Real-time flight tracking
- **Secure:** Enterprise-grade security

---

**Version:** 2.0.3  
**Build:** 2026-05-28  
**Status:** ✅ Production Ready

---

![RunwayHub](https://runwayhub.github.io/assets/og-image.jpg)
