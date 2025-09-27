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
            $table->foreignId('photographer_id')->constrained('photographers');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['available', 'booked'])->default('available');
            $table->foreignId('client_id')->nullable()->constrained('users');
            $table->foreignId('booking_id')->nullable()->constrained();
            $table->text('event_details')->nullable();
            $table->string('contact_number')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['photographer_id', 'date']);
            $table->index(['photographer_id', 'status']);
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
