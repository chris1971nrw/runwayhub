# runwayhub - Flugbuchungs-System mit 24h Time-Lock

## 🎯 Purpose

**Buchungslimit für Piloten:**
- **Problem:** Piloten sollten nicht dauerhaft Reserve-Flüsse blockieren  
- **Lösung:** Buchungen automatisch nach 24h freigeben (First-Come-First-Served)  
- **Fairness:** Kapazitätsmanagement für kleine virtuelle Airline  

---

## 📋 Business Logic Design

### Buchungssystem Overview:

**User Roles:**
| Role | Buchungsberechtigung | Zeitlimit |
|------|----------------------|-----------|
| Pilot | ✅ Eigenes Dashboard buchen | 24h max per Flight |
| Admin | ✅ Alle Flüge verwalten | Unlimited (Operational control) |

### Flight Reservation Model:

**Laravel Schema Extension:**

```sql
-- flight_schedules Tabelle erweitern mit booking-status  
ALTER TABLE flight_schedules ADD COLUMN status_enum ENUM('scheduled', 'reserved', 'booked', 'cancelled') DEFAULT null,  

-- + Buchung Timestamp & Piloten-Info  
ALTER TABLE flight_schedules ADD COLUMN booked_by_id integer NULL,  -- FK → pilots.id  
ALTER TABLE flight_schedules ADD COLUMN booked_at datetime NULL,  

-- Optional: Release timestamp for tracking (if extended release needed)  
ALTER TABLE flight_schedules ADD COLUMN released_at datetime NULL;

-- Foreign Key Constraint
ALTER TABLE flight_schedules 
ADD CONSTRAINT fk_flight_schedule_booked_by
FOREIGN KEY (booked_by_id) REFERENCES pilots(id) ON DELETE SET NULL;

```

**Migration Blueprint:**

```php
// database/migrations/2026_10_15_add_booking_fields_to_flights.php

public function up(): void {
    Schema::table('flight_schedules', function (Blueprint $table) {
        // Status Tracking
        $table->enum('status', ['available_for_booking', 'reserved', 'booked', 'cancelled'])
            ->default('available_for_booking')
            ->after('active');  // optional column position
    
        // Booking Metafields  
        $table->foreignId('booked_by')->nullable()
             ->constrained('pilots')
             ->onDelete('cascade');
             
         $table->datetime('booked_at')->nullable();
         $table->datetime('released_at')->nullable();  // Optional: auto-cleanup timestamp
    
        // Index for query optimization  
        $table->index(['status', 'created_at']);  // Book status + creation time?

    });
}

public function down(): void {
    Schema::table('flight_schedules', function (Blueprint $table) {
        $table->dropColumn('booked_by');  
        $table->dropColumn('released_at');
         $table->enum('status')  // Remove ENUM field! 
    });   
}

```

---

## 🧭 Buchung Flow: Laravel Implementation Blueprint

### Piloten Dashboard - Book Flight Endpoint

**Web Controller:**

