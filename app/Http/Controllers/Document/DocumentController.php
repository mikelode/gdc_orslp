<?php
/**
 * Created by PhpStorm.
 * User: HP i5
 * Date: 11/07/15
 * Time: 16:41
 */

namespace aidocs\Http\Controllers\Document;


use aidocs\Models\Asociacion;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use aidocs\Http\Controllers\Controller;
use aidocs\Http\Requests\StoreDocumentRequest;
use aidocs\Models\Anexo;
use aidocs\Models\Archivador;
use aidocs\Models\Arcparticular;
use aidocs\Models\Document;
use aidocs\Models\Historial;
use aidocs\Models\ManagerDep;
use aidocs\Models\TipoDocumento;
use Illuminate\Support\Facades\DB;


class DocumentController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index(Request $request)
	{
		$tipos = TipoDocumento::where('ttypShow',true)->get();
		$assoc = Asociacion::select('tasId','tasOrganizacion')->get();
		/*$managers = ManagerDep::select('*')
						->where('trepDep',Auth::user()->tusWorkDep)
						->where('trepStatus',true)
						->get();*/

		$view = view('tramite.register_document',['tipos' => $tipos],compact('assoc'));

		if($request->ajax())
		{
			$sections = $view->renderSections();
			return $sections['main-content'];
		}
		return $view;
	}

	public function getManagerDocument(Request $request)
	{
		$managers = ManagerDep::select('*')
			->where('trepDep',Auth::user()->tusWorkDep)
			->where('trepStatus',true)
			->get();

		if($request->ajax())
		{
			return $managers;
		}

		return false;
	}

	public function getSenderDocument($dni, Request $request)
	{
		$sender = Document::where('tdocDni',$dni)->firstOrFail();

		if($request->ajax())
		{
			return $sender;
		}
		return false;
	}

	public function makeUniqueCode($acronimo, $year, $correlative)
	{
		$partial_year = $year - 2000;
		$new_correlative = substr('00000'.$correlative, -5);
		$new_code = $acronimo.$partial_year.$new_correlative;
		return $new_code;
	}

	public function numberDocument()
	{
		$count = Document::all()->count();
		return $count + 1;
	}

	public function storeDocument(StoreDocumentRequest $request)
	{
			DB::transaction(function($request) use ($request){

				$exp = new Archivador();
				$correlative_exp = Archivador::all()->count() + 1;
				//$code_exp = $this->makeUniqueCode('EXP',Carbon::now()->year,$correlative_exp);
				$pref = 'EXP';
				$code_exp = '';
				$stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON; EXEC generar_codigo ?,?');
				$stmt->bindParam(1,$pref);
				$stmt->bindParam(2,$code_exp,\PDO::PARAM_STR | \PDO::PARAM_INPUT_OUTPUT, 10);
				$stmt->execute();
				unset($stmt);
				
				$exp->tarcExp = $code_exp;
				$exp->tarcDatepres = Carbon::now();; // Fecha de creacion del archivador
				$exp->tarcStatus = 'Aperturado';
				$exp->created_at = Carbon::now();
				$exp->created_time_at = Carbon::now()->toTimeString();
				$exp->updated_at = Carbon::now();
				$exp->tarcSource = 'int';
				$exp->tarcYear = Carbon::now()->year;
				$exp->tarcAsoc = $request->asoc_doc;

				$exp->save();

				$doc = new Document();
				//$code_doc = $this->makeUniqueCode('DOC',Carbon::now()->year,$this->numberDocument());
				$pref = 'DOC';
				$code_doc = '';
				$stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON; EXEC generar_codigo ?,?');
				$stmt->bindParam(1,$pref);
				$stmt->bindParam(2,$code_doc,\PDO::PARAM_STR | \PDO::PARAM_INPUT_OUTPUT, 10);
				$stmt->execute();
				unset($stmt);
				
				$doc->tdocId = $code_doc;
				$doc->tdocExp = $code_exp;
				$doc->tdocSenderName = strtoupper($request->name_sender);
				$doc->tdocSenderPaterno = strtoupper($request->patern_sender);
				$doc->tdocSenderMaterno = strtoupper($request->matern_sender);
				$doc->tdocDni = $request->dni_sender;
				$doc->tdocSubject = strtoupper($request->subject_doc);
				$doc->tdocAsoc = $request->asoc_doc;
				$doc->tdocFolio = $request->folio_doc;
				$doc->tdocType = $request->type_doc;
				$doc->tdocRegistro = $request->nreg_doc;
				$doc->tdocDate = $request->date_doc;
				$doc->tdocStatus = 'Pendiente';
				$doc->tdocRegisterBy = Auth::user()->tusId;

				$doc->save();

				$pexp = new Arcparticular();

				$correlative_pexp = Arcparticular::where('tarpDep',Auth::user()->tusWorkDep)->count() + 1;
				$code_pexp = $this->makeUniqueCode('PXP',Carbon::now()->year,$correlative_pexp);

				$pexp->tarpPexp = $code_pexp;
				$pexp->tarpGexp = $code_exp;
				$pexp->tarpDep = Auth::user()->tusWorkDep;
				$pexp->tarpDoc = $code_doc;
				$pexp->created_at = Carbon::now()->toDateString();
				$pexp->tarpYear = Carbon::now()->year;

				$pexp->save();

				$hist = new Historial();

				$hist->thisExp = $code_exp;
				$hist->thisDoc = $code_doc;
				$hist->thisDepS = Auth::user()->tusWorkDep;
				$hist->thisDepT = Auth::user()->tusWorkDep;
				$hist->thisFlagR = true;
				$hist->thisFlagA = false;
				$hist->thisFlagD = false;
				$hist->rec_date_at = Carbon::now()->toDateString();
				$hist->rec_time_at = Carbon::now()->toTimeString();
				$hist->thisDateTimeR = Carbon::now()->format('d/m/Y h:i:s A');

				$hist->save();
			});


		if($request->ajax())
		{
				return "El Documento fue Creado Exitosamente";
		}

		return false;
	}

	public function detailDocument($idDoc, Request $request)
	{
		$document = Document::where('tdocId',$idDoc)->get();
		$anexos = Anexo::where('tdaDocId', $idDoc)->get();

		if($request->ajax())
		{
			$detDoc = ['documento'=>$document, 'anexos'=>$anexos];
			return $detDoc;
		}
		return  false;
	}

	public function detailOperation($idParentDoc, Request $request)
	{
		$parentDoc = Historial::where('thisId', $idParentDoc)->get();

		if($request->ajax())
		{
			return $parentDoc;
		}
		return false;
	}

	public function consultDocument(Request $request)
	{
		$list_docs = Document::select('tarcExp','tdocId','ttypDesc','tdocDni','tdocDate','tdocStatus','tdocSubject','tdocRegistro')
							->join('tramTipoDocumento','ttypDoc','=','tdocType')
							->join('tramArchivador','tarcExp','=','tdocExp')
							->where('tarcYear',$request->period)
							->orderby('tarcDatePres','DESC')
							->get();
		$asoc = Asociacion::select('tasId','tasOrganizacion')->get();
		$tipos = TipoDocumento::all();

		$view = view('tramite.consult_document',['list_docs' => $list_docs, 'asoc' => $asoc, 'tipos' => $tipos]);

		if($request->ajax())
		{
			$sections = $view->renderSections();
			return $sections['main-content'];
		}
		return $view;
	}

	public function listDocument(Request $request)
	{
		$list_docs = Document::select('tarcExp','tdocId','ttypDesc','tdocDni','tdocDate','tdocStatus','tdocSubject')
							->join('tramTipoDocumento','ttypDoc','=','tdocType')
							->join('tramArchivador','tarcDoc','=','tdocId')
							->where('tarcYear',$request->year)
							->orderby('tarcDatePres','DESC')
							->paginate(20);

		$view = view('tramite.consult_documentPage',['list_docs' => $list_docs]);

		return $view;
	}

	public function retrieveDocumentData($year, Request $request)
	{
		$list_docs = Document::select(DB::raw('tdocExp, tdocId, dbo.fnTramGetDscFromId(\'tramTipoDocumento\',tdocType) as docTipo, tdocDni, tdocDate, tdocStatus, tdocSubject, tdocRegistro'))
							->join('tramArchivador','tarcExp','=','tdocExp')
							->where('tarcYear',$year);

        if($request->ndocAsoc != 'all')
        {
        	$list_docs = $list_docs->where('tdocAsoc',$request->ndocAsoc);
        }
        if(trim($request->ndocReg) != '')
        {
        	$list_docs = $list_docs->where('tdocRegistro',$request->ndocReg);
        }
        if(trim($request->ndocCodigo) != '')
        {
        	$list_docs = $list_docs->where('tdocId');
        }

        if($request->ndocTipo != 'all')
        {
        	$list_docs = $list_docs->where('tdocType',$request->ndocTipo);
        }

        if($request->ndocFechaIni != '' && $request->ndocFechaFin != '')
        {
        	$list_docs = $list_docs->whereBetween('tdocDate',[$request->ndocFechaIni,$request->ndocFechaFin]);
        }

        if(trim($request->ndocAsunto) != '')
        {
        	$asunto = '%'.trim($request->ndocAsunto).'%';
        	$list_docs = $list_docs->where('tdocSubject','like',$asunto);
        }

        $list_docs = $list_docs->get();

		$view = view('tramite.consult_documentPage',['list_docs' => $list_docs]);

		return $view;
        
	}
} 