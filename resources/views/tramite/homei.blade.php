@section('htmlheader_title')
    Menu Principal
@endsection

@section('main-content')

<section class="content-header">
    <h1>
        GESTION DOCUMENTARIA
        <small>@yield('contentheader_description')</small>
    </h1>
</section>

<section class="content">
    <div class="container">
        <div class="row" style="padding-bottom: 20px;">
            <div class="col-lg-12">{{ Inspiring::quote() }}</div>
        </div>
        <div class="row" style="padding-left: 1%">
            @if(Auth::user()->can(5))
            <div class="col-md-3" style="text-align: center;">
                <a href="javascript:void(0)" onclick="change_menu_register('doc/register')">
                    <img src="{{ asset('img/registro.png') }}" width="100" height="100">
                    <p>Gestión de Documentos</p>
                </a>
            </div>
            @endif
            @if(Auth::user()->can(7))
            <div class="col-md-3" style="text-align: center;">
                <a href="javascript:void(0)" onclick="change_menu_to('doc/menu')">
                    <img src="{{ asset('img/email_inbox.png') }}" width="100" height="100">
                    <p>Bandeja de Documentos</p>
                </a>
            </div>
            @endif
        </div>
        <div class="row" style="padding-bottom: 20px"></div>
        <div class="row" style="padding-left: 1%">
            @if(Auth::user()->can(9))
            <div class="col-md-3" style="text-align: center;">
                <a href="javascript:void(0)" onclick="change_menu_to('doc/consult')">
                    <img src="{{ asset('img/informe.png') }}" width="100" height="100">
                    <p>Reportes</p>
                </a>
            </div>
            @endif
            @if(Auth::user()->can(12))
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