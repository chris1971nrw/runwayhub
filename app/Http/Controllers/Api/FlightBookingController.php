<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FlightSchedule;
use App\Models\FlightLog;
use App\Models\Pilot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FlightBookingController extends Controller
{
    /**
     * Store a newly created flight booking.
     * Implements 24h Time-Lock Mechanism
     */
    public function store(Request $request)
    {
        // Validation Rules
        $validated = $request->validate([
            'schedule_id' => 'required|exists:flight_schedules,id',
            'pilot_id' => 'nullable|exists:pilots,id',
            'booking_notes' => 'nullable|string|max:500',
        ]);

        // Get the flight schedule
        $schedule = FlightSchedule::findOrFail($validated['schedule_id']);

        // Check if schedule is active and available
        if (!$schedule->isActive()) {
            return response()->json([
                'message' => 'Flight is not available for booking',
                'reason' => $schedule->active ? 'Already completed' : 'Not yet scheduled'
            ], 400);
        }

        // Check 24h Time-Lock (First-Come-First-Served)
        $currentBooking = FlightLog::where('schedule_id', $schedule->id)
            ->whereNull('completed')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($currentBooking && !$request->input('force_book')) {
            $timeRemaining = now()->subHours(24)->diffInMinutes($currentBooking->created_at);
            
            if ($timeRemaining > 0) {
                return response()->json([
                    'message' => 'Flight booking released after 24h time-lock',
                    'time_remaining_minutes' => $timeRemaining,
                    'schedule_id' => $schedule->id
                ], 423); // Too Early
            }
        }

        // Create flight log (booking)
        $flightLog = FlightLog::create([
            'pilot_id' => $request->input('pilot_id') ?? Auth::id(),
            'schedule_id' => $schedule->id,
            'comments' => $request->input('booking_notes'),
            'completed' => false,
        ]);

        // Update schedule status to booked
        $schedule->update(['active' => false]);

        return response()->json([
            'message' => 'Flight booked successfully',
            'flight_log' => $flightLog,
            'schedule' => $schedule->load('assignedPilot')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($flight)
    {
        $flightLog = FlightLog::with(['pilot', 'schedule'])
            ->findOrFail($flight);

        return response()->json($flightLog);
    }

    /**
     * Update the flight booking status (cancel/release).
     */
    public function cancel(Request $request, $flight)
    {
        $flightLog = FlightLog::findOrFail($flight);

        // Only allow cancellation if not completed
        if ($flightLog->completed) {
            return response()->json([
                'message' => 'Completed flights cannot be cancelled',
                'flight_log_id' => $flightLog->id
            ], 400);
        }

        // Update flight log status
        $flightLog->update(['completed' => true]);

        // Release schedule for re-booking
        $schedule = FlightSchedule::findOrFail($flightLog->schedule_id);
        $schedule->update(['active' => true]);

        return response()->json([
            'message' => 'Flight cancelled successfully',
            'flight_log' => $flightLog->fresh()
        ]);
    }

    /**
     * Check flight availability.
     */
    public function checkAvailability($scheduleId)
    {
        $schedule = FlightSchedule::findOrFail($scheduleId);

        return response()->json([
            'schedule_id' => $schedule->id,
            'callsign' => $schedule->callsign,
            'departure_date' => $schedule->departure_date,
            'available' => $schedule->isActive(),
            'booking_deadline' => now()->addHours(24)->toDateTimeString()
        ]);
    }

    /**
     * List all bookings for the authenticated pilot.
     */
    public function index(Request $request)
    {
        $pilots = Pilot::where('role', 'pilot');

        if ($request->user()) {
            $pilots->where('id', $request->user()->id);
        }

        $flights = FlightLog::with(['schedule'])
            ->whereIn('pilot_id', $pilots->pluck('id'))
            ->orderBy('schedule.departure_date')
            ->paginate(15);

        return response()->json($flights);
    }
}
