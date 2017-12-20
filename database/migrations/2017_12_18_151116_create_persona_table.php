<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramPersona', function(Blueprint $table){

            $table->engine = 'InnoDB';

            $table->increments('tprId');
            $table->string('tprDni', 8)->nullable();
            $table->string('tprFulName', 500)->nullable();
            $table->string('tprPaterno', 50)->nullable();
            $table->string('tprMaterno', 50)->nullable();
            $table->string('tprNombres', 150)->nullable();
            $table->string('tprEntidad', 50)->nullable();
            $table->string('tprCargo', 50)->nullable();
            $table->string('tprCelular', 15)->nullable();
            $table->string('tprCorreo', 50)->nullable();
            $table->string('tprRegisterBy', 50)->nullable();
            $table->datetime('tprRegisterAt')->nullable();
            $table->datetime('tprUpdatedAt')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tramPersona');
    }
}
