<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramDocumento', function(Blueprint $table){

            $table->engine = 'InnoDB';

            $table->increments('tdocId');
            $table->string('tdocCod', 10);
            
            $table->integer('tdocExp')->unsigned();
            $table->foreign('tdocExp')
                    ->references('tarcId')
                    ->on('tramArchivador')
                    ->onDelete('cascade');

            $table->string('tdocExp1', 10)->nullable();
            $table->string('tdocDependencia', 100)->nullable();
            
            $table->integer('tdocProject')->unsigned();
            $table->foreign('tdocProject')
                    ->references('tpyId')
                    ->on('tramProyecto');

            $table->string('tdocSender', 500)->nullable();
            $table->string('tdocSenderName', 100)->nullable();
            $table->string('tdocSenderPaterno', 100)->nullable();
            $table->string('tdocSenderMaterno', 100)->nullable();
            $table->integer('tdocDni')->nullable();
            $table->string('tdocJobSender', 100)->nullable();
            
            $table->string('tdocType', 5);
            $table->foreign('tdocType')
                    ->references('ttypDoc')
                    ->on('tramTipoDocumento');

            $table->string('tdocNumber', 200)->nullable();
            $table->string('tdocRegistro', 10)->nullable();
            $table->date('tdocDate');
            $table->integer('tdocFolio')->nullable();
            $table->string('tdocSubject', 250);
            $table->string('tdocStatus', 15)->nullable();
            $table->string('tdocRef', 250)->nullable();
            $table->string('tdocDetail', 500)->nullable();
            $table->string('tdocAccion', 50)->nullable();
            $table->string('tdocFileName', 250)->nullable();
            $table->string('tdocFileExt', 50)->nullable();
            $table->string('tdocPathFile', 500)->nullable();
            $table->string('tdocFileMime', 150)->nullable();
            $table->string('tdocRegisterBy', 8);
            $table->datetime('tdocRegisterAt')->nullable();
            $table->datetime('tdocUpdateAt')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tramDocumento');
    }
}
