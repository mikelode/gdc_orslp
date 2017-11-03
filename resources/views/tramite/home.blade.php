@extends('app')

@section('htmlheader_title')
    Menu Principal
@endsection

@section('main-content')

<section class="content-header">
    <h1>
        GESTION DOCUMENTARIAAAAA
        <small>@yield('contentheader_description')</small>
    </h1>
</section>

<section class="content">
    <div class="container">
        <div class="row" style="padding-bottom: 20px;">
            <div class="col-lg-12">{{ Inspiring::quote() }}</div>
        </div>
        <div class="row" style="padding-left: 1%">
            @if(Auth::user()->can(1))
            <div class="col-md-3" style="text-align: center;">
                <a href="javascript:void(0)" onclick="change_menu_to('doc/register')">
                    <img src="{{ asset('img/registro.png') }}" width="100" height="100">
                    <p>Registrar Documento</p>
                </a>
            </div>
            @endif
            @if(Auth::user()->can(2))
            <div class="col-md-3" style="text-align: center;">
                <a href="javascript:void(0)" onclick="change_menu_to('doc/menu')">
                    <img src="{{ asset('img/email_inbox.png') }}" width="100" height="100">
                    <p>Bandeja de Entrada</p>
                </a>
            </div>
            @endif
            @if(Auth::user()->can(3))
            <div class="col-md-3" style="text-align: center;">
                <a href="javascript:void(0)" onclick="change_menu_to('doc/outbox')">
                    <img src="{{ asset('img/mail_send.png') }}" width="100" height="100">
                    <p>Bandeja de Salida</p>
                </a>
            </div>
            @endif
        </div>
        <div class="row" style="padding-bottom: 20px"></div>
        <div class="row" style="padding-left: 1%">
            @if(Auth::user()->can(4))
            <div class="col-md-3" style="text-align: center;">
                <a href="javascript:void(0)" onclick="change_menu_to('doc/consult')">
                    <img src="{{ asset('img/search.png') }}" width="100" height="100">
                    <p>Consulta y Seguimiento</p>
                </a>
            </div>
            @endif
            @if(Auth::user()->can(6))
            <div class="col-md-3" style="text-align: center;">
                @if(Auth::user()->tusTypeUser == 'admin')
                <a href="javascript:void(0)" onclick="change_menu_to('settings')">
                    <img src="{{ asset('img/configuracion.png') }}" width="100" height="100">
                    <p>Configuración</p>
                </a>
                @endif
            </div>
            @endif
        </div>
        <div class="row" style="padding-bottom: 20px"></div>
        <div class="row" style="padding-left: 100px"></div>
    </div>
</section>

@endsection