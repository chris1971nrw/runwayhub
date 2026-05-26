# GitHub Issues Watcher

## Übersicht

Der Issues Watcher überwacht automatisch neue GitHub Issues und leitet sie an Sub-Agents weiter.

## Features

- **Automatische Issue-Erkennung** - Neue Issues werden alle 10 Minuten gecheckt
- **Label-basierte Routing** - `demo` Issues zu Demo-Agents, `bug` zu Haupt-System
- **Issue-Triage** - Sub-Agent analysiert Issues und priorisiert
- **Screenshot-Anhang** - Automatische Anhängung von Screenshots
- **Benachrichtigung** - E-Mail-Benachrichtigung bei kritischen Issues

## Workflow

```
1. Nutzer meldet Issue auf runwayhub.de
   ↓
2. GitHub Issue wird erstellt mit Label: demo, bug
   ↓
3. Issues Watcher (alle 10 Min) prüft neue Issues
   ↓
4. Sub-Agent Issue analysiert und priorisiert
   ↓
5. Issue wird an zuständigen Entwickler weitergeleitet
   ↓
6. Developer fixt Issue und schließt
   ↓
7. Issue wird als geschlossen markiert
```

## Konfiguration

```bash
# .env.example
GITHUB_OWNER=chris1971nrw
GITHUB_REPO=runwayhub
GITHUB_TOKEN=ghp_***

# Labels
DEMO_LABEL=demo
BUG_LABEL=bug
CRITICAL_LABEL=critical

# Watcher-Interval
ISSUES_WATCHER_INTERVAL=600  # in Sekunden (10 Min)
```

## Sub-Agent Integration

### Issue-Triage-Agent

```yaml
taskName: issue_triage_agent

steps:
  - Issue-Details lesen
  - Reproduktionsschritte analysieren
  - Kritikalität einschätzen
  - Zuweisungs-Priority setzen
  - Developer benachrichtigen
  - Screenshot analysieren
  - Reproduktionstest vorschlagen
```

## Demo Issues vs Haupt-System

### Demo Issues

```yaml
labels:
  - demo
  - bug

description: "Fehler im Demo-System"

impact:
  - Nur Demo-Umgebung
  - Keine Produktionsauswirkung
  - Kann mit Test-Daten reproduziert werden

priority: low
```

### Haupt-System Issues

```yaml
labels:
  - bug
  - critical

description: "Fehler im Haupt-System"

impact:
  - Produktionsauswirkung
  - Sofortige Behebung erforderlich
  - Rollback-Plan erstellen

priority: high
```

## Issue-Kategorien

### Demo Issues

- `demo` - Demo-System
- `demo/bug` - Demo-Fehler
- `demo/enhancement` - Demo-Feature
- `demo/question` - Demo-Frage

### Haupt-System Issues

- `bug` - Produktionsfehler
- `critical` - Kritisch
- `enhancement` - Feature-Anfrage
- `question` - Frage

## API Endpoints

```bash
# Issues abrufen
GET /api/issues?labels=demo
GET /api/issues?labels=bug

# Issue Details
GET /api/issues/{id}

# Issue schließen
POST /api/issues/{id}/close

# Issue zuweisen
POST /api/issues/{id}/assign
```

## Logs

```bash
# Log-Einträge
./runwayhub/docs/deployment/issues-watcher.log

# Status
docker logs runwayhub-issues-watcher
```

## Monitoring

```bash
# GitHub Actions Logs
https://github.com/chris1971nrw/runwayhub/actions/workflows/issues-watcher.yml

# Issue-Status-Dashboard
https://github.com/chris1971nrw/runwayhub/issues?q=labels:demo
```

## Best Practices

1. **Labels nutzen** - `demo` vs `bug` klar trennen
2. **Reproduktionsschritte** - Immer angeben
3. **Screenshots** - Immer anhängen
4. **Priorität** - Kritikalität einschätzen
5. **Rollback-Plan** - Bei Haupt-System-Fehlern

---

**GitHub Issues Watcher** - Automatische Issue-Triage für RunwayHub! 🐛
