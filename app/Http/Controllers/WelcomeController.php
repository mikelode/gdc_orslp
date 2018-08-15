<?php

namespace aidocs\Http\Controllers;

use Exception;
use Carbon\Carbon;
use ErrorException;
use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use aidocs\Models\Archivador;
use aidocs\Models\Document;
use aidocs\Models\Historial;
use aidocs\Models\TipoDocumento;
use aidocs\Models\Dependencia;
use aidocs\Models\Proyecto;

class WelcomeController extends Controller {
    public function index()
    {
        return view('welcome');
    }

    public function storeExpedient($code_exp,$estado,$titulo,$fechaIngreso)
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
		$exp->tarcTitulo = $titulo;

		if($exp->save())
			return $exp->tarcId;
		else
			return 500;
	}

    public function readFiles(Request $request)
    {
    	try{
    		$exception = DB::transaction(function() use($request){

    			$files = $request->file('nctrlFiles');

		    	if($files){

		    		foreach ($files as $key => $file) {

		    			$data = explode('_', $file->getClientOriginalName());

		    			/*
		    			data[0] : Tipo documento
		    			data[1] : Nro del documento
		    			data[2] : Nro de registro
		    			¿Pertenece a un proceso documentario?
		    			data[3] : 1 -> SI, enlazar a su referencia ^ 0 -> NO, crear un nuevo proceso
		    			*/

		    			if($data[3] == '0.pdf'){
		    				$pref = 'EXP';
							$code_exp = '';
							$stmt = DB::select('call generar_codigo(?,?)', array($pref, $code_exp));
							$code_exp = $stmt[0]->codigo;

							$expId = $this->storeExpedient($code_exp, 'aperturado', 'Editar titulo', Carbon::now()->toDateString());
							
							if($expId == '500')
								throw new Exception("No se pudo registrar el proceso documentario");
		    			}
		    			else if($data[3] == '1.pdf'){
		    				$code_exp = 'EXG1899999';
		    				$filer = Archivador::where('tarcExp',$code_exp)->first();
		    				$expId = $filer->tarcId;
		    			}

		    			$pref = 'DOC';
						$code_doc = '';
						$stmt = DB::select('call generar_codigo(?,?)', array($pref, $code_doc));
						$code_doc = $stmt[0]->codigo;

		    			$doc = new Document();
		    			$doc->tdocCod = $code_doc;
						$doc->tdocExp = $expId; //$code_exp;
						$doc->tdocExp1 = $code_exp;
						//$doc->tdocDependencia = 1;
						$doc->tdocProject = 1;
						$doc->tdocType = trim($data[0]);
						$doc->tdocNumber = trim($data[1]);
						$doc->tdocRegistro = trim($data[2]);
						$doc->tdocDate = $request->ndateFiles; //Carbon::now()->toDateString();
						$doc->tdocSubject = 'Editar el asunto del documento';
						$doc->tdocStatus = 'registrado';
						$doc->tdocDetail = 'Editar de acuerdo al documento';
						$doc->tdocAccion = $data[3]=='1.pdf'?'respuesta':'ingreso';
						$doc->tdocRef = $data[3]=='1.pdf'?'0':null;
						$doc->tdocRegisterBy = Auth::user()->tusId;
						$doc->tdocRegisterAt = Carbon::now();

						$doc->tdocFileName = $code_doc.'.'.$file->getClientOriginalExtension();
						$doc->tdocFileExt = $file->getClientOriginalExtension();
						$doc->tdocPathFile = 'docscase/'.$code_exp;
						$doc->tdocFileMime = $file->getMimeType();

						$saveDoc = $doc->save();

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
						$hist->thisDateTimeR = $request->ndateFiles; //Carbon::now();

						$saveHist = $hist->save();

						if($saveDoc && $saveHist){
							$filename = '/'.$code_exp.'/'.$code_doc.'.'.$file->getClientOriginalExtension();
							\Storage::disk('local')->put($filename, \File::get($file));
						}

		    		}

		    	}

    		});

    		return is_null($exception) ? "El Documento fue registrado con éxito" : $exception;

    	}catch(Exception $e){
    		return $e;
    	}
    }
} 