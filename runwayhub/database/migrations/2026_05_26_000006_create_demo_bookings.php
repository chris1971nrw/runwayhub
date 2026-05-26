<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executiere Migration für Demo Bookings
     */
    public function up(): void
    {
        Schema::create('demo_bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            // Flight Relation
            $table->unsignedBigInteger('flight_id')->nullable();
            $table->foreign('flight_id')->references('id')->on('demo_flights')->onDelete('cascade');
            
            // Passenger Info
            $table->string('booking_number', 20)->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('demo_users')->onDelete('set null');
            $table->string('passenger_name', 255);
            $table->string('passenger_email', 255)->nullable();
            $table->enum('passenger_type', ['adult', 'child', 'infant'])->default('adult');
            
            // Class
            $table->enum('class', ['economy', 'business', 'first'])->default('economy');
            
            // Pricing
            $table->decimal('price', 10, 2);
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2);
            
            // Payment
            $table->enum('payment_method', ['credit_card', 'paypal', 'bank_transfer', 'cash'])->default('credit_card');
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->text('transaction_id')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'aboard', 'cancelled'])->default('pending');
            
            // Timestamps
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            // Indexe
            $table->index(['booking_number', 'flight_id', 'status']);
        });
    }

    /**
     * Rollback Migration
     */
    public function down(): void
    {
        Schema::dropIfExists('demo_bookings');
    }
};
