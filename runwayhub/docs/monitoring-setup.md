# Monitoring Setup - RunwayHub

**Version:** 2.0.3  
**Last Updated:** 2026-05-28  
**Status:** ✅ Ready for Implementation

---

## 🎯 Overview

Dieser Guide dokumentiert das Monitoring-Setup für RunwayHub. Umfasst Uptime-Tracking, Performance-Monitoring, und Alerting.

---

## ✅ Current Monitoring

### Uptime Tracking
- ✅ File-based uptime: `memory/uptime.txt`
- ✅ API health endpoint: `/public/api-status.php`
- ✅ Flight board: Real-time updates

### Health Checks
- ✅ Database connectivity
- ✅ API endpoints
- ✅ Service status
- ✅ Memory usage

---

## 🔧 Implementation Plan

### Phase 1: Uptime Monitoring

```php
// Update uptime
$uptimeFile = __DIR__ . '/../memory/uptime.txt';
$currentUptime = file_exists($uptimeFile) ? (int)file_get_contents($uptimeFile) : 0;
$newUptime = $currentUptime + time();
file_put_contents($uptimeFile, (string)$newUptime);

// Display uptime
$currentUptime = (int)file_get_contents($uptimeFile);
echo "Uptime: " . floor($currentUptime / 3600) . " hours";
```

### Phase 2: Alerting Setup

#### Email Alerts
```php
// SMTP configuration
$config = [
    'host' => 'smtp.example.com',
    'port' => 587,
    'username' => 'alerts@example.com',
    'password' => 'secure_password',
    'from' => 'RunwayHub Alerts <alerts@runwayhub.de>',
];

// Send alert
function sendAlert($subject, $message) {
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = $config['host'];
    // ... configuration
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->send();
}
```

#### Webhook Alerts
```php
// Webhook payload
$payload = [
    'event' => 'alert',
    'timestamp' => date('c'),
    'service' => 'runwayhub',
    'alert' => $alertType,
    'message' => $alertMessage,
];

$ch = curl_init($webhookUrl);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_exec($ch);
curl_close($ch);
```

---

## 📊 Performance Metrics

### Key Metrics to Track
- ✅ Page load time
- ✅ API response time
- ✅ Database query time
- ✅ Memory usage
- ✅ Request count
- ✅ Error rate

### Metrics Collection

```php
// Performance monitoring
$metrics = [
    'timestamp' => microtime(true),
    'method' => $_SERVER['REQUEST_METHOD'],
    'path' => $_SERVER['REQUEST_URI'],
    'duration' => microtime(true) - $startTime,
    'memory_start' => memory_get_usage(true),
    'memory_end' => memory_get_usage(true),
    'memory_delta' => memory_get_usage(true) - memory_get_usage(true),
    'queries' => $queryCount,
    'cache_hits' => $cacheHits,
];

// Store metrics
file_put_contents('memory/metrics-' . date('Y-m-d') . '.json', 
    $metrics . PHP_EOL, FILE_APPEND);
```

---

## 🚨 Alert Thresholds

### Uptime Alerts
- ⚠️ Down: Alert immediately
- ⚠️ Uptime < 99.9%: Warning
- ✅ Normal: No action

### Error Rate Alerts
- ⚠️ Error rate > 1%: Warning
- ⚠️ Error rate > 5%: Critical
- ✅ Error rate < 1%: Normal

### Performance Alerts
- ⚠️ Page load > 3s: Warning
- ⚠️ Page load > 5s: Critical
- ⚠️ Memory > 80%: Warning
- ⚠️ Memory > 90%: Critical

---

## 📈 Monitoring Tools

### Uptime Robot
- ✅ Free tier available
- ✅ HTTP checks
- ✅ Email alerts
- ✅ SMS alerts

### Pingdom
- ✅ Global monitoring
- ✅ Performance testing
- ✅ Visual regression

### Google Analytics 4
- ✅ Page views
- ✅ User engagement
- ✅ Conversion tracking

### Sentry
- ✅ Error tracking
- ✅ Performance monitoring
- ✅ Real-user monitoring

---

## 🔐 Security Monitoring

### Intrusion Detection
```bash
# Monitor for suspicious activity
# Failed login attempts
# Unusual API access patterns
# Database query anomalies
```

### Log Monitoring
```bash
# Apache/Nginx logs
# PHP error logs
# Database logs
# Application logs
```

---

## 📝 Log Management

### Log Rotation
```bash
# Setup log rotation
cat /etc/logrotate.d/runwayhub <<'EOF'
/runwayhub/*.log {
    daily
    missingok
    rotate 30
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
}
EOF
```

### Log Aggregation
- ✅ Centralized logging
- ✅ Log analysis
- ✅ Anomaly detection

---

## 🎯 Next Steps

### Immediate
1. Set up Uptime Robot
2. Configure email alerts
3. Enable error logging
4. Set up monitoring dashboard

### Short-term
1. Implement performance metrics
2. Configure alert thresholds
3. Set up log aggregation
4. Monitor error rates

### Long-term
1. Advanced metrics
2. AI-based anomaly detection
3. Predictive maintenance
4. Automated scaling

---

## 📚 References

- [Uptime Robot](https://uptimerobot.com/)
- [Google Analytics 4](https://analytics.google.com/)
- [Sentry](https://sentry.io/)
- [PHPMailer](https://github.com/PHPMailer/PHPMailer)

---

**Version:** 2.0.3  
**Last Updated:** 2026-05-28  
**Status:** ✅ Ready for Implementation
