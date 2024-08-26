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
        Schema::create('mileage', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id')->nullable(true);            
            $table->string('vehicle_id')->nullable(true);            
            $table->string('vehicle_name')->nullable(true);            
            $table->string('from_time')->nullable(true);            
            $table->string('to_time')->nullable(true);            
            $table->string('mileage')->nullable(true);            
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
        Schema::dropIfExists('mileage');
    }
};
