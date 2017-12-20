<?php

use Illuminate\Database\Seeder;
use aidocs\Models\Sistema;

class TramSistemaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	$funciones = [
    		['tsysId' => 1, 'tsysModulo' => 'gestion', 'tsysFunction' => 'registrar', 'tsysDescF' => 'Registrar Documento', 'tsysVarHandler' => 'gregistrar'],
    		['tsysId' => 2, 'tsysModulo' => 'gestion', 'tsysFunction' => 'editar', 'tsysDescF' => 'Editar Documento', 'tsysVarHandler' => 'geditar'],
    		['tsysId' => 3, 'tsysModulo' => 'gestion', 'tsysFunction' => 'eliminar', 'tsysDescF' => 'Eliminar Documento', 'tsysVarHandler' => 'geliminar'],
    		['tsysId' => 4, 'tsysModulo' => 'gestion', 'tsysFunction' => 'derivar', 'tsysDescF' => 'Derivar Documento', 'tsysVarHandler' => 'gderivar'],
    		['tsysId' => 5, 'tsysModulo' => 'gestion', 'tsysFunction' => 'menu', 'tsysDescF' => 'Menu de Gestión Documentaria', 'tsysVarHandler' => 'gmenu'],
    		['tsysId' => 6, 'tsysModulo' => 'bandeja', 'tsysFunction' => 'busqueda', 'tsysDescF' => 'Busqueda de Documentos', 'tsysVarHandler' => 'bbusqueda'],
    		['tsysId' => 7, 'tsysModulo' => 'bandeja', 'tsysFunction' => 'menu', 'tsysDescF' => 'Menu de Bandeja de Documentos', 'tsysVarHandler' => 'bmenu'],
    		['tsysId' => 9, 'tsysModulo' => 'reporte', 'tsysFunction' => 'menu', 'tsysDescF' => 'Menu de Resportes', 'tsysVarHandler' => 'rmenu'],
    		['tsysId' => 10, 'tsysModulo' => 'reporte', 'tsysFunction' => 'ver', 'tsysDescF' => 'Visualizar Reportes', 'tsysVarHandler' => 'rver'],
    		['tsysId' => 11, 'tsysModulo' => 'reporte', 'tsysFunction' => 'pdf', 'tsysDescF' => 'Visualizar Reportes en PDF', 'tsysVarHandler' => 'rpdf'],
    		['tsysId' => 12, 'tsysModulo' => 'configuracion', 'tsysFunction' => 'menu', 'tsysDescF' => 'Menu de Configuración del Sistema', 'tsysVarHandler' => 'cmenu'],
    	];

    	foreach ($funciones as $key => $fun) {
    		$id = \DB::table('tramSistema')->insertGetId($fun);

            DB::table('tramRoles')->insert(array(
                'trolIdUser' => '00000000', 
                'trolIdSyst' => $id, 
                'trolEnable' => true
            ));
    	}

    }
}
