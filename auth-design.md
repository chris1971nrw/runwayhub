# runwayhub - Authentifizierungs- & Authorisierungs-Design

## 🎯 Authentifizierungs-Architektur

### Unified Login System für alle Benutzerrollen

**Prinzip:** Ein Login-System für Admins und Piloten, aber verschiedene Dashboards basierend auf User-Rolle.

```
┌─────────────────────────────────────┐
│ Landing Page                        │  
│ ┌───────────┬────────────────────┐  │
│ │ 🛫 runwayhub   │               │  │
│ ├───────────┼────────────────────┤  │
│ │ [Sign In] │ [Register Pilot]  │  │
│ │           │                   │  │
│ │ email:    │ ✈️ Join as Pilot  │  │
│ │          │                    │  │
│ └───────────┴────────────────────┘  │
└─────────────────────────────────────┘
```

---

## 📋 Authentifizierungs-Flows

### 1. Unified Login (für Admin & Pilot)

**Flow:**
```
Step 1: User navigiert zu /login
         ↓  
Step 2: Formular zeigt Felder:
  - email_address (required, unique)
  - password (type="password", required)
  - remember_me (checkbox, default=false)
         
         ↓
Step 3: Submit → API POST /api/login
      
Response auf valid credentials:
{
  "user": {
    "id": 123,
    "email": "pilot@example.com",
    "name": "Hans Müller",
    "role": "pilot",   // oder "admin"
    "icao_callsign": "GOL123"
  },
  "token": "laravel-session-id...",
  "dashboard_url": "/dashboard"  // Standard für alle
}

Response auf admin login: 
{
  "redirect_after_login": "/dispatch-schedules",  
}

─────────── Login Logic:

if (user.role === 'pilot') {
    return redirect('/flights/assigned');   // Pilot Dashboard
} else if (user.role === 'admin' || 'ground_control') {
    return redirect('/schedules/new');      // Ground Control oder Admin Übersicht
}
```

---

### 2. Registrierung-Flow (Nur für Neue Piloten)

**Flow:** New Pilot Self-Registration  
Admin-Accounts werden hand-manuell im Laravel Seeder angelegt (nicht öffentlich registrierbar!)

**Step-by-Step Registration:**

```html
<!-- resources/views/auth/register.blade.php Blueprint -->

┌─────────────────────────────────────┐
│ ✈️ Register as Virtual Pilot        │  
├─────────────────────────────────────┤
│                                      │
│ Email Address:           [email▼]  │  
│ Call Sign (ICAO):        [GOL123▼] │  
│                             ────────│  
│ First Name:               [input▼] │  
│ Last Name:                [input▼] │  
│                             ────────│  
│ Home Base Airport:        [select▼]│  
│   [EDDM | EGLL | LFPG | KJFK ▼]    │
│                                      │  
│ I understand that this is a         │
│ virtual air simulation platform.     │
│                                     │
│ Password (min 8 characters):      ▼│  
│ Confirm Password:                  ▼│  
│ [type="password"]               ▼└─┘
│                                      │  
│                                    [Register Pilot Account] ───> /register  
|                                      │
└─────────────────────────────────────┘

API Endpoint: POST /api/register
Request Body: { email, password, name, icao_callsign, home_airport }
Response JSON:
{
  "success": true,
  "message": "Pilot account created successfully!",  
  "redirect_url": "/dashboard"
}
```

**Backend Validation (app/Http/Controllers/Auth/RegisterController.php):**
```php
public function store(RegisterRequest $request) {
    $call_sign_exists = Pilot::where('icao_callsign', strtoupper($request->callsign))
        ->exists();
        
    if ($call_sign_exists) {
        return back()->withErrors([
            'callsign' => 'The callsign is not available.',
        ]);    
    }

    // Hash password, create pilot account with role='pilot'
    $userData = UserFactory::createFromRequest($request);  
    $user->save();
    
    return redirect('/dashboard')->with('success', 'Welcome aboard!');
}
```

---

### 3. Password Reset Flow (Für alle Rollen)

**Flow:**
```
Email forgotten → GET /forgot-password
─────────────── Form mit email_address field ↓  
Submit → POST /api/forgot-password 
      ↓                                  
Send reset token via Mailer (Laravel Mailable)  
Token valid: 24h TTL   
        ↓
GET /reset-password?token=xxx&email=yyy  
├── New Password Input Field  
└─ Confirm Password Input Field  

POST /api/reset-password
Response → Redirect to login with new password

────────────── Laravel Built-in forgot-password functionality + Session tokens
```

---

## 🔐 Session & Authorization Policies

### Middleware Blueprint

**Middleware Stack:**

1. `Authenticate` (Laravel default)  
   - Session Check auf jedes Protected Route  
   - If authenticated → Redirect to Role-Specific Dashboard  

2. `CheckForMaintenanceMode` (Standard Laravel)  

