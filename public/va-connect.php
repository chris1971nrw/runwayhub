<?php

declare(strict_types=1);

/**
 * RunwayHub - VA Connect
 * Connect your Visual Air Traffic Controller
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
    <title>VA Connect - RunwayHub</title>
    <meta name="description" content="Connect your Visual Air Traffic Controller">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .container { background: white; border-radius: 16px; padding: 40px; max-width: 600px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { font-size: 28px; color: #1a365d; margin-bottom: 8px; }
        .header p { color: #6b7280; font-size: 14px; }
        .connect-form { display: flex; flex-direction: column; gap: 15px; margin-bottom: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group label { font-size: 14px; color: #374151; font-weight: 500; }
        .form-group input { padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; background: #f9fafb; }
        .form-group input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .btn { padding: 14px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-primary:hover { background: #1d4ed8; }
        .info { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 15px; margin-bottom: 20px; font-size: 13px; color: #1e40af; }
        .info ul { margin-left: 20px; color: #1e40af; }
        .info li { margin-bottom: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔗 VA Connect</h1>
            <p>Connect your Visual Air Traffic Controller</p>
        </div>
        
        <div class="info">
            <strong>📡 Connection Information</strong><br>
            Connect your VA by providing your connection credentials below.
        </div>
        
        <form class="connect-form" action="/va-connect.php" method="post">
            <div class="form-group">
                <label for="va-id">VA Identifier</label>
                <input type="text" id="va-id" name="va_id" placeholder="Enter your VA identifier" required>
            </div>
            
            <div class="form-group">
                <label for="api-key">API Key</label>
                <input type="password" id="api-key" name="api_key" placeholder="Enter your API key" required>
            </div>
            
            <div class="form-group">
                <label for="server">Server Address (optional)</label>
                <input type="text" id="server" name="server" placeholder="https://your-server.com">
            </div>
            
            <button type="submit" class="btn btn-primary">Connect</button>
        </form>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="/" style="color: #6b7280; text-decoration: none; font-size: 14px;">← Back to Home</a>
        </div>
    </div>
</body>
</html>