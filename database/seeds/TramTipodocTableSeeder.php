<?php

use Illuminate\Database\Seeder;
use aidocs\Models\TipoDocumento;

class TramTipodocTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipos = [
        	['ttypDoc' => 'CAR', 'ttypDesc' => 'Carta', 'ttypShow' => true],
        	['ttypDoc' => 'INF', 'ttypDesc' => 'Informe', 'ttypShow' => true],
        	['ttypDoc' => 'MEM', 'ttypDesc' => 'Memorandum', 'ttypShow' => true],
        	['ttypDoc' => 'MMM', 'ttypDesc' => 'Memorandum múltiple', 'ttypShow' => true],
        	['ttypDoc' => 'OFI', 'ttypDesc' => 'Oficio', 'ttypShow' => true],
        	['ttypDoc' => 'OFC', 'ttypDesc' => 'Oficio circular', 'ttypShow' => true],
        	['ttypDoc' => 'PLT', 'ttypDesc' => 'Plan de trabajo', 'ttypShow' => true],
        	['ttypDoc' => 'EXT', 'ttypDesc' => 'Expediente', 'ttypShow' => true],
        	['ttypDoc' => 'SOL', 'ttypDesc' => 'Solicitud', 'ttypShow' => true],
        ];

        foreach ($tipos as $key => $t) {
        	TipoDocumento::insert($t);
        }
    }
}
