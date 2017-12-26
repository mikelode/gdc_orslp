<?php

namespace aidocs\Http\Controllers\Document;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\DocBlock\Tag\ReturnTag;
use aidocs\Http\Requests;
use aidocs\Http\Controllers\Controller;
use aidocs\Http\Requests\CreateUserRequest;
use aidocs\Models\Dependencia;
use aidocs\Models\Rol;
use aidocs\Models\TipoDocumento;
use aidocs\Models\Proyecto;
use aidocs\Models\Cargo;
use aidocs\Models\Persona;
use aidocs\Models\Afiliado;
use aidocs\Models\Sistema;
use aidocs\User;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $view = view('tramite.setting_documentary');

        if($request->ajax())
        {
            $sections = $view->renderSections();
            return $sections['main-content'];
        }

        return $view;
    }

    public function getRegisterUser(Request $request)
    {
        $dependencys = Dependencia::select('*')->get();
        $view = view('setting.register_user',compact('dependencys'));

        if($request->ajax())
        {
            $sections = $view->renderSections();
            return $sections['sub-content'];
        }

        return $view;
    }

    public function  postRegisterUser(CreateUserRequest $request)
    {
        DB::transaction(function($request) use ($request){

            $user = new User();

            $user->tusId = $request->dni_user;
            $user->tusNickName = $request->dni_user;
            $user->password = \Hash::make($request->dni_user);
            $user->tusNames = strtoupper($request->name_user);
            $user->tusPaterno = strtoupper($request->patern_user);
            $user->tusMaterno = strtoupper($request->matern_user);
            $user->tusWorkDep = $request->dependency_user;
            $user->tusTypeUser = $request->profile_user;
            $user->tusRegisterBy = Auth::user()->tusId;
            $user->tusRegisterAt = Carbon::now();//->format('d/m/Y h:i:s A');
            $user->tusState = true;

            $user->save();
            /*
            switch($request->profile_user)
            {
                case 'user1': /* assistant 
                    for($i=1; $i<=8; $i++)
                    {
                        $rol = new Rol();
                        $rol->trolIdUser = $request->dni_user;
                        $rol->trolIdSyst = $i;

                        if($i>=5)
                            $rol->trolEnable = false;
                        else
                            $rol->trolEnable = true;

                        $rol->save();
                        unset($rol);
                    }
                    break;
                case 'user2': /* boss 
                    for($i=1; $i<=8; $i++)
                    {
                        $rol = new Rol();
                        $rol->trolIdUser = $request->dni_user;
                        $rol->trolIdSyst = $i;

                        if($i>=6)
                            $rol->trolEnable = false;
                        else
                            $rol->trolEnable = true;

                        $rol->save();
                        unset($rol);
                    }
                    break;
                case 'admin': /* administrator system 
                    for($i=1; $i<=8; $i++)
                    {
                        $rol = new Rol();
                        $rol->trolIdUser = $request->dni_user;
                        $rol->trolIdSyst = $i;

                        if($i>=8)
                            $rol->trolEnable = false;
                        else
                            $rol->trolEnable = true;

                        $rol->save();
                        unset($rol);
                    }
                    break;
                case 'super': /* VIP user 
                    for($i=1; $i<=8; $i++)
                    {
                        $rol = new Rol();
                        $rol->trolIdUser = $request->dni_user;
                        $rol->trolIdSyst = $i;
                        $rol->trolEnable = true;
                        $rol->save();
                        unset($rol);
                    }
                    break;
            }*/
        });

        if($request->ajax())
        {
            return "Usuario ".$request->dni_user." creado con éxito.";
        }

        return false;

    }

    public function getListUsers(Request $request)
    {
        /* SQL Version
        $list_users = DB::select("SELECT  u.tusId,u.tusNames,u.tusPaterno,u.tusMaterno,u.tusWorkDep,
                                  dbo.fnTramGetDscFromId('tramDependencia',u.tusWorkDep) AS Dependencia,
                                  u.tusTypeUser, u.tusState FROM tramUsuario u;");*/
        /* MySQL Version */
        $list_users = DB::select("SELECT u.tusId, u.tusNames, u.tusPaterno, u.tusMaterno, u.tusWorkDep, fnTramGetDscFromId('tramDependencia',u.tusWorkDep) AS Dependencia, u.tusTypeUser, u.tusState FROM tramUsuario u;");


        $dependencies = Dependencia::select('*')->get();

        $view = view('setting.list_users',compact('list_users'),compact('dependencies'));

        if($request->ajax())
        {
            $sections = $view->renderSections();
            return $sections['sub-content'];
        }

        return $view;
    }

    public function getListProy(Request $request)
    {
        $list_proy = Proyecto::all();
        $view = view('setting.list_proyectos', compact('list_proy'));

        if($request->ajax())
        {
            $sections = $view->renderSections();
            return $sections['sub-content'];
        }

        return $view;
    }

    public function postRegisterProyecto(Request $request)
    {
        try{

            $exception = DB::transaction(function($request) use ($request, &$py){

                $asoc = new Proyecto();

                $asoc->tpyAnio = $request->npyAnio;
                $asoc->tpyName = $request->npyName;
                $asoc->tpyShortName = $request->npyShortName;
                $asoc->tpyCU = $request->npyCu;

                $asoc->save();

                $py = Proyecto::find($asoc->tpyId);

            });

            if(is_null($exception)){
                $proy = $py;
                $msg = "Proyecto registrado con éxito";
                $msgId = 200;
            }
            else{
                $proy = collect([]);
                $msg = "Hubo un error al registrar el proyecto, actualice la página y vuelva a intentarlo";
                $msgId = 500;
            }

        }catch(Exception $e){
            $proy = collect([]);
            $msg = "Error encontrado:".$e->getMessage();
            $msgId = 500;
        }

        return response()->json(compact('proy','msg','msgId'));
        
    }

    public function showProfileUser($idUser, Request $request)
    {
        /*$profile = DB::select("SELECT  r.trolId,r.trolIdUser,r.trolIdSyst, s.tsysDescF, r.trolEnable
                      FROM tramRoles r
                      INNER JOIN tramSistema s ON r.trolIdSyst = s.tsysId
                      WHERE r.trolIdUser = '".$idUser."';");*/

        $profile = Rol::select('*')
                    ->where('trolIdUser',$idUser)
                    ->where('trolEnable',true)
                    ->get();

        $funciones = Sistema::select('*')
                    ->orderby('tsysModulo','ASC')
                    ->get();

        $idFunciones = $funciones->pluck('tsysId');
        $idProfile = $profile->pluck('trolIdSyst');

        $view = view('setting.tabla_perfil_usuario', compact('idUser','idProfile','funciones','profile'));

        //$prof_func = array_intersect($idFunciones->toArray(), $idProfile->toArray());
        //dd($prof_func);

        return $view;
    }

    public function postUpdateProfile(Request $request)
    {
        DB::transaction(function($request) use ($request){

            $roles = Rol::where('trolIdUser',$request->kyUser)->get()->toArray();
            $roles = array_pluck($roles,'trolIdSyst');

            //dd(array_pluck($roles,'trolIdSyst'));
            //dd($request->stateF);

            $userDep = User::find($request->kyUser);
            $userDep->tusWorkDep = $request->work_dep;
            $userDep->save();

            foreach ($roles as $r)
            {
                if(in_array($r,$request->stateF))
                {
                    Rol::where('trolIdUser',$request->kyUser)->where('trolIdSyst',$r)->update(['trolEnable'=>true]);
                }
                else
                {
                    Rol::where('trolIdUser',$request->kyUser)->where('trolIdSyst',$r)->update(['trolEnable'=>false]);
                }
            }
        });

        if($request->ajax())
        {
            return 'Perfil Actualizado';
        }

        return false;
    }

    public function postUpdateStateUser(Request $request)
    {
        DB::transaction(function($request) use($request){

            $user = User::find($request->id);
            $user->tusState = $request->active;
            $user->save();

        });

        if($request->ajax())
        {
            if($request->active)
                return 'El usuario '.$request->id.' ha sido ACTIVADO';
            else
                return 'El usuario '.$request->id.' ha sido DESACTIVADO';
        }
        return false;
    }

    public function getUpdatePasswordUser(Request $request)
    {
        $view = view('setting.update_password');

        if($request->ajax())
        {
            $sections = $view->renderSections();
            return $sections['main-content'];
        }

        return $view;
    }

    public function postUpdatePasswordUser(Request $request)
    {
        DB::transaction(function($request) use($request){

            $dni = $request->idUser;

            $user = User::find($dni);
            $user->password = \Hash::make($request->rpassUser);
            $user->save();

        });

        if($request->ajax())
        {
            return 'Su contrseña ha sido actualizada';
        }

        return false;
    }

    public function getResetPasswordUser(Request $request)
    {
        DB::transaction(function($request) use($request){

            $dni = $request->idUser;

            $user = User::find($dni);
            $user->password = \Hash::make($dni);
            $user->save();

        });

        if($request->ajax())
        {
            return 'La nueva contraseña es el DNI del usuario';
        }

        return false;
    }

    public function getFormularioAfiliado(Request $request)
    {
        $cargo = Cargo::all();
        $asociacion = Asociacion::all();
        $view = view('setting.register_afiliado',compact('cargo','asociacion'));

        $sections = $view->renderSections();

        return $sections['sub-content'];
    }

    public function postRegisterPersona(Request $request) //postRegisterAfiliado
    {
        try{

            $exception = DB::transaction(function($request) use($request){

                $persona = new Persona();
                $persona->tprDni = $request->nprsDni;
                $persona->tprFulName = $request->nprsName.' '.$request->nprsPaterno.' '.$request->nprsMaterno;
                $persona->tprPaterno = $request->nprsPaterno;
                $persona->tprMaterno = $request->nprsMaterno;
                $persona->tprNombres = $request->nprsName;
                $persona->tprCelular = $request->nprsCel;
                $persona->tprCargo = $request->nprsJob;
                $persona->tprRegisterBy = Auth::user()->tudId;
                $persona->tprRegisterAt = Carbon::now();//->format('d/m/Y h:i:s A');
                $persona->save();
            });

            if(is_null($exception)){
                $msg = 'Afiliado registrado correctamente';
                $msgId = 200;
            }
            else{
                $msg = 'Error encontrado: '.$exception;
                $msgId = 500;
            }

        }catch(Exception $e){
            $msg = 'Error detectado: ' . $e->getMessage() . "\n";
            $msgId = 500;
        }

        return response()->json(compact('msg','msgId'));
    }

    public function getListPersonas(Request $request)
    {
        $persona = Persona::select('tprId','tprFulName')
                    ->where('tprFulName','like','%'.$request->search.'%')
                    ->get();

        $result = [];

        foreach ($persona as $key => $p) {
            $result[] = array('id'=>$p->tprId,'name'=>$p->tprFulName);
        }

        //$result['id'] = $persona->pluck('tprId');
        //$result['name'] = $persona->pluck('tprFulName');

        return response()->json($result);
    }

    public function postUpdateAccess(Request $request)
    {
        $user = $request->name;
        $funcion = $request->pk;
        $valor = $request->value; // A: asignado B: no asignado (quitar)

        //check if exist the user with this function

        $profile = Rol::select('*')
                    ->where('trolIdUser',$user)
                    ->where('trolIdSyst',$funcion)
                    ->get();

        if($profile->isEmpty()){
            $addProfile = new Rol();
            $addProfile->trolIdUser = $user;
            $addProfile->trolIdSyst = $funcion;
            $addProfile->trolEnable = true;
            $addProfile->save();
        }
        else{
            $editProfile = Rol::find($profile[0]->trolId);
            $editProfile->trolEnable = $valor=='A' ? true : false;
            $editProfile->save();
        }

        $success = true;
        $msg = 'Estado cambiado correctamente';

        return response()->json(compact('success','msg'));
    }

    public function postUpdateProyecto(Request $request)
    {
        $campo = $request->name;
        $pyId = $request->pk;
        $newVal = $request->value;

        $proy = Proyecto::find($pyId);

        if($campo == 'name'){
            $proy->tpyName = $newVal; 
        }

        if($campo == 'shortname'){
            $proy->tpyShortName = $newVal; 
        }

        if($campo == 'anio'){
            $proy->tpyShortName = $newVal; 
        }

        if($campo == 'coduni'){
            $proy->tpyShortName = $newVal; 
        }

        if($proy->save()){
            $success = true;
            $msg = 'Estado cambiado correctamente';
        }

        return response()->json(compact('success','msg'));
    }

    public function getListDependencias(Request $request)
    {
        $list_depen = Dependencia::all();
        $view = view('setting.list_dependencias', compact('list_depen'));

        if($request->ajax())
        {
            $sections = $view->renderSections();
            return $sections['sub-content'];
        }

        return $view;
    }

    public function postRegisterDependencia(Request $request)
    {
        try{

            $exception = DB::transaction(function($request) use ($request, &$dep){
                
                $dep = new Dependencia();
                $dep->depDsc = $request->ndpName;
                $dep->depDscC = $request->ndpShortName;
                $dep->save();

                if(!$dep) throw new Exception("Algun dato mal ingresado no permitio el registro");

                $dep = Dependencia::find($dep->depId);

            });

            if(is_null($exception)){
                $newDep = $dep;
                $msg = "Dependencia registrada con éxito";
                $msgId = 200;
            }
            else{
                $newDep = collect([]);
                $msg = "Error al intentar registrar la dependencia revise sus datos";
                $msgId = 500;
            }

        }catch(Exception $e){
            $newDep = collect([]);
            $msg = "Error al intentar registrar la dependencia ".$e->getMessage();
            $msgId = 500;
        }

        return response()->json(compact('newDep','msg','msgId'));
        
    }

    public function postUpdateDependencia(Request $request)
    {
        $campo = $request->name;
        $depId = $request->pk;
        $newVal = $request->value;

        $depen = Dependencia::find($depId);

        if($campo == 'name'){
            $depen->depDsc = $newVal; 
        }

        if($campo == 'shortname'){
            $depen->depDscC = $newVal; 
        }

        if($depen->save()){
            $success = true;
            $msg = 'Estado cambiado correctamente';
        }

        return response()->json(compact('success','msg'));
    }
}
