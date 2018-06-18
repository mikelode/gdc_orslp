<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUpdatedbyDocumento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tramDocumento', function(Blueprint $table){

            $table->string('tdocUpdatedBy')->nullable()->after('tdocRegisterAt');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tramHistorial', function(Blueprint $table){

            $table->dropColumn('tdocUpdatedBy');

        });
    }
}
