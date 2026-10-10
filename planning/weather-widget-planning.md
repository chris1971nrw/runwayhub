# runwayhub - Weather-Widget für Flugsimulator & LBA-Kompliantes Planning

## 🎯 Anforderung

**Weather Widget Purpose:**
- Vorhersage für Simulationsflüge (MSFS/X-Plane)  
- Wetter-Icons für Pilot-Dashboards (Sicht, Wind, Wolken)
- Flight-Schedule Planning basierend auf aktuellen Bedingungen
- Auto-Optimierung von Fluggeschwindigkeit bei schlechtem Wetter

---

## 📋 Design-Entscheidungen

### 1. Weather Data Sources (LBA-Kompliant APIs)

**Option A: Open-Meteo API (Free & Reliable)**
```yaml
Provider: Open-Meteo
Endpoints:
  - https://api.open-meteo.com/v1/forecast?latitude={lat}&longitude={lon} 
    &daily=weathercode,surface_solar_radiation_downward,windspeed_10m 
    &hourly=windspeed_10m,temperature_2m,visibility,precipitation
  - https://api.open-meteo.com/v1/airports?codes={list}
```

**Option B: Aviation Weather (FAA METAR/TAF)**  
```yaml
Provider: NOAA Aviation Weather
Endpoints:
  - https://aviationweather.gov/api/metar/data?format=json&ids={airport_code}
  - https://aviationweather.gov/api/metar/data?t+{utc_datetime}&types={METAR,TAF},json 
    &ids={list,airports}&lat=&lon=

```

**Empfhlung für MVP: Open-Meteo API** (Free tier + Simulations-Community-Friendly)

---

### 2. Wetter-Kategorien für Flugsimulator-Planning  

**Metar Codes & Interpretation:**

| Weather Code | Description | Flight Suitability |
|--------------|-------------|---------------------|
| 0 = Clear | Sonnenschein | ⭐⭐⭐ Excellent (Optimal) |
| 1-2 = Cloudy | Cirrus/Cumulus | ⭐⭐⭐ Good for visual flight |
| 3+ = Turbulence | Cumulonimbus, Thunderstorms | ❌ Avoid (ICAO Flight Planning) |

**Widget Components:**

```yaml
Weather Widget Display:
┌─────────────────────────────────────┐
│ ✈️  Weather Conditions              │  
├─────────────────────────────────────┤
│ Visibility: 15km                    │ ← Green > 800m for VFR  
│ Windspeed at Elevation: 22kt       │ ← < 30kt = Good takeoff
│ Dewpoint Differential: +4°C        │ ← < 10deg = Low cloud risk  
│ Ceiling: 4500ft overcast           │ ← > 3000ft acceptable?  

Flight Suitability Score: ⭐⭐⭐⭐⭐     │ ← Auto-calc based on above
└─────────────────────────────────────┘
```

---

### 3. Datenbank Design - flight_logs Erweiterung mit Wetter-Daten

**Migration Blueprint:**

```sql
-- Add weather_data JSON field to flight_logs (if real flights logged)  
ALTER TABLE flight_schedules ADD COLUMN 'weather_conditions' JSON NULL AFTER aircraft_type,  

-- Optional: Add forecast_cache for performance  
ALTER TABLE flight_schedules ADD 'forecast_cache' JSON NULL;  -- Cache für next_24_hours

```

**Flottenmanagement Erweiterung:**

```sql
-- Aircraft Performance Degradation bei schlechtem Wetter  
ALTER TABLE flights_schedule ADD COLUMN weather_adjusted_speed_knots decimal(10,2);

-- Add forecast warning field (optional LBA Advisory?)  
ALTER TABLE flight_schedules ADD COLUMN weather_advisory ENUM('ok', 'turbulence_expected', 'avoid_conditions');
```

---

### 4. Laravel API Implementation Blueprint

**app/Services/WeatherService.php:**

