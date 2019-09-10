<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePvtGlobalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('pvt_globals')) {
            Schema::create('pvt_globals', function (Blueprint $table) {
                $table->increments('id');

                $table->integer('formacion_id')->unsigned();
                $table->foreign('formacion_id')->references('id')->on('formaciones')->onDelete('cascade');

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
        Schema::drop('pvt_globals');
    }
}
