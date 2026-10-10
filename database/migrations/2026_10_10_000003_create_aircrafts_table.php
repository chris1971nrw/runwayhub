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
        Schema::create('aircrafts', function (Blueprint $table) {
            $table->id();
            $table->string('registration'); // D-AIMR, etc.
            $table->enum('model_type', ['A320', 'B738', 'A319', 'B752', 'A330']);
            $table->integer('total_seats')->default(180);
            $table->integer('total_crew_required')->default(4);
            $table->enum('maintenance_status', ['airworthy', 'scheduled_maint', 'grounded']);
            $table->date('next_maintenance_due')->nullable();
            $table->integer('engine_cycles')->default(0);
            $table->boolean('available')->default(true);
            $table->text('airworthiness_certificate')->nullable(); // LBA §15 Compliance
            $table->timestamps();

            $table->index(['model_type', 'maintenance_status', 'available']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aircrafts');
    }
};
