<?php
/**
 * Created by PhpStorm.
 * User: HP i5
 * Date: 12/07/15
 * Time: 11:43
 */

namespace aidocs\Http\Controllers\Document;


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
use Illuminate\Support\Facades\DB;
use aidocs\Models\TipoDocumento;

class HistorialController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function storeHistorialDerived(DeriveDocumentRequest $request)
    {
        /* derivación simple */
        DB::transaction(function($request) use ($request){

            if(count($request->dep_target)>0)
            {
                foreach($request->dep_target as $d)
                {
                    $hist_copy = new Historial();

                    $hist_copy->thisExp = $request->expedient;
                    $hist_copy->thisDoc = $request->document;
                    $hist_copy->thisDepS = $request->dep_source;
                    $hist_copy->thisDepT = $d;
                    $hist_copy->thisFlagR = false;
                    $hist_copy->thisFlagA = false;
                    $hist_copy->thisFlagD = false;
                    $hist_copy->rec_date_at = Carbon::now()->toDateString();
                    $hist_copy->rec_time_at = Carbon::now()->toTimeString();
                    $hist_copy->thisIdSourceD = $request->kyId;
                    $hist_copy->save();

                    unset($hist_copy);
                }
            }

            $update_hist = Historial::find($request->kyId);
            $update_hist->thisFlagD = true;
            $update_hist->thisDscD = strtoupper($request->dsc_derived);
            $update_hist->thisDateTimeD = Carbon::now()->format('d/m/Y h:i:s A');
            $update_hist->save();

            $update_filer = Archivador::find($request->expedient);
            $update_filer->tarcStatus = 'En Proceso';
            $update_filer->updated_at = Carbon::now()->toDateString();
            $update_filer->save();

        });
    }

    public function storeHistorialDerivedDc(DeriveDocumentRequest $request)
    {
        /* derivación con documento */
        DB::transaction(function($request) use ($request){

            /*DOCUMENTO CREADO PARA DERIVAR EL DOCUMENTO ORIGINAL, este documento no se registra en el historial porque corresponde a la cadena de historial del primer documento*/

            $file = $request->file('file_derived');

            $doc = new Document();
            
            $pref = 'DOC';
            $code_doc = '';
            $stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON; EXEC generar_codigo ?,?');
            $stmt->bindParam(1,$pref);
            $stmt->bindParam(2,$code_doc,\PDO::PARAM_STR | \PDO::PARAM_INPUT_OUTPUT, 10);
            $stmt->execute();
            unset($stmt);
                
            $doc->tdocId = $code_doc;
            $doc->tdocExp = $request->expedient;
            $doc->tdocSenderName = Auth::user()->tusNames;
            $doc->tdocSenderPaterno = Auth::user()->tusPaterno;
            $doc->tdocSenderMaterno = Auth::user()->tusMaterno;
            $doc->tdocDni = Auth::user()->tusId;
            $doc->tdocSubject = strtoupper($request->sbj_derived);
            $doc->tdocAsoc = $request->asociacion;
            $doc->tdocRef = $request->document;
            $doc->tdocFolio = $request->folio_doc;
            $doc->tdocType = $request->type_doc;
            $doc->tdocRegistro = $request->nrodc;
            $doc->tdocDate = Carbon::now();
            $doc->tdocStatus = 'Pendiente';
            $doc->tdocRegisterBy = Auth::user()->tusId;

            if($file){
                $doc->tdocFileExt = $file->getClientOriginalExtension();
                $doc->tdocPathFile = 'docscase/'.$request->expedient;
                $doc->tdocFileMime = $file->getMimeType();
                $doc->tdocFileName = $code_doc.'.'.$file->getClientOriginalExtension();

                $filename = '/'.$request->expedient.'/'.$code_doc.'.'.$file->getClientOriginalExtension();
                \Storage::disk('local')->put($filename, \File::get($file));
            }

            $doc->save();

            $pexp = new Arcparticular();

            $correlative_pexp = Arcparticular::where('tarpDep',Auth::user()->tusWorkDep)->count() + 1;
            $code_pexp = $this->makeUniqueCode('PXP',Carbon::now()->year,$correlative_pexp);

            $pexp->tarpPexp = $code_pexp;
            $pexp->tarpGexp = $request->expedient;
            $pexp->tarpDep = Auth::user()->tusWorkDep;
            $pexp->tarpDoc = $code_doc;
            $pexp->created_at = Carbon::now()->toDateString();
            $pexp->tarpYear = Carbon::now()->year;

            $pexp->save();

            /* Registro de la derivación del documento de respuesta del doc original o referencia  */

            if(count($request->dep_target)>0)
            {
                foreach($request->dep_target as $d)
                {
                    $hist_copy = new Historial();

                    $hist_copy->thisExp = $request->expedient;
                    $hist_copy->thisDoc = $code_doc;
                    $hist_copy->thisDepS = $request->dep_source;
                    $hist_copy->thisDepT = $d;
                    $hist_copy->thisFlagR = false;
                    $hist_copy->thisFlagA = false;
                    $hist_copy->thisFlagD = false;
                    $hist_copy->rec_date_at = Carbon::now()->toDateString();
                    $hist_copy->rec_time_at = Carbon::now()->toTimeString();
                    $hist_copy->thisIdSourceD = $request->kyId;
                    $hist_copy->save();

                    unset($hist_copy);
                }
            }

            $update_hist = Historial::find($request->kyId);
            $update_hist->thisFlagD = true;
            $update_hist->thisDscD = strtoupper($request->nta_derived);
            $update_hist->thisDateTimeD = Carbon::now()->format('d/m/Y h:i:s A');
            $update_hist->thisDocD = $code_doc;
            $update_hist->save();

            $update_doc = Document::find($request->document);
            $update_doc->tdocStatus = 'En proceso';
            $update_doc->save();

            $update_filer = Archivador::find($request->expedient);
            $update_filer->tarcStatus = 'En Proceso';
            $update_filer->updated_at = Carbon::now()->toDateString();
            $update_filer->save();

        });
    }

    public function firstHistorialDerived(DeriveDocumentRequest $request)
    {
        DB::transaction(function($request) use ($request){

            if(count($request->dep_target)>0)
            {
                foreach($request->dep_target as $d)
                {
                    $hist_copy = new Historial();

                    $hist_copy->thisDoc = $request->document;
                    $hist_copy->thisExp = $request->expedient;
                    $hist_copy->thisDepS = $request->dep_source;
                    $hist_copy->thisDepT = $d;
                    $hist_copy->thisFlagR = false;
                    $hist_copy->thisFlagA = false;
                    $hist_copy->thisFlagD = false;
                    $hist_copy->rec_date_at = Carbon::now()->toDateString();
                    $hist_copy->rec_time_at = Carbon::now()->toTimeString();
                    $hist_copy->thisIdSourceD = $request->kyId;
                    $hist_copy->save();

                    unset($hist_copy);
                }
            }

            $update_hist = Historial::find($request->kyId);
            $update_hist->thisFlagD = true;
            $update_hist->thisDateTimeD = Carbon::now()->format('d/m/Y h:i:s A');
            $update_hist->thisDscD = trim($request->nota_derivado);
            $update_hist->save();

            $idPFile = Arcparticular::select('tarpId')
                ->where('tarpDep','=',$request->dep_source)
                ->where('tarpGexp','=',$request->expedient)
                ->first();

            $updtPfile = Arcparticular::find($idPFile->tarpId);
            $updtPfile->tarpFlagD = true;
            $updtPfile->save();

            $update_doc = Document::find($request->document);
            $update_doc->tdocStatus = 'En proceso';
            $update_doc->save();

            $update_filer = Archivador::find($request->expedient);
            $update_filer->tarcStatus = 'En Proceso';
            $update_filer->updated_at = Carbon::now()->toDateString();
            $update_filer->save();
        });
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
        $destinos = Historial::select(DB::raw('thisId,thisDepT,dbo.fnTramGetDscFromId(\'TLogGrlDep\',thisDepT) as thisDestino,thisIdSourceD'))->where('thisIdSourceD',$histId)->get();

        return compact('historial','withdoc','tipodoc','arcdoc','destinos');
    }

    public function makeUniqueCode($acronimo, $year, $correlative)
    {
        $partial_year = $year - 2000;
        $new_correlative = substr('00000'.$correlative, -5);
        $new_code = $acronimo.$partial_year.$new_correlative;
        return $new_code;
    }

    public function getHistorialDoc(Request $request)
    {
        $inbox = Historial::select('*')
            ->join('tramDocumento','tdocId','=','thisDoc')
            ->join('tramArchivador','tarcExp','=','tdocExp')
            ->join('TLogGrlDep','thisDepS','=','depID')
            ->where('thisDepT',Auth::user()->tusWorkDep)
            ->where('thisDepS','<>',Auth::user()->tusWorkDep)
            ->where('tarcYear',$request->period)
            ->orderby('tdocId','DESC')
            ->get();

        $dependencys = Dependencia::select('*')
            ->where('depActive',1)
            ->get();

        $tipos = TipoDocumento::where('ttypShow',true)->get();

        $view = view('tramite.inbox_document',compact('inbox'),['dependencys' => $dependencys, 'tipos' => $tipos]);

        if($request->ajax())
        {
            $sections = $view->renderSections();
            return $sections['main-content'];
        }

        return $view;
    }

    public function acceptDocumentDerived($idExp, Request $request)
    {
        DB::transaction(function($idExp) use ($idExp, $request){

            $FlagR = Historial::find($idExp);
            $FlagR->thisFlagR = true;
            $FlagR->thisDateTimeR = Carbon::now()->format('d/m/Y h:i:s A');
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
            $flagA->thisDateTimeA = Carbon::now()->format('d/m/Y h:i:s A');
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
        $notifications = Historial::select('*')
            ->where('thisDepT',$request->dep)
            ->where('thisFlagA',false)
            ->where('thisFlagD',false)
            ->whereRaw('year(tdocDate) = year(getdate())')
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