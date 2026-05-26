<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Erstellt Demo-User für DemoAirline in Haupt-System
     * 
     * User:
     * - Demo Administrator (admin@runwayhub.de)
     * - Demo Pilot (pilot@runwayhub.de)
     * - Demo Guest (guest@runwayhub.de)
     */
    public function up(): void
    {
        Schema::create('demo_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('airline_id');
            $table->string('username', 50);
            $table->string('email', 100);
            $table->string('password'); // Hash
            $table->string('role'); // admin|staff|pilot|guest
            $table->string('first_name');
            $table->string('last_name');
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable(); // Bildpfad oder URL
            $table->string('phone')->nullable();
            $table->enum('status', 'active', 'inactive', 'suspended');
            $table->timestamps();

            // Indexe
            $table->index('airline_id');
            $table->index('email');
            $table->index('role');
            $table->index('status');

            // Constraints
            $table->foreign('airline_id')
                ->references('id')
                ->on('airlines')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demo_users');
    }
};
