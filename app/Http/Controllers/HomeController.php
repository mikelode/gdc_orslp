<?php

namespace aidocs\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use aidocs\Models\Historial;
use aidocs\Models\Document;
use aidocs\Models\Archivador;

class HomeController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        /* SQL Version 
        $vigentes = Archivador::select('*')
                        ->whereRaw('year(tarcDatePres) = year(getdate())')
                        ->whereRaw('DATEDIFF(day, tarcDatePres,GETDATE()) <= 4')
                        ->count();

        $xvencer = Archivador::select('*')
                        ->whereRaw('year(tarcDatePres) = year(getdate())')
                        ->whereRaw('DATEDIFF(day, tarcDatePres,GETDATE()) BETWEEN 5 AND 7')
                        ->count();

        $vencidos = Archivador::select('*')
                        ->whereRaw('year(tarcDatePres) = year(getdate())')
                        ->whereRaw('DATEDIFF(day, tarcDatePres,GETDATE()) > 7')
                        ->count();
        */

        /* MYSQL Version */

        $vigentes = Archivador::select('*')
                        ->whereRaw('year(tarcDatePres) = year(now())')
                        ->whereRaw('fnTramDateDiff(tarcDatePres, NOW()) <= 4')
                        ->count();

        $xvencer = Archivador::select('*')
                        ->whereRaw('year(tarcDatePres) = year(now())')
                        ->whereRaw('fnTramDateDiff(tarcDatePres, NOW()) BETWEEN 5 AND 7')
                        ->count();

        $vencidos = Archivador::select('*')
                        ->whereRaw('year(tarcDatePres) = year(now())')
                        ->whereRaw('fnTramDateDiff(tarcDatePres, NOW()) > 7')
                        ->count();

        $totaldocs = Archivador::select('*')
                        ->whereRaw('year(tarcDatePres) = year(now())')
                        ->count();

        $chartInfo = Document::select(\DB::raw('count(*) as docs, tdocDate'))
                        ->groupBy('tdocDate')
                        ->orderBy('tdocDate','desc')
                        ->take(10)
                        ->get();

        $data = '';

        foreach ($chartInfo as $key => $info) {
            //array_push($data, array('fecha' => $info->tdocDate, 'cant' => $info->docs));
            $data .= "{ fecha:'".$info->tdocDate."', value:".$info->docs."}, ";
        }
        $data = substr($data, 0, -2);

        return view('tramite.home', compact('vigentes','xvencer','vencidos','totaldocs','data'));
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