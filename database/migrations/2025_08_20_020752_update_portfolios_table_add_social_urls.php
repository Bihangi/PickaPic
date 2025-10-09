<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('photographers', function (Blueprint $table) {
            // Remove social media columns from photographers table
            if (Schema::hasColumn('photographers', 'instagram')) {
                $table->dropColumn('instagram');
            }
            if (Schema::hasColumn('photographers', 'facebook')) {
                $table->dropColumn('facebook');
            }
            if (Schema::hasColumn('photographers', 'website')) {
                $table->dropColumn('website');
            }
        });
    }

    public function down()
    {
        Schema::table('photographers', function (Blueprint $table) {
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('website')->nullable();
        });
    }
};