```php
class WeatherService {
    
    /*** Fetch Open-Meteo Forecast for Airport Coordinates ***/  
    public static function getForecast(string $from_icao, 
                                        string $to_icao, 
                                        int $hours = 24): array {
        
        // 1. Lookup airport coordinates (database or fallback to geo-api)  
            $airport_data = self::getAirportCoordinates($from_icao);
            
        // 2. Fetch Open-Meteo API for hourly forecast  
           $response = https_get_request('forecast', [
            'latitude': $from_lat,
            'longitude': $from_lon, 
            'hourly' => ['windspeed_10m', 'temperature_2m, precipitation'],
            'daily' => ['weathercode, visibility'],
        ]);

        return new Forecast($response); // Parse & Cache to DB or Redis  
    }

     /*** Fetch METAR (Aviation) for Airport Code ***/  
    public static function getMetar(string $airport_code): array { 
       
       return HTTP::get("aviationweather/api/metar/data?ids={airport_code}...
    
        // Parse METAR format: [METAR EDDM 101730Z 270/10KT 9999 FEW040  
             ...]  → Convert to Widget-Friendly JSON

    }

     /*** Calculate Flight Suitability Score **/
    
    public static function calculateFlightSuitabilityScore(Visibility, 
                                                             Windspeed, 
                                                                 Dewpoint): string {
       
       score = 5; // default ok
      
     if (visibility > 800 && wind < 30 && dewpoint < 10) {  
         return 'excellent';  // VFR conditions safe for visual flight?
    } elseif (visibility > 500 && wind <= 40) {  
        return 'good';      // Instrument approach needed? Or IFR minimums?
       } else if (visibility <= 800 || wind <= 50) { 
            return 'poor';   // Warning for pilots about turbulence conditions.
     } else {
         return 'avoid';    // Thunderstorm, icing conditions...
    }
}

```

---

### 5. Widget-Komponenten (Alpine.js Components)

**resources/views/admin/dashboard_weather_widget.blade.php:**

```html
<div x-data="{ 
       weatherData: null, 
          loading: true, 
          
           methods: {
               async fetchWeather(airport_code): Promise<void> { 
                   this.loading = false; 
                   
                   const response = await WeatherService::getForecast(airport_code); 
                   if (response.success) {  
                       this.weather_data = response.data; 
                   } else {
                       // Fallback to cached data?

               }
           },
           updateFlightSuitabilityScore(): void {
                // Auto-calc based on visibility/wind conditions?  
           }
       }}">
       
    <!-- Weather Conditions Display -->  
    <div class="weather-card" x-show="!loading"> 
        <h3>Witterungs-Bedingungen für Route: $airport_code</h3>

        <template x-html="weatherData.formatted_display()">
      </template>

        <!-- Widget Actions Button (for admin dashboard) -->  
    <button @click="fetchWeather($airport_code)">Update Forecast</button>
    
        <!-- Visual Indicator -->  
    <div class="flight_suitability-indicator" :class="getSuitabilityClass">  
        Flight Suitability: ⭐⭐⭐⭐⭐ ($score, 5)
    </div> 

    </div>

    <!-- Loading Spinner (initial fetch) -->  
     <div x-show="loading">Loading weather data for $airport_code...</div> 
        
</div>

<!-- Alpine JS Component: WeatherDisplayComponent (register in app.js) -->  
Alpine.data('WeatherWidget', function() {
  return Reactive({ 
    init(): void {
      this.fetchWeather(this.from_icao);
    }
  });
});

// Registration: Vue/Alpine CLI or Laravel Asset Pipeline  
// assets/js/components/WeatherWidget.js  

```

---

### 6. LBA-Kompliance für Wetter-Advisories

**LBA LuftV Advisory Integration (Optional Phase 2):**

```yaml
Business Rules for Weather Advisory:
[✅] Visibility < 500m → Avoid non-instrument flight (VFR minimums)  
[ ] Icing conditions expected → Warn pilots about de-icing required  
[ ✅] Crosswind > 35kt → Not recommended for light aircraft C172  

Optional LBA Compliance Widget:
┌───────────────────────────────┐
│ ⚠️ LBA Advisory Alert         │  
├───────────────────────────────┤
│ Visibility < 800m            │ ← Below VFR minimums?  
│ Winds from NNE > 45kt        │ ← Dangerous crosswind for C172?    
│ Icing expected: true         │ ← Warn about anti-icing requirements  
│                           ──────▲  
│ Recommendation:              │ ← Auto-generated warning based on LBA§14  
│   Consider delaying departure │ ← Until conditions improve?  
└───────────────────────────────┘

```

**Business Logic for Weather Safety:**

