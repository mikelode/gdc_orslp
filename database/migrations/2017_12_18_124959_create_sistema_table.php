<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSistemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramSistema', function(Blueprint $table){

            $table->engine = 'InnoDB';

            $table->increments('tsysId');
            $table->string('tsysModulo',50)->nullable();
            $table->string('tsysFunction',50);
            $table->string('tsysDescF',250);
            $table->string('tsysVarHandler',50);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tramSistema');
    }
}
