# RunwayHub Projekt Roadmap

## 📋 Statusübersicht
Dieses Dokument dient zur Verfolgung des Fortschritts der Entwicklung von **RunwayHub**, einer Virtual Airline Plattform.

---

## ✅ Abgeschlossen (Done)
- [x] Grundstruktur der Anwendung (PHP-Basis)
- [x] Header und Footer Integration
- [x] Sidebar Navigation Implementierung
- [x] Basis-CSS Definitionen & Branding-Platzhalter

## 🏗️ In Bearbeitung / Nächste Schritte
### 1. Flugzeugverwaltung (Aircraft Management)
- [ ] Datenbankmodell für die Flotte erstellen
- [ ] **Wartungsmodul** implementieren:
    - [ ] Berechnung von Werte wie "Nächste Inspektion"
    - [ ] Protokollierung von Instandhaltungsarbeiten
- [ ] Verwaltung individueller Flugzeugdetails (RH-Kennung, Status)

### 2. Benutzerrollen & Mitglieder
- [ ] Rollenverwaltung: Administrator vs. Pilot
- [ ] Login/Registrierung Schnittstellen
- [ ] Mitgliedsverwaltung (Member Services)

### 3. Systemarchitektur
- [ ] Migration vorbereiten: SQL-Skripte für SQLite zu MySQL Übergang
- [ ] Backend-Bereinigung und Dokumentation der Core-Logik

---

## 📌 Projekt-Konstanten (Referenz)
- **Identifikationsnummern:**
  - Flugzeuge/Flotten: **RH####** (z. B. RH0124)
  - Pilot-IDs: **RWH####** (z. B. RWH001)
- **Datenbank:** Aktuell SQLite, Migration zu MySQL geplant.

## 📝 Notizen
*Projektpfad: /home/christoph/runwayhub/*