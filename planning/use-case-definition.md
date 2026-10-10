# Use-Case Definition - runwayhub

## 🎯 Zweck dieses Dokuments
Hier definieren wir **vor** der Code-Erstellung den Use-Case für runwayhub.  
Kein Code wird geschrieben, bis dieser Abschnitt vollständig ist!  

---

## 1. Zielgruppe & Nutzerprofile

### Bitte beschreibe:
- **Primärnutzer**: Wer nutzt die App hauptsächlich?
- **Rollen**: Admin? Editor? Viewer? Gast?
- **Zugriffslevel**: Public Login required? Dashboard nur für interne Teams?

**Beispiel-Antwort (falls du noch unsicher bist):**  
> "runwayhub ist ein Internal-Tool für Projektmanagement.  
> Rollen: Admin (Full Access), Team Lead (Meister Projects), Member (nur eigene Projekte)."

---

## 2. Hauptfunktionalitäten (User Stories)

### 📋 Bitte antworte auf diese Fragen:

**Erwartungswerte des Systems:**
1. **Projekt-Management**:  
   → Neue Projects anlegen, Task zuweisen, Status aktualisieren?

2. **Fortschritts-Tracking**:  
   → Dashboard mit Burndown Charts / Gannt diagrams via Chart.js?

3. **Zusammenarbeit**:  
   → Kommentar-Sektion pro Ticket/Bug? @Mention-Nachrichten?

4. **Dateianhang**:  
   → File Uploads (Befestigung)? Welche Dateitypen erlauben? 5MB Limit oder unbegrenzt?

5. **Integrationen**:  
   → Slack/Discord Notifications? Git-Hub Webhooks? Jira-Sync?

**Priorisierte Features (1-3 für MVP):**
- [ ] Featureliste ausfüllen
- [ ] MVP-Minimum definieren (welche 2-3 Features sind zwingend?)

---

## 3. Datenmodell Skizze

### 📊 Welche Data Entities werden benötigt?

**Bitte liste die Kernobjekte auf:**

| Entity | Beschreibung | Beispiel-Spalten |
|--------|--------------|------------------|
| users | Benutzer/Teams | id, email, name, role, timestamps |
| projects | Projektcontainer | title, description, deadline, status |  
| ? | ??? | ??? |

**Bitte ergänze:**
- Was sind die **Attribute** pro Entity?
- Welche **Beziehungen** (One-to-One, One-to-Many)?
- Welches Feld ist **Primary Key**?

---

## 4. Authentifizierung & Autorisierung

### 🔐 Strategie-Auswahl

**Wähle bitte:**
- [ ] Session Auth (standard Laravel - einfachste Lösung)
- [ ] JWT Tokens (für SPA/API-Frontends)  
- [ ] OAuth Login (Google/GitHub/SSO)

**Rollen & Berechtigungen:**
| Rolle | Can Create? | Can Edit? | Can Delete? |
|-------|-------------|-----------|--------------|  
| ? | ? | ? | ? |

---

## 5. Frontend-Technologien

### 🖼️ UI/UX Entscheidungen

**Bitte spezifizieren:**
- Views: Blade Templates **oder** Alpine.js SPA mit Inertia? 
- Styling: Bootstrap 5 **oder** Tailwind CSS?  
- Charts: Chart.js (einfach) oder ApexCharts (besser)?  
- Responsive Design: Mobile-first oder Desktop-fürsich?

---

## 6. Technische Anforderungen

### ⚙️ Umgebungsvariablen (optional für MVP):
```env
DB_CONNECTION=mysql
DB_DATABASE=runwayhub_db
DB_USERNAME=user
DB_PASSWORD=pass
APP_DEBUG=true
APP_ENV=local
```

### 📦 Datenbankanforderungen:
- MySQL/MariaDB 5.7+  
- Mindest RAM: ? MB  
- Max. Concurrent Users: ?  

---

## ✅ Nächste Schritte nach Use-Case-Ausfüllung

Sobald **alle** oben genannten Punkte geklärt sind, erstelle ich:
1. ✅ DETAILLIERTES Datenmodell (Mermaid ER-Diagramm)  
2. ✅ Routen-Konzept (routes/web.php Blueprint)  
3. ✅ Middleware & Auth-Flow Diagramm  
4. ✅ Blade Templates Wireframes  

**Dann folgt Code-Erstellung im nächsten Schritt!** 🚀

---

## 📝 Checkliste: Use-Case fertig, wenn...

- [ ] Zielgruppen-Rollen definiert
- [ ] Top 3 MVP-Funktionen priorisiert
- [ ] Core-Datenmodel Entities skizziert  
- [ ] Auth-Strategie ausgewählt (Session vs JWT)
- [ ] Frontend-Stack entschieden (Blade vs Alpine.js)

**Wenn alle Haken gesetzt sind, sag einfach - dann erstelle ich die Planungs-Docs!** 🎯
