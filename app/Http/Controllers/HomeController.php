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
                        ->whereRaw('DATEDIFF(tarcDatePres,now()) <= 4')
                        ->count();

        $xvencer = Archivador::select('*')
                        ->whereRaw('year(tarcDatePres) = year(now())')
                        ->whereRaw('DATEDIFF(tarcDatePres,now()) BETWEEN 5 AND 7')
                        ->count();

        $vencidos = Archivador::select('*')
                        ->whereRaw('year(tarcDatePres) = year(now())')
                        ->whereRaw('DATEDIFF(tarcDatePres,now()) > 7')
                        ->count();

        return view('tramite.home', compact('vigentes','xvencer','vencidos'));
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