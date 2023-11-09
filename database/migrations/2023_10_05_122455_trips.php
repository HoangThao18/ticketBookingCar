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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->date('departure_time');
            $table->date('arrival_time');
            $table->string('status');
            $table->unsignedBigInteger('car_id');
            $table->unsignedBigInteger('start_station');
            $table->unsignedBigInteger('end_station');
            $table->unsignedBigInteger('driver_id');
            $table->timestamps();

            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('users');
            $table->foreign('start_station')->references('id')->on('stations');
            $table->foreign('end_station')->references('id')->on('stations');
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
