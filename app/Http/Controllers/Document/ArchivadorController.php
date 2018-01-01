<?php
/**
 * Created by PhpStorm.
 * User: HP i5
 * Date: 11/07/15
 * Time: 19:44
 */

namespace aidocs\Http\Controllers\Document;

use Session;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use aidocs\Http\Controllers\Controller;
use aidocs\Models\Archivador;
use aidocs\Models\Document;
use Illuminate\Http\Request;

class ArchivadorController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function findDataFor($id, $by)
    {
        $expedient = '';

        if($by == 'exp')
        {
            $expedient = DB::select("SELECT  a.tarcExp,d.tdocId,td.ttypDesc,d.tdocDni,a.tarcDatePres,
                                    a.tarcStatus,d.tdocSubject
                                    FROM tramDocumento d
                                    INNER JOIN tramTipoDocumento td ON td.ttypDoc = d.tdocType
                                    INNER JOIN tramArchivador a ON a.tarcDoc = d.tdocId
                                    WHERE a.tarcExp = ?
                                    ORDER BY a.tarcDatePres DESC;",[$id]);
        }
        else if($by == 'doc')
        {
            $expedient = DB::select("SELECT  a.tarcExp,d.tdocId,td.ttypDesc,d.tdocDni,a.tarcDatePres,
                                    a.tarcStatus,d.tdocSubject
                                    FROM tramDocumento d
                                    INNER JOIN tramTipoDocumento td ON td.ttypDoc = d.tdocType
                                    INNER JOIN tramArchivador a ON a.tarcDoc = d.tdocId
                                    WHERE d.tdocId = ?
                                    ORDER BY a.tarcDatePres DESC;",[$id]);
        }

        return $expedient;
    }

    public function getDocumentsFromFiler()
    {
        $documents = Archivador::select('tdocId', 'tarcExp', 'tarcDatePres', 'tarcStatus')
            ->orderBy('tarcDatePres','DESC')
            ->join('tramDocumento','tramArchivador.tarcDoc','=','tramDocumento.tdocId')
            ->get();

        return view('tramite.show_document',compact('documents'));
    }

    public function findExpedient($id, Request $request)
    {
        $expedient = $this->findDataFor($id, 'exp');

        if($request->ajax())
        {
            return $expedient;
        }

        return false;
    }

    public function findDocument($id, Request $request)
    {
        $document = $this->findDataFor($id, 'doc');

        if($request->ajax())
        {
            return $document;
        }

        return false;
    }

    public function findBySubject(Request $request)
    {
        $subject = '%'.trim($request->ndescAsunto).'%';
        $funcion = $request->nidFuncion;

        $docs = Document::select('*')
                    ->join('tramTipoDocumento','ttypDoc','=','tdocType')
                    ->join('tramArchivador','tarcId','=','tdocExp')
                    ->where('tdocSubject','like',$subject)
                    ->whereRaw('year(tarcDatePres) = '.trim($request->period))
                    ->orderBy('tarcDatePres','DESC')
                    ->get();

        if($docs){
            if($docs->count() == 0){
                $resultado = 'No se ha encontrado ningún registro';
            }
            else{
                $view = view('tramite.tabla_resultado_documentos',compact('docs','funcion'));
                $resultado = $view->render();
            }
            $msg = "Recuperado correctamente";
            $Respuesta = 200;
        }
        else{
            $resultado = '';
            $msg = 'Error: no se pudo recuperar la información solicitada';
            $Respuesta = 500;
        }

        return response()->json(compact('Respuesta','msg','resultado'));

    }

    public function findByDates(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $funcion = $request->nidFuncion; // referencia o busqueda

        $docs = Document::select('*')
            ->join('tramTipoDocumento','ttypDoc','=','tdocType')
            ->join('tramArchivador','tarcId','=','tdocExp')
            ->whereRaw('year(tdocDate) = '.Session::get('periodo'))
            ->wherebetween('tdocDate',[$startDate,$endDate])
            ->get();

        if($docs){
            if($docs->count() == 0){
                $resultado = 'No se ha encontrado ningún registro';
            }
            else{
                $view = view('tramite.tabla_resultado_documentos',compact('docs','funcion'));
                $resultado = $view->render();
            }
            $msg = "Recuperado correctamente";
            $Respuesta = 200;
        }
        else{
            $resultado = '';
            $msg = 'Error: no se pudo recuperar la información solicitada';
            $Respuesta = 500;
        }

        return response()->json(array('Respuesta' => $Respuesta, 'msg' => $msg, 'resultado' => $resultado));
    }

    public function findByRegistro(Request $request)
    {
        $registro = trim($request->ndescRegistro);
        $funcion = $request->nidFuncion;

        $docs = Document::select('*')
                    ->join('tramTipoDocumento','ttypDoc','=','tdocType')
                    ->join('tramArchivador','tarcId','=','tdocExp')
                    ->where('tdocRegistro',$registro)
                    ->whereRaw('year(tarcDatePres) = '.trim($request->period))
                    ->orderBy('tarcDatePres','DESC')
                    ->get();

        if($docs){
            if($docs->count() == 0){
                $resultado = 'No se ha encontrado ningún registro';
            }
            else{
                $view = view('tramite.tabla_resultado_documentos',compact('docs','funcion'));
                $resultado = $view->render();
            }
            $msg = "Recuperado correctamente";
            $Respuesta = 200;
        }
        else{
            $resultado = '';
            $msg = 'Error: no se pudo recuperar la información solicitada';
            $Respuesta = 500;
        }

        return response()->json(compact('Respuesta','msg','resultado'));
    }

    public function findBySender(Request $request)
    {
        $nameSender = trim($request->ndescRemitP);
        $funcion = $request->nidFuncion;

        $docs = Document::select('*')
                    ->join('tramTipoDocumento','ttypDoc','=','tdocType')
                    ->join('tramArchivador','tarcId','=','tdocExp')
                    ->where('tdocSender','like','%'.$nameSender.'%')
                    ->whereRaw('year(tarcDatePres) = '.trim($request->period))
                    ->orderBy('tarcDatePres','DESC')
                    ->get();

        if($docs){
            if($docs->count() == 0){
                $resultado = 'No se ha encontrado ningún registro';
            }
            else{
                $view = view('tramite.tabla_resultado_documentos',compact('docs','funcion'));
                $resultado = $view->render();
            }
            $msg = "Recuperado correctamente";
            $Respuesta = 200;
        }
        else{
            $resultado = '';
            $msg = 'Error: no se pudo recuperar la información solicitada';
            $Respuesta = 500;
        }

        return response()->json(compact('Respuesta','msg','resultado'));
    }

    public function findByAttaches(Request $request)
    {
        $attach = trim($request->attachesDoc);

        $docs = Document::select('*')
            ->join('tramTipoDocumento','ttypDoc','=','tdocType')
            ->join('tramDocAnexos','tdocId','=','tdaDocId')
            ->join('tramArchivador','tarcDoc','=','tdocId')
            // version SQL ->where(DB::raw("CONCAT(tdaNumAnex,' ',tdaDsc)"),'LIKE','%'.$attach.'%')
            // version Mysql
            ->where(DB::raw("CONCAT_WS(' ',tdaNumAnex,tdaDsc)"),'LIKE','%'.$attach.'%')
            ->get();

        if($request->ajax())
        {
            return $docs;
        }

        return false;
    }
} 