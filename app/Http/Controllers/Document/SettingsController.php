<?php

namespace aidocs\Http\Controllers\Document;

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
use aidocs\Models\Asociacion;
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
            $user->tusRegisterAt = Carbon::now()->format('d/m/Y h:i:s A');
            $user->tusState = true;

            $user->save();

            switch($request->profile_user)
            {
                case 'user1': /* assistant */
                    for($i=1; $i<=7; $i++)
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
                case 'user2': /* boss */
                    for($i=1; $i<=7; $i++)
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
                case 'admin': /* administrator system */
                    for($i=1; $i<=7; $i++)
                    {
                        $rol = new Rol();
                        $rol->trolIdUser = $request->dni_user;
                        $rol->trolIdSyst = $i;

                        if($i>=7)
                            $rol->trolEnable = false;
                        else
                            $rol->trolEnable = true;

                        $rol->save();
                        unset($rol);
                    }
                    break;
                case 'super': /* VIP user */
                    for($i=1; $i<=7; $i++)
                    {
                        $rol = new Rol();
                        $rol->trolIdUser = $request->dni_user;
                        $rol->trolIdSyst = $i;
                        $rol->trolEnable = true;
                        $rol->save();
                        unset($rol);
                    }
                    break;
            }
        });

        if($request->ajax())
        {
            return "Usuario ".$request->dni_user." creado con éxito.";
        }

        return false;

    }

    public function getListUsers(Request $request)
    {
        $list_users = DB::select("SELECT  u.tusId,u.tusNames,u.tusPaterno,u.tusMaterno,u.tusWorkDep,
                                  dbo.fnTramGetDscFromId('TLogGrlDep',u.tusWorkDep) AS Dependencia,
                                  u.tusTypeUser, u.tusState FROM tramUsuario u;");

        $dependencies = Dependencia::select('*')->get();

        $view = view('setting.list_users',compact('list_users'),compact('dependencies'));

        if($request->ajax())
        {
            $sections = $view->renderSections();
            return $sections['sub-content'];
        }

        return $view;
    }

    public function getListAsoc(Request $request)
    {
        $list_asoc = Asociacion::all();
        $view = view('setting.list_asociaciones', compact('list_asoc'));

        if($request->ajax())
        {
            $sections = $view->renderSections();
            return $sections['sub-content'];
        }

        return $view;
    }

    public function postRegisterAsociacion(Request $request)
    {
        DB::transaction(function($request) use ($request){

            $asoc = new Asociacion();

            $asoc->tasAnio = $request->asocAnio;
            $asoc->tasCutElig = $request->asocCutelig;
            $asoc->tasCutTec = $request->asocCuttec;
            $asoc->tasConvenio = $request->asocConv;
            $asoc->tasOrganizacion = $request->asocOrg;
            $asoc->tasNegocio = $request->asocNeg;
            $asoc->tasRuc = $request->asocRuc;
            $asoc->tasCadena = $request->asocCad;
            $asoc->tasDireccion = $request->asocDir;
            $asoc->tasProv = $request->asocProv;
            $asoc->tasDist = $request->asocDist;
            $asoc->tasPresidente = $request->asocPresi;
            $asoc->tasCoordinador = $request->asocCoord;
            $asoc->tasVigenciaIni = $request->asocVigini;
            $asoc->tasVigenciaFin = $request->asocVigfin;

            $asoc->save();

        });

        if($request->ajax())
        {
            return "Asociacion registrada con éxito. Porfavor refresca la página con F5";
        }

        return false;
    }

    public function showProfileUser($idUser, Request $request)
    {
        $profile = DB::select("SELECT  r.trolId,r.trolIdUser,r.trolIdSyst, s.tsysDescF, r.trolEnable
                      FROM tramRoles r
                      INNER JOIN tramSistema s ON r.trolIdSyst = s.tsysId
                      WHERE r.trolIdUser = '".$idUser."';");

        if($request->ajax())
        {
            return $profile;
        }

        return false;
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
}
