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
        Schema::create('pilots', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('callsign')->unique(); // ICAO Callsign (ALPHA 1, BRAVO 2)
            $table->string('email')->unique();
            $table->string('password');
            $table->string('home_base_icao')->nullable(); // EGKK, LHBB, etc.
            $table->enum('skill_level', ['beginner', 'intermediate', 'expert']);
            $table->enum('license_type', ['PPL', 'CPL', 'ATPL']);
            $table->json('aircraft_certified')->nullable(); // Array of aircraft types
            $table->enum('role', ['pilot', 'crew', 'ground_control']);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_active')->nullable();
            $table->timestamps();

            $table->index(['skill_level', 'license_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pilots');
    }
};
