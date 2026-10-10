<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pilot_id',
        'schedule_id',
        'actual_from_airport',
        'actual_to_airport',
        'actual_off',
        'actual_arrival',
        'fuel_burned_gallons',
        'comments',
        'completed',
        'screenshots',
        'weather_conditions',
        'flight_rating',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'actual_off' => 'datetime',
        'actual_arrival' => 'datetime',
        'completed' => 'boolean',
        'screenshots' => 'array',
        'flight_rating' => 'decimal:2',
    ];

    /**
     * Get the pilot who created this log.
     */
    public function pilot()
    {
        return $this->belongsTo(Pilot::class);
    }

    /**
     * Get the schedule for this flight log.
     */
    public function schedule()
    {
        return $this->belongsTo(FlightSchedule::class);
    }

    /**
     * Check if this is a completed flight.
     */
    public function isCompleted(): bool
    {
        return $this->completed === true;
    }

    /**
     * Get the on-time performance metric.
     */
    public function getOnTimePerformanceAttribute(): float
    {
        if (!$this->actual_off || !$this->estimated_off) {
            return 0.0;
        }

        $diff = now()->diffInMinutes($this->estimated_off);
        $actualDiff = now()->diffInMinutes($this->actual_off);
        
        return max(0, 100 - abs($diff - $actualDiff));
    }
}