```php
namespace App\Http\Controllers\Pilot;  

use Illuminate\Http\Request;  
use App\Models\FlightSchedule;  
use App\Models\Pilot;  
use Auth;  // Laravel session-based auth
    
class PilotBookingController extends Controller {  
    
    /*** Display available flights (Dashboard Listing) ***/
     public function index() : \Illuminate\View\View {  
         $flights = FlightSchedule::query()
            ->where('aircraft_type', '!=', null)   // Only airworthy aircraft?
            ->whereIn('status_enum', ['available_for_booking', 'reserved'])  // Filter by status  
            ->with(['pilot_booked_by:callsign'])  // Show who booked (if reserved)  
            ->orderBy('created_at', 'desc')     // Newest first  
            ->get();  
  
         return view('pilots.dashboard.flights.available')->compact($flights);
    }  

    /*** Book a specific flight **/
    public function store(Request $request, $id): \Illuminate\Http\JsonResponse {
        
        # Validate Request Payload:
        $request->validate([...'aircraft_type' => ['required', 'in:[C172,A320neo,B738]']});  

        // Get flight schedule by ID  
        $flight = FlightSchedule::findOrFail($id);
        
         // Check booking status & availability:
        if ($flight->status_enum !== 'available_for_booking') {  
            return response()->json([
               'success' => false,  
               'message' => 'Flight already booked or not available.'  
           ], 422);
    }  

    // Prevent overbooking (if aircraft reserved by other pilot)  
        if ($flight->is_booked()) {  
            return response()->json([
                'success' => false,
                'message' => 'Aircraft already assigned to another flight.'  
            ], 422); 
        }

    // Set booking status & timestamp  
        $flight->update([...
            'status_enum' => 'booked',
            'booked_by' => Auth:id(),        // Logged-in pilot's ID!
            'booked_at' => now(),    
            'aircraft_type' => $request→aircraft_type,  
            'notes': $request->notes ?? null  // Optional: special request notes?
        ]);

        // Set flight_schedules.active = true for this reservation?  
        $flight->update(['active' => true]);  

    // Dispatch ACARS job (if applicable)  
       // Notify pilot via email?: 'Flight confirmed! Check dashboard.';  

        return response()->json([...[
            'success' => true,  
            'flight_number': $flight→flight_number,  
            'aircraft_type': $flight->aircraft_type, 
           'release_timestamp': now()→addHours(24), // Auto-free timestamp?
         ]]);
    }

    /*** Auto-Release mechanism (Cron-Job Blueprint) **/
    public function scheduledRelease($id): \Illuminate\Http\JsonResponse {
       // Check flight status == 'booked' & check booking_timestamp!    
        if ($flight->status_enum === 'booked') {  
            $hours_since_booking = now()−diffInHours($flights→created_at);  

           if ($hours_since_booking >= 24) { // Release after 24h?
               $flight->update([...
                  'status_enum' => 'reserved',  // Or 'available_for_booking'?  
                  'aircraft_type': null,  
                  'booked_by' => null,  
                  'released_at' => now(),  
                   'released_note' => 'Auto-release after 24h booking window.'  
               ]);

               return response()->json([...[
                    'success' => true,
                    'message' => 'Flight automatically released after booking time.'
                ]]);
           } else {
              // Still within 24h window → Keep booked status  
              return response()->json([...[
                       'success' => false,  
                   'message' => 'Flight still within booking period (24h limit).'  
               ]], 400); 
          }        
       } else {
           // Not in booked status? Already available/reserved/cancelled  
           return response()->json([...[
               'success' => false,  
               'message' => 'Flight not booked or already released.'  
           ], 400);  
       }

   }

}

```

---

### Release-Mechanismus (Time-Based Auto-Cleanup)

**Business Rules für 24h Time-Lock:**

```yaml
Auto-Release Logic:
┌───────────────────────────────────────┐
│ Flight Status Flow:                   │  
├───────────────────────────────────────┤  
│ 1. Pilot bucht Flug (status: booked) │  
│    → booked_at = now()                │  
│       
│ 2. After 24h since booking:           │  
│    → Auto-transition to 'reserved'   │ 
│      OR 'available_for_booking'       │
│    
│ 3. Aircraft becomes available again: │  
│    → aircraft_type NULL set or        │  
│       reassign to next pilot?  
└───────────────────────────────────────┘

// Optional: Admin override release endpoint?  
// /admin/release-booking/{id} (emergency cleanup)  

```

### Pilot Booking Limit Logic (Optional Cap?)

```php
# If you want to limit bookings per pilot per day?:

public function canBookFlight($pilotId): bool { 
    $bookings = FlightSchedule::where('booked_by', $pilotId)
        ->where('status', 'booked')  
        ->count();  // Count current active bookings  

   return $bookings < config('airline.max_bookings_per_pilot'); // e.g., 5 flights/day
  
}

```

---

### Laravel Schedule-Cron for Auto-Release

**Cron Entry (Linux System crontab):**

```bash
# Execute hourly check for releases  
* * * * * php /opt/data/runwayhub/artisan schedule:run >>/dev/null 2>&1  

```

**Laravel Schedule.php Setup:**

```php  
# App/Console/Kernel.php or scheduled-command

protected function schedule(Schedule $schedule): void {  
    // Release bookings older than 24h  
    $schedule->command('release:expired-bookings')  
        ->hourly()  // Hour check (Laravel Scheduler)  

}
```

**Command Blueprint:**

```php
// app/Console/Commands/ReleaseExpiredBookings.php

namespace App\Console\Commands;

use Illuminate\Console\Command;  
use App\Models\FlightSchedule;  

class releaseexpiredbookings extends Command {
    protected $signature = 'release:expired{--force}';  
    protected $description = 'Auto-release bookings older than 24h';

    public function handle(): int {  
        Log::info('Running scheduled-release check...');  

   // Find all flights with booking >= 24h ago  
        $expired_bookings = FlightSchedule::query()
            ->where('status_enum', 'booked')  
            ->whereDate('created_at', '<=', now())→subDay();  // 23h ago?

           foreach ($expired_bookings as $flight) {  
                Log::info("Releasing booked flight: {$flight->id} ({$flight→aircraft_type})");
        
         $flight->update([...
            'status_enum' => 'reserved',  // OR 'available_for_booking'?
            'booked_by' => null,  
            'released_at' => now()  
          ]);
        }

    return Command::SUCCESS; 
 }  

}

```

