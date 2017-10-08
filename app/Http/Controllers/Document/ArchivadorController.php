<?php
/**
 * Created by PhpStorm.
 * User: HP i5
 * Date: 11/07/15
 * Time: 19:44
 */

namespace aidocs\Http\Controllers\Document;


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
        $subject = '%'.trim($request->subjectDoc).'%';
        $docs = DB::select("SELECT  a.tarcExp,d.tdocId,td.ttypDesc,d.tdocDni,a.tarcDatePres,
                            a.tarcStatus,d.tdocSubject
                            FROM tramDocumento d
                            INNER JOIN tramTipoDocumento td ON td.ttypDoc = d.tdocType
                            INNER JOIN tramArchivador a ON a.tarcDoc = d.tdocId
                            WHERE d.tdocSubject LIKE ?
                            ORDER BY a.tarcDatePres DESC;",[$subject]);

        if($request->ajax())
        {
            return $docs;
        }

        return false;

    }

    public function findByDates(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $docs = Document::select('*')
            ->join('tramTipoDocumento','ttypDoc','=','tdocType')
            ->join('tramArchivador','tarcDoc','=','tdocId')
            ->wherebetween('tarcDatePres',[$startDate,$endDate])
            ->get();

        if($request->ajax())
        {
            return $docs;
        }

        return false;
    }

    public function findBySender(Request $request)
    {
        $dniSender = trim($request->dniSender);
        $nameSender = trim($request->nameSender);

        if($dniSender != '')
        {
            $docs = Document::select('*')
                ->join('tramTipoDocumento','ttypDoc','=','tdocType')
                ->join('tramArchivador','tarcDoc','=','tdocId')
                ->where('tdocDni',$dniSender)
                ->get();
        }
        else if($nameSender != '')
        {
            $docs = Document::select('*')
                ->join('tramTipoDocumento','ttypDoc','=','tdocType')
                ->join('tramArchivador','tarcDoc','=','tdocId')
                ->where(DB::raw("CONCAT(tdocSenderName,' ',tdocSenderPaterno,' ',tdocSenderMaterno)"),'like','%'.$nameSender.'%')
                ->get();
        }
        else
        {
            $docs = [];
        }

        if($request->ajax())
        {
            if(count($docs) != 0)
            {
                return $docs;
            }
        }

        return false;
    }

    public function findByAttaches(Request $request)
    {
        $attach = trim($request->attachesDoc);

        $docs = Document::select('*')
            ->join('tramTipoDocumento','ttypDoc','=','tdocType')
            ->join('tramDocAnexos','tdocId','=','tdaDocId')
            ->join('tramArchivador','tarcDoc','=','tdocId')
            ->where(DB::raw("CONCAT(tdaNumAnex,' ',tdaDsc)"),'LIKE','%'.$attach.'%')
            ->get();

        if($request->ajax())
        {
            return $docs;
        }

        return false;
    }
} 