3. `DispatchOnly` (Custom)  
   ```php
   public function handle(Request $request, Closure $next, ...$guards) {
       $user = Auth::guard('web')->user();
       
       if ($user && 
           in_array($user->role, ['admin', 'ground_control'])) {
           return $next($request);  
       } else {
           return redirect('/dashboard'); // Pilot Dashboard redirect  
       }
   }
   ```

### Authorization Policies (app/Policies/)

**PilotPolicy.php:**
```php
class PilotPolicy implements PolicyContract {
    
    public function viewSchedule(User $user, FlightSchedule $schedule): bool {
        // Piloten können nur sehen, wenn sie assigned oder flight_log existiert
        return ($user->role === 'pilot' && 
                $user->flight_logs->contains($schedule->id));
    }
    
    public function createFlightLog(User $user, FlightSchedule $schedule): bool {
        // Pilot muss aircraft certified haben  
        return ($user->role === 'pilot' && 
                in_array($schedule->aircraft_type, $user->certified_aircrafts) ? true : false);
    }
        
    public function deleteLog(User $user, FlightLog $log): bool {
        // Nur deletion auf eigene flights allowed
        return ($user->id === $log->pilot_id); 
    }
}
```

**GroundControlPolicy.php:**
```php
class GroundControlPolicy implements PolicyContract {
    
    public function manageSchedule(User $user, FlightSchedule $schedule): bool {
        // Nur admin & ground_control dürfen schedule CRUD operations  
        return in_array($user->role, ['admin', 'ground_control']);
    }
    
    public function assignPilotToSchedule(User $user, Pilot $pilot): bool {  
        // Admin/ground control können piloten zuweisen
        return ($user->role === 'admin' || $user->role === 'ground_control');
    }
}
```

---

## 🎨 UI Blueprint: Login/Register Pages

### 1. Landing Page + Login (resources/views/auth/login.blade.php)

**Alpine.js Setup:**
```html
<div x-data="{ 
  email: '', 
  password: '', 
  remember_me: false, 
  loading: false,
  error_message: null, 
  
  submitForm() {
    if (!this.validate()) return
    
    this.loading = true;  
    axios.post('/login', { email: this.email, password: this.password })
      .then(() => redirect('/dashboard'))
      .catch(error => {
        this.error_message = error.response?.data?.errors['email'] || 'Invalid Login';
        this.loading = false;
      });
  },
  
  validate() {
    if (!this.email) return !!addError('Email required')
    if (!this.password) return !!addError('Password required') 
    return true
  }
}">

  ✈️ runwayhub - Flight Simulator Login
  
  Email: [input email={email}=required]  
  Password: [type="password" value={password}]  
  Remember me: [{remember_me}, change→checkbox()]  
  ┌───────────────────────┐    
  │   [Sign in]            │  
  │                        │  
  │ Forgot Password?       │  
  │                        │
  │ Don't have an account? ←  
  │ Register as Pilot      │← Link to /register
  └───────────────────────┘  

</div>

Login Buttons:
- Primary: [Sign in] → POST /api/login  
- Secondary: [Forgot Password?] → GET /forgot-password  
- Tertiary (Link): Register Pilot → Link /register  

---

### 2. Registration Page (resources/views/auth/register.blade.php)

```html
<div x-data="{ 
  email: '', password: '', confirmPassword: '', callsign: '', home_airport: 'EDDM',
  loading: false, 
  
  submitForm() {
    if (!this.validate()) return
    
    this.loading = true;  
    axios.post('/api/register', { email, password, callsign, home_airport })
      .then(() => redirect('/dashboard'))
      .catch(error => {
        alert('Error: ' + error.response?.data?.errors);
        this.loading = false;
      });
  },
  
  validate() {
    if (!this.email) return !!addError('Email required')
    if (!this.password) return !!addPasswordError('Password required')  
    if (password !== confirmPassword) return addError('Passwords must match');
    
    // Check if callsign exists
    axios.get(`/api/check-callsign?callsign=${this.callsign}`)
      .then(response => { 
        if (!response.data.exists) return !!addError('Callsign is available');    
      })
  }
}">

  ✈️ Join runwayhub as a Virtual Pilot
  
  
  Email: [input email={email}=required]  
  Call Sign: [input value={callsign}=min_length=3 max_length=10=required=unique]  
  ────────────  
  First Name: [text required]  
  Last Name: [text required]  
  Home Base Airport: [select options=[EDDM, EGLL, LFPG, KJFK] required=min_1]  
                      
  Password (min 8 chars): [type="password" value={password}]  
  Confirm Password: [type="password" value={confirmPassword}=required=valid_same]  
                                    
  ┌───────────────────────┐    
  │   [Register Pilot]   →│ POST /api/register  
  │                       │
  │ Already have an account?←  
  │ Login instead         │→ Link to /login
  └───────────────────────┘  

</div>

