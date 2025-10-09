<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('photographers', function (Blueprint $table) {
            $table->dropColumn(['rating', 'hourly_rate', 'daily_rate', 'portfolio']);
        });
    }

    public function down()
    {
        Schema::table('photographers', function (Blueprint $table) {
            $table->enum('rating', ['1','2','3','4','5'])->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->decimal('daily_rate', 8, 2)->nullable();
            $table->longText('portfolio')->nullable();
        });
}

};
