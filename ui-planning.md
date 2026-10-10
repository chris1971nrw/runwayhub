# runwayhub - User Interface & UX Planning

## 🎨 Design System Specification (Text Blueprint)

### Color Palette (Virtual Airline Theme)

```yaml
Colors:
  Primary:    #1a73e8   # Airline Blue (trust + reliability)
  Secondary:  #fbbc04   # Aviation Yellow (caution/important elements) 
  Success:    #1e8e3e   # Flight Operations Green  
  Warning:    #fb8c00   # Late Flight Orange
  Error:      #d93025   # Cancelled/Rejected  

  // Status Colors for Flights
  PendingFlight: "#ff6b6b" 
  ActiveFlight: "#1a73e8"  
  Completed:      "#1e8e3e"
  Canceled:       "#d93025"
  
Background System:
  Surface: #ffffff     # Clean white for forms/tables
  Background: #f5f7fa  # Soft gray for page backgrounds
  Borders:     #dadce0 # Subtle borders
  
// Dark Mode Ready (optional Phase 2):
DarkSurface: "#1f2937"  
DarkSurfaceSecondary: "#2d3748"
```

---

### Typography & Layout

```yaml
Fonts: 
  PrimaryFont: "Inter, system-ui, sans-serif"  
  FontSize: {
    h1: "2rem/30px",      // Page titles
    h2: "1.5rem/24px",   // Section headings
    body: "1rem/16px",     // Standard text
    small: "0.875rem/14px",   // Notes, labels
  }  
LineHeight: 1.5         // Readable spacing

Layout Grid (Bootstrap System):
  Container: 12 columns wide default
  Cards: border-radius 8px (rounded)
  Forms: padding 1rem, min-width 100% mobile-first
```

---

## 🎯 Wireframe Specifications

### 1. Landing Page + Login Registration Flow

```html
<!-- resources/views/auth/login.blade.php Blueprint -->
<div class="auth-container">
  <logo><!-- ✈️ Flight Simulator Icon ──></logo>
  
  <login-form>
    email_input [placeholder="Pilot Callsign"]
    password_input [type="password"] 
    remember_me_checkbox "Remember me for 14 days"
    
    buttons:
      [Sign In] -> Redirect /dashboard
      [Forgot Password?] -> Link to reset page
      Register Free Pilot -> /register
      
    alerts: (optional)
      - Invalid credentials flash message  
      - Account locked/awaiting approval notice
  </login-form>
</div>

<!-- resources/views/auth/register.blade.php Blueprint -->  
<div class="register-container">   
  <title><!-- ✈️ Sign up as Virtual Pilot ──></title>  
  
  form:
    name_input [placeholder="John Smith"]
    email_input [UNIQUE, required]  
    callsign_input [placeholder="GOL123", UNIQUE]
    password_input
    confirm_password_input
    
    select:
      home_airport [EDDM, EGLL, LFPG, etc.]
      
  submit: [Register Pilot Account]
</div>

// Alpine.js logic:
<script defer setup>
document.addEventListener('alpine:init', () => {
  Alpine.data('authForm', () => ({
    email: '', password: '', callsign: '', home_airport: 'EDDM',
    
    validate() {
      return this.email && 
             this.password_length >= 8 && 
             this.callsign_regex.matches(this.callsign);  // ICAO format
    },
    
    submitForm(formData) {
      if (this.validate()) {
        axios.post('/register', formData)
          .then(() => redirect('/dashboard'));
      } else {
        showValidationAlert();
      }
    }
  }));
});
</script>
```

---

### 2. Pilot Dashboard Overview + Schedule Management

```
┌───────────────────────────────────────────────────────┐
│ 🛫 My Flight Simulator Airline | [🔍 Notifications]   │  
├───────────────────────────────────────────────────────┤
│                                                        │ 
│ ╭─────────────────────╮  ╭─────────────────────────╯  │
│ │  Assigned Flights   │  │ 📊 My Performance Stats    │  
│ ├─────────────────────┤  │                             │  
│ │ ✈️ VH100            │  │ Completion Rate:          92%│ 
│ │   EDDM → EGLL       │  │                          ┌──╮│
│ │   Apr 14, 14:30     │  │ On-Time Rating:            │○││ ← Chart.js Line
│ │   Aircraft: A320    │  │                           └──┤│  
│ │   Pilot: GOL123     │  │ Total Completed Flights:    │○││ 
│ │   ⏰ Up in 2d       │  │                                │─┘│
│ ├─────────────────────┤  │                                  │  │
│ │ ✈️ GLO200          │  │ Cancelled Due To Weather:      ▼ │  │ ← Bar Chart
│ │   EGLL → KJFK      │  │                             ┌─────┘│  
│ │   Apr 18, 16:00     │  │                            └───────┘│ 
│ │   Aircraft: B738    │  │                                      │
│ └─────────────────────┘  └─────────────────────────────┘        │  
│                                                        │
│ ╭─────────────────────────────────────────────────────╮ │
│ │ Upload Flight Log for VH100                          │ │ ← Button: [Choose Photo]
│ ├─────────────────────────────────────────────────────┤ │
│ │  Estimated Departure: ───── Apr 14, 14:30 (EDDM)   │ │  Alpine Tabs  
│ │  Actual Off Time:      ───── [Select DateTime...]   │ │
│ │                                                            │
│ │  Actual Arrival:       ───── [Select...]              │ │  
│ │  On-Time Status:       ───── [Early | On-Time | Late ▼]    │
│ │                                                            │
│ │  Comments for Ground Control:                             │← Textarea
│ │  ────────────────────────────────────────────────────  │
│ │                                                          │  
│ ╰─────────────────────────────────────────────────────╯   │
│                                                           │
│ [Upload Log] Cancel Scheduled ❌                         │
└───────────────────────────────────────────────────────────┘
```

