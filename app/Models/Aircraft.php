<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aircraft extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'registration',
        'model_type',
        'total_seats',
        'total_crew_required',
        'maintenance_status',
        'next_maintenance_due',
        'engine_cycles',
        'available',
        'airworthiness_certificate',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_seats' => 'integer',
        'total_crew_required' => 'integer',
        'engine_cycles' => 'integer',
        'available' => 'boolean',
        'next_maintenance_due' => 'date',
    ];

    /**
     * Check if aircraft is airworthy.
     */
    public function isAirworthy(): bool
    {
        return $this->maintenance_status === 'airworthy' && 
               $this->available === true;
    }

    /**
     * Check if aircraft is within LBA §15 compliance.
     */
    public function isLbaCompliant(): bool
    {
        if (!$this->airworthiness_certificate) {
            return false;
        }

        // Check certificate expiry (simplified logic)
        $expiry = \Carbon\Carbon::parse($this->airworthiness_certificate);
        return $expiry->isFuture();
    }

    /**
     * Get aircraft by registration.
     */
    public static function findByRegistration(string $registration): ?self
    {
        return static::where('registration', $registration)->first();
    }
}
