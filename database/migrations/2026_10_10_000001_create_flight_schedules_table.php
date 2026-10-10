<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flight_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('callsign')->unique(); // RWH-XXX format
            $table->date('departure_date');
            $table->string('from_airport_icao'); // EGKK, LHBB, etc.
            $table->string('to_airport_icao');
            $table->dateTime('estimated_off');
            $table->dateTime('estimated_arrival');
            $table->enum('aircraft_type', ['A320', 'B738', 'A319', 'B752']);
            $table->integer('distance_nm')->nullable(); // Nautical Miles
            $table->boolean('active')->default(true);
            $table->text('route_notes')->nullable();
            $table->timestamps();

            $table->index(['departure_date', 'from_airport_icao', 'to_airport_icao']);
            $table->index(['callsign', 'departure_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_schedules');
    }
};
