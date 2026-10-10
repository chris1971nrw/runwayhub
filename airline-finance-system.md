# runwayhub - Virtual Airline Finance System Blueprint

## 🎯 Purpose

**Business Intelligence für virtuelle Airline:**
- **Revenue Streams:** Ticketverkauf, Cargo-Flugleistungen  
- **Kostenstruktur:** Treibstoff + Catering + Crew + Ground Handling  
- **Profitability Tracking:** Break-even Analyse pro Flug  
- **Dynamic Pricing:** Automatische Ticketpreise nach Nachfrage  

---

## 💰 Revenue Model (MVP)

### 1. Ticketpreise nach Route & Flugzeugtyp

**Automatische Preisberechnung per Entfernungsklasse:**

```php
// Datenbank: routes, aircrafts tables mit standard preisen

// Beispiel: RWH001 EDDM → EGLL (640 NM Medium Distance)
struct FlightPricing {
    flight_number: "RWH001",  
    route_class: "medium",  // < 800 NM, 801-2500 NM, > 2500 NM  
    
    // Economy Class (Default für Standard-Flugzeug)
    economy_base_price: 199.00 EUR  // Short Haul, ~50$/Mile × base_fuel_cost

    // Business Class (if aircraft supports)
    business_base_price: 899.00 EUR  // Premium service pricing
    
    // First Class (long-haul only > 2000 NM)
    first_class_base_price: 3499.00 EUR  // Luxury long-distance flights  
    
    // Dynamic Multiplier Factors per season/demand:
    peak_season_multiplier: 1.5        // Summer/Major Events  
    low_season_discount: 0.8           // Winter/Off-peak seasons  
}

// Preisberechnungsformel (simulated real-world logic):
final_price = base_price × demand_factor × cabin_class × season_multiplier

// Seasonal Factors:
season_map: {  
    winter_low: [1, 2]: 0.8×,  
    spring_normal: [3, 5]: 1.0×,  
    summer_peak: [6, 8]: 1.4×,  
    autumn_holiday: [9]: 1.2×,      // European holiday period
},  

// Demand-based pricing (dynamic):  
demand_factor = min(2.0, max(0.5, current_passenger_demand_percent / 100))  
```

---

### 2. Ticketklassen System (Realistic Pricing)

**Passenger Cabin Classes:**

| Class | Multiplier | Economy Base (EUR/flight) | Business Multiplier | Features |
|-------|------------|---------------------------|---------------------|-----------|
| **Economy (Y)** | 1.0× | 199-299 EUR | N/A | Standard baggage, meals included |  
| **Premium Economy (W)** | 1.4× | 350-450 EUR | N/A | Extra space + priority boarding  
| **Business (J)** | 2.8× | 650-950 EUR | ~2x economy price | Lie flat seats, lounge access  
| **First (F)** | 4.5× | 1150–1800 EUR | ~3x business price | Premium experience + chauffeur service  

**Automatische Preisberechnung:**
```php
// Beispiel für flight RWH302 (Short Haul EDDM→EHAM):

$base_economy_price = 199.00;  
$economy_final = $base * ($cabin_class === 'Y' ? 1.0 : 
                       ($cabin_class === 'W' ? 1.4 : 
                       ($cabin_class === 'J' ? 2.8 : 4.5)));

// Multi-season-demand (dynamic):
$current_demand = 0.7; // Off-peak season with no major demand spikes  
$season_multiplier = 0.9; // Winter Low Season  

$fuel_price_impact = current_fuel_price × aircraft_efficiency_factor;

$economy_final × ($cabin_class === 'J' ? 1.2 : 1.0);
```

---

### 3. Premium Add-ons (Ticket Extras)

**Optional Ticket Components:**

| Add-on | Price | Cost for Airline | Margin |
|--------|-------|-------------------|---------|
| **Extra Baggage +25kg** | €75 | €18 ground handling | €57 markup |
| **Priority Boarding** | €40 | €3 crew cost | €37 markup  
| **Lounge Access (2h)** | €95 | €65 facility access | €30 margin  
Wine/Gourmet Meals | €85/seat | $35-60 catering | Profit 40-55%
**WiFi Pass** | €35/mid-flight | €12 satellite data | €23 profit  

---

## 💸 Kostenstruktur Breakdown (Per Flight)

