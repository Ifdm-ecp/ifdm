<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFinesRemediation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('fines_remediation')) {
            Schema::create('fines_remediation', function(Blueprint $table) {

                $table->increments('id');
                
                $table->integer('id_scenary')->unsigned();
                $table->foreign('id_scenary')->references('id')->on('escenarios');

                $table->double('initial_porosity');
                $table->double('initial_permeability');
                $table->double('temperature');
                $table->double('well_radius');
                $table->double('damage_radius');
                $table->double('net_pay');
                $table->double('rock_compressibility');
                $table->double('pressure');
                $table->text('excel_damage_diagnosis_input');
                $table->double('acid_concentration');
                $table->double('injection_rate');
                $table->double('invasion_radius');
                $table->text('excel_rock_composition_input');
                $table->text('check_minerals_input');

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
        Schema::dropIfExists('fines_remediation');
    }
}
