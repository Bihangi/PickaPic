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
        // Drop existing portfolios table if it exists
        Schema::dropIfExists('portfolios');
        
        // Create new portfolios table with correct structure
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photographer_id')->constrained('photographers')->onDelete('cascade'); 
            $table->string('file_path'); 
            $table->string('original_name')->nullable();
            $table->integer('file_size')->nullable(); 
            $table->string('mime_type')->nullable(); 
            $table->string('title')->nullable(); 
            $table->text('description')->nullable(); 
            $table->boolean('is_featured')->default(false); 
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index('photographer_id');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};