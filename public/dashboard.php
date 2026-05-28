<?php

declare(strict_types=1);

/**
 * RunwayHub - Dashboard
 */

// Set headers
header('Content-Type: text/html; charset=utf-8');
header('X-Content-Type-Options: nosniff');

// Display dashboard (for demo, show welcome message)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#667eea">
    <meta name="msapplication-TileColor" content="#667eea">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    <!-- Primary Meta Tags -->
    <title>Dashboard - RunwayHub - Free Visual Air Traffic Controller Software</title>
    <meta name="title" content="Dashboard - RunwayHub - Free Visual Air Traffic Controller Software">
    <meta name="description" content="RunwayHub Dashboard - Welcome to your free Visual Air Traffic Controller Hub. Access your flight tracking, weather data, and VA management interface. Secure, self-hosted, and open-source.">
    <meta name="keywords" content="dashboard, visual air traffic controller, ATC, flight tracking, weather, METAR, TAF, aviation software, FBO, airport operations">
    <meta name="author" content="RunwayHub Team">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://runwayhub.github.io/dashboard.php">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://runwayhub.github.io/dashboard.php">
    <meta property="og:title" content="Dashboard - RunwayHub - Free Visual Air Traffic Controller Software">
    <meta property="og:description" content="Access your flight tracking, weather data, and VA management. Open source, free, self-hosted.">
    <meta property="og:image" content="https://runwayhub.github.io/assets/og-image.jpg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="RunwayHub Dashboard - Flight Tracking Interface">
    <meta property="og:locale" content="en_DE">
    <meta property="og:site_name" content="RunwayHub">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="https://runwayhub.github.io/dashboard.php">
    <meta name="twitter:title" content="Dashboard - RunwayHub">
    <meta name="twitter:description" content="Access your flight tracking, weather data, and VA management. Open source, free, self-hosted.">
    <meta name="twitter:image" content="https://runwayhub.github.io/assets/og-image.jpg">
    <meta name="twitter:site" content="@runwayhub">
    <meta name="twitter:creator" content="@chris1971nrw">
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "Dashboard - RunwayHub",
      "description": "RunwayHub Dashboard - Welcome to your free Visual Air Traffic Controller Hub.",
      "url": "https://runwayhub.github.io/dashboard.php",
      "inLanguage": "en",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://runwayhub.github.io/search.php?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8fafc; color: #1e293b; line-height: 1.6; }
        .dashboard { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 12px; margin-bottom: 20px; }
        .header h1 { font-size: 32px; margin-bottom: 8px; }
        .header p { opacity: 0.9; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .card h2 { color: #1a365d; margin-bottom: 12px; font-size: 18px; }
        .card p { color: #64748b; font-size: 14px; }
        .btn { display: inline-block; padding: 10px 20px; background: #2563eb; color: white; text-decoration: none; border-radius: 8px; font-weight: 500; transition: background 0.2s; }
        .btn:hover { background: #1d4ed8; }
        .cta { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 24px; text-align: center; }
        .cta h2 { color: #1e40af; margin-bottom: 8px; }
        .cta p { color: #1d4ed8; margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="header">
            <h1>🎯 Welcome to RunwayHub</h1>
            <p>Your Free Visual Air Traffic Controller Hub</p>
        </div>
        
        <div class="grid">
            <div class="card">
                <h2>🚀 Get Started</h2>
                <p>Download RunwayHub and start managing your visual air traffic controller network in minutes.</p>
                <a href="https://github.com/chris1971nrw/runwayhub" target="_blank" rel="noopener noreferrer" class="btn">Download from GitHub</a>
            </div>
            
            <div class="card">
                <h2>📚 Documentation</h2>
                <p>Check out the setup guide and API documentation to learn how to use RunwayHub.</p>
                <a href="/SETUP.md" class="btn">View Documentation</a>
            </div>
            
            <div class="card">
                <h2>🔧 Features</h2>
                <ul style="margin-left: 20px; color: #64748b; font-size: 14px;">
                    <li>Multi-airline support</li>
                    <li>Live flight tracking</li>
                    <li>Weather API integration</li>
                    <li>VA management system</li>
                    <li>Leaderboards & PIREPs</li>
                </ul>
            </div>
            
            <div class="card">
                <h2>🔒 Security</h2>
                <p>RunwayHub includes industry-standard security features:</p>
                <ul style="margin-left: 20px; color: #64748b; font-size: 14px;">
                    <li>Password hashing (bcrypt)</li>
                    <li>CSRF protection</li>
                    <li>XSS prevention</li>
                    <li>Rate limiting</li>
                </ul>
            </div>
        </div>
        
        <div class="cta">
            <h2>Ready to Optimize Your Operations?</h2>
            <p>Join the aviation community and streamline your FBO operations with RunwayHub.</p>
            <a href="https://github.com/chris1971nrw/runwayhub" target="_blank" rel="noopener noreferrer" class="btn" style="background: #1a365d;">Get Started Now</a>
        </div>
    </div>
</body>
</html>