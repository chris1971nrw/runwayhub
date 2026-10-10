# runwayhub 🌉

## Projektübersicht - Laravel PHP + MySQL

### 🎯 Zielsetzung
Web-App mit Laravel und MySQL als Datenbank-Speicher (ohne Overhead!)

### 📋 Planungsstatus
🟡 **Phase 1: Concept & Anforderungen**  
- Tech-Stack definiert: Laravel 10/11, MySQL, PHP 8.x  
- Projektstruktur entworfen  
- Keine Code-Erstellung bis Anforderungen stehen  

---

## 1. Use-Case Definition

### ⚠️ Wichtiger Hinweis
Bitte beschreibe den Use-Case **vor** weiterer Planung:
- Wer sind die Zielnutzer?
- Was ist der Kern-Zweck der App?
- Welche Funktionen braucht sie konkret?
- Gibt es externe API-Anbindungen?

Ohne diese Informationen bleibt die Planung auf hoher Ebene - kein Code wird geschrieben!

---

## 2. Geplante Architektur (Konzept-Stufe)

### 🏗️ Systemdesign
```
┌─────────────────────────────────────┐
│        Frontend (Blade/Vue)         │
├─────────────────────────────────────┤
│    Laravel Controllers              │
│         (Routing)                   │
├─────────────────────────────────────┤
│       Models (Eloquent ORM)         │
├─────────────────────────────────────┤
│         MySQL Database              │
└─────────────────────────────────────┘
```

### 💾 Datenbankstruktur (Konzept)
| Tabelle | Zweck | Spalten (Beispiel) |
|---------|-------|-------------------|
| `users` | Authentifizierung | id, email, password, role |
| ? | ? | ? |

**Zu definieren:**
- 📋 Welche Entity/Modelle werden benötigt?
- 🗄️ Welche Beziehungen (One-to-One, One-to-Many)?
- 🔐 Welche Berechtigungsstufen (Roles & Permissions)?

---

## 3. Planungsphase-Zeitleist

| Woche | Aufgabenfeld | Ziel |
|-------|-------------|------|
| 1 | Anforderungen definieren | User Stories schreiben |
|  | Datenmodelle skizzieren | ER-Diagramm erstellen |
| 2 | Routen planen | API/web Endpoints festlegen |
|  | Middleware & Auth Strategie | Session/Auth Flow entwerfen |
| 3 | Datenbank Design | Migrations skizzieren |
| 4 | UI/UX Konzepte | Wireframes für Views |

---

## 4. Zu klärende Fragen (vor Code-Erstellung)

Bitte beantworte:
1. **Wer nutzt runwayhub?**  
   → Admins? Endnutzer? Interne Tools?

2. **Welche Hauptfunktionalitäten benötigt sie?**  
   → CRUD? Reporting? Echtzeit-Updates? Dateiuploads?

3. **Authentifizierung**  
   → Session-basiert? JWT? OAuth (Google/GitHub)?

4. **Datenquellen**  
   → Nur interne Tables? Oder API-Anbindungen an externe Dienste?

5. **Frontend-Stack**  
   → Blade Templates? Alpine.js SPA? Vue/React?

---

## 5. Nächste Schritte

Sobald der Use-Case geklärt ist, erstelle ich:
- ✅ Detaillierte Anforderungs-Dokumente  
- ✅ Datenmodell als Mermaid ER-Diagramm  
- ✅ Routenplanung (routes/web.php Konzept)  
- ✅ Auth-Flow Diagram  

**Kein Code schreiben bis diese Dokumente stehen!**

---

## 📝 Statusübersicht

| Datei | Inhalt | Status |
|-------|---------|--------|
| README.md | Übersicht & Planungsstatus | ✅ Aktuell |
| requirements.pdf | Use-Case Dokument (in der Planung) | ⏳ |  
| database.sql | Datenmodell als ER-Diagramm | 🔄 Entwurf |

---

**Status:** Planungsmode aktiv - kein Code generieren, bis Anforderungen geklärt! 🎯
