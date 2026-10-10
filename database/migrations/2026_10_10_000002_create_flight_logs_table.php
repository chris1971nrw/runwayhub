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
        Schema::create('flight_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pilot_id')->constrained('pilots')->onDelete('cascade');
            $table->foreignId('schedule_id')->constrained('flight_schedules')->onDelete('cascade');
            $table->string('actual_from_airport')->nullable(); // Real flight departed from
            $table->string('actual_to_airport')->nullable(); // Real flight arrived at
            $table->dateTime('actual_off')->nullable();
            $table->dateTime('actual_arrival')->nullable();
            $table->integer('fuel_burned_gallons')->nullable();
            $table->text('comments')->nullable();
            $table->boolean('completed')->default(false);
            $table->json('screenshots')->nullable(); // Array of file paths
            $table->string('weather_conditions')->nullable();
            $table->decimal('flight_rating', 3, 2)->default(0.00); // ⭐ rating
            $table->timestamps();

            $table->index(['schedule_id', 'completed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_logs');
    }
};
