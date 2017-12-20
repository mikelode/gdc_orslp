<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipodocumentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramTipoDocumento', function(Blueprint $table){
            
            $table->engine = 'InnoDB';

            $table->string('ttypDoc',5)->primary();
            $table->string('ttypDesc',100);
            $table->date('created_at')->nullable();
            $table->date('updated_at')->nullable();
            $table->boolean('ttypShow')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tramTipoDocumento');
    }
}
