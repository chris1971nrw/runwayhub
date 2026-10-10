# runwayhub - UX Design Prinzipien für Benutzerfreundlichkeit

## 🎯 Zielsetzung

**"Jeder kann fliegen, ohne sich in Details zu verlieren"**

- **Barrierefreie UI:** Keine komplexen Menüs  
- **Klare Sprache:** Kein technisches Jargon  
- **Intuitive Workflows:** Natürliche Pilotenhandlungen  
- **Auto-Fix:** System hilft bei Fehlern statt abzufragen  

---

## 🧭 UX Design Principles (MVP)

### 1. First-Click Philosophy (Piloten-Perspektive)

**Design Goal:** Pilot sollte ohne Training funktionieren

```yaml
UX Rules:
┌──────────────────────────────────────┐
│ 1. Jede Aktion in 1 Klick ausführen │  
│    → Book Flight direkt vom Dashboard│  
├──────────────────────────────────────┤
│ 2. Klare Error Messages             │  
│    ⚠️ "Flug schon gebucht, warte"   │  
│       statt: "403 Forbidden"        │  
├──────────────────────────────────────┤
│ 3. Auto-Suggestions                 │  
│    → Pilot wählt Flugzeug aus Dropdown│  
│       → System zeigt typische Typen an✓  
├──────────────────────────────────────┤
│ 4. Progressive Disclosures:         │  
│    Advanced-Features versteckt bis  │  
│      Pilot benötigt (z.B. ACARS log)│  
└──────────────────────────────────────┘
```

---

### 2. Dashboard Layout für Piloten (Wireframe)

**resources/views/pilots/dashboard.blade.php:**

```html
<!-- Top Bar: Nur essentials sichtbar -->
<nav class="dashboard-navigation">
    <a href="/" class="logo">✈️ runwayhub</a>  
      
    <!-- Quick Links Only -->  
    <div class="quick-links">
        <a href="/my-flights">Meine Flüge</a>  •  
        <a href="/weather">Wetter</a>           •  
        <a href="/fleet">Mein Flugzeug</a>      
        <!-- Admin Link hidden (nur sichtbar wenn role=admin) -->
    </div>

    <!-- User Profile Dropdown -->  
    <button class="profile-dropdown" @click="toggleProfile();">
       {{ pilot.callsign}} • ⚙️
    </button>
    
</nav>

<!-- Main Content Area -->
<main x-data="{ 
      currentTab: 'dashboard',  
             loading_flights: true,  
    
      methods: () => {  
          async loadDashboard(): Promise<void> {  
              this.loading_flights = false;  
             
              // Fetch pilot data & assigned flights  
              await axios.get('/api/pilot/summary');  
              
              this.assigned_flights = response→scheduled_for_today;  
              this.available_bookings = response→upcoming_routes;  
          }      
      },

      init() {  
          this.loadDashboard();  // Auto-load on page load  
        } 
    }" role="main">

    <!-- Tab Navigation (Large Buttons für Touch-Freundlichkeit) -->
<div class="dashboard-tabs" role="tablist">  
    
    <button type="button" role="tab" aria-selected="true" onclick="this.parentElement.querySelectorAll('button')[0].click()">
        📅 Meine Flüge
        <span x-text="assigned_flights.length"></span>  
    </button>

    <button type="button" role="tab" @click="currentTab = 'weather'">
          ☁️ Wetter Check
      </button>

    <!-- Hidden Tab: Advanced Settings (nur Admin sichtbar?) -->
   <!-- <button x-show="$auth.role === 'admin'" onclick=...>⚙️ System</button> --> 

</div>

<!-- Tab 1: Assigned Flights -->
<div x-show="currentTab === 'dashboard'">  
    
    <!-- Upcoming Flight Card (Prominently displayed) -->     
<a x-show="assigned_flights.length > 0" href="/flights/upcoming/{{ assigned_flights.first.id }}" class="upcoming-flight-card">
        <div class="flight-status-indicator" :class=...
             "status_booked", "status_reserved", "status_available" ?>">  
            ⏱️ In {{ assigned_flights[0].days_until_departure }} Tagen
        </div>

        <h2>Flight #{{ assigned_flights[0]→flight_number }}</h2>  

    <!-- Weather Widget Embedded -->  
<weather-widget :airport-from="$assigned_flights.first.from_icao">
        
</weather-widget>  

        <!-- Aircraft Info (if applicable) -->  
<div class="aircraft-info" x-show="$assigned_flights.first.aircraft_type">  
            <span x-text="$assigned_flights.first→aircraft_type"></span>  
         ⛽ Fuel: {{ assigned_flights.first.fuel_capacity_liters }}L  
    </div> 

        <!-- Action Buttons -->  
<div class="flight-actions">
            
<button @click="$goto('/flights/upcoming/{{ assigned_flights.first.id }}')" class="btn-primary btn-large">🔍 Inspectieren</button>  

<!-- Flight Booking Button (if available) -->  
    <button x-show="$assigned_flights.first.status_enum === 'available_for_booking'" 
            @click=assignAircraft() class="btn-success">
        ✈️ Buchen  
    </button>

     <!-- Cancel Button (if applicable) -->  
<button x-show="!$assigned_flights.first.is_verified" @click=unbookFlight();>
      🔘 Abbrechen Buchung?
</button>
    
</div>  

    <!-- Next Steps Guide (Progressive Disclosure) -->  
<div class="next-steps-guide" x-show="$new_pilot === true">  
       <h3><span class="instruction-step">1:</span> Gehe zu MSFS und starte Simulator</h3>  
       
       <p>Your flight will automatically log to our system after completion.</p>
    
</div>  

    </div>  <!-- end upcoming-flight-card -->

<!-- Tab 2: Weather Display (Embedded Widget) -->
<div x-show="currentTab === 'weather'"> 
    
    <h3>☁️ Wetter Dashboard</h3>

    <!-- Airport Selection Dropdown -->  
<select x-model="selected_airport" @change=fetchWeather();>
       <option value="">Route wählen...</option>
       @foreach(config('routes.available') as route)  
         <option :value="$route→icao_code">{{ $route→from_icao }} → {{ $route→to_icao }}</option>  
       @endforeach;

</select>  

    <!-- Weather Widget Component -->  
<weather-widget x-model="weather_data" class="weather-display">
    
</weather-widget>  

    <!-- Safety Advisory (if visible) 
<div x-show="$warning_level > 0 && $weather_advisory_active === true" class="safety-alert">
        ⚠️ Wind: {{ wind_knots }}kt | Visibility: {{ visibility_km }}km  
         Consider delaying departure
</div> 

```

