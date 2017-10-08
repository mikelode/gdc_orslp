<?php

namespace aidocs\Http\Controllers\Document;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use aidocs\Http\Requests;
use aidocs\Http\Controllers\Controller;
use aidocs\Models\Arcparticular;
use aidocs\Models\Dependencia;
use aidocs\Models\Historial;

class ArcparticularController extends Controller{

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function getDocumentsFromPrivateFiler(Request $request)
	{
		$documents = Historial::select('*')
				->join('tramDocumento','tdocId','=','thisDoc')
				->join('tramArchivador','tarcExp','=','tdocExp')
				->join('tramArchivadorParticular','tarpGexp','=','tarcExp')
				->where('thisDepS',Auth::user()->tusWorkDep)
				->where('thisDepT',Auth::user()->tusWorkDep)
				->where('tarpYear',$request->period)
				->orderby('tdocId','DESC')
				->get();

		$dependencys = Dependencia::select('*')
			->where('depActive',true)
			->get();

		$view = view('tramite.outbox_document',compact('documents'),compact('dependencys'));

		if($request->ajax())
		{
			$sections = $view->renderSections();
			return $sections['main-content'];
		}

		return $view;
	}
}
