<?php

use Illuminate\Database\Seeder;

class TramContadorCodigoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('cod_cont')->insert(array(
            'last_doc' => 'DOC1700000',
            'last_exp' => 'EXP1700000',
        ));
    }
}
