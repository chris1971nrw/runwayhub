<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightSchedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'callsign',
        'departure_date',
        'from_airport_icao',
        'to_airport_icao',
        'estimated_off',
        'estimated_arrival',
        'aircraft_type',
        'distance_nm',
        'active',
        'route_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'departure_date' => 'date',
        'estimated_off' => 'datetime',
        'estimated_arrival' => 'datetime',
        'active' => 'boolean',
    ];

    /**
     * Get the pilot assigned to this schedule.
     */
    public function assignedPilot()
    {
        return $this->belongsTo(Pilot::class, 'assigned_pilot_id');
    }

    /**
     * Get the flight logs for this schedule.
     */
    public function logs()
    {
        return $this->hasMany(FlightLog::class, 'schedule_id');
    }

    /**
     * Check if schedule is active and ready for booking.
     */
    public function isActive(): bool
    {
        return $this->active && 
               $this->departure_date->isFuture() && 
               !$this->logs()->where('completed', true)->exists();
    }

    /**
     * Generate RWH callsign format.
     */
    public static function generateCallsign(int $number): string
    {
        return 'RWH-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}
