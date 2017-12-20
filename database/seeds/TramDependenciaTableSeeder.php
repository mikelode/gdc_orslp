<?php

use Illuminate\Database\Seeder;

class TramDependenciaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('tramDependencia')->insert(array(
            'depCod' => 'DEP00001',
            'depDsc' => 'OFICINA REGIONAL DE SUPERVISIÓN Y LIQUIDACIÓN DE PROYECTOS',
            'depDscC' => 'Oficina de Supervisión',
            'depActive' => true,
        ));
    }
}
