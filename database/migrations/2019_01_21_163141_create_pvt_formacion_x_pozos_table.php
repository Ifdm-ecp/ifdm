<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePvtFormacionXPozosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('pvt_formacion_x_pozos')) {
            Schema::create('pvt_formacion_x_pozos', function (Blueprint $table) {
                $table->increments('id');

                $table->integer('formacionxpozos_id')->unsigned();
                $table->foreign('formacionxpozos_id')->references('id')->on('formacionxpozos')->onDelete('cascade');

                $table->string('pressure');
                $table->string('uo');
                $table->string('ug');
                $table->string('uw');
                $table->string('bo');
                $table->string('bg');
                $table->string('bw');
                $table->string('rs');
                $table->string('rv');
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
        Schema::drop('pvt_formacion_x_pozos');
    }
}
