<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('manuals')) {
            Schema::create('manuals', function (Blueprint $table) {
                $table->increments('id');
                $table->string('tittle');
                $table->text('body');

                $table->integer('tag_manuals_id')->unsigned();
                $table->foreign('tag_manuals_id')->references('id')->on('tag_manuals');

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
        Schema::drop('manuals');
    }
}
