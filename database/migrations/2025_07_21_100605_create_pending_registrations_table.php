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
        Schema::create('pending_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('google_id')->nullable()->unique(); 
            $table->string('name');
            $table->string('email')->unique(); 
            $table->string('contact')->nullable();
            $table->string('password')->nullable(); 
            $table->string('role')->default('photographer');
            $table->json('google_profile_data')->nullable(); 
            $table->boolean('is_google_registered')->default(false); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_registrations');
    }
};