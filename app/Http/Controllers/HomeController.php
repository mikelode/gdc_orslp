<?php

namespace aidocs\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use aidocs\Models\Historial;

class HomeController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications = Historial::select('*')
                    ->join('tramDocumento','tdocId','=','thisDoc')
                    ->where('thisDepT',Auth::user()->tusWorkDep)
                    ->where('thisFlagA',false)
                    ->where('thisFlagD',false)
                    ->whereRaw('year(tdocDate) = year(getdate())')
                    ->count();

        return view('tramite/home', compact('notifications'));
    }

    public function index_section(Request $request)
    {
        $view = view('tramite.homei');

        if($request->ajax())
        {
            $sections = $view->renderSections();
            return $sections['main-content'];
        }

        return $view;

    }

} 