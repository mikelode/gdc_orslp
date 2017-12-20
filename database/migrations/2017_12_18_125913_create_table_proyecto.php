<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProyecto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramProyecto', function(Blueprint $table){

            $table->engine = 'InnoDB';

            $table->increments('tpyId');
            $table->integer('tpyNro')->nullable()->unsigned();
            $table->integer('tpyAnio')->nullable()->unsigned();
            $table->string('tpyName',500)->nullable();
            $table->string('tpyShortName',500)->nullable();
            $table->string('tpyCU',12)->nullable();
            $table->string('tpyCadena',50)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tramProyecto');
    }
}
