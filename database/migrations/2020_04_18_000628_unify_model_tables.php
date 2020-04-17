<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UnifyModelTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('renters', function (Blueprint $table) {
            $table->renameColumn('description', 'info');
        });

        Schema::table('places', function (Blueprint $table) {
            $table->renameColumn('description', 'info');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('newsletter', 'is_loyal');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('agent_id')->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('renters', function (Blueprint $table) {
            $table->renameColumn('info', 'description');
        });

        Schema::table('places', function (Blueprint $table) {
            $table->renameColumn('info', 'description');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('is_loyal', 'newsletter');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->bigInteger('agent_id')->unsigned()->change();
        });
    }
}
