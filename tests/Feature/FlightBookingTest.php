<?php

namespace Tests\Feature;

use App\Models\Pilot;
use App\Models\FlightSchedule;
use App\Models\FlightLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FlightBookingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function pilot_can_book_flight_when_available()
    {
        // Arrange
        $pilot = Pilot::create([
            'name' => 'Test Pilot',
            'callsign' => 'TEST 1',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'pilot',
        ]);

        $schedule = FlightSchedule::create([
            'callsign' => 'RWH-001',
            'departure_date' => now()->addDays(7),
            'from_airport_icao' => 'EGKK',
            'to_airport_icao' => 'LHBB',
            'estimated_off' => now()->addDays(7)->addHours(8),
            'estimated_arrival' => now()->addDays(7)->addHours(9),
            'aircraft_type' => 'A320',
            'active' => true,
        ]);

        // Act
        $response = $this->actingAs($pilot)
            ->postJson('/api/v1/flights/booking', [
                'schedule_id' => $schedule->id,
            ]);

        // Assert
        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Flight booked successfully',
        ]);

        $this->assertDatabaseHas('flight_logs', [
            'schedule_id' => $schedule->id,
            'pilot_id' => $pilot->id,
        ]);
    }

    /** @test */
    public function pilot_cannot_book_flight_after_24h_time_lock()
    {
        // Arrange
        $pilot = Pilot::create([
            'name' => 'Test Pilot',
            'callsign' => 'TEST 1',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'pilot',
        ]);

        $schedule = FlightSchedule::create([
            'callsign' => 'RWH-001',
            'departure_date' => now()->addDays(7),
            'from_airport_icao' => 'EGKK',
            'to_airport_icao' => 'LHBB',
            'estimated_off' => now()->addDays(7)->addHours(8),
            'estimated_arrival' => now()->addDays(7)->addHours(9),
            'aircraft_type' => 'A320',
            'active' => true,
        ]);

        // Book flight and wait for time lock
        FlightLog::create([
            'pilot_id' => $pilot->id,
            'schedule_id' => $schedule->id,
            'completed' => false,
        ]);

        // Wait 24 hours (simulated)
        now()->addHours(25)->subMinutes(10);

        // Act
        $response = $this->actingAs($pilot)
            ->postJson('/api/v1/flights/booking', [
                'schedule_id' => $schedule->id,
            ]);

        // Assert
        $response->assertStatus(423); // Too Early
        $response->assertJson([
            'message' => 'Flight booking released after 24h time-lock',
        ]);
    }

    /** @test */
    public function pilot_can_cancel_flight_before_completion()
    {
        // Arrange
        $pilot = Pilot::create([
            'name' => 'Test Pilot',
            'callsign' => 'TEST 1',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'pilot',
        ]);

        $schedule = FlightSchedule::create([
            'callsign' => 'RWH-001',
            'departure_date' => now()->addDays(7),
            'from_airport_icao' => 'EGKK',
            'to_airport_icao' => 'LHBB',
            'estimated_off' => now()->addDays(7)->addHours(8),
            'estimated_arrival' => now()->addDays(7)->addHours(9),
            'aircraft_type' => 'A320',
            'active' => true,
        ]);

        $flightLog = FlightLog::create([
            'pilot_id' => $pilot->id,
            'schedule_id' => $schedule->id,
            'completed' => false,
        ]);

        // Act
        $response = $this->actingAs($pilot)
            ->putJson('/api/v1/flights/' . $flightLog->id . '/cancel');

        // Assert
        $response->assertStatus(200);
        $this->assertTrue($flightLog->fresh()->completed);
    }

    /** @test */
    public function pilot_cannot_cancel_completed_flight()
    {
        // Arrange
        $pilot = Pilot::create([
            'name' => 'Test Pilot',
            'callsign' => 'TEST 1',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'pilot',
        ]);

        $schedule = FlightSchedule::create([
            'callsign' => 'RWH-001',
            'departure_date' => now()->addDays(7),
            'from_airport_icao' => 'EGKK',
            'to_airport_icao' => 'LHBB',
            'estimated_off' => now()->addDays(7)->addHours(8),
            'estimated_arrival' => now()->addDays(7)->addHours(9),
            'aircraft_type' => 'A320',
            'active' => true,
        ]);

        $flightLog = FlightLog::create([
            'pilot_id' => $pilot->id,
            'schedule_id' => $schedule->id,
            'completed' => true,
        ]);

        // Act
        $response = $this->actingAs($pilot)
            ->putJson('/api/v1/flights/' . $flightLog->id . '/cancel');

        // Assert
        $response->assertStatus(400);
    }

    /** @test */
    public function ground_control_can_assign_pilot_to_schedule()
    {
        // Arrange
        $pilot = Pilot::create([
            'name' => 'Test Pilot',
            'callsign' => 'TEST 1',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'pilot',
            'aircraft_certified' => ['A320'],
        ]);

        $schedule = FlightSchedule::create([
            'callsign' => 'RWH-001',
            'departure_date' => now()->addDays(7),
            'from_airport_icao' => 'EGKK',
            'to_airport_icao' => 'LHBB',
            'estimated_off' => now()->addDays(7)->addHours(8),
            'estimated_arrival' => now()->addDays(7)->addHours(9),
            'aircraft_type' => 'A320',
            'active' => true,
        ]);

        // Act
        $response = $this->actingAs(Pilot::find(1)) // Admin pilot
            ->postJson('/dispatch/schedules/' . $schedule->id . '/assign-pilot', [
                'pilot_id' => $pilot->id,
            ]);

        // Assert
        $response->assertStatus(200);
        $this->assertEquals($pilot->id, $schedule->fresh()->assigned_pilot_id);
    }

    /** @test */
    public function ground_control_cannot_assign_unqualified_pilot()
    {
        // Arrange
        $pilot = Pilot::create([
            'name' => 'Test Pilot',
            'callsign' => 'TEST 1',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'pilot',
            'aircraft_certified' => ['B738'], // Not certified for A320
        ]);

        $schedule = FlightSchedule::create([
            'callsign' => 'RWH-001',
            'departure_date' => now()->addDays(7),
            'from_airport_icao' => 'EGKK',
            'to_airport_icao' => 'LHBB',
            'estimated_off' => now()->addDays(7)->addHours(8),
            'estimated_arrival' => now()->addDays(7)->addHours(9),
            'aircraft_type' => 'A320', // Mismatch
            'active' => true,
        ]);

        // Act
        $response = $this->actingAs(Pilot::find(1))
            ->postJson('/dispatch/schedules/' . $schedule->id . '/assign-pilot', [
                'pilot_id' => $pilot->id,
            ]);

        // Assert
        $response->assertStatus(302); // Redirect with error message
    }
}
