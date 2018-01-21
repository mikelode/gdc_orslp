<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnHistorialArchivado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tramHistorial', function(Blueprint $table){
            
            $table->boolean('thisFlagF')->after('thisFlagD')->default(0)->nullable();
            $table->datetime('thisDateTimeF')->after('thisDateTimeD')->nullable();
            $table->string('thisDscF', 1000)->after('thisDscA')->nullable();

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
            
            //$table->dropColumn('thisFlagF');
            //$table->dropColumn('thisDateTimeF');
            $table->dropColumn('thisDscF');

        });
    }
}
