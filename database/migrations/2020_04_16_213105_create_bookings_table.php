<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('begin_at');
            $table->dateTime('end_at');
            $table->integer('status_id')->unsigned()->default(1);
            $table->integer('client_id')->unsigned()->nullable();
            $table->morphs('agent');
            $table->integer('place_id')->unsigned();
            $table->text('info')->nullable();
            $table->timestamps();

            $table->foreign('status_id')->on('statuses')->references('id');
            $table->foreign('client_id')->on('clients')->references('id')->onDelete('SET NULL');

            $table->foreign('place_id')->on('places')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
