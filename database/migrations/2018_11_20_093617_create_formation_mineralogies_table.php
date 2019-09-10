<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormationMineralogiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('formation_mineralogies')) {
            Schema::create('formation_mineralogies', function (Blueprint $table) {
                
                $table->increments('id');
                $table->integer('formacion_id')->unsigned();
                $table->foreign('formacion_id')->references('id')->on('formaciones');

                $table->double('quarts')->default(0);
                $table->double('feldspar')->default(0);
                $table->double('clays')->default(0);

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
        Schema::drop('formation_mineralogies');
    }
}