### Alpine.js Interactive Elements Logic:

```javascript
/* Alpine Tab Component for Dashboard Sections */
const dashboardTabs = {
  tab: 'all', // all, assigned, completed  
  switchTab(event) { 
    if(!event) event = {};
    this.tab = event.detail || 'all';
    this.renderCharts(); // Filter data by tab state
  },
  
  // Charts.js Line Chart for Performance
  chart: new Chart(document.getElementById('performanceChart'), {
    type: 'line',
    data: {
      labels: ['Jan','Feb','Mar','Apr','May'],
      datasets: [{
        label: 'Completed Flights',
        data: [42,43,41,45,47],
        borderColor: '#1e8e3e' // green
      }]
    }
  })
};
```

---

### 3. Schedule Detail + Log Upload Wireframe (Full-Size)

```html
<!-- Blueprint: resources/views/pilots/flights/schedule_detail.blade.php -->
<div class="schedule-detail-container">
  
  // Header Section
  <div class="header-banner">
    ✈️ VH100 Flight Schedule
    [Flight Number] [Date & Time] [From EDDM → To EGLL]
    
    <alerts-section> 
      Status Badge: Pending / Active / Completed  
      Weather Alert if severe (Turbulence icon + advisory text)
    </alerts-section>
  </div>
  
  // Two-column Layout
  <div class="schedule-layout">
      
    LEFT COLUMN - Flight Details
    ─────────────────────────────
    
    [Route Map Visualization] ← optional leafletjs map
    
    Basic Info:
      From Airport: EDDM → To Airport: EGLL  
      Departure Time: Apr 14, 14:30 (EDST+2)  
      Estimated Duration: 2h30m  
      
    Aircraft Assigned:
      Model: Airbus A320neo  
      Registration: D-AIMR  
      Seats: 168 available capacity  
      Fuel Calculation: Displayed
      
    Pilot Info:
      Your Callsign: GOL123 ✅  
      Skill Level: Intermediate (2)  
      Home Base: EDDM ✈️
      
    Right Column - Upload Form
    ────────────────────────────
    
    <file-upload-component>
      [Choose Flight Screenshot] ← Alpine.js hidden input + label
      
      Allowed Types: JPG, PNG, WEBP
      Max Size: 4MB (optional limit?)
      
      Preview thumbnail before upload
    </file-upload-component>
    
    <datetime-picker-component>
      Actual Off Time [Local EDDM → UTC+2]  
      Gate Departure Time  
      Actual Arrival Time (EGLL)
      
      On-Time Calculator:
        Early  = Before -10m
        On-Time= Within ±15m window  
        Late   = After buffer_time_minutes?
        
        Status Dropdown: [Select...]
    </datetime-picker-component>
    
    <comments-textarea>
       Title: "Flight Experience Notes" or "Ground Control Feedback"
       Placeholder: e.g., "Arrived 5min early due to tailwind..."  
       
       Markdown Support (optional): Bold text, links only  
       Max Length: 1000 chars
    </comments-textarea>
    
    Upload Actions:
      [Submit Log]  Primary CTA Button Green
      Cancel Flight  Secondary Button Red
      
</div>
```

---

### 4. Ground Control - Schedule Creation Wireframe

```html
<!-- resources/views/ground_control/schedules_new.blade.php Blueprint -->
<div class="dispatch-dashboard">
  
  <title>[✈️ New Flight Schedule Request]</title>   
  
  // Left Panel: Schedule Config Form
  ┌────────────────────────────────┐
  │ From Airport [EDDM ▼]         │ ← Alpine Dropdown  
  │ To Airport     [EGLL▼]        │
  │                               │
  │ Departure Date [Calendar...] →│ Alpine Date Picker (date-fns)
  │ Time            [14:30 ▼]     │ 24h Format
  │                   ──────────── │  
  │ Aircraft Type [A320 ▼]        │ ← Select Component
  │ Seats Available: 168          │
  │                               │
  │ Buffer Time (mins): [±15 ▼]   │ ← Delay tolerance  
  │                               │
  └────────────────────────────────┘  
  

  // Right Panel: Pilot Assignment
  ┌───────────────────────────────────┐
  │ Assigned Flight Pilot             │← Display current assignment if exists  
  │                                   │
  │ Available Pilots                  │ ← Alpine.js Table with Filter
  ├───────────────────────────────────┤
  │ Callsign: GOL123 | Skill: Int.    │ ← Card layout  
  │   Certs: A320, B738               │  (hover for details)   
  │   Home Base: EDDM ✅              │  
  │                                   │  
  │ Callsign: FLY456 | Skill: Exp     │  
  │   Certs: All Aircraft              │
  └───────────────────────────────────┘  
  
  Action Buttons:
    [✅ Assign Pilot & Create Schedule] Primary Green Button
</div> 
```

