<?php

namespace App\Http\Controllers;

use App\Models\Pilot;
use App\Models\FlightSchedule;
use App\Models\Aircraft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display admin dashboard with finance reports.
     */
    public function dashboard()
    {
        // Fetch statistics for dashboard
        $statistics = [
            'total_revenue' => FlightLog::where('completed', true)
                ->sum('fuel_burned_gallons'), // TODO: Calculate actual revenue
            
            'total_flights' => FlightLog::count(),
            
            'active_pilots' => Pilot::where('role', 'pilot')->count(),
            
            'pending_flights' => FlightSchedule::where('active', true)->count(),
            
            'aircraft_fleet' => Aircraft::count(),
        ];

        return view('admin.dashboard', compact('statistics'));
    }

    /**
     * Manage pilots.
     */
    public function pilots(Request $request)
    {
        $pilots = Pilot::with(['logs'])
            ->latest()
            ->paginate(15);

        if ($request->method() === 'POST') {
            // Create new pilot
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'callsign' => 'required|unique:pilots,callsign|max:10',
                'email' => 'required|email|unique:pilots,email',
                'password' => 'required|min:8|confirmed',
                'role' => 'required|in:pilot,crew,ground_control',
            ]);

            Pilot::create([
                'name' => $validated['name'],
                'callsign' => $validated['callsign'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => $validated['role'],
            ]);

            return redirect()->route('admin.pilots')->with('success', 'Pilot created successfully.');
        }

        return view('admin.pilots.index', compact('pilots'));
    }

    /**
     * Manage flight schedules.
     */
    public function schedules(Request $request)
    {
        $schedules = FlightSchedule::with(['assignedPilot'])
            ->latest()
            ->paginate(15);

        if ($request->method() === 'POST') {
            // Create new schedule
            $validated = $request->validate([
                'callsign' => 'required|unique:flight_schedules,callsign|max:20',
                'departure_date' => 'required|date',
                'from_airport_icao' => 'required|string|max:10',
                'to_airport_icao' => 'required|string|max:10',
                'estimated_off' => 'required|date|after:departure_date',
                'estimated_arrival' => 'required|date|after:estimated_off',
                'aircraft_type' => 'required|in:A320,B738,A319,B752',
                'distance_nm' => 'nullable|integer|min:0|max:5000',
            ]);

            FlightSchedule::create([
                'callsign' => $validated['callsign'],
                'departure_date' => $validated['departure_date'],
                'from_airport_icao' => $validated['from_airport_icao'],
                'to_airport_icao' => $validated['to_airport_icao'],
                'estimated_off' => $validated['estimated_off'],
                'estimated_arrival' => $validated['estimated_arrival'],
                'aircraft_type' => $validated['aircraft_type'],
                'distance_nm' => $validated['distance_nm'] ?? null,
            ]);

            return redirect()->route('ground_control.schedules')->with('success', 'Flight schedule created successfully.');
        }

        return view('admin.schedules.index', compact('schedules'));
    }

    /**
     * Manage aircraft fleet.
     */
    public function aircraft(Request $request)
    {
        $aircrafts = Aircraft::with(['assignments'])
            ->latest()
            ->paginate(15);

        if ($request->method() === 'POST') {
            // Create new aircraft
            $validated = $request->validate([
                'registration' => 'required|unique:aircrafts,registration|max:20',
                'model_type' => 'required|in:A320,B738,A319,B752,A330',
                'total_seats' => 'required|integer|min:0|max:500',
                'total_crew_required' => 'required|integer|min:0|max:20',
                'maintenance_status' => 'required|in:airworthy,scheduled_maint,grounded',
                'next_maintenance_due' => 'nullable|date',
            ]);

            Aircraft::create([
                'registration' => $validated['registration'],
                'model_type' => $validated['model_type'],
                'total_seats' => $validated['total_seats'],
                'total_crew_required' => $validated['total_crew_required'],
                'maintenance_status' => $validated['maintenance_status'],
                'next_maintenance_due' => $validated['next_maintenance_due'] ?? null,
            ]);

            return redirect()->route('admin.aircraft')->with('success', 'Aircraft added successfully.');
        }

        return view('admin.aircraft.index', compact('aircrafts'));
    }

    /**
     * View finance reports.
     */
    public function finance()
    {
        // Fetch financial data for reports
        $reports = [
            'monthly_revenue' => FlightLog::where('completed', true)
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->count(),
            
            'total_fuel_consumed' => FlightLog::where('completed', true)->sum('fuel_burned_gallons'),
            
            'average_flight_duration' => FlightLog::where('completed', true)
                ->avg('actual_arrival') ?? 0,
        ];

        return view('admin.finance.index', compact('reports'));
    }

    /**
     * Update aircraft maintenance status.
     */
    public function updateMaintenance(Request $request, $id)
    {
        $aircraft = Aircraft::findOrFail($id);

        $validated = $request->validate([
            'maintenance_status' => 'required|in:airworthy,scheduled_maint,grounded',
            'next_maintenance_due' => 'nullable|date',
        ]);

        $aircraft->update($validated);

        return redirect()->route('admin.aircraft')->with('success', 'Maintenance status updated successfully.');
    }

    /**
     * Delete a pilot (admin only).
     */
    public function deletePilot($id)
    {
        $pilot = Pilot::findOrFail($id);

        // Prevent deleting ground_control or admin users
        if ($pilot->role !== 'pilot') {
            return redirect()->route('admin.pilots')->with('error', 'Cannot delete non-pilot accounts.');
        }

        $pilot->delete();

        return redirect()->route('admin.pilots')->with('success', 'Pilot deleted successfully.');
    }
}
