<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinePercentagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('fine_percentages')) {
            Schema::create('fine_percentages', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('formation_mineralogy_id')->unsigned();
                $table->foreign('formation_mineralogy_id')->references('id')->on('formation_mineralogies');

                $table->string('finestraimentselection');
                $table->double('percentage');

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
        Schema::drop('fine_percentages');
    }
}