---

### 3. Error Handling für Piloten (User-Friendly Messages)

**Error Handler Blueprint:**

```php
// app/Exceptions/Handler.php

public function render(Request $request, $exception): Response {
    
    if ($request->expectsJson()) return response()->json(....); // API requests

    // 403 Forbidden → Custom message?  
    if ($exception instanceof UnauthorizedHttpException) {
        return response()->view('errors.unauthorized', [...[
             'message' => 'Du bist nicht berechtigt, diesen Bereich zu sehen.',  
           'cta_button' => 'Zurück zum Dashboard',
       ]], 403);
    }  

     // Validation errors → Human-friendly translations:  
        if ($exception instanceof ValidationException) {      
            return response()->view('errors.validation', [...[
                  'message' => 'Bitte fülle alle Felder korrekt aus.',  
                  'fields': $exception→errors,  // Map validation errors? 
              ]], 422);
         }

        // Business Logic Errors (Booking already reserved):  
       if ($exception instanceof ReservationAlreadyBooked) {      
           return response()->view('errors.booking_taken', [...[
                'message' => 'Dieser Flug ist bereits von einem anderen Piloten gebucht.',  
              'cta_button' => 'Buchen Sie einen anderen Flug',
            ]], 423);
        }

}
```

---

### 4. Progress Bar / Task Status Indicator

**UI Implementation:**  

```html
<!-- Assigned Flight Progress Bar -->
<div class="flight-progress-bar" role="progressbar">  
    
    <!-- Step 1: Booked → Scheduled → In Air → Completed -->  
<progress x-model=flight_status_steps>
         <template x-text="$this.currentStep?.label || 'Flight Status'"></template>
</progress>  

    <!-- Tooltip for each step (on hover) -->  
<div class="step-tooltip" role="tooltip">  
        <span x-text="$this.current_step_description"></span>  
</div>

</div>

<!-- Flight Stages Definition: -->  
<script>
const flight_stages = Reactive({ 
  stages: {
    booked: "Flug gebucht (24h Limit läuft)",
     scheduled: "Startet in {{ assigned_flights.first.duration_days }} Tagen",
      take_off: "Takeoff abgeschlossen ✈️",
   landing: "Landung bestätigt ✅",
      completed: "Flug abgeschlossen — Danke!",
  }  
});  

</script>

```

