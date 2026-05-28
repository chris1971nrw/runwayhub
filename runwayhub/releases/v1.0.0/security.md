# Security Guide - RunwayHub

**Version:** 0.1.0  
**Last Updated:** 2026-05-27 18:47 GMT+2  

---

## Security Overview

RunwayHub implements comprehensive security measures to protect user data, API endpoints, and system integrity.

---

## Authentication Security 🔐

### Password Hashing

- **Algorithm:** bcrypt
- **Cost Factor:** 12 (recommended for PHP 8.2+)
- **Salt:** Automatic generation
- **Storage:** Hash only, never plaintext

```php
// Password hashing example
$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

// Verification
if (password_verify($input_password, $hash)) {
    // Valid password
}
```

### Session Management

- **Token Format:** UUID v4
- **Storage:** Database-backed sessions
- **Expiry:** Configurable TTL
- **Rotation:** On sensitive actions

### CSRF Protection

- **Implementation:** Token-based
- **Mechanism:** Hidden form fields
- **Validation:** On form submission
- **Storage:** Database tokens

---

## Authorization Control 🔒

### Role-Based Access Control (RBAC)

| Role | Permissions |
|------|-------------|
| **Admin** | Full system access |
| **Staff** | Management duties |
| **Pilot** | Flight operations |
| **Guest** | View-only access |

### API Authorization

- **API Keys:** Required for all endpoints
- **Rate Limiting:** Enforced per key
- **Scopes:** Fine-grained permissions
- **Validation:** On every request

---

## API Security 🛡️

### Rate Limiting

```
Endpoint                  Limit          Window
------------------------- -------------- ----------------
OpenAIP                   100 requests   1 minute
Weather API               60 requests    1 minute  
FlightAware               10 requests    1 minute
VA Management             60 requests    1 minute
Login/Registration        10 requests    1 minute
```

### Request Validation

- **Sanitization:** All inputs validated
- **Type Checking:** Strict types
- **Length Limits:** Prevent buffer attacks
- **Pattern Matching:** Validate formats

### Response Protection

- **Error Messages:** Obfuscated in production
- **Stack Traces:** Never exposed
- **Sensitive Data:** Masked/redacted
- **IP Logging:** For abuse detection

---

## Database Security 🗄️

### SQLite Protection

- **File Permissions:** 644 (rw-r--r--)
- **Path Validation:** Whitelisted only
- **Injection Prevention:** Prepared statements
- **Transaction Isolation:** SERIALIZABLE

### SQL Injection Prevention

```php
// Vulnerable ❌
$stmt = $pdo->query("SELECT * FROM users WHERE callsign = '$callsign'");

// Secure ✅
$stmt = $pdo->prepare("SELECT * FROM users WHERE callsign = :callsign");
$stmt->execute([':callsign' => $callsign]);
```

### Data Encryption

- **In Transit:** TLS/SSL required
- **At Rest:** Optional for sensitive fields
- **Keys:** External key management
- **Rotation:** Regular updates

---

## File Security 📁

### Sensitive Files

```
🚫 EXCLUDED (never commit):
- .env (environment variables)
- .git/ (repository metadata)
- *.log (log files)
- *.sqlite.journal (SQLite temp)
- backup-*.sqlite (old backups)
- vendor/ (dependencies)

✅ INCLUDED (safe to commit):
- .gitignore (ignored files)
- README.md (documentation)
- *.php (source code)
- *.xml (configuration)
```

### Upload Restrictions

- **File Types:** Whitelisted only
- **Extensions:** PHP disabled
- **Size Limits:** Configured maximums
- **Content Scanning:** Virus checks

---

## Web Application Firewall (WAF) 🧯

### Protected Against

- **SQL Injection:** Parameterized queries
- **XSS Attacks:** Output escaping
- **CSRF:** Token validation
- **DDoS:** Rate limiting
- **Brute Force:** Account lockout

### Headers

```http
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
Content-Security-Policy: default-src 'self'
Strict-Transport-Security: max-age=31536000
```

---

## Environment Security 🌍

### .env Best Practices

```ini
# DO NOT commit .env to git
DB_PATH=/secure/path/database.sqlite
API_KEY_OPENAIP=sk_live_xxxxxxxxxxxxxxxxxxxx
API_KEY_METEO=xxxxxxxxxxxxxxxxxx
SECRET_KEY=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

### Deployment Security

1. **Generate new secrets** on each deployment
2. **Never share API keys**
3. **Use environment variables**
4. **Rotate keys regularly**
5. **Monitor access logs**

---

## Monitoring & Alerts 📊

### Security Events

- **Failed Logins:** Alert after 5 attempts
- **Rate Limit Exceeded:** Log and alert
- **Unusual Patterns:** Anomaly detection
- **API Abuses:** Flag suspicious requests

### Log Rotation

```bash
# Rotate logs daily
logrotate -f /var/log/runwayhub/app.log

# Keep 30 days
logrotate -f /var/log/runwayhub/error.log
```

---

## Incident Response 🚨

### Breach Detection

1. **Monitor:** Access logs, API logs
2. **Alert:** Email on threshold
3. **Contain:** Disable compromised keys
4. **Investigate:** Root cause analysis
5. **Remediate:** Patch and secure

### Response Plan

- **Immediate:** Disable affected access
- **Short-term:** Audit logs, patch vulnerabilities
- **Long-term:** Security review, policy updates

---

## Compliance 📜

### OpenAIP Compliance

- **Rate Limits:** Respected
- **Error Handling:** Proper responses
- **Authentication:** Required

### Data Protection

- **Privacy:** No PII collection
- **Minimization:** Only necessary data
- **Retention:** Configurable periods
- **Deletion:** User data removable

---

## Testing 🧪

### Security Testing

```bash
# Static analysis
phpstan analyse --security-type all

# Dependency check
composer audit

# Penetration testing
burpsuite -t http://localhost:8080/
```

### Vulnerability Checks

- **SQL Injection:** Sensitive queries reviewed
- **XSS:** Output encoding verified
- **CSRF:** Token mechanism tested
- **Brute Force:** Rate limits confirmed

---

## Best Practices ✅

### Development

- [x] Use prepared statements
- [x] Hash passwords with bcrypt
- [x] Validate all inputs
- [x] Use HTTPS in production
- [x] Enable security headers

### Deployment

- [x] Never commit .env
- [x] Use strong secrets
- [x] Keep dependencies updated
- [x] Monitor logs regularly
- [x] Implement rate limiting

### Operations

- [x] Rotate keys quarterly
- [x] Audit access logs
- [x] Test backups regularly
- [x] Plan incident response
- [x] Document procedures

---

## Resources 📚

- **OWASP Cheat Sheets:** https://cheatsheetseries.owasp.org/
- **PHP Security:** https://php.net/manual/en/security.php
- **SQLite Security:** https://www.sqlite.org/lang_createtable.html

---

*Generated by RunwayHub Security System*
