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
        Schema::table('photographers', function (Blueprint $table) {
            // Add social media & profile fields to photographers table if they don't exist
            if (!Schema::hasColumn('photographers', 'instagram')) {
                $table->string('instagram')->nullable();
            }
            if (!Schema::hasColumn('photographers', 'facebook')) {
                $table->string('facebook')->nullable();
            }
            if (!Schema::hasColumn('photographers', 'website')) {
                $table->string('website')->nullable();
            }
            if (!Schema::hasColumn('photographers', 'location')) {
                $table->string('location')->nullable();
            }
            if (!Schema::hasColumn('photographers', 'profile_image')) {
                $table->string('profile_image')->nullable();
            }
            if (!Schema::hasColumn('photographers', 'bio')) {
                $table->text('bio')->nullable();
            }
            if (!Schema::hasColumn('photographers', 'categories')) {
                $table->json('categories')->nullable();
            }
            if (!Schema::hasColumn('photographers', 'languages')) {
                $table->json('languages')->nullable();
            }
            if (!Schema::hasColumn('photographers', 'experience')) {
                $table->integer('experience')->nullable();
            }
            if (!Schema::hasColumn('photographers', 'availability')) {
                $table->json('availability')->nullable();
            }
            if (!Schema::hasColumn('photographers', 'is_verified')) {
                $table->boolean('is_verified')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('photographers', function (Blueprint $table) {
            $table->dropColumn([
                'instagram',
                'facebook', 
                'website',
                'location',
                'profile_image',
                'bio',
                'categories',
                'languages',
                'experience',
                'availability',
                'is_verified'
            ]);
        });
    }
};
