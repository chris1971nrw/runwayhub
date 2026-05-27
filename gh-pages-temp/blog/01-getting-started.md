# Getting Started with RunwayHub

**Published:** 2026-05-26  
**Category:** Tutorials  
**Tags:** beginner, tutorial, virtual-airlines

---

## 🚀 Introduction

Welcome to RunwayHub! This open-source virtual airline management software helps you run your own VASO (Virtual Airline Software) for flight simulators.

## ✈️ What is RunwayHub?

RunwayHub provides:

- **Multi-Airline Support**: Manage multiple virtual airlines in one system
- **OpenAIP Integration**: Real-time aviation data from OpenAIP API
- **Weather Integration**: Live weather updates for airports
- **Flight Tracking**: Real-time flight status monitoring
- **Role-Based Access**: Admin, Staff, Pilot, Guest roles
- **Open Source**: MIT licensed, fully transparent

## 📥 Installation

### Prerequisites

- PHP 8.2+
- MySQL 8.0+
- Composer (PHP dependency manager)
- Web server (Apache/Nginx)

### Quick Start

```bash
git clone https://github.com/chris1971nrw/runwayhub.git
cd runwayhub
composer install
php artisan demo:install
```

### Configuration

Copy the environment file:

```bash
cp .env.example .env
# Edit .env with your database credentials
```

## 🎮 Demo Users

RunwayHub includes pre-seeded demo users:

- **Admin**: Full system access
- **Pilot**: Can file flight plans
- **Guest**: Read-only access

## 📚 Next Steps

1. **Read Documentation**: Visit [docs/architecture.md](/docs/architecture.md)
2. **Explore Features**: See [features.md](/docs/features.md)
3. **API Reference**: Check [api.md](/docs/api.md)
4. **Database Schema**: Review [database.md](/docs/database.md)

## 🤝 Contributing

Want to contribute?

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Open a Pull Request

## 📖 More Tutorials

- [OpenAIP Integration](/docs/openaip.md)
- [Weather API Setup](/docs/weather-api.md)
- [FlightAware Integration](/flightaware.md)
- [Deployment Guide](/docs/deployment.md)

## 💬 Questions?

Join the community discussions or [file an issue](https://github.com/chris1971nrw/runwayhub/issues).

---

**Last Updated:** 2026-05-26  
**Author:** RunwayHub Team  
**License:** MIT