### 1. Fuel Cost Calculation (Treibstoffverbrauch)

**ICAO Standard Consumption:**

```php
class FuelCostCalculator {  
    // ICAO Aircraft Performance Data (realistic fuel burn)
    
    // A320neo Example: Typical cruise consumption ~2,800 kg/hr × flight_time_hrs
    
    function calculateFuelBurn(aircraft: Aircraft, route_length_nautical_miles): int {
        // Estimated fuel burn per NM based on aircraft efficiency
        let fuel_per_nm = this.get_fuel_burn_rate_for_aircraft_type();  
        
        base_fuel_kg = route_length_nm × fuel_per_nm;  

        // Add takeoff/landing overhead (higher consumption)  
        takeoff_landing_overhead = 15%; // ~350-450kg extra for each segment  

        // Reserve fuel (ICAO mandated: minimum +5 hours at cruise power)
        reserve_fuel_kg = aircraft_cruise_thrust × 5 hours;

        return $base_fuel_kg + $takeoff_overhead + $reserve_fuel_kg;  
    }  
    
    function calculateCost(fuel_kg, fuel_price_per_kg): float {
        // Real-time fuel price (IATA benchmark)
        let current_fuel_price = config('fuel.current_apt_euros'); // €0.85/kg avg for Jet A1 9/2024
        return $fuel_kg × $current_fuel_price;
    }  
}

// Example: Short Haul flight 600 NM with A320neo:
$fuel_burn = calculateFuelBurn(A320neo, 600nm); // ~3120kg total  
$cost_at_current_prices = 3120×€0.85 = €2745
```

---

### 2. Crew Costs (Besatzungsverkehr)

**Crew Payroll per Flight:**

```php
class CrewCostCalculator {  
    // Standard flight crew: 1 Captain + 2 First Officers for A320
    standard_crew_count = 4;  

    function calculateCrewPay(crew_members, flight_hours): float {
        // Example hourly pay rates (European pilot wages simulated)
        let base_pay_rates = {  
            captain: 45.00 EUR/hour,  
            first_officer: 32.00 EUR/hour,  
            stewardess_first: 18.00 EUR/hour × 2,
            stewardess_cabin: 16.00 EUR/hour × 2
        };

        total_cost = sum_over(crew_members)(role → base_rate × flight_hours);
        
        // Add overtime premium if > 14h duty period (EASA regulation)  
        if (flight_duration > daily_duty_limit(14)) {
            total_cost×1.50; // Overtime multiplier for extra crew hours
        }

        return $total_cost × config('crew.overhead_tax'); // 20% insurance/agency fees
    } 
}

// Example: A320neo flight 6h duration  
$base_crew_pay = (1×45) + (2×32) + (4×17) = €198/hr × 6hrs = €1,188
```

---

### 3. Catering Costs per Seat

**Meal Service Pricing:**

| Meal Type | Food Cost (EUR) | Drinks Cost | Total Per Seat |
|-----------|------------------|-------------|---------------|
| **Economy Breakfast** | 4.50 € | 1.20 € | 5.70 €  
| **Lunch/Dinner Economy** | 6.50 € | 2.50 € | 9.00 €
| **Business Class Meal** | 18.00 € | 4.50 €/glass | 35.00 €
| **First Class Service** | 45.00 € + wine pairing | 65.00 € | 120.00 €  

```php
function calculateMealCost(per_passenger_count, meal_type): float {
    let base_meal_pricing = {  
        'economy_breakfast': 4.50,
        'economy_lunch': 6.50,  // Standard business lunch costs
        'business_class': 18.00,  # Premium dining experience
        'first_class_dinner': 45.00 + beverage_pairing_cost()  
    };

    return $base_meal_pricing[meal_type] × per_passenger_count;
}

// Add catering supplier margin (Airline negotiates bulk rates):
$wholesale_catering_margin = config('catering.supplier_contracts.margin'); // 10-15% markup on bulk orders
```

---

### 4. Ground Handling & Airport Fees

**Ground Costs Breakdown:**

