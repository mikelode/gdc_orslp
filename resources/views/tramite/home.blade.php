@extends('app')

@section('htmlheader_title')
    Menu Principal
@endsection

@section('main-content')

<section class="content-header">
    <h1>
        Número de procesos por atender:
        <small>Cada proceso representa un conjunto de documentos relacionados para atender un documento principal</small>
    </h1>
</section>

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{ $totaldocs }}</h3>
                        <p>Procesos Registrados</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-briefcase"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        bandeja de documentos
                        <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ $vigentes }}</h3>
                        <p>Procesos Vigentes</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-document"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        bandeja de documentos
                        <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-orange">
                    <div class="inner">
                        <h3>{{ $xvencer }}</h3>
                        <p>Procesos Por Vencer</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-clock"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        bandeja de documentos
                        <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{ $vencidos }}</h3>
                        <p>Procesos Vencidos</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-alert-circled"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        bandeja de documentos
                        <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs pull-right">
                        <li class="active"><a href="#registro-chart" data-toggle="tab">Area</a></li>
                        <!--<li><a href="#sales-chart" data-toggle="tab">Donut</a></li>-->
                        <li class="pull-left header"><i class="fa fa-inbox"></i> Registro documentario diario</li>
                    </ul>
                    <div class="tab-content no-padding">
                        <div class="chart tab-pane active" id="registro-chart" style="position: relative; height: 300px;"></div>
                        <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row" style="padding-bottom: 20px;">
                    <div class="col-lg-12">Opciones del Sistema</div>
                </div>
                <div class="row" style="padding-left: 1%">
                    @if(Auth::user()->can(5))
                    <div class="col-md-6" style="text-align: center;">
                        <a href="javascript:void(0)" onclick="change_menu_register('doc/register')">
                            <img src="{{ asset('img/registro.png') }}" width="100" height="100">
                            <p>Gestión de Documentos</p>
                        </a>
                    </div>
                    @endif
                    @if(Auth::user()->can(7))
                    <div class="col-md-6" style="text-align: center;">
                        <a href="javascript:void(0)" onclick="change_menu_to('doc/menu')">
                            <img src="{{ asset('img/email_inbox.png') }}" width="100" height="100">
                            <p>Bandeja de Documentos</p>
                        </a>
                    </div>
                    @endif
                </div>
                <div class="row">
                    @if(Auth::user()->can(9))
                    <div class="col-md-4" style="text-align: center;">
                        <a href="javascript:void(0)" onclick="change_menu_to('doc/reports')">
                            <img src="{{ asset('img/informe.png') }}" width="100" height="100">
                            <p>Reportes</p>
                        </a>
                    </div>
                    @endif
                    @if(Auth::user()->can(12))
                    <div class="col-md-4" style="text-align: center;">
                        <a href="javascript:void(0)" onclick="change_menu_to('settings')">
                            <img src="{{ asset('img/configuracion.png') }}" width="100" height="100">
                            <p>Configuración</p>
                        </a>
                    </div>
                    @endif
                    <div class="col-md-4" style="text-align: center;">
                        <a href="javascript:void(0)" onclick="change_menu_to('progress')">
                            <img src="{{ asset('img/progreso.png') }}" width="100" height="100">
                            <p>Progreso</p>
                        </a>
                    </div>
                </div>
                <div class="row" style="padding-bottom: 20px;">
                    <div class="col-lg-12">{{ Inspiring::quote() }}</div>
                </div>
            </div>
        </div>
        <div class="row" style="padding-bottom: 20px"></div>
        <div class="row" style="padding-left: 100px"></div>
    </div>
</section>

@endsection