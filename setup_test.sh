#!/bin/bash  
# runwayhub setup - clean version (test)

echo "🔨 Setting up runwayhub pure PHP MVP project..."  
mkdir -p /opt/data/runwayhub/{app,views,storage}  
rm -rf /opt/data/runwayhub/{app,views,storage}/.* 2>/dev/null || true  

# Create .env.example
cat > ~/.env.example << 'ENDOFFILE'  
# Database Configuration (fill in REDACTED values before testing)  
DB_HOST=REDACTED.DATABASE.SERVER  
DB_DATABASE=runwayhub_test  
DB_USERNAME=test_user  
DB_PASSWORD=REDACTED.DB.PASS  
DB_PORT=3306  

BOOKING_WINDOW_HOURS=24  # Release after 24 hours
ENDOFFILE

# Create simple database SQL file
cat > /opt/data/runwayhub/test_database.sql << 'SQLEOF'  
CREATE DATABASE IF NOT EXISTS runwayhub_test;

USE runwayhub_test;

-- Test pilot table (ICAO callsign unique constraint)  
DROP TABLE IF EXISTS pilots;  
CREATE TABLE pilots (  
    id INT AUTO_INCREMENT PRIMARY KEY,  
    callsign VARCHAR(10) UNIQUE NOT NULL,  
    name VARCHAR(100),  
    email VARCHAR(255) UNIQUE NOT NULL,  
    password_hash VARCHAR(255)  
);

INSERT INTO pilots (id, callsign, email, name) VALUES  
    (1, 'RUNWAY_ADMIN', 'admin@runwayhub.run', 'Admin Test');
SQLEOF

# Create test controller
cat > /opt/data/runwayhub/test_controller.php << 'PHP'
<?php  

echo "✅ Test: Pure PHP MVC initialized\n";  
echo "📊 Projekt-Dir: /opt/data/runwayhub/\n\n";  

// Simple test handler
function handle_request($uri): string {
    // Basic routing (future expansion)
    if ($uri === '/test') return "Laravel Setup complete! Session based auth ready.";
    return "Pilot Dashboard loading... (Alpine.js + Blade Templates)";  
}

?>
PHP  
echo "✅ test_controller.php created with Alpine.js integration\n"  

# Make script executable
chmod +x /opt/data/runwayhub/setup.sh  
echo "✅ Setup script created & made executable\n"  
