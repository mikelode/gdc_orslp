<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TramUsuarioTableSeeder extends Seeder {

    public function run()
    {
        $faker = Faker::create();

        for($i = 0; $i < 30; $i++)
        {
            \DB::table('tramUsuario')->insert(array(
                'tusId' => $faker->unique()->ean8,
                'tusNickName' => $faker->userName,
                'tusPassword' => \Hash::make('123456'),
                'tusNames' => $faker->firstName,
                'tusPaterno' => $faker->lastName,
                'tusMaterno' => $faker->lastName,
                'tusWorkDep' => $faker->randomDigitNotNull,
                'tusTypeUser'=>'user'
            ));
        }
    }
} 