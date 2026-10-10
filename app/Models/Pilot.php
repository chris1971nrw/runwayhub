<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pilot extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'callsign',
        'email',
        'password',
        'home_base_icao',
        'skill_level',
        'license_type',
        'aircraft_certified',
        'role',
        'is_active',
        'last_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'aircraft_certified' => 'array',
        'is_active' => 'boolean',
        'last_active' => 'datetime',
    ];

    /**
     * Get the schedules assigned to this pilot.
     */
    public function schedules()
    {
        return $this->hasMany(FlightSchedule::class, 'assigned_pilot_id');
    }

    /**
     * Get the flight logs created by this pilot.
     */
    public function logs()
    {
        return $this->hasMany(FlightLog::class, 'pilot_id');
    }

    /**
     * Check if pilot is certified for aircraft type.
     */
    public function isCertifiedForAircraft(string $aircraftType): bool
    {
        return in_array($aircraftType, $this->aircraft_certified ?? []);
    }

    /**
     * Get the pilot's role.
     */
    public function getRoleAttribute(): string
    {
        return $this->role ?? 'pilot';
    }
}