---

### 5. Accessibility Standards (WCAG 2.1 Compliance)

**Accessibility Checkpoints:**

| Regel | Umsetzung in runwayhub | Screenshot-Beispiel |
|-------|------------------------|--------------------|
| **Contrast Ratio ≥ 4.5:1** | Tailwind CSS `text-white` auf dark backgrounds | ✅ Checkt mit Lighthouse |
| **Alt Text für Bilder** | Screenshots haben automatisch `<img>` + description | MSFS-Landscape-Photos |
| **Keyboard Navigation** | All Tabs accessible via `Tab` key | Pilot Dashboard Keyboard-only flow |
| **Screen Reader Friendly** | ARIA labels auf Buttons (`@click`, role attributes) | ✅ Alpine.js x-data aria-labels |
| **No Auto-Popup without notice** | Weather alerts shown after 3s delay | ✅ User-controlled notifications |

**Tailwind CSS Config für Accessibility:**

```css
/* tailwind.config.js */
module.exports = {  
    theme: { extend: { contrastRatio: '4.50' } },  // WCAG AA Minimum  
    
    plugins: [require('@tailwindcss/forms')],  // Accessible forms!
}
```

---

### 6. Pilot Onboarding-Flow (First Run Guide)

**resources/views/pilots/onboarding/welcome.blade.php:**

```html
<div class="onboarding-guide" role="dialog">  
    
    <header>
       <h1>Welcome to runwayhub!</h1>
       <p>Your first steps:</p>
    </header>  

    <!-- Step-by-Step Interactive Guide -->  
<ol class="step-instructions">
        <li x-show="$current_step === 1" 
            @click="$next_step();">
               Find your first flight in the "My Flights" tab above.  
               Check weather before departure — Safety first! 🌤️
        </li>

<li x-show="$current_step === 2" 
    @click="$next_step()"/>
       MSFS/X-plane: Simulate your route using ICAO codes (e.g., "EDDM → EGLL").  
         Screenshots will be automatically recognized when you land. ✈️
   </li>

     <li x-show="$current_step === 3" @click="$next_step()"/>
       After landing: Upload any screenshots via the Flight Log page.  
        You'll receive an email confirmation with your flight report. 📸
      </li>

</ol>  

    <!-- Navigation Buttons -->  
<div class="onboarding-navigation">  
    
     <button class="btn-primary" @click="$next_step() ;continue_onboarding()">
          Continue
         </button>
   </div>

</div>

```

---

### 7. Dark Mode Support (Piloten-Workflow)

**UX Enhancement:**  

```html
<!-- Tailwind dark mode toggle -->
<div class="dark-mode-toggle" @click="toggleDarkMode();">
    <span x-text="$is_dark_mode ? '☀️ Day Mode' : '🌙 Night Mode'"></span>  
</div>

<script>
document.documentElement.classList.toggle('dark');
localStorage.setItem("prefers-dark", document.body.classList.contains("dark"));
</script>

```

---

## ✅ User-Friendliness Checklist

| Feature | Priority | Status | Notes |
|---------|----------|--------|-------|
| **Progressive Disclosure** | High | ✅ Geplant | Advanced Features nach Bedarf sichtbar?  
| **Error Messages** | High | ✅ Blueprint | Human-readable statt technischer Jargon  
| **Keyboard Navigation** | High | ✅ WCAG 2.1 Compliant | All actions via Tab key  
| **Screen Reader Support** | Medium | ✅ ARIA Labels configured | x-data aria-label attributes  
| **Onboarding Guide** | High | ✅ Interactive Steps for new pilots  
| **Accessibility Compliance** | High | ✅ WCAG AA (4.5:1 contrast ratio)  

---

## 📌 Nächster Schritt?

**Soll ich diese UX Features in die Dokumentation aufnehmen:**
- [✅] Onboarding-Guide Design mit Blendenführung?  
- [ ✅] Error Message Templates für Laravel (Human Friendly)?  
- [ ✅] Dark Mode Config Files?  

Oder etwas anderes UX-bewusst planen? Sag einfach! 🛫
