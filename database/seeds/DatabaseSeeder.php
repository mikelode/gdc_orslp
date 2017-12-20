<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        /*$this->call('AdminTableSeeder');
        $this->call('UserTableSeeder');*/
        $this->call('TramContadorCodigoTableSeeder');
        $this->call('TramDependenciaTableSeeder');
        $this->call('TramAdminTableSeeder');
        $this->call('TramSistemaTableSeeder'); // se migran los datos del sistema y del usuario administrador y su perfil
        $this->call('TramTipodocTableSeeder');

        Model::reguard();
    }
}
