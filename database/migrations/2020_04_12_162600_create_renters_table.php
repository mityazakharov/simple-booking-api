<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('phone', 15)->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->text('description')->nullable();
            $table->integer('color_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('color_id')->on('colors')->references('id')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('renters');
    }
}
