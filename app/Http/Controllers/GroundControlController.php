<?php

namespace App\Http\Controllers;

use App\Models\FlightSchedule;
use App\Models\Pilot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroundControlController extends Controller
{
    /**
     * Display dispatch dashboard with all schedules.
     */
    public function schedules(Request $request)
    {
        $filter = $request->input('status', 'all');
        
        $schedules = FlightSchedule::with(['assignedPilot'])
            ->when($filter !== 'all', function ($query) use ($filter) {
                return $query->where('active', $filter === 'active' || $filter === 'pending');
            })
            ->latest()
            ->paginate(15);

        return view('ground_control.schedules', compact('schedules'));
    }

    /**
     * Create a new flight schedule.
     */
    public function createSchedule(Request $request)
    {
        // Generate RWH callsign automatically
        $nextNumber = FlightSchedule::max('id') + 1;
        $callsign = FlightSchedule::generateCallsign($nextNumber);

        return view('ground_control.schedules.create', compact('callsign'));
    }

    /**
     * Store a newly created flight schedule.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'callsign' => 'required|unique:flight_schedules,callsign|max:20',
            'departure_date' => 'required|date',
            'from_airport_icao' => 'required|string|max:10',
            'to_airport_icao' => 'required|string|max:10',
            'estimated_off' => 'required|date|after:departure_date',
            'estimated_arrival' => 'required|date|after:estimated_off',
            'aircraft_type' => 'required|in:A320,B738,A319,B752',
            'distance_nm' => 'nullable|integer|min:0|max:5000',
            'route_notes' => 'nullable|string|max:1000',
        ]);

        // Auto-generate callsign if not provided
        if (empty($validated['callsign'])) {
            $nextNumber = FlightSchedule::max('id') + 1;
            $validated['callsign'] = FlightSchedule::generateCallsign($nextNumber);
        }

        FlightSchedule::create([
            'callsign' => $validated['callsign'],
            'departure_date' => $validated['departure_date'],
            'from_airport_icao' => $validated['from_airport_icao'],
            'to_airport_icao' => $validated['to_airport_icao'],
            'estimated_off' => $validated['estimated_off'],
            'estimated_arrival' => $validated['estimated_arrival'],
            'aircraft_type' => $validated['aircraft_type'],
            'distance_nm' => $validated['distance_nm'] ?? null,
            'route_notes' => $validated['route_notes'] ?? null,
        ]);

        return redirect()->route('ground_control.schedules')->with('success', 'Flight schedule created successfully.');
    }

    /**
     * Display the specified schedule.
     */
    public function show($scheduleId)
    {
        $schedule = FlightSchedule::with(['assignedPilot', 'logs'])
            ->findOrFail($scheduleId);

        return view('ground_control.schedules.show', compact('schedule'));
    }

    /**
     * Update the specified schedule.
     */
    public function update(Request $request, $scheduleId)
    {
        $schedule = FlightSchedule::findOrFail($scheduleId);

        $validated = $request->validate([
            'from_airport_icao' => 'required|string|max:10',
            'to_airport_icao' => 'required|string|max:10',
            'estimated_off' => 'required|date|after:departure_date',
            'estimated_arrival' => 'required|date|after:estimated_off',
            'aircraft_type' => 'required|in:A320,B738,A319,B752',
            'distance_nm' => 'nullable|integer|min:0|max:5000',
        ]);

        $schedule->update($validated);

        return redirect()->route('ground_control.schedules')->with('success', 'Flight schedule updated successfully.');
    }

    /**
     * Assign a pilot to a flight schedule.
     */
    public function assignPilot(Request $request, $scheduleId)
    {
        $schedule = FlightSchedule::findOrFail($scheduleId);

        $validated = $request->validate([
            'pilot_id' => 'required|exists:pilots,id',
        ]);

        // Check if pilot is certified for aircraft type
        $pilot = Pilot::findOrFail($validated['pilot_id']);
        
        if (!$pilot->isCertifiedForAircraft($schedule->aircraft_type)) {
            return redirect()->route('ground_control.schedules.show', $scheduleId)
                ->with('error', 'Pilot is not certified for this aircraft type.');
        }

        // Assign pilot to schedule
        $schedule->update([
            'assigned_pilot_id' => $validated['pilot_id'],
        ]);

        return redirect()->route('ground_control.schedules.show', $scheduleId)
            ->with('success', 'Pilot assigned successfully.');
    }

    /**
     * Activate a pending schedule.
     */
    public function activateSchedule(Request $request, $scheduleId)
    {
        $schedule = FlightSchedule::findOrFail($scheduleId);

        // Check if schedule is within 7 days for activation
        if ($schedule->departure_date->diffInDays(now(), false) < -7 || 
            $schedule->departure_date->diffInDays(now(), false) > 7) {
            return redirect()->route('ground_control.schedules')->with('error', 'Can only activate schedules within ±7 days.');
        }

        // Check if schedule has been assigned a pilot
        if (!$schedule->assigned_pilot_id) {
            return redirect()->route('ground_control.schedules')->with('error', 'Schedule must be assigned a pilot before activation.');
        }

        $schedule->update(['active' => true]);

        return redirect()->route('ground_control.schedules')->with('success', 'Flight schedule activated successfully.');
    }

    /**
     * Cancel an active schedule.
     */
    public function cancelSchedule(Request $request, $scheduleId)
    {
        $schedule = FlightSchedule::findOrFail($scheduleId);

        if (!$schedule->active) {
            return redirect()->route('ground_control.schedules')->with('error', 'Only active schedules can be cancelled.');
        }

        // Release schedule for re-booking
        $schedule->update(['active' => false]);

        return redirect()->route('ground_control.schedules')->with('success', 'Flight schedule cancelled successfully.');
    }

    /**
     * Get available pilots for assignment.
     */
    public function getAvailablePilots(Request $request)
    {
        $aircraftType = $request->input('aircraft_type');
        
        $pilots = Pilot::where('role', 'pilot')
            ->where('is_active', true)
            ->when($aircraftType, function ($query) use ($aircraftType) {
                return $query->whereHas('aircraftCertified', function ($q) use ($aircraftType) {
                    $q->where('aircraft_type', $aircraftType);
                });
            })
            ->latest()
            ->get();

        return response()->json($pilots);
    }

    /**
     * Bulk assign pilots to multiple schedules.
     */
    public function bulkAssign(Request $request)
    {
        $validated = $request->validate([
            'schedules' => 'required|array',
            'schedule.*.pilot_id' => 'required|exists:pilots,id',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['schedules'] as $scheduleData) {
                $schedule = FlightSchedule::findOrFail($scheduleData['id']);
                
                $pilot = Pilot::findOrFail($scheduleData['pilot_id']);
                
                // Check certification
                if (!$pilot->isCertifiedForAircraft($schedule->aircraft_type)) {
                    continue; // Skip if not certified
                }

                $schedule->update([
                    'assigned_pilot_id' => $scheduleData['pilot_id'],
                    'active' => true,
                ]);
            }
        });

        return response()->json(['message' => 'Bulk assignment completed successfully.']);
    }
}