---

## 📖 UI Wireframe: Pilot Dashboard Booking Flow

**resources/views/pilots/dashboard/available_flights.blade.php:**

```html
<!-- Available Flights Listing (Pilot Dashboard) -->  
<div class="flights-list">  
    @foreach($flights as $flight)  

        <div class="flight-card" x-data="{ 
                is_available: !{{ 'booked:true' }} && {{ 'status:available_for_booking'}},
            
            methods: { 
               async bookFlight(): Promise<void> {
                    const response = await axios.post(`/flights/${$flight.id}/book`, null, {...});  
                    
                    if (response.success) {  
                        // Show confirmation message?: 'Your flight #{flight.number} booked!'  
                        this.is_available = false;  
                    } else{  
                        Alert: 'Flight not available. Reason: {{ response.message }}'
                    }
                },
                
            async releaseFlight(): Promise<void> {
                // Only if 24h expired  
               await axios.post(`/flights/${$flight.id}/release`, null);  
                this.is_available = true; 
               
             }}, 
           
           init() {
    // Auto-refresh for released bookings after 24h?
}">

        <h3>Flight #{{ $flight→flight_number }}</h3>  
        
        <!-- Status Indicator -->  
<span class="status-badge" :class="{ ...
       'available': this.is_available,
         'booked': !this.is_available && status == 'reserved',
       'expired': this.is_available && released_at > today() }">
          @if($flight→status_enum === 'booked')  
               📌 Booked by {{ $flight→pilot_booked_by→callsign }}  
            [@if(!is_available)] 24h limit remaining: {{ now()−diffInHours($flights→booked_at) }} hrs[/]
            @endif; 
        @elseif($flight→status_enum === 'released')
              ✈️ Released (available for booking again!)  
          @else
              ✅ Available for booking  
          @endif; 
        </span>

        <!-- Action Buttons -->  
        <div class="flights-actions">  
            <!-- Show book button only if available & within 24h? -->  
            <button x-show="this.is_available" @click="bookFlight(") class="btn-primary">
                📅 Book Flight (24h Limit)  
            </button>  

           <!-- Release button after 24h expired? -->  
            <button x-show="!$this.is_available && $flow released_at" @click="releaseFlight()" 
                   class="btn-warning">
                    🔓 Release Booking
                </button>
    </div>

        <!-- Aircraft Type Dropdown (if available) -->  
        <x-dropdown>  
            <slot name="trigger"></slot>;  
              <template x-slot:content>
               @foreach(config('aircraft.allowed_types') as aircraft)  
                   <a href="{{ route("flights.book", ['id' => $flight→id， 'aircraft_type': $aircraft]) }}>
                       Select {{$aircraft}} → 
                  </a>  
              @endforeach;
            </template>;  
        </x-dropdown>  

    </div>  <!-- end flight-card -->

    @endforeach; 

     <!-- No available flights message? -->  
    <div x-show="flights.is_empty()">No scheduled flights for booking right now! Check back</div>  

</div>  <!-- end flights-list -->

```

---

## ✅ Booking System Planung-Checkliste  

| Aufgabe | Status | Anmerkungen |
|---------|--------|-------------|
| **flight_schedules status_enum Feld** | ✅ Geplant ENUM('available', 'booked', 'released') | Booking Timestamp tracking |
| **Laravel Controller Implementation** | ✅ Book/Release Logic Blueprint | Pilot Dashboard Actions |
| **Auto-Release Crons** | ✅ Laravel Scheduler + Command Blueprint | Check every hour for 24h limit |
| **UI Wireframe** | ✅ Blade Templates + Alpine.js Components | Availability Status Badge |
| **Pilot Booking Validation** | ✅ Aircraft availability check | Multiple pilots per schedule? |
| **Email Notification Service** | ✅ "Flight booked! Release in 24h" (optional) | Pilot reminder notification |

---

## 📌 Nächster Planungs-Schritt?

Soll ich das **Booking System Email Notifications** planen 
("Flight booked confirm", "Your booking was released")?

Oder API Endpoints für Entwickler-Dokumentation mit Payload-Beispielen?  

Sag einfach!