```php
/*** Check if weather meets VFR minimums per LBA ***/  
public static function checkVfRConditions(visibility_km, wind_knots): bool { 
       
       visibility_m = visibility_km * 1000; // Convert to meters 
  
     if (visibility_m < 800) return false;  // VFR minimum not met?
       if (wind_knots > 40) return false;   // Crosswind exceeds limit?  
       return true;
}

/*** Check if conditions warrant delay (Safety First) **/  
public static function shouldDelayFlight(): bool { 
    return !self::checkVfRConditions($visibility, $wind_knots);    
}

```

---

### 7. Widget UI - Alpine.js Component Planning  

**components/WeatherDisplayComponent.vue:**

```vue
<template>  
  <div class="weather-widget-card" x-data="{ 
      weather: null, 
      loading: true,      
      
      methods: {
          async fetchWeather(): Promise<void> {
              this.loading = false;
              const response = await axios.get('/api/weather'); 
              
              if (response.success) {
                  this.weather = response.data;  
                  this.updateSuitabilityScore(); 
              } else{
                  // Fallback to cached data or display error state?  
              }
          }, 
          
          updateSuitabilityScore(): void {
              // Calculate VFR/IFR conditions  
              if (this.weather.visibility_m > 800) score=5;
              else if (this.weather.wind < 30) score = 4;
              else score = 3; // Poor conditions
              
              this.suitability_score = score; 
          },
      
      },
      init() {
          this.fetchWeather();  
      }
   }">

    <!-- Loading Spinner -->  
<span x-show="!loading" class="spinner"></span>  
    
    <!-- Weather Data Display -->  
<div x-show="weather && !loading"> 
    
    <h3>Wetter für $airport_code</h3>
    
    <!-- Visibility Icon + Text -->  
    <div class="weather-icon-container" :class="getVisibilityClass">
        <span x-text="icon visibility_name"></span>  
        <p x-text="weather.visibility_km + ' km'"></p>
    </div>

    <!-- Wind Speed BarChart -->  
    <progress-bar x-value="wind_knots" max_value="50">
        🌬️ Windspeed: $wind_knots kt
    </progress-bar>  
    
    <!-- LBA Safety Advisory (if conditions degrade) -->  
<div x-show="$visibility_m < 800 || wind > 40" class="warning-box"> 
    ⚠️  VFR Minimum Conditions Below Standard!  
        Consider delaying departure or switch to IFR procedure.
</div>

</div>  

    <!-- Error State -->   
<div x-show="error_state">Failed to load weather data. Please try again.</div> 
        
</div> 

<script setup >import Reactive from "alpine"
const reactive = (data) => { 
    return new Reactive({  
        ...data,  
        init(): void {
            this.fetchWeather();
        },  
        getVisibilityClass(): string { 
            if (visibility_m < 500) return 'vfr_minimum_broken';
             else if (visibility >= 800) return 'vfr_excellent';
            }
    });
};  
</script>

```

---

### 8. Config File Planning - Weather Service Thresholds

**config/system.weather_thresholds.php:**

```php
return [
    // VFR Minimum Conditions (LBA Compliance Reference)  
    'vfr_minimums': { 
        'visibility_meters' => 800,      // Standard VFR minimum for day flight?  
        'cloud_ceiling_ft' => 3000,     // Below this = instrument approach needed?
        'wind_knots_max' => 40,         // Safe crosswind limit?
    },
    
    // Icing Conditions Warning Threshold  
    'icing_threshold_celsius': -10,      // < -10°C = ice crystal formation  
    'dewpoint_freeze_warning': true,     // Show freeze warning if dewpoint < 0°C?

```

---

## ✅ Planung Status - Weather Widget

Checkliste Weather System:

[✅] Open-Meteo API Blueprint (Free tier + Simulations-Friendly)  
[✅] METAR/TAF Integration Option (Aviation Weather API)  
[✅] Widgets UI Wireframe für Pilot-Dashboard & Admin Dashboard  
[✅] Laravel Service Implementation Blueprint for Weather Service  
[ ✅] Alpine.js Component Planning für Widget Display  
[ ] Config File für Weather Thresholds  
[ ] Maintenance Alert System mit Weather-Warnungen?  
[ ] LBA VFR/IFR Minimum Documentation  

---

## 📌 Nächster Planungs-Schritt?

Soll ich den **Maintenance Alert System** detailliert planen (E-Mail-Warnungen bei LBA Compliance)?

Oder das **Weather Widget API Blueprint** mit Open-Meteo Integration? Sag einfach!
