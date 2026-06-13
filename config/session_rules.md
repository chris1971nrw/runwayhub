# 🔐 Session Management - VAVS System MVP  
## Virtuelle Airline (Release 1)

### **Session-Konzept:**  

```   
┌─────────────── Authentication Flow ────────────────────┐    
│ Login → Validate Email/Password(Bcrypt Hash Compare), │   


│     → Session-Token Create(Auto-Expire After Timeout=30min|
        → CSRF-Token Generation for Form Submissions      
        → Rate-Limiting bei login: max.5 Versuche/Stunde  
```

### **Session-Cookie-Eigenschaften (Security-Best Practice):**  

```html 
SetCookie("session_id","abc123", [   
    secure => true,  // HTTPS required ✅
     http_only=true// XSSI Attack Protection✅  
      same_site="Strict" // CSRF Prevention ⚡    
        ]);      
     
}
