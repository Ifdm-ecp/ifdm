<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTablaAsphalteneTreatment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('asphaltene_treatment')) {
            Schema::create('asphaltene_treatment', function(Blueprint $table) {

                $table->increments('id');
                $table->text('name');
                $table->text('dilution_capacity');
                $table->text('components')->nullable();

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
        Schema::dropIfExists('asphaltene_treatment');
    }
}
