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
use aidocs\Models\TipoDocumento;
use aidocs\Models\Dependencia;
use aidocs\Models\Vdestinatario;
use Illuminate\Support\Facades\DB;


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
		try{
			$exception = DB::transaction(function($request) use ($request){

				if($request->ndocProceso == "no"){
					$exp = new Archivador();
					$correlative_exp = Archivador::all()->count() + 1;
					//$code_exp = $this->makeUniqueCode('EXP',Carbon::now()->year,$correlative_exp);
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
					
					$exp->tarcExp = $code_exp;
					$exp->tarcDatepres = Carbon::now(); // Fecha de creacion del archivador
					$exp->tarcStatus = 'aperturado';
					$exp->created_at = Carbon::now();
					$exp->created_time_at = Carbon::now()->toTimeString();
					$exp->updated_at = Carbon::now();
					$exp->tarcSource = 'int';
					$exp->tarcYear = Carbon::now()->year;
					$exp->tarcAsoc = $request->ndocProy;
					$exp->tarcTitulo = $request->ndocTitulo;

					$exp->save();

					$expId = $exp->tarcId;
				}
				else if($request->ndocProceso == "si"){
					$docId = $request->ndocReferencia;
					$docRef = Document::find($docId); // expediente al que pertenece el documento registrado con referencia
					$expId = $docRef->tdocExp;
					$code_exp = $docRef->tdocExp1;
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
					$hist->thisDateTimeR = Carbon::now();

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
					$hist->thisDateTimeR = Carbon::now();//->format('d/m/Y h:i:s A');

					$hist->save();

					$hist_prev = Historial::select('*')
									->where('thisDoc',$request->ndocReferencia)
									->where('thisFlagR',true)
									->where('thisFlagD',true)
									->get();

					$hist_ref = Historial::find($hist_prev[0]->thisId);
					$hist_ref->thisIdRef = $hist->thisId;
					$hist_ref->save();
				}
			});

			return is_null($exception) ? "El Documento fue registrado con éxito" : $exception;

		}catch(Exception $e){
			return 'Error encontrado:'.$e->getMessage()."\n";
		}
	}

	public function storeEditDocument(Request $request)
	{
		try{
			$exception = DB::transaction(function($request) use ($request){

				$doc = Document::find($request->ndocId);

				$exp = Archivador::find($doc->tdocExp);
				$exp->updated_at = Carbon::now();
				$exp->tarcAsoc = $request->ndocProy;

				if($request->ndocProceso == "no"){	// falta especificar bien los datos a modificarse pues al variar se convierte en proceso o expediente
					$exp->tarcTitulo = $request->ndocTitulo;
				}

				$exp->save();

				$file = $request->file('ndocFile');
				$code_exp = $exp->tarcExp;
				$code_doc = $doc->tdocCod;
				
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

				if($request->ndocProceso == "si"){
					$doc->tdocAccion = $request->ndocAccion;
					$doc->tdocRef = $request->ndocReferencia;
				}

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
			});

			if(is_null($exception)){
                $msg = 'El cambio realizado en el documento se ha guardado con éxito';
                $idMsg = 200;
            }
            else{
                $msg = $exception;
                $idMsg = 500;
            }

		}catch(Exception $e){
			$msg = 'Error encontrado:' . $e . "\n";
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

	public function consultDocument(Request $request)
	{
		$list_docs = Document::select('tarcExp','tdocId','ttypDesc','tdocDni','tdocDate','tdocStatus','tdocSubject','tdocRegistro')
							->join('tramTipoDocumento','ttypDoc','=','tdocType')
							->join('tramArchivador','tarcExp','=','tdocExp1')
							->where('tarcYear',$request->period)
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

		if($request->posicion == 'anterior')
		{
			$beforeDocId = (int) $docId - 1;

			if($beforeDocId < $min)
				$beforeDocId = $min;

			do{
				$resultado = Document::find($beforeDocId);
				$beforeDocId--;
			}while(!$resultado);
		}
		else if($request->posicion == 'posterior')
		{
			$nextDocId = (int) $docId + 1;

			if($nextDocId > $max)
				$nextDocId = $max;

			do{
				$resultado = Document::find($nextDocId);
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

		//return $docElegido;
		return response()->json(array('docElegido' => $docElegido,'docReferencia' => $docReferencia));

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
		$documentos = Document::select(DB::raw('*,fnTramGetDestinatario(thisDepT) AS destino, fnTramGetRegistroRef(thisIdRef) AS ref, fnTramGetTimeAtention(tdocId,thisId,tdocRef) AS tiempo'))
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
                    ->where('tarcYear',$request->period);*/

        /* MySQL Version */
        $inbox = Document::select(DB::raw('*,fnTramDateDiff(tarcDatePres, NOW()) as plazo'))
                    ->join('tramArchivador','tarcId','=','tdocExp')
                    ->join('tramProyecto','tpyId','=','tdocProject')
                    ->join('tramTipoDocumento','ttypDoc','=','tdocType')
                    ->where('tarcYear',$request->period);

        if($campo == 'proyecto'){
        	$inbox = $inbox->where('tdocRef',null)
    					->where('tdocProject',$request->key);
        }
        
        if($campo == 'registro'){

        	$doc = Document::select('tdocId','tdocExp')
        				->join('tramArchivador','tarcId','=','tdocExp')
        				->where('tarcYear',$request->period)
        				->where('tdocRegistro',$request->key)
        				->get();

        	$inbox = $inbox->where('tdocRef',null)
						->where('tdocExp',$doc[0]->tdocExp);
        }

        if($campo == 'asunto'){
        	$doc = Document::select('tdocId','tdocExp')
        				->join('tramArchivador','tarcId','=','tdocExp')
        				->where('tarcYear',$request->period)
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
} 