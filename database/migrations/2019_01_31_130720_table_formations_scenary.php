<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableFormationsScenary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('formations_scenary')) {
            Schema::create('formations_scenary', function ($table) {
                $table->increments('id');

                $table->integer('id_scenary')->unsigned();
                $table->foreign('id_scenary')->references('id')->on('escenario')->onDelete('cascade');
                
                $table->integer('id_formation');

                $table->float('stress_sensitive_reservoir')->nullable();
                $table->double('initial_reservoir_pressure')->nullable();
                $table->double('absolute_permeability_it_initial_reservoir_pressure')->nullable();
                $table->double('net_pay')->nullable();
                $table->double('current_reservoir_pressure')->nullable();
                $table->double('permeability_module')->nullable();
                $table->double('reservoir_parting_pressure')->nullable();
                $table->double('absolute_permeability')->nullable();

                $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
        Schema::drop('formations_scenary');
    }
}
