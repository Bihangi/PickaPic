<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('photographers', function (Blueprint $table) {
            $table->text('bio')->nullable();
            $table->string('profile_picture')->nullable();
            $table->json('categories')->nullable();
            $table->string('location')->nullable();
            $table->string('languages')->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->decimal('daily_rate', 8, 2)->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->string('profile_image')->nullable();
            $table->json('portfolio')->nullable();
        });
    }

    public function down()
    {
        Schema::table('photographers', function (Blueprint $table) {
            $table->dropColumn([
                'bio',
                'profile_picture',
                'categories',
                'location',
                'languages',
                'hourly_rate',
                'daily_rate',
                'rating',
                'profile_image',
                'portfolio',
            ]);
        });
    }
};
