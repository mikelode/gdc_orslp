<?php
/**
 * Created by PhpStorm.
 * User: HP i5
 * Date: 12/07/15
 * Time: 11:43
 */

namespace aidocs\Http\Controllers\Document;

use Exception;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use aidocs\Http\Controllers\Controller;
use aidocs\Http\Requests\DeriveDocumentRequest;
use aidocs\Models\Anexo;
use aidocs\Models\Archivador;
use aidocs\Models\Arcparticular;
use aidocs\Models\Dependencia;
use aidocs\Models\Document;
use aidocs\Models\Historial;
use aidocs\Models\Proyecto;
use Illuminate\Support\Facades\DB;
use aidocs\Models\TipoDocumento;

class HistorialController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getHistorialDoc(Request $request) // para mostrar la bandeja de entrada
    {
        /*$inbox = Historial::select('*')
            ->join('tramDocumento','tdocCod','=','thisDoc1')
            ->join('tramArchivador','tarcExp','=','tdocExp1')
            //->join('tramDependencia','thisDepS','=','depId')
            //->where('thisDepT',Auth::user()->tusWorkDep)
            //->where('thisDepS','<>',Auth::user()->tusWorkDep)
            ->where('tarcYear',$request->period)
            ->orderby('tdocId','DESC')
            ->get();*/

            // se elige tarcdatepres pues guarda la fecha de registro del documento origen y de reapertura para actualizar su vigencia

        /* SQL Version
        $inbox = Document::select(DB::raw('*,DATEDIFF(day,tarcDatePres,GETDATE()) as plazo'))
                    ->join('tramArchivador','tarcId','=','tdocExp')
                    ->join('tramProyecto','tpyId','=','tdocProject')
                    ->join('tramTipoDocumento','ttypDoc','=','tdocType')
                    ->where('tarcYear',$request->period)
                    ->where('tdocRef',null)
                    ->orderby('tdocId','DESC')
                    ->get();*/

        /* MySQL Version */
        $inbox = Document::select(DB::raw('*,fnTramDateDiff(tarcDatePres, NOW()) as plazo, fnDescDependencia(tdocDependencia,2) as dep'))
                    ->join('tramArchivador','tarcId','=','tdocExp')
                    ->join('tramProyecto','tpyId','=','tdocProject')
                    ->join('tramTipoDocumento','ttypDoc','=','tdocType')
                    ->where('tarcYear',Session::get('periodo'))
                    ->where('tdocRef',null)
                    ->orderby('tdocId','DESC')
                    ->get();

        $dependencys = Dependencia::select('*')
            ->where('depActive',1)
            ->get();

        $tipos = TipoDocumento::where('ttypShow',true)->get();
        $proyectos = Proyecto::all();

        $view = view('tramite.inbox_document',compact('inbox','proyectos'),['dependencys' => $dependencys, 'tipos' => $tipos]);

        if($request->ajax())
        {
            $sections = $view->renderSections();
            return $sections['main-content'];
        }

        return $view;
    }

    public function envioHistorial(Request $request)
    {
        try {
            $exception = DB::transaction(function($request) use ($request){

                $doc = Document::select('*')
                        ->join('tramHistorial','tdocId','=','thisDoc')
                        ->where('tdocId',$request->ndocEnvioExp)
                        ->where('thisFlagR',true)
                        ->get(); //ndocEnvioExp recibe el codigo del documento id que se va registrar su envio


                if($doc[0]->tdocAccion == 'atendido-salida'){ 

                    $hist = Historial::find($doc[0]->thisId);
                    $hist->thisDepT = $request->ndocEnvioDestino;
                    $hist->thisFlagD = true;
                    $hist->thisDateTimeD = $request->ndocFecEnvio; //Carbon::now();//->format('d/m/Y h:i:s A');
                    $hist->thisDscD = trim($request->ndocEnvioMensaje);
                    $hist->rec_date_at = Carbon::now()->toDateString();
                    $hist->rec_time_at = Carbon::now()->toTimeString();
                    $hist->save();

                    $documento = Document::find($doc[0]->tdocId);
                    $documento->tdocStatus = 'derivado';
                    $documento->save();
                    
                    /* registrara el registro del historial activando el flag de atendido del documento original 
                     se actualizara el estado de Atendido en el registro historial del documento origen
                    */
                    $docOrigen = Document::select('tdocId','thisId','tdocExp')
                                    ->join('tramHistorial','tdocId','=','thisDoc')
                                    ->where('tdocExp',$doc[0]->tdocExp)
                                    ->where('tdocRef',null)
                                    ->get();

                    $histOrigen = Historial::find($docOrigen[0]->thisId);
                    $histOrigen->thisFlagA = true;
                    $histOrigen->thisDateTimeA = $request->ndocFecEnvio; //Carbon::now();//->format('d/m/Y h:i:s A');
                    $histOrigen->thisDscA = trim($request->ndocEnvioMensaje);
                    $histOrigen->rec_date_at = Carbon::now()->toDateString();
                    $histOrigen->rec_time_at = Carbon::now()->toTimeString();
                    $histOrigen->save();

                    /* siendo asi, actualizamos el status del archivador o expediente al que pertenece el documento pues ya 
                    será atendido */
                    $exp = Archivador::find($docOrigen[0]->tdocExp);
                    $exp->tarcStatus = 'atendido';
                    $exp->updated_at = Carbon::now();
                    $exp->save();
                }
                else if($doc[0]->tdocAccion == 'reapertura'){
                    $hist = Historial::find($doc[0]->thisId);
                    $hist->thisDepT = $request->ndocEnvioDestino;
                    $hist->thisFlagD = true;
                    $hist->thisDateTimeD = $request->ndocFecEnvio; //Carbon::now(); //->format('d/m/Y h:i:s A');
                    $hist->thisDscD = trim($request->ndocEnvioMensaje);
                    $hist->rec_date_at = Carbon::now()->toDateString();
                    $hist->rec_time_at = Carbon::now()->toTimeString();
                    $hist->save();

                    $update_filer = Archivador::find($doc[0]->tdocExp);
                    $update_filer->tarcStatus = 'reaperturado';
                    $update_filer->updated_at = Carbon::now()->toDateString();
                    /* Se debe cambiar, actualizar la fecha de presentacion pues se genera un nuevo plazo */
                    //$update_filer->tarcDatePres = Carbon::now();
                    $update_filer->save();

                    $documento = Document::find($doc[0]->tdocId);
                    $documento->tdocStatus = 'derivado';
                    $documento->save();
                }
                else{

                    $hist = Historial::find($doc[0]->thisId);
                    $hist->thisDepT = $request->ndocEnvioDestino;
                    $hist->thisFlagD = true;
                    $hist->thisDateTimeD = $request->ndocFecEnvio; //Carbon::now();//->format('d/m/Y h:i:s A');
                    $hist->thisDscD = trim($request->ndocEnvioMensaje);
                    $hist->rec_date_at = Carbon::now()->toDateString();
                    $hist->rec_time_at = Carbon::now()->toTimeString();
                    $hist->save();

                    $update_filer = Archivador::find($doc[0]->tdocExp);
                    $update_filer->tarcStatus = 'procesando';
                    $update_filer->updated_at = Carbon::now()->toDateString();
                    $update_filer->save();

                    $documento = Document::find($doc[0]->tdocId);
                    $documento->tdocStatus = 'derivado';
                    $documento->save();
                }
            });

            if(is_null($exception)){
                $msg = 'Envío del documento registrado con éxito';
                $idMsg = 200;
            }
            else{
                $msg = $exception;
                $idMsg = 500;
            }
        } catch (Exception $e) {
            $msg = 'Error encontrado:' . $e . "\n";
            $idMsg = 500;
        }

        return response()->json(compact('msg','idMsg'));
        
    }

    public function anularEnvioHistorial(Request $request)
    {
        try{
            $exception = DB::transaction(function() use ($request){

                $doc = Document::select('*')
                        ->join('tramHistorial','tdocId','=','thisDoc')
                        ->where('tdocId',$request->doc)
                        ->where('thisFlagR',true)
                        ->where('thisFlagD',true)
                        ->get();

                if($doc[0]->tdocAccion == 'atendido-salida'){ 

                    $hist = Historial::find($doc[0]->thisId);
                    $hist->thisDepT = Auth::user()->tusId;
                    $hist->thisFlagD = false;
                    $hist->thisDateTimeD = null;
                    $hist->thisDscD = null;
                    $hist->rec_date_at = Carbon::now()->toDateString();
                    $hist->rec_time_at = Carbon::now()->toTimeString();
                    $hist->save();

                    $documento = Document::find($doc[0]->tdocId);
                    $documento->tdocStatus = 'registrado';
                    $documento->save();
                    
                    /* registrara el registro del historial activando el flag de atendido del documento original 
                     se actualizara el estado de Atendido en el registro historial del documento origen
                    */
                    $docOrigen = Document::select('tdocId','thisId','tdocExp')
                                    ->join('tramHistorial','tdocId','=','thisDoc')
                                    ->where('tdocExp',$doc[0]->tdocExp)
                                    ->where('tdocRef',null)
                                    ->get();

                    $histOrigen = Historial::find($docOrigen[0]->thisId);
                    $histOrigen->thisFlagA = false;
                    $histOrigen->thisDateTimeA = null;
                    $histOrigen->thisDscA = null;
                    $histOrigen->rec_date_at = Carbon::now()->toDateString();
                    $histOrigen->rec_time_at = Carbon::now()->toTimeString();
                    $histOrigen->save();

                    /* siendo asi, actualizamos el status del archivador o expediente al que pertenece el documento pues ya 
                    será atendido */
                    $exp = Archivador::find($docOrigen[0]->tdocExp);
                    $exp->tarcStatus = 'procesando';
                    $exp->updated_at = Carbon::now();
                    $exp->save();
                }
                else if($doc[0]->tdocAccion == 'reapertura'){
                    $hist = Historial::find($doc[0]->thisId);
                    $hist->thisDepT = Auth::user()->tusId;
                    $hist->thisFlagD = false;
                    $hist->thisDateTimeD = null;
                    $hist->thisDscD = null;
                    $hist->rec_date_at = Carbon::now()->toDateString();
                    $hist->rec_time_at = Carbon::now()->toTimeString();
                    $hist->save();

                    $update_filer = Archivador::find($doc[0]->tdocExp);
                    $update_filer->tarcStatus = 'atendido';
                    $update_filer->updated_at = Carbon::now()->toDateString();
                    /* Se debe cambiar, actualizar la fecha de presentacion pues se genera un nuevo plazo */
                    //$update_filer->tarcDatePres = Carbon::now();
                    $update_filer->save();

                    $documento = Document::find($doc[0]->tdocId);
                    $documento->tdocStatus = 'registrado';
                    $documento->save();
                }
                else{

                    $hist = Historial::find($doc[0]->thisId);
                    $hist->thisDepT = Auth::user()->tusId;
                    $hist->thisFlagD = false;
                    $hist->thisDateTimeD = null;
                    $hist->thisDscD = null;
                    $hist->rec_date_at = Carbon::now()->toDateString();
                    $hist->rec_time_at = Carbon::now()->toTimeString();
                    $hist->save();

                    $docByExp = Document::where('tdocExp',$doc[0]->tdocExp)->count();

                    $update_filer = Archivador::find($doc[0]->tdocExp);

                    if($docByExp == 1)
                        $update_filer->tarcStatus = 'aperturado';
                    else
                        $update_filer->tarcStatus = 'procesando';

                    $update_filer->updated_at = Carbon::now()->toDateString();
                    $update_filer->save();

                    $documento = Document::find($doc[0]->tdocId);
                    $documento->tdocStatus = 'registrado';
                    $documento->save();
                }

            });

            if(is_null($exception)){
                $msg = 'Envío del documento registrado con éxito';
                $idMsg = 200;
            }
            else{
                throw new Exception($exception);
            }

        }catch(Exception $e){
            $msg = 'Error encontrado:' . $e . "\n";
            $idMsg = 500;
        }

        return response()->json(compact('msg','idMsg'));
    }

    public function getDetailDerivation($histId, Request $request)
    {
        $historial = Historial::find($histId);
        $withexp = Archivador::find($historial->thisDocD); //thisDocD ahora hace referencia al EXP
        $withdoc = Document::find(is_null($withexp)?null:$withexp->tarcDoc);

        if(empty($withdoc))
            $tipodoc = "Sin documento";
        else
            $tipodoc = TipoDocumento::find($withdoc->tdocType);

        $arcdoc = Archivador::where('tarcExp',$historial->thisDocD)->get();
        /* SQL Version
        $destinos = Historial::select(DB::raw('thisId,thisDepT,dbo.fnTramGetDscFromId(\'TLogGrlDep\',thisDepT) as thisDestino,thisIdSourceD'))->where('thisIdSourceD',$histId)->get();*/

        /* MySQL Version */
        $destinos = Historial::select(DB::raw('thisId,thisDepT,fnTramGetDscFromId(\'tramDependencia\',thisDepT) as thisDestino,thisIdSourceD'))->where('thisIdSourceD',$histId)->get();


        return compact('historial','withdoc','tipodoc','arcdoc','destinos');
    }

    public function makeUniqueCode($acronimo, $year, $correlative)
    {
        $partial_year = $year - 2000;
        $new_correlative = substr('00000'.$correlative, -5);
        $new_code = $acronimo.$partial_year.$new_correlative;
        return $new_code;
    }

    public function acceptDocumentDerived($idExp, Request $request)
    {
        DB::transaction(function($idExp) use ($idExp, $request){

            $FlagR = Historial::find($idExp);
            $FlagR->thisFlagR = true;
            $FlagR->thisDateTimeR = Carbon::now();//->format('d/m/Y h:i:s A');
            $FlagR->save();

        });

        if($request->ajax())
        {
            return "updated";
        }
    }

    public function attendDocument($idExp, Request $request)
    {
        DB::transaction(function($request) use ($request, $idExp){

            $flagA = Historial::find($idExp);
            $flagA->thisFlagA = true;
            $flagA->thisDateTimeA = Carbon::now();//->format('d/m/Y h:i:s A');
            $flagA->thisDscA = strtoupper($request->dsc_attend);
            $flagA->save();

            /* Cambiar el estado del documento a completado */
            $update_doc = Document::find($request->document_to_attend);
            $update_doc->tdocStatus = 'Cerrado';
            $update_doc->tdocUpdateAt = Carbon::now()->format('d/m/Y h:i:s A');
            $update_doc->save();

            /* Cambiar estado del archivador: evaluar que todos los documentos del expediente estenen estado cerrado - ahora esta en forma temporal */
            $update_filer = Archivador::find($request->expedient_to_attend);
            $update_filer->tarcStatus = 'Completado';
            $update_filer->updated_at = Carbon::now()->toDateString();
            $update_filer->save();

        });

        if($request->ajax())
        {
            return "attended";
        }
    }

    public function documentaryTracking($docID, Request $request)
    {
        $firstDoc = Historial::where('thisExp',$docID)->whereRaw('thisDepS = thisDepT')->get();

        $doc = Document::find($docID);
        //$time_line = Historial::where('thisExp',$doc->tdocExp)->get();

        $time_line = DB::select("SELECT h.thisDoc, h.thisExp, h.thisDepS, 
        dbo.fnTramGetDscFromId('TLogGrlDep', h.thisDepS) AS SourceDsc, h.thisDepT, 
        dbo.fnTramGetDscFromId('TLogGrlDep',h.thisDepT) AS TargetDsc , h.thisFlagR, 
        h.thisFlagA, h.thisFlagD, h.thisDscD, h.thisDscA, h.thisIdSourceD, h.rec_date_at, 
        h.rec_time_at, h.thisDateTimeR, h.thisDateTimeA, h.thisDateTimeD,
        dbo.fnTramGetDateDif(h.thisDateTimeR, h.thisDateTimeD, 2) AS DuraD,
        dbo.fnTramGetDateDif(h.thisDateTimeR, h.thisDateTimeA, 2) AS DuraA,
        h.thisDocD AS DocuD
        FROM tramHistorial h 
        WHERE h.thisExp = ?",[$doc->tdocExp]);

        $view = view('tramite.tracking_documentary',['time_line' => $time_line]);

        if($request->ajax())
        {
            $sections = $view->renderSections();
            return $sections['main-content'];
        }

        return $view;

    }

    public function getNotifications(Request $request)
    {
        /* SQL Version
        $notifications = Historial::select('*')
            ->where('thisDepT',$request->dep)
            ->where('thisFlagA',false)
            ->where('thisFlagD',false)
            ->whereRaw('year(tdocDate) = year(getdate())')
            ->count();*/

        /* MySQL Version*/
        $notifications = Historial::select('*')
            ->where('thisDepT',$request->dep)
            ->where('thisFlagA',false)
            ->where('thisFlagD',false)
            ->whereRaw('year(tdocDate) = year(now())')
            ->count();


        echo $notifications;
    }

    public function undoDocument($id, Request $request)
    {
        $historial = Historial::find($id);
        $reply = '';

        if($historial->thisFlagD == true)
        {
            /* NO SE PUEDE ELIMINAR DOCUMENTO SOLO CANCELAR LA DERIVACION SI EL DOCUMENTO NO FUE RECEPCIONADO POR OTROS */
            $derived_to = Historial::select('*')
                ->where('thisExp',$historial->thisExp)
                ->where('thisDepS',$historial->thisDepT)
                ->get();

            if(!$this->is_document_receipt($derived_to))
            {
                $this->undo_derivation($id, $derived_to);
                Arcparticular::where('tarpGexp',$historial->thisExp)
                    ->update(['tarpFlagD' => false, 'updated_at' => Carbon::now()->toDateString()]);

                $reply = 'La operación de Derivación ha sido ANULADA';
            }
            else
            {
                $reply = 'No es posible anular la operación porque ya ha sido recepcionado por alguna dependencia destino';
            }
        }
        else if($historial->thisFlagD == false)
        {
            if($historial->thisDepS == $historial->thisDepT)
            {
                $this->delete_document($historial->thisExp);
                $reply = 'Se ha eliminado el registro del documento';
            }
        }

        if($request->ajax())
        {
            return $reply;
        }

        return false;
    }

    public function undoOperationDocument($id, Request $request)
    {
        $operation = Historial::find($id);
        $reply = '';

        if(!$operation->thisFlagR)
        {
            $reply = 'Documento aun no recepcionado';
            $reply_codec = ['codec' => 409, 'msg' => $reply];
        }
        else if($operation->thisFlagA)
        {
            $this->undo_attend($id);
            $reply = 'Atencion anulada';
            $reply_codec = ['codec' => 200, 'msg' => $reply];

        }
        else if($operation->thisFlagD)
        {
            /*$derived_to = Historial::select('*')
                            ->where('thisExp',$operation->thisExp)
                            ->where('thisDepS',$operation->thisDepT)
                            ->get(); ESTA CONSULTA TIENE FALLAS*/

            $derived_to = Historial::select('*')
                            ->where('thisIdSourceD',$operation->thisId)
                            ->get();

            if(!$this->is_document_receipt($derived_to))
            {
                $this->undo_derivation($id, $derived_to);
                $reply = 'La Derivación seleccionada ha sido anulada';
                $reply_codec = ['codec' => 200, 'msg' => $reply];
            }
            else
            {
                $reply = 'No es posible anular la operación porque ya ha sido recepcionado por alguna dependencia destino';
                $reply_codec = ['codec' => 409, 'msg' => $reply];
            }
        }
        else
        {
            $reply = 'No es posible realizar esta operación';
            $reply_codec = ['codec' => 409, 'msg' => $reply];
        }

        if($request->ajax())
        {
            return $reply_codec;
        }
        return false;
    }

    public function delete_document($idFiler)
    {
        $filer = Archivador::find($idFiler);
        $idDoc = $filer->tarcDoc;
        $idExp = $filer->tarcExp;

        DB::transaction(function($idDoc) use($idDoc, $idExp){

            $document = Document::find($idDoc);
            $document->delete();

            Arcparticular::where('tarpDoc',$idDoc)
                    ->where('tarpGexp',$idExp)
                    ->delete();

        });
    }

    public function undo_attend($id)
    {
        DB::transaction(function($id) use($id){

            $record = Historial::find($id);
            $record->thisFlagA = false;
            $record->save();

            $filer = Archivador::find($record->thisExp);
            $filer->tarcStatus = 'En Proceso';
            $filer->updated_at = Carbon::now()->toDateString();
            $filer->save();

        });
    }

    public function undo_derivation($id, $dep_target)
    {
        DB::transaction(function($dep_target) use($dep_target, $id){

            foreach($dep_target as $dep)
            {
                if($dep->thisDepS != $dep->thisDepT)
                {
                    $hist = Historial::find($dep->thisId);
                    $hist->delete();
                }
            }

            $hist_main = Historial::find($id);
            $hist_main->thisFlagD = false;
            $hist_main->save();

        });
    }

    public function is_document_receipt($dep_target)
    {
        foreach($dep_target as $dep)
        {
            if($dep->thisDepS != $dep->thisDepT)
            {
                if($dep->thisFlagR == true)
                {
                    return true;
                }
            }
        }
        return false;
    }
} 