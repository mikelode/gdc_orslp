<?php

namespace aidocs\Http\Controllers\Document;

use Exception;
use aidocs\Models\Proyecto;
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
use aidocs\Models\Persona;
use aidocs\Models\TipoDocumento;
use aidocs\Models\Dependencia;
use aidocs\Models\Vdestinatario;
use Illuminate\Support\Facades\DB;
use Session;


class DocumentController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index(Request $request)
	{
		$tipos = TipoDocumento::where('ttypShow',true)->get();
		$assoc = Proyecto::select('tpyId','tpyName')->get();
		$dep = Dependencia::all();
		$dest = Vdestinatario::all();
		
		$document = Document::select('*')
						->join('tramArchivador','tarcId','=','tdocExp')
						->join('tramTipoDocumento','ttypDoc','=','tdocType')
						->whereRaw('year(tdocDate) = '.Session::get('periodo'))
						->orderby('tdocId','DESC')
						->take(1)
						->get();

		$view = view('tramite.register_document',compact('tipos','assoc','dep','dest'));

		if($request->ajax())
		{
			$sections = $view->renderSections();
			//return $sections['main-content'];
			return response()->json(array('view' => $sections['main-content'],'lastdoc' => $document));
		}
		//return $view;
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

	public function storeExpedient($code_exp,$estado,$proy,$titulo,$fechaIngreso)
	{
		$exp = new Archivador();
		$exp->tarcExp = $code_exp;
		$exp->tarcDatepres = $fechaIngreso; //Carbon::now(); // Fecha de creacion del archivador
		$exp->tarcStatus = $estado;
		$exp->created_at = Carbon::now();
		$exp->created_time_at = Carbon::now()->toTimeString();
		$exp->updated_at = Carbon::now();
		$exp->tarcSource = 'int';
		$exp->tarcYear = Carbon::now()->year;
		$exp->tarcAsoc = $proy;
		$exp->tarcTitulo = $titulo;

		if($exp->save())
			return $exp->tarcId;
		else
			return 500;
	}

	public function storeDocument(StoreDocumentRequest $request)
	{
		try{
			$exception = DB::transaction(function($request) use ($request){

				if($request->ndocProceso == "no"){ // ¿Does it belong to a documentary process?
					
					$pref = 'EXP';
					$code_exp = '';
					/* SQL Version
					$stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON; EXEC generar_codigo ?,?');
					$stmt->bindParam(1,$pref);
					$stmt->bindParam(2,$code_exp,\PDO::PARAM_STR | \PDO::PARAM_INPUT_OUTPUT, 10);
					$stmt->execute();
					unset($stmt);*/

					/* MySQL Version */
					$stmt = DB::select('call generar_codigo(?,?)', array($pref, $code_exp));
					$code_exp = $stmt[0]->codigo;

					$expId = $this->storeExpedient($code_exp, 'aperturado', $request->ndocProy, $request->ndocTitulo, $request->ndocFecha);
					
					if($expId == '500')
						throw new Exception("No se pudo registrar el proceso documentario");
						
				}
				else if($request->ndocProceso == "si"){
					$docId = $request->ndocReferencia;
					$docRef = Document::select('*')
								->join('tramHistorial','thisDoc','=','tdocId')
								->where('tdocId',$docId)
								->get(); // expediente al que pertenece el documento registrado con referencia
					$expId = $docRef[0]->tdocExp;
					$code_exp = $docRef[0]->tdocExp1;

					if($docRef[0]->tdocStatus == 'registrado' || $docRef[0]->thisFlagD == false)
						throw new Exception("El documento al que hace referencia está NO DERIVADO, ubíquelo y registre su envío");
					if($docRef[0]->tdocStatus == 'archivado' || $docRef[0]->thisFlagF == true)
						throw new Exception("El documento al que hace referencia esta ARCHIVADO, no puede referenciarlo");
				}

				$file = $request->file('ndocFile');

				$doc = new Document();
				//$code_doc = $this->makeUniqueCode('DOC',Carbon::now()->year,$this->numberDocument());
				$pref = 'DOC';
				$code_doc = '';

				/* SQL Version
				$stmt = DB::connection('sqlsrv')->getPdo()->prepare('SET NOCOUNT ON; EXEC generar_codigo ?,?');
				$stmt->bindParam(1,$pref);
				$stmt->bindParam(2,$code_doc,\PDO::PARAM_STR | \PDO::PARAM_INPUT_OUTPUT, 10);
				$stmt->execute();
				unset($stmt);*/

				/* MySQL Version */
				$stmt = DB::select('call generar_codigo(?,?)', array($pref, $code_doc));
				$code_doc = $stmt[0]->codigo;
				
				$doc->tdocCod = $code_doc;
				$doc->tdocExp = $expId; //$code_exp;
				$doc->tdocExp1 = $code_exp;
				$doc->tdocDependencia = $request->ndocDepend;
				$doc->tdocProject = $request->ndocProy;
				$doc->tdocSender = $request->ndocSender;
				$doc->tdocDni = $request->ndocSenderId;
				$doc->tdocJobSender = $request->ndocJob;
				$doc->tdocType = $request->ndocTipo;
				$doc->tdocNumber = $request->ndocNro;
				$doc->tdocRegistro = $request->ndocReg;
				$doc->tdocDate = $request->ndocFecha;
				$doc->tdocFolio = $request->ndocFolio;
				$doc->tdocSubject = $request->ndocAsunto;
				$doc->tdocStatus = 'registrado';
				$doc->tdocDetail = $request->ndocDetalle;
				$doc->tdocRegisterBy = Auth::user()->tusId;

				if($request->ndocProceso == "si"){
					$doc->tdocAccion = $request->ndocAccion;
					$doc->tdocRef = $request->ndocReferencia;
				}else if($request->ndocProceso == "no"){
					$doc->tdocAccion = "ingreso";
				}

				if($file){
					$doc->tdocFileName = $code_doc.'.'.$file->getClientOriginalExtension();
					$doc->tdocFileExt = $file->getClientOriginalExtension();
					$doc->tdocPathFile = 'docscase/'.$code_exp;
					$doc->tdocFileMime = $file->getMimeType();

					$filename = '/'.$code_exp.'/'.$code_doc.'.'.$file->getClientOriginalExtension();
					\Storage::disk('local')->put($filename, \File::get($file));
				}

				$doc->save();

				/*$pexp = new Arcparticular();

				$correlative_pexp = Arcparticular::where('tarpDep',Auth::user()->tusWorkDep)->count() + 1;
				$code_pexp = $this->makeUniqueCode('PXP',Carbon::now()->year,$correlative_pexp);

				$pexp->tarpPexp = $code_pexp;
				$pexp->tarpGexp = $code_exp;
				$pexp->tarpDep = Auth::user()->tusWorkDep;
				$pexp->tarpDoc = $code_doc;
				$pexp->created_at = Carbon::now()->toDateString();
				$pexp->tarpYear = Carbon::now()->year;

				$pexp->save();*/

				if($request->ndocProceso == "no"){

					$hist = new Historial();

					$hist->thisExp = $code_exp;
					$hist->thisDoc = $doc->tdocId; //$code_doc;
					$hist->thisDoc1 = $code_doc;
					$hist->thisDepS = Auth::user()->tusId;
					$hist->thisDepT = Auth::user()->tusId;
					$hist->thisFlagR = true;
					$hist->thisFlagA = false;
					$hist->thisFlagD = false;
					$hist->rec_date_at = Carbon::now()->toDateString();
					$hist->rec_time_at = Carbon::now()->toTimeString();
					/* SQL Version: $hist->thisDateTimeR = Carbon::now()->format('d/m/Y h:i:s A'); */
					/* MySQL Version */
					$hist->thisDateTimeR = $request->ndocFecha; //Carbon::now();

					$hist->save();

				}
				else if($request->ndocProceso == "si"){

					$hist = new Historial();

					$hist->thisExp = $code_exp;
					$hist->thisDoc = $doc->tdocId; //$code_doc;
					$hist->thisDoc1 = $code_doc;
					$hist->thisDepS = Auth::user()->tusId;
					$hist->thisDepT = Auth::user()->tusId;
					$hist->thisFlagR = true;
					$hist->thisFlagA = false;
					$hist->thisFlagD = false;
					$hist->rec_date_at = Carbon::now()->toDateString();
					$hist->rec_time_at = Carbon::now()->toTimeString();
					$hist->thisDateTimeR = $request->ndocFecha; //Carbon::now();//->format('d/m/Y h:i:s A');

					$hist->save();

					if($request->ndocAccion == 'adjuntado'){
						// Nothing to do
					}
					else{

						$hist_prev = Historial::select('*')
									->where('thisDoc',$request->ndocReferencia)
									->where('thisFlagR',true)
									->where('thisFlagD',true)
									->get();

						$hist_ref = Historial::find($hist_prev[0]->thisId);
						$hist_ref->thisIdRef = $hist->thisId;
						$hist_ref->save();
					}
				}
			});

			return is_null($exception) ? "El Documento fue registrado con éxito" : $exception;

		}catch(Exception $e){
			return 'Error encontrado: '.$e->getMessage()."\n";
		}
	}

	public function storeEditDocument(Request $request)
	{
		try{
			$exception = DB::transaction(function() use ($request){
				$doc = Document::find($request->ndocId);

				$code_exp = $doc->tdocExp1;
				$code_doc = $doc->tdocCod;
				$file = $request->file('ndocFile');
				$moveDocument = false;

				/* REFERENCE DATA SECTION */

				$belongProcess = $request->ndocProceso;
				if($belongProcess == 'no'){
					$filer = Archivador::find($doc->tdocExp);
					$filer->tarcTitulo = $request->ndocTitulo;
					$filer->tarcAsoc = $request->ndocProy;
					$filer->save();

					$code_exp = $filer->tarcExp;
				}
				else if($belongProcess == 'si'){
					$currentDocIdRef = $doc->tdocRef;
					$doc->tdocAccion = $request->ndocAccion;

					if($currentDocIdRef != $request->ndocReferencia){

						$histRef = Document::find($request->ndocReferencia)->historial;
						$docRef = Document::find($request->ndocReferencia);

						if($histRef->thisFlagD == false)
							throw new Exception("El documento al que hace referencia está NO DERIVADO, ubíquelo y registre su envío");

						if($histRef->thisFlagF == true)
							throw new Exception("El documento al que hace referencia esta ARCHIVADO, no puede referenciarlo");

						if($doc->tdocAccion!='adjuntado' && $histRef->thisIdRef != null){
							throw new Exception("No se puede referenciar al registro elegido, puesto que dicho registro ya tiene otro documento que le hace referencia");
						}

						if($currentDocIdRef != '0'){ /* si hace ref a 0 indica que se registro por el método rápido */
							$currentHistRef = Document::find($currentDocIdRef)->historial;
							$currentHistRef->thisIdRef = null;
							$currentHistRef->save();
						}

						$doc->tdocRef = $request->ndocReferencia;
						$doc->tdocExp = $docRef->tdocExp;
						$doc->tdocExp1 = $docRef->tdocExp1;

						$histDoc = Document::find($request->ndocId)->historial;
						$histDoc->thisExp = $docRef->tdocExp1;
						$histDoc->save();

						if($doc->tdocAccion != 'adjuntado'){
							$histRef->thisIdRef = $histDoc->thisId;
							$histRef->save();
						}

						/* si se hace referencia a un documento de otro expediente se debe mover el doc al nuevo archivero */
						$moveDocument = true;
						$current_location = substr($doc->tdocPathFile, 9).'/'.$doc->tdocFileName;
						$new_location = $docRef->tdocExp1.'/'.$doc->tdocFileName;

						$doc->tdocPathFile = 'docscase/'.$docRef->tdocExp1;

						$code_exp = $docRef->tdocExp1;
					}
					else{
						if($currentDocIdRef == 0){
							throw new Exception("Debe elegir un documento de referencia.\nCodigo 0x0000");
						}
					}

				}
				else{
					throw new Exception("No se puede identificar si el documento pertenece o no a un proceso documentario");
				}

				/* DOCUMENT DATA SECTION */

				$doc->tdocDependencia = $request->ndocDepend;
				$doc->tdocProject = $request->ndocProy;
				$doc->tdocSender = $request->ndocSender;
				$doc->tdocDni = $request->ndocSenderId;
				$doc->tdocJobSender = $request->ndocJob;
				$doc->tdocType = $request->ndocTipo;
				$doc->tdocNumber = $request->ndocNro;
				$doc->tdocRegistro = $request->ndocReg;
				$doc->tdocDate = $request->ndocFecha;
				$doc->tdocFolio = $request->ndocFolio;
				$doc->tdocSubject = $request->ndocAsunto;
				$doc->tdocDetail = $request->ndocDetalle;

				if($file){ // entra aqui si esta cambiando de archivo

					if($doc->tdocFileName != null){
						$file_actual = public_path($doc->tdocPathFile).'/'.$doc->tdocFileName;
						if(\File::exists($file_actual))
							\File::delete($file_actual);
					}

					$filename = '/'.$code_exp.'/'.$code_doc.'.'.$file->getClientOriginalExtension();
					\Storage::disk('local')->put($filename, \File::get($file));

					$doc->tdocFileName = $code_doc.'.'.$file->getClientOriginalExtension();
					$doc->tdocFileExt = $file->getClientOriginalExtension();
					$doc->tdocPathFile = 'docscase/'.$code_exp;
					$doc->tdocFileMime = $file->getMimeType();
				}

				$doc->save();

				if($moveDocument){
					\Storage::move($current_location,$new_location);
				}

			});

			if(is_null($exception)){
                $msg = 'El cambio realizado en el documento se ha guardado con éxito';
                $idMsg = 200;
            }
            else{
                throw new Exception($exception);
            }

		}catch(Exception $e){
			$msg = 'Error encontrado:' . $e->getMessage() . ' -- ' . $e->getFile() . ' - ' . $e->getLine() . " \n";
            $idMsg = 500;
		}

		return response()->json(compact('msg','idMsg'));
	}

	public function storeEditDocument1(Request $request)
	{
		try{
			$exception = DB::transaction(function($request) use ($request){

				$doc = Document::find($request->ndocId);

				/* Verificamos si se esta cambiando de proceso documentario */
				$actualRef = $doc->tdocRef;
				$nuevaRef = $request->ndocReferencia;

				if($actualRef == $nuevaRef){

					$exp = Archivador::find($doc->tdocExp);
					$exp->updated_at = Carbon::now();
					$exp->tarcAsoc = $request->ndocProy;

					if($request->ndocProceso == "no"){	// falta especificar bien los datos a modificarse pues al variar se convierte en proceso o expediente
						$exp->tarcTitulo = $request->ndocTitulo;
					}

					$exp->save();

				}
				else{
					$newDocRef = Document::find($nuevaRef);
					$exp = Archivador::find($newDocRef->tdocExp);
				}

				$file = $request->file('ndocFile');
				$code_exp = $exp->tarcExp;
				$code_doc = $doc->tdocCod;

				if($request->ndocProceso == "si"){ /* actualizamos las refencias en la tabla documento e historial */
					
					if($actualRef != $nuevaRef){
						
						$histNewRef = Document::find($nuevaRef)->historial;

						if($doc->tdocAccion != 'adjuntado' && $histNewRef->thisIdRef != null){
							throw new Exception("No se puede referenciar al registro elegido, puesto que dicho registro ya tiene otro documento que le hace referencia");
						}
						else if($doc->tdocAccion != 'adjuntado' && $histNewRef->thisIdRef == null){
							
							$doc->tdocRef = $request->ndocReferencia; // datos del nuevo doc al que hace referencia
							$docPrevHist = Document::find($actualRef)->historial; // actualizamos datos del doc anterior ref
							$docPrevHist->thisIdRef = null;
							$docPrevHist->save();

							if($histNewRef->thisFlagD == false)
								throw new Exception("El documento al que hace referencia debe estar derivado, por favor ubíquelo y registre su derivación");

							$histId = Document::find($doc->tdocId)->historial;
							$histId->thisExp = $code_exp;
							$histId->save();

							$histNewRef->thisIdRef = $histId[0]->thisId;
							$histNewRef->save();

							/* si se cambia a una refernecia de otro expediente se debe mover el doc al nuevo archivero */

							$current_location = substr($doc->tdocPathFile, 9).'/'.$doc->tdocFileName;
							$new_location = $code_exp.'/'.$doc->tdocFileName;
							\Storage::move($current_location,$new_location);

							$doc->tdocPathFile = 'docscase/'.$code_exp;
						}
						else if($doc->tdocAccion == 'adjuntado' && $histNewRef->thisIdRef == null){
							if($newDocRef->tdocExp == $doc->tdocExp)
								$doc->tdocRef = $request->ndocReferencia; // datos del nuevo doc al que hace referencia
							else
								throw new Exception("El documento adjuntado debe hacer referencia a un documento que pertenezca al mismo proceso documentario");
								
						}
					}
					
					$doc->tdocAccion = $request->ndocAccion;
				}

				$doc->tdocExp = $exp->tarcId;
				$doc->tdocExp1 = $code_exp;
				$doc->tdocDependencia = $request->ndocDepend;
				$doc->tdocProject = $request->ndocProy;
				$doc->tdocSender = $request->ndocSender;
				$doc->tdocDni = $request->ndocSenderId;
				$doc->tdocJobSender = $request->ndocJob;
				$doc->tdocType = $request->ndocTipo;
				$doc->tdocNumber = $request->ndocNro;
				$doc->tdocRegistro = $request->ndocReg;
				$doc->tdocDate = $request->ndocFecha;
				$doc->tdocFolio = $request->ndocFolio;
				$doc->tdocSubject = $request->ndocAsunto;
				$doc->tdocDetail = $request->ndocDetalle;

				if($file){ // entra aqui si esta cambiando de archivo

					if($doc->tdocFileName != null){
						$file_actual = public_path($doc->tdocPathFile).'/'.$doc->tdocFileName;
						if(\File::exists($file_actual))
							\File::delete($file_actual);
					}

					$filename = '/'.$code_exp.'/'.$code_doc.'.'.$file->getClientOriginalExtension();
					\Storage::disk('local')->put($filename, \File::get($file));

					$doc->tdocFileName = $code_doc.'.'.$file->getClientOriginalExtension();
					$doc->tdocFileExt = $file->getClientOriginalExtension();
					$doc->tdocPathFile = 'docscase/'.$code_exp;
					$doc->tdocFileMime = $file->getMimeType();
				}

				$doc->save();

				$filer = Archivador::find($doc->tdocExp);
				$filer->tarcTitulo = $request->ndocTitulo;
				$filer->save();

			});

			if(is_null($exception)){
                $msg = 'El cambio realizado en el documento se ha guardado con éxito';
                $idMsg = 200;
            }
            else{
                throw new Exception($exception);
            }

		}catch(Exception $e){
			$msg = 'Error encontrado:' . $e->getMessage() . "\n";
            $idMsg = 500;
		}

		return response()->json(compact('msg','idMsg'));
	}

	public function detailDocument($idDoc, Request $request)
	{
		$document = Document::find($idDoc);// where('tdocId',$idDoc)->get();
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

	public function reporteDocumentario(Request $request)
	{
		$proy = Proyecto::all();
		$tipos = TipoDocumento::all();
		$pers = Persona::all();
		$list_docs = Document::select('*')
						->join('tramTipoDocumento','ttypDoc','=','tdocType')
						->join('tramArchivador','tarcId','=','tdocExp')
						->join('tramProyecto','tpyId','=','tdocProject')
						->where('tarcYear',Session::get('periodo'))
						->orderby('tarcDatePres','DESC')
						->get();

		$view = view('tramite.report_document',compact('proy','tipos','pers','list_docs'));

		if($request->ajax())
		{
			$sections = $view->renderSections();
			return $sections['main-content'];
		}
		return $view;
	}

	public function procesarReporteDoc(Request $request)
	{
		$list_docs = Document::select('*')
					->join('tramTipoDocumento','ttypDoc','=','tdocType')
					->join('tramArchivador','tarcId','=','tdocExp')
					->join('tramProyecto','tpyId','=','tdocProject')
					->whereRaw('year(tdocDate) = '.Session::get('periodo'));

		if($request->ndocPy != 'all'){
			$list_docs = $list_docs->where('tdocProject',$request->ndocPy);
		}

		if($request->ndocTipo != 'all'){
			$list_docs = $list_docs->where('tdocType',$request->ndocTipo);
		}

		if($request->ndocPers != 'all'){
			$list_docs = $list_docs->where('tdocDni', $request->ndocPers); 
			// DNI se almacena el ID de la tabla persona que guarda a los trabajadores del área de supervision
		}

		$list_docs = $list_docs->get();

		$view = view('tramite.report_documentPage',compact('list_docs'));

		return $view;
	}

	public function consultDocument(Request $request)
	{
		$list_docs = Document::select('tarcExp','tdocId','ttypDesc','tdocDni','tdocDate','tdocStatus','tdocSubject','tdocRegistro')
							->join('tramTipoDocumento','ttypDoc','=','tdocType')
							->join('tramArchivador','tarcExp','=','tdocExp1')
							->where('tarcYear',Session::get('periodo'))
							->orderby('tarcDatePres','DESC')
							->get();
		$asoc = Proyecto::select('tpyId','tpyName')->get();
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
		/* SQL VERSION
		$list_docs = Document::select(DB::raw('tdocExp, tdocId, dbo.fnTramGetDscFromId(\'tramTipoDocumento\',tdocType) as docTipo, tdocDni, tdocDate, tdocStatus, tdocSubject, tdocRegistro'))
							->join('tramArchivador','tarcExp','=','tdocExp')
							->where('tarcYear',$year);*/
		/* MySQL Version */
		$list_docs = Document::select(DB::raw('tdocExp, tdocId, fnTramGetDscFromId(\'tramTipoDocumento\',tdocType) as docTipo, tdocDni, tdocDate, tdocStatus, tdocSubject, tdocRegistro'))
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

	public function showDocumentData(Request $request)
	{
		$min = Document::min('tdocId');
		$max = Document::max('tdocId');

		$docId = $request->docActual;
		$newDocId = 0;

		if($request->posicion == 'first'){

			$minDocId = Document::select(DB::raw('min(tdocId) as minId'))
							->whereRaw('year(tdocDate) = '.Session::get('periodo'))
							->first();

			$resultado = Document::where('tdocId',$minDocId->minId)
							->whereRaw('year(tdocDate) = '.Session::get('periodo'))
							->first();

		}
		else if($request->posicion == 'last'){

			$maxDocId = Document::select(DB::raw('max(tdocId) as maxId'))
							->whereRaw('year(tdocDate) = '.Session::get('periodo'))
							->first();

			$resultado = Document::where('tdocId',$maxDocId->maxId)
							->whereRaw('year(tdocDate) = '.Session::get('periodo'))
							->first();

		}
		else if($request->posicion == 'anterior')
		{
			$beforeDocId = (int) $docId - 1;

			if($beforeDocId < $min)
				$beforeDocId = $min;

			do{
				$resultado = Document::where('tdocId',$beforeDocId)
								->whereRaw('year(tdocDate) = '.Session::get('periodo'))
								->first();
				$beforeDocId--;
			}while(!$resultado);
		}
		else if($request->posicion == 'posterior')
		{
			$nextDocId = (int) $docId + 1;

			if($nextDocId > $max)
				$nextDocId = $max;

			do{
				$resultado = Document::where('tdocId',$nextDocId)
								->whereRaw('year(tdocDate) = '.Session::get('periodo'))
								->first();
				$nextDocId++;
			}while(!$resultado);
		}
        else
        {
            $docId = $request->posicion;
            $resultado = Document::find($docId);
        }

		$newDocId = $resultado->tdocId;

		$docElegido = Document::select('*')
						->join('tramArchivador','tarcId','=','tdocExp')
						->join('tramTipoDocumento','ttypDoc','=','tdocType')
						->where('tdocId',$newDocId)
						->orderby('tdocId','DESC')
						->get();

		if($docElegido[0]->tdocRef != null){
			$docReferencia = Document::find($docElegido[0]->tdocRef);
		}
		else{
			$docReferencia = collect(['sin_referencia']);
		}

		/* Agregamos datos del historial */

		$docHistorial = Historial::select(DB::raw('*,fnTramGetDestinatario(thisDepT) AS destino, fnTramGetRegistroRef(thisIdRef) AS ref'))
							->where('thisDoc',$docElegido[0]->tdocId)
							->get();

		//return $docElegido;
		return response()->json(array('docElegido' => $docElegido,'docReferencia' => $docReferencia,'docHistorial' => $docHistorial));

		/*while ($Fila = $docElegido -> fetch_row())
		{
			$docFecha = Carbon::createFromFormat('Y-m-d H:i:s',$Fila[8])->toDateString();

			if($Fila[15] != '')
				$docFechaRecepcion = Carbon::createFromFormat('Y-m-d H:i:s',$Fila[15])->format('Y-m-d H:i:s');
			else
				$docFechaRecepcion = '';

			$respuesta .= str_replace('PK',$Fila[9],$Fila[0])."|".$Fila[1]."|".$Fila[2]."|".$Fila[3]."|".$Fila[4]."|".$Fila[5]."|".$Fila[6]."|".$Fila[7]."|".$docFecha."|".$Fila[9]."|".$Fila[10]."|".$Fila[11]."|".$Fila[12]."|".$Fila[13]."|".$Fila[14]."|".$docFechaRecepcion."|".$Fila[16]."|".$Fila[17]."$";
		}
		$respuesta .= "%";

		$Sentencia = "select a.nCodigo, a.cUsuario, b.ofiId, b.ofiCod, b.ofiDesc from musuarios a
						inner join munioficina b on b.ofiId = a.ofiId
						where nCodigo = '" . $_SESSION['codigo'] . "'";
		$OficinaUsuario = $Conexion->query($Sentencia);
		while($Fila = $OficinaUsuario->fetch_row())
			$respuesta .= $Fila[0]."|".$Fila[1]."|".$Fila[2]."|".$Fila[3]."|".$Fila[4]."$";
		$respuesta .= "%";

		$Conexion -> close();*/
	}

	public function getExpediente(Request $request){
		$claves = explode('-',$request->claves);
		$docId = $claves[0];
		$expId = $claves[1];

		/* SQL Version
		$documentos = Document::select(DB::raw('*,dbo.fnTramGetDestinatario(thisDepT) AS destino, dbo.fnTramGetRegistroRef(thisIdRef) AS ref, dbo.fnTramGetTimeAtention(tdocId,thisId,tdocRef) AS tiempo'))
					->join('tramHistorial','thisDoc','=','tdocId')
					->join('tramTipoDocumento','ttypDoc','=','tdocType')
					->join('tramProyecto','tpyId','=','tdocProject')
					->where('tdocExp',$expId)
					->get();*/

		/* MySQL Version */
		$documentos = Document::select(DB::raw('*,fnTramGetDestinatario(thisDepT) AS destino, fnTramGetRegistroRef(thisIdRef) AS ref, fnTramGetTimeAtention(tdocId,thisId,tdocRef) AS tiempo, fnDescDependencia(tdocDependencia,2) as dep'))
					->join('tramHistorial','thisDoc','=','tdocId')
					->join('tramTipoDocumento','ttypDoc','=','tdocType')
					->join('tramProyecto','tpyId','=','tdocProject')
					->where('tdocExp',$expId)
					->get();

		$view = view('tramite.tabla_proceso_documentario', compact('documentos'));

		return $view;
	}

	public function filtrarDocumento(Request $request)
    {
        $campo = $request->campo;
        /* SQL Version 
        $inbox = Document::select(DB::raw('*,DATEDIFF(day,tdocDate,GETDATE()) as plazo'))
                    ->join('tramArchivador','tarcId','=','tdocExp')
                    ->join('tramProyecto','tpyId','=','tdocProject')
                    ->join('tramTipoDocumento','ttypDoc','=','tdocType')
                    ->where('tarcYear',Session::get('periodo'));*/

		// plazo se refiere al tiempo transcurrido de atención

        /* MySQL Version */
        $inbox = Document::select(DB::raw('*,fnTramDateDiff(tarcDatePres, NOW()) as plazo, fnDescDependencia(tdocDependencia,2) as dep'))
                    ->join('tramArchivador','tarcId','=','tdocExp')
                    ->join('tramProyecto','tpyId','=','tdocProject')
                    ->join('tramTipoDocumento','ttypDoc','=','tdocType')
					->where('tarcYear',Session::get('periodo'));

        if($campo == 'proyecto'){
        	$inbox = $inbox->where('tdocRef',null)
    					->where('tdocProject',$request->key);
        }
        
        if($campo == 'registro'){

        	$doc = Document::select('tdocId','tdocExp')
        				->join('tramArchivador','tarcId','=','tdocExp')
        				->where('tarcYear',Session::get('periodo'))
        				->where('tdocRegistro',$request->key)
						->get();

        	$inbox = $inbox->where('tdocRef',null)
						->where('tdocExp',$doc[0]->tdocExp);
        }

        if($campo == 'asunto'){
        	$doc = Document::select('tdocId','tdocExp')
        				->join('tramArchivador','tarcId','=','tdocExp')
        				->where('tarcYear',Session::get('periodo'))
        				->where('tdocSubject','like','%'.trim($request->key).'%')
        				->get();

        	$idExps = $doc->pluck('tdocExp');
        	$idExps = $idExps->toArray();

        	$inbox = $inbox->where('tdocRef',null)
						->whereIn('tdocExp',$idExps);
        }

		$inbox = $inbox->orderby('tdocId','DESC')->get();

        $view = view('tramite.tabla_bandeja_documentos',compact('inbox'));

        return $view;
    }

    public function deleteDocument(Request $request)
    {
    	try{

    		$exception = DB::transaction(function() use ($request){

    			$doc = Document::find($request->ndocId);

    			$docInExp = Document::select('*')
    						->where('tdocExp', $doc->tdocExp)
    						->count();

    			if($doc->tdocRef == null && $docInExp == 1){

					$exp = Archivador::find($doc->tdocExp);
					$doc->delete();
					$exp->delete();

    			}
    			else if($docInExp > 1){
    				$docId = $doc->tdocId;
    				
    				$hist = Historial::select('*')->where('thisDoc',$docId)->get();
					$histId = $hist[0]->thisId;

    				if(count($hist)>1) throw new Exception("No se puede eliminar, existen mas de 1 registro al que hace referencia");
    				
    				$histPrev = Historial::select('*')->where('thisIdRef',$histId)->get();

    				if(count($histPrev)>0){
	    				$histPrev = Historial::find($histPrev[0]->thisId);
	    				/* se comenta por el doc mantiene su estado derivado, solo se anula al que hacia ref 
	    				$histPrev->thisDepT = Auth::user()->tusId;
	    				$histPrev->thisFlagD = false;
	    				$histPrev->thisDateTimeD = null;
	    				$histPrev->thisDscD = null;*/
	    				$histPrev->thisIdRef = null;
	    				$histPrev->save();
	    			}

    				/*$docPrev = Document::find($histPrev->thisDoc);
    				$docPrev->tdocStatus = 'registrado';
    				$docPrev->save();*/
    				$doc->delete(); // en cascada se elimina su registro en historial que le corresponde
    			}
    		});

    		if(is_null($exception)){
    			$msg = "Documento eliminado";
    			$msgId = 200;
    		}
    		else{
    			$msg = "No se pudo eliminar el documento seleccionado";
    			$msgId = 500;
    		}

    	}catch(Exception $e){
    		$msg = 'Error al eliminar el documento: '.$e->getMessage();
    		$msgId = 500;
    	}

    	return response()->json(compact('msg','msgId'));
    }

    public function retriveFullDataDocument(Request $request)
    {
    	if($request->campo == 'id'){
    		$documento = Document::select(DB::raw('*,fnDescDependencia(tdocDependencia,2) as dependencia, fnTramGetDscFromId(\'tramProyecto\',tdocProject) as proyecto, fnTramGetDscFromId(\'tramTipoDocumento\',tdocType) as tipodoc'))
    						->where('tdocId',$request->doc)
    						->get();
    	}

    	else if($request->campo == 'reg'){
    		$documento = Document::where('tdocRegistro',$request->reg)->first();
    	}

    	$expediente = Archivador::select(DB::raw('*,fnTramGetDscFromId(\'tramProyecto\',tarcAsoc) as proyecto'))
    					->where('tarcId',$documento[0]->tdocExp)
    					->get();

    	$carpetaExp = Document::select('*')
    					->join('tramHistorial','thisDoc','=','tdocId')
    					->where('tdocExp',$documento[0]->tdocExp)
    					->get();

    	$historialDoc = Historial::select(DB::raw('*, fnTramGetDestinatario(thisDepT) as destinatario'))
    					->where('thisDoc',$documento[0]->tdocId)
    					->get();

    	return view('setting.tabla_data_document',compact('documento','expediente','historialDoc','carpetaExp'));

    }
} 