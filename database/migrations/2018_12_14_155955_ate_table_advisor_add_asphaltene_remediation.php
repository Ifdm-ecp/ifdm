<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AteTableAdvisorAddAsphalteneRemediation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::table('advisor', function (Blueprint $table) {
            $table->text('asphaltene_remediation')->nullable()->after('asphaltene_stability_analysis');
        });
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
