<?php

declare(strict_types=1);

/**
 * RunwayHub - VA Admin Panel
 * Visual Air Traffic Controller Management
 */

// Set headers
header('Content-Type: text/html; charset=utf-8');
header('X-Content-Type-Options: nosniff');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VA Admin - RunwayHub</title>
    <meta name="description" content="Visual Air Traffic Controller Management Panel">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8fafc; color: #1e293b; line-height: 1.6; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 12px; margin-bottom: 20px; }
        .header h1 { font-size: 32px; margin-bottom: 8px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .card h2 { color: #1a365d; margin-bottom: 12px; font-size: 18px; }
        .card p { color: #64748b; font-size: 14px; }
        .btn { display: inline-block; padding: 10px 20px; background: #2563eb; color: white; text-decoration: none; border-radius: 8px; font-weight: 500; transition: background 0.2s; }
        .btn:hover { background: #1d4ed8; }
        .stats { display: flex; gap: 20px; margin-bottom: 20px; }
        .stat { flex: 1; background: white; padding: 20px; border-radius: 12px; text-align: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .stat-value { font-size: 36px; font-weight: bold; color: #2563eb; }
        .stat-label { color: #64748b; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 VA Admin Panel</h1>
            <p>Manage your Visual Air Traffic Controllers</p>
        </div>
        
        <div class="stats">
            <div class="stat">
                <div class="stat-value">80+</div>
                <div class="stat-label">PHP Files</div>
            </div>
            <div class="stat">
                <div class="stat-value">40+</div>
                <div class="stat-label">API Endpoints</div>
            </div>
            <div class="stat">
                <div class="stat-value">100%</div>
                <div class="stat-label">Open Source</div>
            </div>
            <div class="stat">
                <div class="stat-value">Free</div>
                <div class="stat-label">Forever</div>
            </div>
        </div>
        
        <div class="grid">
            <div class="card">
                <h2>📦 Installation</h2>
                <p>Download and install RunwayHub to start managing your VA network.</p>
                <a href="https://github.com/chris1971nrw/runwayhub" target="_blank" rel="noopener noreferrer" class="btn">Download</a>
            </div>
            
            <div class="card">
                <h2>🔐 Security</h2>
                <p>RunwayHub includes enterprise-grade security features out of the box.</p>
                <ul style="margin-left: 20px; color: #64748b; font-size: 14px;">
                    <li>Password hashing (bcrypt cost=12)</li>
                    <li>CSRF token protection</li>
                    <li>XSS prevention measures</li>
                    <li>SQL injection protection</li>
                    <li>Rate limiting enabled</li>
                </ul>
            </div>
            
            <div class="card">
                <h2>📊 Features</h2>
                <ul style="margin-left: 20px; color: #64748b; font-size: 14px;">
                    <li>Multi-airline support</li>
                    <li>Live flight tracking</li>
                    <li>Weather API (METAR/TAF)</li>
                    <li>VA management system</li>
                    <li>Leaderboards & PIREPs</li>
                    <li>Statistics & reporting</li>
                </ul>
            </div>
            
            <div class="card">
                <h2>🌐 Documentation</h2>
                <p>Comprehensive documentation for getting started with RunwayHub.</p>
                <a href="/SETUP.md" class="btn">View Setup Guide</a>
            </div>
            
            <div class="card">
                <h2>🚀 API</h2>
                <p>Access RunwayHub via RESTful API with 40+ endpoints.</p>
                <a href="/api-status.php" class="btn">API Status</a>
            </div>
            
            <div class="card">
                <h2>💬 Support</h2>
                <p>Get help with RunwayHub through our support channels.</p>
                <ul style="margin-left: 20px; color: #64748b; font-size: 14px;">
                    <li>GitHub Issues: <a href="https://github.com/chris1971nrw/runwayhub/issues">Issues</a></li>
                    <li>Discussions: <a href="https://github.com/chris1971nrw/runwayhub/discussions">Discussions</a></li>
                    <li>Email: <a href="mailto:demo@airline.com">demo@airline.com</a></li>
                </ul>
            </div>
        </div>
        
        <div style="margin-top: 20px; padding: 20px; background: #eff6ff; border-radius: 12px; border: 1px solid #bfdbfe;">
            <h3 style="color: #1e40af; margin-bottom: 8px;">🎯 Next Steps</h3>
            <ol style="margin-left: 20px; color: #1d4ed8; font-size: 14px; line-height: 2;">
                <li>Download RunwayHub from GitHub</li>
                <li>Follow the setup guide in SETUP.md</li>
                <li>Configure your database and settings</li>
                <li>Start managing your VA network</li>
                <li>Explore the API documentation</li>
            </ol>
        </div>
    </div>
</body>
</html>