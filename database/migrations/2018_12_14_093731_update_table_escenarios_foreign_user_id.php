<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableEscenariosForeignUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::table('escenarios', function (Blueprint $table) {
            $table->dropForeign('escenarios_ibfk_6');
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->change();
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
