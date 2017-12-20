<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramUsuario', function (Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->string('tusId', 8)->primary();
            $table->string('tusNickName', 20);
            $table->string('password', 60);
            $table->string('tusNames',100);
            $table->string('tusPaterno',100);
            $table->string('tusMaterno',100);
            $table->string('tusWorkDep',12);
            $table->string('tusTypeUser',50);
            $table->string('tusRegisterBy',50)->nullable();
            $table->dateTime('tusRegisterAt')->nullable();
            $table->boolean('tusState')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tramUsuario');
    }
}
