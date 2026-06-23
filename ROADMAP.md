# RunwayHub Projekt Roadmap

## 📋 Statusübersicht
Dieses Dokument dient zur Verfolgung des Fortschritts der Entwicklung von **RunwayHub**, einer Virtual Airline Plattform.

---

## ✅ Abgeschlossen (Done)
- [x] Grundstruktur der Anwendung (PHP-Basis)
- [x] Header und Footer Integration
- [x] Sidebar Navigation Implementierung
- [x] Basis-CSS Definitionen & Branding-Platzhalter
- [x] **GitHub-Vorbereitung**: .gitignore und strukturierte Dokumentation
- [x] Kernmodule Extraktion (Library-Struktur)

## 🏗️ In Bearbeitung / Nächste Schritte
### 1. Flugzeugverwaltung (Aircraft Management)
- [ ] Datenbankmodell für die Flotte finalisieren
- [ ] **Wartungsmodul** implementieren:
    - [ ] Berechnung von Werten wie "Nächste Inspektion"
    - [ ] Protokollierung von Instandhaltungsarbeiten
    - [ ] Verwaltung individueller Flugzeugdetails (RH-Kennung, Status)

### 2. Benutzerrollen & Mitglieder
- [ ] Rollenverwaltung: Administrator vs. Pilot
- [ ] Login/Registrierung Schnittstellen vervollständigen
- [ ] Mitgliedsverwaltung (Member Services) ausbauen

### 3. Systemarchitektur & Skalierung
- [ ] Migration Vorbereitung: SQL-Skripte für SQLite zu MySQL Übergang optimieren
- [x] **Backend-Bereinigung**: Entfernung von Debug-Tools und Dokumentation der Core-Logik
- [ ] Performance-Optimierung für die Mitgliederdatenbank

---

## 📌 Projekt-Konstanten (Referenz)
- **Identifikationsnummern:**
  - Flugzeuge/Flotten: **RH####** (z. B. RH0124)
  - Pilot-IDs: **RWH####** (z. B. RWH001)
- **Datenbank:** Aktuell SQLite, Migration zu MySQL geplant.

## 📝 Notizen
*Projektpfad: /home/christoph/runwayhub/*
*Technik: Native PHP & SQLite Portfolio.*
