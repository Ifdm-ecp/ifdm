<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableIprMultiResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('ipr_intervalo_results')) {
            Schema::create('ipr_intervalo_results', function ($table) {
                $table->increments('id');

                $table->integer('id_scenary')->unsigned();
                $table->foreign('id_scenary')->references('id')->on('escenario')->onDelete('cascade');

                $table->integer('id_formations_scenary')->unsigned();
                $table->foreign('id_formations_scenary')->references('id')->on('formations_scenary')->onDelete('cascade');

                $table->double('skin')->nullable();
                $table->text('ipr_skin')->nullable();
                $table->text('ipr_skin_cero')->nullable();

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
        if (Schema::hasTable('ipr_intervalo_results')) {
            Schema::drop('ipr_intervalo_results');
        }
    }
}
