<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TramAdminTableSeeder extends Seeder {

    public function run()
    {
        $faker = Faker::create();

        \DB::table('tramUsuario')->insert(array(
            'tusId' => '44768268',
            'tusNickName' => 'ymiguel',
            'password' => \Hash::make('123456'),
            'tusNames' => 'Miguel',
            'tusPaterno' => 'Velasquez',
            'tusMaterno' => 'Alanoca',
            'tusWorkDep' => 'DP001',
            'tusTypeUser'=> 'admin',
            'tusRegisterBy'=> 'admin',
            'tusRegisterAt'=> \Carbon\Carbon::now()->format('d/m/Y h:i:s A'),
            'tusState'=> true,
        ));
    }
} 