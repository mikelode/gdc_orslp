<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContadorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cod_cont', function(Blueprint $table){

            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('last_doc', 10);
            $table->string('last_exp', 10);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cod_cont');
    }
}
