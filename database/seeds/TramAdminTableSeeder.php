<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TramAdminTableSeeder extends Seeder {

    public function run()
    {
        $faker = Faker::create();

        \DB::table('tramUsuario')->insert(array(
            'tusId' => '00000000',
            'tusNickName' => 'admin',
            'password' => \Hash::make('supervision'),
            'tusNames' => 'Usuario',
            'tusPaterno' => 'Administrador',
            'tusMaterno' => 'Sistema',
            'tusWorkDep' => '1',
            'tusTypeUser'=> 'admin',
            'tusRegisterBy'=> 'admin',
            'tusRegisterAt'=> \Carbon\Carbon::now()->format('d/m/Y h:i:s A'),
            'tusState'=> true,
        ));
    }
} 