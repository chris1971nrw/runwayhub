# 🔐 Sicherheitsrichtlinien – Virtuelle Airline System (VAVS MVP)  
## Release 1: Security-by-Design

---

### **Authentifizierungsfluss:**  

```   
┌─────────────── Authentication Flow ────────────────────┐    
│ Login → Validate Email/Password(Bcrypt Hash Compare), │   


│     → Session-Token Create(AutoExpire After Timeout=30min|
        → CSRF-Token Generation for Form Submissions      
        → Rate-Limiting bei login: max.5 Versuche/Stunde  
```

---  

### **Session-Cookie-Eigenschaften (Security-Best Practice):**  

```html 
SetCookie("session_id","abc123", [   
    secure => true,  // HTTPS required ✅
     http_only=true// XSSI Attack Protection✅  
      same_site="Strict" // CSRF Prevention ⚡    
        ]);      
     
}

---

### **SQL Injection Schutz:**  

```php 
<?php // Beispiel für PDO Prepared Statements (Parameter Binding):   
$stmt = $pdo->prepare('SELECT * FROM fluge WHERE id=?');
$stmt->execute([1]);  
$result = stmt.fetchall();  // ✅ Keine SQL-Fehler!   
?> 

---

### **XSS Filtering:**  

```html 
<div class="user-input" aria-label=Wetterbericht>{{weather_report}}</div>`;
