<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePvtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('pvt')) {
            Schema::create('pvt', function(Blueprint $table) {
                $table->increments('id');
                $table->double('pressure');
                $table->double('uo');
                $table->double('ug');
                $table->double('uw');
                $table->double('bo');
                $table->double('bg');
                $table->double('bw');
                $table->double('rs');
                $table->double('rv');

                $table->integer('formacion_id')->unsigned();            
                $table->foreign('formacion_id')->references('id')->on('formaciones');

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
        Schema::dropIfExists('pvt');
    }
}
