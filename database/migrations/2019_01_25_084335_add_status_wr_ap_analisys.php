<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusWrApAnalisys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asphaltenes_d_precipitated_analysis', function($table) {
            $table->float('status_wr')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asphaltenes_d_precipitated_analysis', function($table) {
            $table->dropColumn('status_wr');
        });
    }
}