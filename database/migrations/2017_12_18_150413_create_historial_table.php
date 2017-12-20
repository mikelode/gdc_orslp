<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistorialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramHistorial', function(Blueprint $table){

            $table->engine = 'InnoDB';

            $table->increments('thisId');
            $table->string('thisExp', 10);
            
            $table->integer('thisDoc')->unsigned();
            $table->foreign('thisDoc')
                    ->references('tdocId')
                    ->on('tramDocumento')
                    ->onDelete('cascade');

            $table->string('thisDoc1', 10)->nullable();
            $table->string('thisDepS', 12);
            $table->string('thisDepT', 12);
            $table->boolean('thisFlagR');
            $table->boolean('thisFlagA');
            $table->boolean('thisFlagD');
            $table->date('rec_date_at');
            $table->time('rec_time-at');
            $table->datetime('thisDateTimeR')->nullable();
            $table->datetime('thisDateTimeA')->nullable();
            $table->datetime('thisDateTimeD')->nullable();
            $table->string('thisDscD', 1000)->nullable();
            $table->string('thisDscA', 1000)->nullable();
            $table->integer('thisIdRef')->unsigned()->nullable();
            $table->string('thisDocD', 50)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tramHistorial');
    }
}
