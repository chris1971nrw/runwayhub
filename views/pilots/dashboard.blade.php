<!DOCTYPE html>
<html {{ dataTheme ?? 'light' }}>
<head>
    <title>Pilot Dashboard</title>
    <!-- Alpine.js CDN integration -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.6/dist/cdn.min.js"></script>
    <style>
        :root {
            --primary: #1a73e8;
            --secondary: #5f6368;
            --success: #1e8e3e;
            --warning: #f9ab00;
            --danger: #db4437;
            --background: #ffffff;
            --surface: #f8f9fa;
            --text: #202124;
            --border: #dadce0;
        }
        
        {{ themeMode ?? 'light' === 'dark' ? ':root { --primary: #8ab4f8; --secondary: #b0b5bb; --background: #1e1e1e; --surface: #2d2d2d; --text: #e8e8e8; --border: #444444; }' : '' }}
        
        body {
            font-family: 'Roboto', system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 24px;
            background: var(--surface);
            color: var(--text);
        }
        
        h1 {
            margin-bottom: 24px;
            font-size: 28px;
        }
        
        .weather-widget {
            background: var(--background);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        }
        
        .weather-widget h2 {
            margin: 0 0 12px 0;
            font-size: 18px;
        }
        
        .flight-list {
            display: grid;
            gap: 16px;
        }
        
        .flight-card {
            background: var(--background);
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        }
        
        .flight-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        
        .flight-number {
            font-size: 20px;
            font-weight: 500;
        }
        
        .flight-route {
            color: var(--secondary);
            font-size: 14px;
        }
        /* Alpine.js stub - replace with API integration */
        .flights-placeholder {
            background: var(--surface);
            border: 2px dashed var(--border);
            border-radius: 8px;
            padding: 32px;
            text-align: center;
            color: var(--secondary);
        }
    </style>
</head>
<body x-data="{ flights: [], loading: true, loadingError: null }" 
     x-init="async () => { try { const res = await fetch('/api/pilot/flights'); flights = await res.json(); } catch (e) { loadingError = e.message; } finally { loading = false; } }">
    <h1>Pilot Dashboard - {{user_name}}</h1>
    
    <!-- Weather Widget Placeholder -->
    <div class="weather-widget" 
         x-data="{ weather: { current: {}, forecast: {} } }"
         x-on:loaded.window="fetchWeather() || null">
        <h2>Weather Overview</h2>
        <div>
            <!-- Weather information placeholder - integrate with aviation weather API -->
            <p class="weather-placeholder" x-show="!weather.current.airport">Loading weather data...</p>
            
            <template x-if="weather.current.airport">
                <div :class="{ 'storm-warning': weather.current.thunderstorm, 'rainy': weather.current.precipitation }">
                    <div><span x-text="weather.current.temp"></span>°C</div>
                    <div><span x-text="weather.current.wind_speed"></span> knots</div>
                    <div><span x-text="weather.current.visibility"></span> visibility</div>
                </div>
            </template>
        </div>
    </div>
    
    <!-- Flight Listings -->
    <div class="flight-list">
        <!-- Pending/Active Flights -->
        <template x-for="flight in flights.pending" :key="flight.id">
            <div class="flight-card">
                <div class="flight-header">
                    <span class="flight-number" x-text="'Flight ' + flight.call_sign"></span>
                    <span class="badge status-pending" style="background: var(--warning); padding: 4px 12px; border-radius: 999px; font-size: 12px;">Pending</span>
                </div>
                <div class="flight-route">
                    <span x-text="flight.departure"></span> → <span x-text="flight.arrival"></span> | 
                    Departures in <strong x-text="formatDuration(flight.departure_time - Date.now())"></strong>
                </div>
            </div>
        </template>
        
        <!-- Active Flights -->
        <template x-for="flight in flights.active" :key="flight.id">
            <div class="flight-card">
                <div class="flight-header">
                    <span class="flight-number" x-text="'Flight ' + flight.call_sign"></span>
                    <span class="badge status-active" style="background: var(--primary); padding: 4px 12px; border-radius: 999px; font-size: 12px;">In Air</span>
                </div>
                <div class="flight-route">
                    <span x-text="flight.departure"></span> —●— <span x-text="flight.arrival"></span>
                    <div style="margin-top: 8px; font-size: 13px;">
                        Altitude: <span x-text="flight.altitude"></span> ft | 
                        Speed: <span x-text="flight.airspeed"></span> kts |
                        Heading: <span x-text="flight.heading">°</span>
                    </div>
                </div>
            </div>
        </template>
        
        <!-- Scheduled Flights -->
        <template x-for="flight in flights.scheduled" :key="flight.id">
            <div class="flight-card">
                <div class="flight-header">
                    <span class="flight-number" x-text="'Flight ' + flight.call_sign"></span>
                    <span class="badge status-scheduled" style="background: var(--success); padding: 4px 12px; border-radius: 999px; font-size: 12px;">Scheduled</span>
                </div>
                <div class="flight-route">
                    <span x-text="flight.departure"></span> → <span x-text="flight.arrival"></span> at 
                    <strong x-text="'HH:MM' | formatTime(flight.estimated_takeoff_at)"></strong>
                </div>
            </div>
        </template>
        
        <!-- Completion Summary -->
        <template x-for="flight in flights.completed" :key="flight.id">
            <div class="flight-card" style="opacity: 0.8;">
                <div class="flight-header">
                    <span class="flight-number" x-text="'Flight ' + flight.call_sign"></span>
                    <span class="badge status-completed" style="background: var(--surface); padding: 4px 12px; border-radius: 999px; font-size: 12px;">Completed</span>
                </div>
                <div class="flight-route">
                    <span x-text="flight.departure"></span> → <span x-text="flight.arrival"></span> via <span x-text="flight.route"></span>
                    <div style="margin-top: 8px; font-size: 13px;">
                        Duration: <strong x-text="'HH:MM' | formatDuration(flight.duration)"></strong>
                    </div>
                </div>
            </div>
        </template>
    </div>
</body>
</html>
