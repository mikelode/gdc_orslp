<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramRoles', function(Blueprint $table){

            $table->engine = 'InnoDB';

            $table->increments('trolId');

            $table->string('trolIdUser',8);
            $table->foreign('trolIdUser')
                    ->references('tusId')
                    ->on('tramUsuario')
                    ->onDelete('cascade');

            $table->integer('trolIdSyst')->unsigned();
            $table->foreign('trolIdSyst')
                    ->references('tsysId')
                    ->on('tramSistema')
                    ->onDelete('cascade');


            $table->boolean('trolEnable');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tramRoles');
    }
}
