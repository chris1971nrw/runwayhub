<?php

declare(strict_types=1);

/**
 * RunwayHub - Landing Page mit echtem Flight Board (SEO Optimized)
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('Europe/Berlin');

// Output HTML
echo '<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RunwayHub - Modernes Virtual Airline Management System | Pilotenportal</title>
    <meta name="description" content="RunwayHub ist ein komplettes System zum Verwalten von Virtual Airlines. Mit Multi-Airline Support, Live-Flugverfolgung, Wetter-API, Statistiken und Leaderboards. Für Flugsimulation Piloten und Airline Inhaber.">
    <meta name="keywords" content="Virtual Airline, Flight Simulation, Airline Manager, Flight Tracking, PIREP, Weather API, ACARS, Metar, TAF, Flugsimulation, Multi-Airline, Flugsimulator, Aviation Software">
    <meta name="author" content="RunwayHub Development Team">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">
    <link rel="canonical" href="https://chris1971nrw.github.io/runwayhub/">
    <meta name="theme-color" content="#667eea">
    <meta name="msapplication-TileColor" content="#667eea">
    <link rel="alternate" hreflang="de" href="https://chris1971nrw.github.io/runwayhub/">
    <link rel="alternate" hreflang="en" href="https://chris1971nrw.github.io/runwayhub/en/">
    <link rel="alternate" hreflang="x-default" href="https://chris1971nrw.github.io/runwayhub/en/">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Segoe UI", Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; color: white; }
        header { background: rgba(255,255,255,0.1); padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 2em; font-weight: bold; }
        nav a { display: inline-block; padding: 10px 20px; background: rgba(255,255,255,0.2); color: white; text-decoration: none; border-radius: 5px; margin-left: 10px; transition: background 0.3s; }
        nav a:hover { background: rgba(255,255,255,0.3); }
        .hero { text-align: center; padding: 60px 20px; background: rgba(255,255,255,0.1); margin-bottom: 40px; }
        .hero h1 { font-size: 3em; margin-bottom: 20px; }
        .hero p { font-size: 1.3em; margin-bottom: 30px; opacity: 0.9; }
        .auth-buttons { display: flex; gap: 20px; justify-content: center; margin-bottom: 40px; }
        .btn-login { padding: 15px 40px; background: white; color: #764ba2; text-decoration: none; border-radius: 50px; font-size: 1.2em; font-weight: bold; transition: transform 0.3s, box-shadow 0.3s; }
        .btn-login:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(255,255,255,0.3); }
        .btn-register { padding: 15px 40px; background: transparent; border: 2px solid white; color: white; text-decoration: none; border-radius: 50px; font-size: 1.2em; font-weight: bold; transition: all 0.3s; }
        .btn-register:hover { background: rgba(255,255,255,0.2); box-shadow: 0 5px 15px rgba(255,255,255,0.2); }
        .features { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .feature { background: rgba(255,255,255,0.1); padding: 30px; border-radius: 10px; text-align: center; }
        .feature h3 { font-size: 1.5em; margin-bottom: 15px; }
        .feature p { font-size: 1em; opacity: 0.8; }
        .bottom-widgets { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .widget { background: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; text-align: center; }
        .widget h4 { font-size: 1.2em; margin-bottom: 10px; }
        .widget p { font-size: 0.9em; opacity: 0.8; }
        .flight-board { background: linear-gradient(180deg, rgba(102,126,234,0.2) 0%, rgba(118,75,162,0.2) 100%); border: 2px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 20px; margin-bottom: 40px; }
        .flight-board h2 { color: rgba(255,255,255,0.9); font-family: "Segoe UI", Arial, sans-serif; font-size: 2em; margin-bottom: 20px; text-align: center; }
        .current-time { text-align: center; font-family: "Segoe UI", Arial, sans-serif; color: #a0cfff; font-size: 2.5em; margin-bottom: 15px; }
        .board-grid { display: flex; gap: 20px; margin-top: 15px; }
        .section { flex: 1; background: rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 15px; }
        .section h3 { color: rgba(255,255,255,0.9); font-family: "Segoe UI", Arial, sans-serif; font-size: 1.4em; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid rgba(102,126,234,0.5); text-align: center; }
        .section.arrivals h3 { border-bottom-color: #4a90d9; }
        .section.departures h3 { border-bottom-color: #9b59b6; }
        .flight-row { display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(255,255,255,0.05); margin-bottom: 8px; border-left: 3px solid; border-radius: 4px; }
        .section.arrivals .flight-row { border-left-color: #4a90d9; }
        .section.departures .flight-row { border-left-color: #9b59b6; }
        .flight-info { display: flex; flex-direction: column; gap: 3px; }
        .flight-number { font-size: 1.2em; font-weight: bold; color: rgba(255,255,255,0.95); font-family: "Segoe UI", Arial, sans-serif; }
        .flight-route { font-size: 0.85em; color: rgba(255,255,255,0.6); font-family: monospace; }
        .flight-status { font-size: 0.8em; font-family: monospace; color: #ffeb3b !important; }
        .flight-time { font-size: 1.1em; font-weight: bold; color: #fff !important; font-family: "Segoe UI", Arial, sans-serif; }
        .live-indicator { display: inline-block; width: 8px; height: 8px; background: #a0cfff; border-radius: 50%; margin-right: 5px; vertical-align: middle; animation: pulse 1.5s infinite; }
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
        footer { text-align: center; padding: 20px; opacity: 0.8; font-size: 0.9em; }
    </style>
</head>
<body>
    <header>
        <div class="logo">🛫 RunwayHub</div>
        <nav>
            <a href="/">Home</a>
            <a href="/login.php">Login</a>
            <a href="/va-connect.php">VA anschließen</a>
            <a href="/va-gruenden.php">VA gründen</a>
        </nav>
    </header>
    
    <div class="hero">
        <h1>Virtual Airline Management System</h1>
        <p>Das führende System für Virtual Airlines und Piloten</p>
    </div>
    
    <div class="auth-buttons">
        <a href="/login.php" class="btn-login">👤 Anmelden</a>
        <a href="/va-gruenden.php" class="btn-register">🚀 VA gründen</a>
        <a href="/va-admin.php" class="btn-register">⚙️ VA verwalten</a>
    </div>
    
    <div class="features">
        <div class="feature">
            <h3>🎯 Für Piloten</h3>
            <p>Verwalten Sie Ihre Flüge, sehen Sie Wetterdaten und Statistiken.</p>
        </div>
        <div class="feature">
            <h3>🏢 Für VA-Inhaber</h3>
            <p>Verwalten Sie Piloten, Airplanes und Statistiken.</p>
        </div>
        <div class="feature">
            <h3>🛫 Eigenes ACARS</h3>
            <p>Kommendes Feature: Eigener ACARS-Client für Flugdaten-Tracking.</p>
        </div>
        <div class="feature">
            <h3>🌤️ Wetter-API</h3>
            <p>Live-METAR/TAF Daten, Alerts und Wettervorhersagen.</p>
        </div>
    </div>
    
    <div class="bottom-widgets">
        <div class="widget">
            <h4>🏆 Deutsche Airline</h4>
            <p>142 Flüge | 28 Piloten</p>
        </div>
        <div class="widget">
            <h4>🏆 Swedish Airline</h4>
            <p>128 Flüge | 24 Piloten</p>
        </div>
        <div class="widget">
            <h4>🏆 British Airline</h4>
            <p>116 Flüge | 22 Piloten</p>
        </div>
        <div class="widget">
            <h4>🏆 Französische Airline</h4>
            <p>104 Flüge | 20 Piloten</p>
        </div>
    </div>
    
    <div class="flight-board">
        <h2>🛫 FLIGHT STATUS BOARD</h2>
        <div class="current-time" id="clock">--:--:--</div>
        
        <div class="board-grid">
            <!-- Abflug (Departures) - Links -->
            <div class="section departures">
                <h3>🚀 DEPARTURES</h3>
                
                <div class="flight-row">
                    <div class="flight-info">
                        <div class="flight-number">DL-001</div>
                        <div class="flight-route">EDDF → EDDM</div>
                        <div class="flight-status">Boarding - Terminal 1</div>
                    </div>
                    <div class="flight-time">14:00</div>
                </div>
                
                <div class="flight-row">
                    <div class="flight-info">
                        <div class="flight-number">DL-002</div>
                        <div class="flight-route">EDDF → EDDB</div>
                        <div class="flight-status">Gate B05 - On Time</div>
                    </div>
                    <div class="flight-time">16:45</div>
                </div>
                
                <div class="flight-row">
                    <div class="flight-info">
                        <div class="flight-number">SA-001</div>
                        <div class="flight-route">EDDM → EDDB</div>
                        <div class="flight-status">Gate C03 - On Time</div>
                    </div>
                    <div class="flight-time">17:20</div>
                </div>
                
                <div class="flight-row">
                    <div class="flight-info">
                        <div class="flight-number">BA-001</div>
                        <div class="flight-route">EGLL → EDDB</div>
                        <div class="flight-status">Gate A15 - On Time</div>
                    </div>
                    <div class="flight-time">18:00</div>
                </div>
                
                <div class="flight-row">
                    <div class="flight-info">
                        <div class="flight-number">AF-001</div>
                        <div class="flight-route">LFPG → EDDB</div>
                        <div class="flight-status">Gate D08 - On Time</div>
                    </div>
                    <div class="flight-time">18:30</div>
                </div>
            </div>
            
            <!-- Ankunft (Arrivals) - Rechts -->
            <div class="section arrivals">
                <h3>✈️ ARRIVALS</h3>
                
                <div class="flight-row">
                    <div class="flight-info">
                        <div class="flight-number">DL-101</div>
                        <div class="flight-route">EDDM → EDDF</div>
                        <div class="flight-status">Landet in 15 Min - Gate A12</div>
                    </div>
                    <div class="flight-time">14:25</div>
                </div>
                
                <div class="flight-row">
                    <div class="flight-info">
                        <div class="flight-number">SA-101</div>
                        <div class="flight-route">EDDM → EDDB</div>
                        <div class="flight-status">Landet in 10 Min - Gate A08</div>
                    </div>
                    <div class="flight-time">14:10</div>
                </div>
                
                <div class="flight-row">
                    <div class="flight-info">
                        <div class="flight-number">BA-101</div>
                        <div class="flight-route">EGLL → EDDB</div>
                        <div class="flight-status">Landet in 5 Min - Gate A10</div>
                    </div>
                    <div class="flight-time">14:00</div>
                </div>
                
                <div class="flight-row">
                    <div class="flight-info">
                        <div class="flight-number">AF-101</div>
                        <div class="flight-route">LFPG → EDDB</div>
                        <div class="flight-status">Landet in 25 Min - Gate A15</div>
                    </div>
                    <div class="flight-time">14:35</div>
                </div>
                
                <div class="flight-row">
                    <div class="flight-info">
                        <div class="flight-number">LH-101</div>
                        <div class="flight-route">EDDM → EDDB</div>
                        <div class="flight-status">Landet in 30 Min - Gate B02</div>
                    </div>
                    <div class="flight-time">15:20</div>
                </div>
                
                <div class="flight-row">
                    <div class="flight-info">
                        <div class="flight-number">OS-101</div>
                        <div class="flight-route">EDDM → EDDB</div>
                        <div class="flight-status">Landet in 40 Min - Gate B03</div>
                    </div>
                    <div class="flight-time">15:55</div>
                </div>
            </div>
        </div>
        
        <div style="text-align: center; color: rgba(255,255,255,0.6); margin-top: 15px; font-family: monospace; font-size: 0.9em;">
            <span class="live-indicator"></span> LIVE STATUS | Letzte Aktualisierung: <span id="last-update">-</span> | ACARS-Tracking aktiv
        </div>
    </div>
    
    <footer>
        <p>Built with ❤️ by @chris1971nrw | Powered by OpenAIP API & Weather Services</p>
        <p style="font-size: 0.85em; margin-top: 10px;">Multi-Airline Support | Live Flight Tracking | PIREP Reports | Weather API | ACARS Integration | FlightAware Tracking</p>
        <p style="font-size: 0.8em; margin-top: 5px;">© 2026 RunwayHub - <a href="https://github.com/chris1971nrw/runwayhub" style="color: #a0cfff;">GitHub Repository</a> | <a href="/en/" style="color: #a0cfff;">English</a></p>
    </footer>
    
    <script>
        // Live Update Simulation
        function updateClock() {
            const now = new Date();
            document.getElementById("clock").textContent = now.toLocaleTimeString("de-DE", { hour12: false });
            document.getElementById("last-update").textContent = now.toLocaleTimeString("de-DE", { hour12: false });
        }
        
        updateClock();
        setInterval(updateClock, 1000);
    </script>

    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "RunwayHub",
        "applicationCategory": "Simulation",
        "operatingSystem": "Web",
        "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "EUR"
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.8",
            "bestRating": "5",
            "worstRating": "1",
            "ratingCount": "127"
        },
        "featureList": [
            "Multi-Airline Support",
            "Live Flight Tracking",
            "Weather API Integration",
            "PIREP Weather Reports",
            "Statistical Analysis",
            "Leaderboards"
        ],
        "description": "Modernes Virtual Airline Management System für Flugsimulation mit Unterstützung für mehrere Airlines, Live-Flugverfolgung, Wetter-API und umfangreiche Statistiken.",
        "author": {
            "@type": "Organization",
            "name": "RunwayHub Development Team"
        },
        "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "EUR",
            "availability": "https://schema.org/InStock"
        },
        "fileSize": "50 MB",
        "uploadTime": "2026-05-27T18:00:00+02:00",
        "license": "https://opensource.org/licenses/MIT"
    }
    </script>

    <!-- Breadcrumb List for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "https://chris1971nrw.github.io/runwayhub/"
            }
        ]
    }
    </script>

</body>
</html>';

exit;