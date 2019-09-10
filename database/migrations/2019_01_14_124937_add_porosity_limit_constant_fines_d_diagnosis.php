<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPorosityLimitConstantFinesDDiagnosis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fines_d_diagnosis', function($table) {
            $table->double('porosity_limit_constant')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fines_d_diagnosis', function($table) {
            $table->dropColumn('porosity_limit_constant');
        });
    }
}
