<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMineralsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('mineral_data')) {
            Schema::create('mineral_data', function(Blueprint $table) {

                $table->increments('id');
                $table->text('name');
                $table->text('family');
                $table->text('formula');
                $table->double('wt');
                $table->double('mw');
                $table->double('density');
                $table->double('dissolution_power');
                $table->double('enthalpy');
                $table->double('free_energy');
                $table->double('a1');
                $table->double('a2');
                $table->double('a3');
                $table->double('a4');

                $table->nullableTimestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mineral_data');
    }
}
