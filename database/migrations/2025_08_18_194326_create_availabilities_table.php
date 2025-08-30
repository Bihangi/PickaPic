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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photographer_id')->constrained('users')->onDelete('cascade');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['available', 'booked', 'cancelled'])->default('available');
            $table->foreignId('booked_by')->nullable()->constrained('users')->onDelete('set null');
            $table->json('booking_details')->nullable(); 
            $table->string('contact_number')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['photographer_id', 'date', 'status']);
            $table->index(['date', 'status']);
            $table->index('status');
            $table->unique(['photographer_id', 'date', 'start_time', 'end_time'], 'unique_time_slot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};