---

## 🎨 Interactive Components Library (Alpine.js)

### 1. Alpine.js File Upload Component:

```javascript
Alpine.data('flightLogUpload', () => ({
  
  // Form Fields & Alpine state
  file_input: null,        // hidden input ref
  selected_file: null,     // object {name, size, type} 
  preview_url: null,       // FileReader result
  
  upload_status: 'idle',   // idle → uploading → success|error  
  errors: []              // validation errors array
    
  methods: {
    
    setFileSelected(file) {
      this.selected_file = file;
      this.errors = [];  // Clear errors on new file selection
      
      // Preview immediately via FileReader API
      const reader = new FileReader();
      reader.onload = (e) => {
        this.preview_url = e.target.result;  
      };
      
      if (file) reader.readAsDataURL(file);
    },
    
    validateFile() {
      return this.selected_file?.size <= 4*1024*1024 && // 4MB max limit optional
             ['image/jpeg', 'image/png'].includes(this.selected_file.type);
    },    
    
    handleUpload() {
      if (this.upload_status !== 'idle') return;      
      
      this.upload_status = 'uploading';
      
      // Laravel API endpoint for upload  
      axios.post('/flights/{schedule_id}/log-upload', { file: this.selected_file })
        .then(response => { 
          alert('Upload success!');  
          window.location = '/dashboard';  // Redirect after successful save
        })
        .catch(error => {
          this.errors = [error.response?.data?.errors || 'Upload failed'];
          this.upload_status = 'error';
        });
    }
    
  },
  
  init() {
    if (!this.selected_file) return;
    // Load preview on first load if selected by user already?
  }
}));
```

---

### 2. Alpine.js Date/Time Picker + On-Time Calculators:

```javascript
Alpine.data('flightTiming', () => ({
  
  estimated_off_time: null,  // from flight_schedule table via model  
  actual_off_time: null,     // user selection input [datetime-local]
  actual_arrival_time: null, // user selection input
  on_time_status: 'on-time', // Early | On-Time | Late?
  
  bufferMinutes: 15,         // from schedule config ± window
  
  methods: {
    
    calculateOnTimeStatus() { 
      if (
        !this.actual_off_time || 
        !this.estimated_off_time || 
        !this.actual_arrival_time
      ) return 'pending';
      
      const estimatedMinutes = this.estimated_off_time.getTime();  
      const actualMinutes = this.actual_off_time.getTime();      
      const diffMinutes = (actualMinutes - estimatedMinutes) / (60*1000); // ms → min
      
      if(diffMinutes < -this.bufferMinutes/2) return 'early';
      if(Math.abs(diffMinutes) <= this.bufferMinutes) return 'on-time';  
      return 'late';
    },
    
    formatDateTime(input) {
      return input ? 
        new Date(input).toLocaleString('de-DE', {
          day: '2-digit', month: 'short', hour: '2-digit', minute:'2-digit'
        }) : '';  // dd.MM. hh:mm de-DE locale
    },
    
    getFlightDuration() {
      const from = this.actual_off_time || new Date();  
      const to = this.actual_arrival_time || new Date();
      return (to - from) / (1000*60*60).toFixed(1); // hours float format
    },    
    
  }
}));
```

---

## 📱 Responsive Design Breakpoints (Bootstrap System)

```css
// Mobile-first approach:
mobile       : < 768px  → Stack vertically, single column  
tablet       : ≥ 768px → Two-column grids activate for forms  
desktop      : ≥ 1024px→ Full dashboard with sidebar navigation  

@media (min-width: 768px) {
  .schedule-layout { grid-template-columns: 1fr 1fr; } // Two column
}

@media (min-width: 1024px){
  .dispatch-dashboard { display: flex; gap: 1rem;}
}
```

---

## ✅ UI Planning Status Checklist

[✅] Color System & Theme specified  
[✅] Typography & Layout Grid planned  
[✅] Wireframes für alle Key Views (Landing, Pilot Dashboard, Schedule Upload, Ground Control)  
[✅] Alpine.js Interactive Components designed (Upload, Charts, Timing Calc)  
[ ] Accessibility Standards (WCAG 2.1 AA): Contrast Ratios, Keyboard Navigation check  
[ ] Error Handling Patterns (invalid forms, upload failures, network offline?)

**Next Planning Step:**  
Soll ich jetzt **Chart.js API Configurations** planen pro Performance Metric?  
