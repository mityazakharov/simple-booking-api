<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('phone', 15)->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->boolean('newsletter')->default(0);
            $table->text('info')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('client_employer', function (Blueprint $table) {
            $table->integer('client_id')->unsigned();
            $table->integer('employer_id')->unsigned();
            $table->timestamps();

            $table->unique(['client_id', 'employer_id']);

            $table->foreign('client_id')->on('clients')->references('id')->onDelete('cascade');
            $table->foreign('employer_id')->on('employers')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_employer');
        Schema::dropIfExists('clients');
    }
}
