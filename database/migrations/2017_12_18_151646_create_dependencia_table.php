<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDependenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramDependencia', function(Blueprint $table){

            $table->engine = 'InnoDB';

            $table->increments('depId');
            $table->string('depCod', 12)->nullable();
            $table->string('depDsc', 1000)->nullable();
            $table->string('depDscC', 1000)->nullable();
            $table->boolean('depActive')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tramDependencia');
    }
}
