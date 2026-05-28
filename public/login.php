<?php

declare(strict_types=1);

/**
 * RunwayHub - Login Page
 * Demo login interface with SQLite authentication
 */

// Set headers
header('Content-Type: text/html; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');

// Include bootstrap if running on server
if (file_exists(dirname(dirname(dirname(__DIR__))).'/runwayhub/src/core/Bootstrap.php')) {
    // Try to load application context
    try {
        require_once dirname(dirname(dirname(__DIR__))).'/runwayhub/src/core/Bootstrap.php';
    } catch (\Exception $e) {
        // Ignore if bootstrap not available yet
    }
}

// Display demo login form
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#1a365d">
    <meta name="msapplication-TileColor" content="#1a365d">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    <!-- Primary Meta Tags -->
    <title>Login - RunwayHub - Demo Access</title>
    <meta name="title" content="Login - RunwayHub - Demo Access">
    <meta name="description" content="RunwayHub demo login. Demo mode enabled. Download from GitHub to set up your own installation. Open source, free, self-hosted Visual Air Traffic Controller Hub.">
    <meta name="keywords" content="login, demo, authentication, demo mode, runways hub, air traffic controller, ATC, aviation software, flight tracking">
    <meta name="author" content="RunwayHub Team">
    <meta name="robots" content="noindex, nofollow">
    <link rel="canonical" href="https://runwayhub.github.io/login.php">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://runwayhub.github.io/login.php">
    <meta property="og:title" content="Login - RunwayHub - Demo Access">
    <meta property="og:description" content="Demo login for RunwayHub. Download from GitHub to set up your own installation.">
    <meta property="og:image" content="https://runwayhub.github.io/assets/og-image.jpg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="RunwayHub Login - Demo Access">
    <meta property="og:locale" content="en_DE">
    <meta property="og:site_name" content="RunwayHub">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="https://runwayhub.github.io/login.php">
    <meta name="twitter:title" content="Login - RunwayHub - Demo Access">
    <meta name="twitter:description" content="Demo login for RunwayHub. Download from GitHub to set up your own installation.">
    <meta name="twitter:image" content="https://runwayhub.github.io/assets/og-image.jpg">
    <meta name="twitter:site" content="@runwayhub">
    <meta name="twitter:creator" content="@chris1971nrw">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: #1a365d;
            font-size: 28px;
            margin-bottom: 8px;
        }
        
        .login-header p {
            color: #6b7280;
            font-size: 14px;
        }
        
        .demo-alert {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #92400e;
        }
        
        .demo-accounts {
            background: #f3f4f6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .demo-accounts h3 {
            color: #374151;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .demo-accounts ul {
            list-style: none;
        }
        
        .demo-accounts li {
            font-size: 13px;
            color: #6b7280;
            padding: 6px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .demo-accounts li:last-child {
            border-bottom: none;
        }
        
        .demo-accounts a {
            color: #2563eb;
            text-decoration: none;
        }
        
        .demo-accounts a:hover {
            text-decoration: underline;
        }
        
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        
        .form-group label {
            font-size: 14px;
            color: #374151;
            font-weight: 500;
        }
        
        .form-group input {
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        
        .btn {
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background: #2563eb;
            color: white;
        }
        
        .btn-primary:hover {
            background: #1d4ed8;
        }
        
        .btn-secondary {
            background: #6b7280;
            color: white;
        }
        
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 13px;
            color: #6b7280;
        }
        
        .footer a {
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>🔐 Login</h1>
            <p>RunwayHub - Visual Air Traffic Controller Hub</p>
        </div>
        
        <div class="demo-alert">
            <strong>📢 Demo Mode</strong><br>
            This is a demo login page. To use RunwayHub, download from <a href="https://github.com/chris1971nrw/runwayhub">GitHub</a> and set up your own installation.
        </div>
        
        <div class="demo-accounts">
            <h3>🎭 Demo Accounts</h3>
            <ul>
                <li><strong>Demo Pilot:</strong> Use email <code>demo_pilot@example.com</code></li>
                <li><strong>Default:</strong> Leave fields empty for demo access</li>
                <li><a href="https://github.com/chris1971nrw/runwayhub">Learn more on GitHub →</a></li>
            </ul>
        </div>
        
        <form class="login-form" action="/login.php" method="post">
            <div class="form-group">
                <label for="email">Email (optional for demo)</label>
                <input type="email" id="email" name="email" placeholder="Enter email or leave empty" autocomplete="email">
            </div>
            
            <div class="form-group">
                <label for="password">Password (optional for demo)</label>
                <input type="password" id="password" name="password" placeholder="Enter password or leave empty" autocomplete="current-password">
            </div>
            
            <button type="submit" class="btn btn-primary">Login</button>
            
            <button type="button" class="btn btn-secondary">
                <a href="/">Cancel</a>
            </button>
        </form>
        
        <div class="footer">
            <p>Not ready to login? <a href="/">Go to homepage</a></p>
            <p>Documentation: <a href="/SETUP.md">Setup Guide</a></p>
        </div>
    </div>
</body>
</html>