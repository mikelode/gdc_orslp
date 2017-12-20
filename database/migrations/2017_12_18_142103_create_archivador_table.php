<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchivadorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramArchivador', function(Blueprint $table){

            $table->engine = 'InnoDB';

            $table->increments('tarcId');
            $table->string('tarcExp', 10);
            $table->date('tarcDatePres');
            $table->string('tarcStatus', 15);
            $table->date('created_at');
            $table->time('created_time_at');
            $table->date('updated_at')->nullable();
            $table->string('tarcSource', 3);
            $table->string('tarcPathFile', 500)->nullable();
            $table->integer('tarcYear')->unsigned()->nullable();
            $table->integer('tarcAsoc')->unsigned()->nullable();
            $table->string('tarcTitulo', 100)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tramArchivador'); 
    }
}
