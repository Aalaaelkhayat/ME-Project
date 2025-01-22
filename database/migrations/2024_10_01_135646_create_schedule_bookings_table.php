<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booker_id')->nullable();
            $table->foreign('booker_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unsignedBigInteger('schedule_id')->nullable();
            $table->foreign('schedule_id')->references('id')->on('schedules')->cascadeOnDelete();
            $table->integer('confirm')->default('0');
            $table->integer('attended')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_bookings');
    }
};