| Cost Item | Formula/Rate | Example EUR (Short Haul) |
|-----------|--------------|--------------------------|
| **Landing Fee** | Fixed per aircraft + weight | €500-800 (A320 landing)  
| **Parking/Gate Fee** | Hourly + FIDS system | €120/hr × 2hrs boarding = €240  
| **Baggage Handling** | Per bag + ground labor | €6.50/bag × luggage_count |
| **Pushback/Away Turnaround** | Fixed + ramp crew cost | ~€180 turn-around operation  
| **Navigation/Radio Fee** | ENAV/Lufthansa NAV fees | ~€50 per flight plan  

```php
class GroundCostCalculator {  
    function calculateGroundFees(airport_code, aircraft_type, passengers): float {
        // Airport landing fees (ICAO standard based on weight categories)
        let landing_fee_chart = {  
            'small_aircraft': 400.00 EUR,  
            'medium_a320': 650.00 EUR,   // A320 class: €500-800 typical range
            'large_b777': 1200.00 EUR    // Large aircraft >200 passengers
        };
        
        base_landing_fee = landing_fee_chart[aircraft_type];

        // Gate/parking fees (per airport authority rates):
        gate_fee = get_gate_rate_per_hour(airport_code); // 80-150 EUR/hour per FIDS database
        
        // Baggage handling costs:
        baggage_cost = $base_handling_fee + ($bag_count × €6.50/bag);

        return ($landing_fee + $gate_fee × estimated_turnaround_hours) + 
                $navigation_fees(airport_code) + $baggage_cost;
    }  
}
```

---

### 5. Maintenance & Insurance Overhead

**Operational Reserves:**

| Expense | Calculation Formula | Per Flight EUR (avg) |
|---------|--------------------|---------------------|
| **Maintenance Reserve** | €120 × aircraft_age_years | ~€300 per flight  
| **Insurance Premiums** | 0.08% per flight revenue | €5-25 variable  
| **Spare Parts Allocation** | 5% fuel cost reserve | €140  
*   **Airworthiness Certification** | Annual LBA audits (included yearly budget) |  

---

## 📊 Flight Profitability Calculator (Combined Model)

**Complete Revenue vs Cost Calculation:**

```php
class FlightProfitabilityAnalyzer {  
    
    public static function calculate_flight_pnl(aircraft: Aircraft, flight_distance_nm, 
                                                passenger_manifest: []): array {  
        
        # Revenue Components ## Ticket Income
        let total_ticket_revenue = sum_over(passengers)(ticket_type → dynamic_price_calculation(
             cabin_class, distance, cabin_multiplier
        ));

        # Optional Upsell Revenue
        $baggage_upsell = sum_over(upgraded_bags)(€75 × bag_count);  
        $vip_perks_revenue = €120 × premium_passengers_count; // Lounge passes + wine

        /** Total Flight Revenue **/
        let total_revenue = $total_ticket_revenue + $baggage_upsell + $vip_perks_revenue;  

        # Cost Components ## Fuel Cost (primary operating cost)
        let fuel_burn_kg = FuelCostCalculator::calculateFuelBurn(aircraft, flight_distance_nm);  
        $fuel_cost = FuelCostCalculator::calculateCost(fuel_burn_kg, 
            current_apt_fuel_price_per_kg('jet_a1')); // Dynamic pricing

        # Crew Costs
        let crew_hours_pilot = min($flight_duration, 14); // EASA max duty period
        $crew_pay_cost = CrewCalculator::calculateCrewPay(flight_airspace + stewardess_crew_hours);  

        # Ground Fees  
        $ground_handling_fees = groundCostCalculator::CalculateGroundFees(airport_code, aircraft_type);

        # Catering & Meals
        let catering_cost = calculateMealCost(passenger_manifest, meal_type_pref); // Economy vs Business meals  

        /** Total Flight Overhead **/
        let total_cost = $fuel_cost + $crew_pay_cost + $ground_fees + 
                        $catering_cost + maintenance_overhead();

        # Net Profit Calculation
        let profit_margin_percent = ($total_revenue - $total_cost) / $total_revenue × 100;

        return [  
            'revenue': $total_revenue,
            'cost_breakdown': { fuel_cost, crew_pay, ground_fees, catering},
            'net_profit': ($total_revenue - $total_cost),
            'break_even_passengers_min': ceil(fuel_cost ÷ (average_ticket_price_per_capita)) // Break-even threshold  
        ]  

   }  
}

# Example Output for Medium Distance Flight (RWH304):
{
    revenue: { tickets: €8950, bag_upsell: €2175, vip_perks: €540 },
    costs: { fuel: €3880, crew: €1260, ground: €560, catering: €970, maintenance: €360 },  
    total_profit: $9775 - $7030 = €2745 net profit,
    break_even_point: 28 passengers minimum / flight for positive margin
}
```

