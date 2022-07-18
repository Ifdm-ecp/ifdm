<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablaAsphalteneRemediation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('asphaltene_remediation')) {
            Schema::create('asphaltene_remediation', function(Blueprint $table) {

                $table->increments('id');
                
                $table->integer('id_scenary')->unsigned();
                $table->foreign('id_scenary')->references('id')->on('escenarios');

                $table->integer('type_asphaltene');

                $table->double('initial_porosity');
                $table->double('net_pay');
                $table->double('water_saturation');
                $table->double('initial_permeability');
                $table->double('current_permeability')->nullable();
                

                $table->double('skin_characterization_scale')->nullable();
                $table->double('skin_characterization_induced')->nullable();
                $table->double('skin_characterization_asphaltene')->nullable();
                $table->double('skin_characterization_fines')->nullable();
                $table->double('skin_characterization_non_darcy')->nullable();
                $table->double('skin_characterization_geomechanical')->nullable();

                // Se cambia por un id 
                $table->integer('option_treatment')->unsigned();
                $table->double('asphaltene_apparent_density');

                //En caso de estar activo el campo stimate_ior_input 
                $table->text('stimate_ior');
                $table->double('monthly_decline_rate')->nullable();
                $table->double('current_oil_production')->nullable();

                //Campo fecha en la sección Treatment Reward 
                $table->date('data_input')->nullable();

                //Condiciones 
                $table->text('chemical_treatment_impl');

                //Tabla principal 
                $table->text('excel_changes_along_the_radius')->nullable();

                //Select con opciones 
                $table->double('asphaltene_dilution_capacity')->nullable();

                $table->double('treatment_radius');
                $table->double('wellbore_radius');
                $table->double('asphaltene_remotion_efficiency_range_a');
                $table->double('asphaltene_remotion_efficiency_range_b');

                $table->timestamps();

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
        Schema::dropIfExists('asphaltene_remediation');
    }
}
