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
        Schema::create('multiple_distance', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id');
            $table->string('pickup_latitude');
            $table->string('pickup_longitude');
            $table->longText('via_locations');
            $table->string('delivery_latitude');
            $table->string('delivery_longitude');
            $table->string('distance');
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
        Schema::dropIfExists('multiple_distance');
    }
};