---

## 📈 Cargo Revenue System

**Cargo Load Optimization:**

### 1. Cargo Pricing per Weight Class

| Volume Category | Space Usage (%) | Price/kg (EUR) | Profit Margin (after overheads)  
|-----------------|----------------|---------------|-------------|
| **Standard Goods (≤200kg)** | 35% cabin space | €4.50/kg | ~€180/200kg container  
| **Bulk Freight >500kg** | 70% cargo hold | €3.20/kg   | €640 for palletized load
| **Perishables + Cold Chain** | Priority space allocation | €8.50/kg | Premium pricing with temperature control  

---

### 2. Cargo Revenue Model (Dynamic Factors)

```php
class CargoRevenueCalculator {  
    
    public function calculateCargoRevenue(flight_number, cargo_manifest): float {  
        # Base revenue per shipment weight class
        let base_cargo_rates = {  
            'standard': 4.50 EUR/kg,  
            'bulk_freight': 3.20 EUR/kg,   // Heavy cargo pallets (lower unit price)  
            'perishables': 8.50 EUR/kg    # Premium perishable shipping
        };

        # Calculate revenue from each shipment:
        $cargo_revenue = sum_over(cargo_shipments)(shipment → weight_in_kg × base_rates[type]);  

        # Volume-based discounting (aircraft capacity optimization):  
        if ($shipments_count > aircraft_cargo_capacity) {  
            $multiplier = 0.85; // 15% discount when exceeding maximum load
        } else {  
           $multiplier = 1.0; // Standard rate per shipment
        };  

        # Dynamic multi-year inflation adjustment:  
        let current_market_rate_adjustments = config('cargo.current_market_inflation_factor'); // 1.2x for recent hikes
        total_cargo_revenue = sum_over(shipments)(weight × rates × $multiplier × current_inflation_factor);

        return total_cargo_revenue;  
    }  
    
}

# Break-Even Analysis for Cargo:
// Standard freight (≤500kg): ~€180/kg container revenue per cargo hold space allocated  
// Bulk palletized shipments (>500kg): €640/200kg unit after overheads paid  

```

---

### 3. Mixed Revenue Flight Calculation

**Combined Passenger + Cargo Analysis:**

```php
function calculate_total_flight_economics(flight_details: { aircraft, route, duration }) {  
    
    # Calculate passenger revenue (dynamic pricing): 
    $passenger_revenue = dynamic_pricing(
        cabin_distribution['economy': 72%, 'business': 18%, 'first_class': 5%],
        season_multiplier,  
        demand_factor
    );

    # Compute cargo revenue:
    let cargo_manifest = flight_dispatch_system.get_cargo_shipments(flight_number);  
    $cargo_revenue = calculateCargoRevenue(flight_number, cargo_manifest);  

    /** Total Flight Economics **/
    let total_revenue = ($passenger_revenue + $cargo_revenue) + upsell_addons();

    # Calculate all flight costs (from earlier breakdown):
    let operating_cost_breakdown = [ 
        fuel_cost: €3880,  
        crew_pay: €1260,  
        ground_fees: €560,  
        catering: €970,  
        maintenance_reserve: €360,  
        insurance_overhead: €140  // 0.08% per flight revenue
   ];

    let total_cost = sum_over(operating_cost_breakdown)(cost_item → cost_value);  

    # Net Margin Calculation
    net_profit_margin_percent = (($total_revenue - $total_cost) / $total_revenue) × 100;

     return { 
         'passenger_revenue': $passenger_revenue,  // €8950 (example from combined model)  
         'cargo_revenue': $cargo_revenue,          // €3200 (mixed cargo loads
         'total_revenue': $combined_sum,
         'total_cost': $operating_cost_breakdown.sum(),
         'net_profit': ($combined_sum - $costs),
         'profit_margin_percentage": margin_percent,  
         'break_even_status': net_profit <= 0 ? 'below_breakeven' : 'profitable'
    };  
    
}
```

