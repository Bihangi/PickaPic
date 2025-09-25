<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('premium_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photographer_id')
                  ->constrained('photographers')
                  ->onDelete('cascade');
            $table->enum('package_type', ['monthly', 'quarterly', 'yearly'])
                  ->default('monthly');
            $table->decimal('amount_paid', 10, 2);
            $table->string('payment_slip')->nullable(); 
            $table->text('message')->nullable();        
            $table->enum('status', ['pending', 'approved'])
                  ->default('pending');
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('expires_at')->nullable(); 
            $table->timestamps();

            $table->index(['photographer_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('premium_requests');
    }
};
