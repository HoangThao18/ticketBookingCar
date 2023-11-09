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
        Schema::create('time_points', function (Blueprint $table) {
            $table->id();
            $table->time('time');
            $table->string('type');
            $table->unsignedBigInteger('point_id');
            $table->unsignedBigInteger('trip_id');
            $table->foreign('point_id')->references('id')->on('points');
            $table->foreign('trip_id')->references('id')->on('trips');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