---

## 📊 Analytics Dashboard Features (Finance View)

### Finanzübersicht für Admins:

**API Endpoints Blueprint:**

1. **`/api/admin/flights/{id}/economics`**: Flight-by-flight profit breakdown  
2. **`/api/admin/aircrafts/{id}/fuel-consumption`**: Fuel burn statistics per aircraft type  
3. **`/api/admin/routes/{route_id}/revenue-optimization`**: Route profitability analysis  

**Real-time Metrics:**
| Metric | Formula | Dashboard Card Display  
|--------|---------|------------------------|
| **Daily Revenue** | sum_flights(today → total_revenue) / count(flights, today × 100%)  
| **Fuel Efficiency Index** | Average fuel burn kg per km-nm (ICAO standard comparison: A320neo = 3.2L/NM efficient)
| **Passenger Load Factor** | ($total_passengers_onboard / available_seats × 100%), # Occupancy rate  
| **Cargo Utilization** | ($actual_cargo_space_used / total_freight_capacity × 100%)  
| **Break-even Flight Count** | count(>profitable_flights_for_day) / total_daily_flights × 100)  

---

## 📋 Datenbanktabellen für Finance System

### 1. Flights Economics Tracking:

```sql
CREATE TABLE flight_economics 
( flight_id INT PRIMARY KEY REFERENCES flights(id),  
  revenue_components DECIMAL(15,2) DEFAULT (0.00),  
  cost_breakdown JSON,   # { fuel, crew, catering, ground_fees }  
  net_profit DECIMAL(15,2),  
  profit_margin_percent DECIMAL(4,2),
  cargo_revenue DECIMAL(15,2),  
  passenger_revenue DECIMAL(15,2)
);

# Usage: Store economics per flight after completion (automatic calculation triggered by ACARS job)
```

---

### 2. Dynamic Pricing Configuration:

```sql
CREATE TABLE dynamic_pricing_rules 
( id INT PRIMARY KEY AUTO_INCREMENT,  
  cabin_class VARCHAR(30),   # 'economy', 'business', 'first'
  base_price DECIMAL(10,2),
  seasonal_multiplier DECIMAL(4,2),    # Peak season × higher, low seasons ↓
  cargo_discount_percent INT,          # Bulk freight volume discount %  
);

-- Usage: Admin can adjust pricing parameters in GUI (e.g., increase economy during holiday peak season)
```

---

## 🔄 Automation Workflow (Post-Flight Economics Calculation)

**ACARS + Finance System Integration:**

```yaml
Workflow:
  1. Flight completes landing → ACARS job triggers economics_calculation_job(flight_id)
  2. Calculate ticket revenue from passenger_manifest + upsell add-ons  
  3. Pull cargo shipments from flight dispatch system (cargo loaded for return trip if applicable)  
  4. Compute fuel burn via SimConnect logs or standard fuel consumption estimation  
  5. Generate economics_report(flight_id, route_from: 'Economy → Passenger + Cargo Revenue
  6. Store in flight_economics database table for analytics
  7. Email report to admin with optimization suggestions (e.g., "Increase economy class pricing by 10% on next seasonal update")
```

---

## 🎯 MVP Implementation Prioritäten:

**Phase 1 (Essential):**
- ✅ Passenger ticket revenue calculation  
- ✅ Basic fuel/crew costs per flight  
- 🔄 Dynamic pricing system for Economy/Business classes  
✈️ Ground handling & catering cost tracking  

**Phase 2 (Post-MVP):**  
- ❌ Cargo freight revenue automation  
- ❌ Advanced upsell tracking (baggage, VIP perks)  
- ❌ Financial reports with break-even analytics  
- ✅ Route profitability comparison views  

---

## 📊 Roadmap Status: Finance System Planiert!

| Feature | Status | Priority | ETA Phase  
|---------|--------|----------|-----------|
| **Dynamic Passenger Pricing** ✅ | MVP Ready | High | Launch v1.x  

| **Basic Fuel/Crew Costs**  | ✅ | MVP Ready | Immediate  
| **Ground Handling Tracking** | 🔄 | Medium | Post-MVP  
| Cargo Freight System      | ❌ | Low | Phase 3 (Optional)  

Was soll zuerst implementiert werden in MVP? Sag einfach!
