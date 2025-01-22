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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->integer('spelization_id')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('countryNameCode')->nullable();
            $table->string('countryPhoneCode')->nullable();
            $table->date('registerationDate')->nullable();
            $table->text('image')->nullable();
            $table->integer('active')->nullable();
            $table->integer('hide')->nullable();
            $table->integer('availableForWork')->nullable();
            $table->integer('trusted')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
