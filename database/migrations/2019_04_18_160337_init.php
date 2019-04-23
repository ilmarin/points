<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Init extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('Point name');
            $table->point('location')->comment('Point location');
            $table->text('desc')->comment('Short description');
            $table->unsignedInteger('city_id')->comment('City id');
        });

        Schema::table('point', function (Blueprint $table) {
            $table->spatialIndex('location');
        });

        Schema::create('city', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->comment('City name');
        });

        Schema::table('point', function (Blueprint $table) {
            $table->foreign('city_id')
                ->references('id')
                ->on('city');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('point');
        Schema::dropIfExists('city');
    }
}