```

---

## 🔄 Auth Flow Diagram (Text-basiert)

```
┌─────────────────────────────────────────────────────────────────┐
│                        User Journey Map                         │  
├─────────────────────────────────────────────────────────────────┤  
                                                                   
  ┌──────────┐              ┌──────────────────────────┐        
  │ Landing  │────New User──►│   Registration Page      │  
  │           │         (email & password input)       │          
  └──────────┘              └──────────────────────────┘
                            ↓ [If Already Registered]
                      
  ┌─────────┐          ┌─────────┐              
  │ Landing │────Guest──►│ /login │ → Session Check    │
  │  Page   │←─────────|         | ← Authenticated?──→│  
  └─────────┘   Guest───└─────────┘                  ↓
                                                     ┌────Verified User────────┐
                                                     │ Role Check & Redirect    │
                                                     ├────────────────────────┤
                                                   │ pilot → /flights/assigned  │  
                                                 │ admin → /schedules/new/dash  │
                                             └───────────────────────────┘
                                                         ↓
                                              ┌──────────┴──────────┐
                                              │ Dashboard Views    │
                                              ├─────────────────────┤
                                              │ pilot_dashboard     │
                                              │ ground_control_dash │
                                              └─────────────────────┘
                                               ↓ (Logout / Logout Redirect)
                                        ┌──────────────────────────┐
                                        │ /logout → /login         │
                                        └──────────────────────────┘
                                                  
    ─────────────────────────────────────────────────
      ┌──────────────────────────────────────┐
      │  Password Reset Flow                  │
      ├──────────────────────────────────────┤
      │ Forgot Password:                      │
      │   GET /forgot-password               │  
      │       → Form [email_input]           │  
      │             ↓                         │
      │   POST /api/forgot-password          │  
      │              → Mailer Sends Token    │  
      │                    ↓                 │
      │  GET /reset-password?token=xxx       │  
      │         → New Password Form          │  
      │                ↓                     │  
      │   POST /api/reset-password           │  
      │            → Session Refresh         │  
      └──────────────────────────────────────┘
    
```

---

## 📦 Seeder: Initial User Accounts (Hand-Angelegt, nicht Public)

**Laravel Migrations:** `database/migrations/{timestamp}_create_admin_user_table.php` (optional, oder direkt im Piloten-Table als role column)

**Initial Seed-Accounts (app/Console/Commands/CreateAdminUser Command):**
```php
class CreateAdminUser extends Command {
    
    /**
     * Execute the console command
     */
    public function handle() {
        DB::table('users')->insert([
            'email' => env('ADMIN_EMAIL','admin@runwayhub.dev'), // .env variable aus Umbrel  
            'password' => Hash::make(Env('ADMIN_PASSWORD','admin123')), // Change me!
            'name' => 'System Admin',
            'role' => 'admin',  // Custom column für admin
            'created_at' => now(),
        ]);

        DB::table('users')->insert([
            'email' => env('GROUND_EMAIL','ground@runwayhub.dev'),  
            'password' => Hash::make(Env('GROUND_PASSWORD', 'ground123')), 
            'name' => 'Ground Control',
            'role' => 'ground_control',  
            'created_at' => now(),
        ]);

        return null; // Success! Admin & Ground accounts erstellt (nicht öffentlich registrierbar!)
    }
    
    /**
     * The console command description
     */
    protected $signature = 'runwayhub:create-admin {--env=local}';
        
    /**
     * Execute the command.
     */
    public function handle(): void {
        // Check if already exists first to avoid duplicates?
        return parent::handle();
    }
}
```

**Environment Configuration (.env.example):**  
```env
ADMIN_EMAIL=admin@runwayhub.dev
GROUND_EMAIL=ground@runwayhub.dev  
# Change these in production! ⚠️

APP_NAME="runwayhub"
# Enable maintenance mode on deploy if needed?
```

---

## ✅ Auth Flow Checkliste (Sollte komplett sein!)

[✅] Unified Login für Pilot & Admin  
[✅] Registration-Formular Nur für Piloten  
[✅] Password Reset Flow für alle Rollen (Laravel built-in)  
[✅] Session TTL & Refresh Token logic später optional  
[✅] Role-based dashboards /flights/assigned vs /schedules/new  
[✅] Middleware: Authenticate + DispatchOnly policies  
[ ] 2FA-Planung? Nur wenn in Phase 2+ gebraucht (bcrypt für now)  

---

## 📝 Nächste Planungsphase (keinen Code schreiben!)

Soll ich jetzt noch planen:

1. **Landing Page Layout mit Alpine.js Interaktion** (optional aber schön)?  
2. **API Spec für Auth Endpoints** (/login, /register, /forgot-password)?  
3. **Email TEMPLATES** (Reset Token Mailer)?  

Oder hast du direkt den Code-Erstellungs-Befehl nach der nächsten Planungs-Schritt?
