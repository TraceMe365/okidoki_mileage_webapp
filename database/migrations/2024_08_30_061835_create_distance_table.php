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
        Schema::create('distance', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->nullable(true);
            $table->string('pickup_latitude')->nullable(true);
            $table->string('pickup_longitude')->nullable(true);
            $table->string('dropoff_latitude')->nullable(true);
            $table->string('dropoff_longitude')->nullable(true);
            $table->string('distance')->nullable(true);
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
        Schema::dropIfExists('distance');
    }
};
