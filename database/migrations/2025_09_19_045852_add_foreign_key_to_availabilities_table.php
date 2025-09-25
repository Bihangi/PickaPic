<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('availabilities', function (Blueprint $table) {
            // make sure it's unsigned big integer (matches photographers.id)
            $table->unsignedBigInteger('photographer_id')->change();

            // add foreign key
            $table->foreign('photographer_id')
                ->references('id')
                ->on('photographers')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('availabilities', function (Blueprint $table) {
            $table->dropForeign(['photographer_id']);
        });
    }

};
