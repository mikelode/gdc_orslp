
@section('htmlheader_title')
    Registrar
@endsection

@section('main-content')

<section class="content">

@include('partials.messages')

<form id="frm_reg_doc" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <div class="panel-body">
        <div class="panel panel-primary" style="border: none; margin-bottom: 0;">
            <div class="panel-heading" style="overflow: hidden">
                <span style="float: left; height: 34px; padding: 5px; font-size: 20px; font-weight: bold">REGISTRO DE INGRESO:</span>
                <input type="text" class="form-control" style="width: 60px; float: left" id="periodoTramite" value="{{ Carbon\Carbon::now()->year }}" readonly>
                <div class="input-group" style="float: left;">
                    <input type="text" class="form-control" style="width: 160px; float: left; margin-left: 3px" id="docId" name="ndocId" onkeydown="ejecutar_teclado(event,'interno')" readonly>
                    <span class="input-group-btn" style="float: left;">
                        <!--<button type="button" id="btnIngresarDoc" class="btn btn-success" style="width: 66px; height: 33px;" data-estado="desactivado" onclick="modo_buscar(this)">-->
                        <button type="button" id="btnIngresarDoc" class="btn btn-success" style="width: 66px;" data-toggle="modal" data-target="#modalEncontrarDocumento" data-origen="busqueda">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                        </button>
                    </span>
                </div>
                <button type="button" onclick="mostrar_documento('anterior','interno')" class="btn btn-success btn-recorrer" style="float: left; margin-left: 10px;"><span class="glyphicon glyphicon-step-backward"></span></button>
                <button type="button" onclick="mostrar_documento('posterior','interno')" class="btn btn-success btn-recorrer" style="float: left;"><span class="glyphicon glyphicon-step-forward"></span></button>
                <div class="pull-right">
                    @if(Auth::user()->can(1))
                    <button type="button" id="btnNuevoDoc" class="btn btn-success" style="width: 100px;" onclick="nuevo_documento('interno')">Nuevo</button>
                    @endif
                    @if(Auth::user()->can(2))
                    <button type="button" id="btnEditarDoc" class="btn btn-info" style="width: 100px; display: none;" onclick="editar_documento('interno')">Editar</button>
                    @endif
                    @if(Auth::user()->can(3))
                    <button type="button" id="btnEliminarDoc" class="btn btn-danger" style="width: 100px; display: none;" onclick="eliminar_documento('interno')">Eliminar</button>
                    @endif
                </div>
            </div>
        </div>
        <div id="mod_register">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label class="lbl-frm">¿Refiere a otro documento?</label>
                                            <select name="ndocProceso" class="form-control input-sm" id="docProceso" disabled="required">
                                                <option value="na">-- Seleccionar --</option>
                                                <option value="no">NO</option>
                                                <option value="si">SI</option>
                                            </select>
                                        </div>
                                        <div class="col-md-10">
                                            <div id="con_referencia" style="display: none;">
                                                <div class="col-md-2">
                                                    <label class="lbl-frm">¿Que acción representa?</label>
                                                    <select name="ndocAccion" id="docAccion" class="form-control input-sm">
                                                        <option value="na" selected>-- Seleccionar --</option>
                                                        <option value="respuesta">Respuesta</option>
                                                        <option value="reapertura">Reapertura</option>
                                                        <option value="atendido-salida">Atendido (salida)</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="lbl-frm">Documento de Referencia (Nro de registro):</label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-default btn-sm" type="button" id="btnFindDoc" data-toggle="modal" data-target="#modalEncontrarDocumento" data-origen="referencia"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                                        </span>
                                                        <input type="text" name="ndocRefRegistro" id="docRefRegistro" class="form-control input-sm" readonly>
                                                        <input type="hidden" name="ndocReferencia" id="docReferencia">
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="sin_referencia" style="display: none;">
                                                <div class="col-md-5">
                                                    <label class="lbl-frm">Esta a punto de registrar un documento que dará inicio a un nuevo proceso documentario, si desea puede asignarle un nombre a dicho proceso para su fácil ubicación</label>
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="lbl-frm">Nombre del Proceso Documentario</label>
                                                    <input type="text" name="ndocTitulo" id="docTitulo" class="form-control input-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Datos del remitente</h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="lbl-frm">Dependencia</label>
                                            <select name="ndocDepend" id="docDepend" class="form-control input-sm" disabled="required">
                                                @foreach($dep as $d)
                                                    <option value="{{ $d->depId }}">{{ $d->depDsc }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="lbl-frm">Proyecto</label>
                                            <select name="ndocProy" id="docProy" class="form-control input-sm" disabled required>
                                                @foreach($assoc as $as)
                                                    <option value="{{ $as->tpyId }}">{{ $as->tpyName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="lbl-frm">Remitente</label><br>
                                            <div class="input-group">
                                                <input type="hidden" name="ndocSenderId" id="docSenderId">
                                                <input type="text" name="ndocSender" id="docSender" class="form-control input-sm" placeholder="Nombres y apellidos" autocomplete="off" readonly required>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default btn-sm" type="button" data-toggle="modal" data-target="#modalAgregarPersona">
                                                        <i class="glyphicon glyphicon-user"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="lbl-frm">Cargo</label>
                                            <input type="text" name="ndocJob" id="docJob" class="form-control input-sm" placeholder="Cargo laboral" readonly required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Datos del Documento</h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-2" style="padding-right: 3px;">
                                            <label class="lbl-frm">N° de Registro</label>
                                            <input type="text" name="ndocReg" id="docReg" class="form-control input-sm" placeholder="Registro en el cuaderno" readonly required>
                                        </div>
                                        <div class="col-md-3" style="padding: 0 3px 0 3px;">
                                            <label class="lbl-frm">Tipo Documento</label>
                                            <select name="ndocTipo" id="docTipo" class="form-control input-sm" disabled required>
                                                @foreach($tipos as $t)
                                                    <option value="{{ $t->ttypDoc }}">{{ $t->ttypDesc }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4" style="padding: 0 3px 0 3px;">
                                            <label class="lbl-frm">N° Documento</label>
                                            <input type="text" name="ndocNro" id="docNro" class="form-control input-sm" placeholder="Número de documento" readonly required>
                                        </div>
                                        <div class="col-md-3" style="padding-left: 3px;">
                                            <label class="lbl-frm">Fecha de Presentación (*)</label>
                                            <input type="text" name="ndocFecha" id="docFecha" class="form-control input-sm" readonly required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="lbl-frm" for="subject_doc">Asunto</label>
                                            <textarea name="ndocAsunto" id="docAsunto" class="form-control" rows="3" placeholder="Asunto del documento (*)" readonly required></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="lbl-frm" for="detail_doc">Detalle u observación</label>
                                            <textarea name="ndocDetalle" id="docDetalle" class="form-control" rows="3" placeholder="De talle u observación" readonly required></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label class="lbl-frm">Folio</label>
                                            <input type="number" name="ndocFolio" id="docFolio" class="form-control input-sm" placeholder="Cantidad de Hojas" readonly required>
                                        </div>
                                        <div class="col-md-10">
                                            <label class="lbl-frm">Archivo digitalizado</label>
                                            <div id="withoutDigFile" style="display: none;">
                                                <input type="file" class="form-control" name="ndocFile" id="docFile" accept="application/pdf" disabled="required">
                                                <progress class="form-control" value="0"></progress>
                                            </div>
                                            <div id="withDigFile"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-body">
                                    <div class="row" id="operacionEnviar" style="display: none;">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="control-label">ESTADO DE ENVIO: Documento registrado pero NO enviado</label>
                                                @if(Auth::user()->can(4))
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalOperacionEnviar">
                                                    Enviar Documento
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <button type="button" id="btnGuardarDoc" onclick="guardar_documento('interno')" class="btn btn-success" style="width: 100px; display: none;">Guardar</button>
                    <button type="button" id="btnEnviarDoc" class="btn btn-info" style="width: 100px; display: none;">Enviar</button>
                    <button type="button" id="btnGuardarEdicionDoc" onclick="terminar_edicion_documento('interno')" class="btn btn-success" style="width: 100px; display: none;">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="modalAgregarPersona" tabindex="-1" role="dialog" aria-labelledby="agregarPersona" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="enviarDocumento">Agregar Nueva Persona</h4>
            </div>
            <div class="modal-body">
                <form id="frmAddPerson" method="post" action="settings/new_prs">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-md-2">
                            <label class="lbl-frm">Documento</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="nprsDni" id="prsDni" class="form-control input-sm" placeholder="DNI">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label class="lbl-frm">Nombres</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="nprsName" id="prsName" class="form-control input-sm" placeholder="Nombres">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label class="lbl-frm">Ap.Paterno</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="nprsPaterno" id="prsPaterno" class="form-control input-sm" placeholder="Apellido Paterno">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label class="lbl-frm">Ap.Materno</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="nprsMaterno" id="prsMaterno" class="form-control input-sm" placeholder="Apellido Materno">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label class="lbl-frm">Celular</label>
                        </div>
                        <div class="col-md-10">
                            <input type="number" name="nprsCel" id="prsCel" class="form-control input-sm" placeholder="Número de celular">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label class="lbl-frm">Cargo</label>
                        </div>
                        <div class="col-md-10">
                            <input type="email" name="nprsJob" id="prsJob" class="form-control input-sm" placeholder="Cargo laboral">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary"  onclick="registrar_persona()">Registrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalOperacionEnviar" role="dialog" aria-labelledby="enviarDocumento" aria-hidden="true" style="color:#3c763d">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="enviarDocumento">Enviar Documento</h4>
            </div>
            <div class="modal-body">
                <form id="frmEnvDoc" method="post" action="hist/envio">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-md-4"><label class="lbl-frm">El documento con registro:</label></div>
                        <div class="col-md-3">
                            <div class="form-control input-sm" id="docEnvioExp"></div>
                            <input type="hidden" name="ndocEnvioExp" id="hdocEnvioExp"><!-- ndocEnvioExp envia el codigo del documento id -->
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                    <!--<div class="form-group">
                        <label class="control-label">Usted pertenece a la oficina:</label>
                        <div class="form-control" id="docEnvioOrigen"></div>
                        <input type="hidden" name="ndocEnvioOrigen" id="hdocEnvioOrigen">
                    </div>-->
                    <div class="row">
                        <div class="col-md-4"><label class="lbl-frm">Será enviado a:</label></div>
                        <div class="col-md-8">
                            <select name="ndocEnvioDestino" id="docEnvioDestino" style="width: 100%;">
                                <optgroup label="Especialista, supervisor, evaluador u otros">
                                    @foreach($dest as $d)
                                        @if($d->idtabla == 1) <!-- persona -->
                                            <option value="{{ $d->clave.'-'.$d->idtabla }}"> {{ $d->denominacion }} </option>
                                        @endif
                                    @endforeach
                                </optgroup>
                                <optgroup label="Oficina o dependencia regional">
                                    @foreach($dest as $d)
                                        @if($d->idtabla == 2) <!-- dependencia -->
                                            <option value="{{ $d->clave.'-'.$d->idtabla }}"> {{ $d->denominacion }} </option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><label class="lbl-frm">Detalle u Observación:</label></div>
                        <div class="col-md-8"><textarea id="docEnvioMensaje" name="ndocEnvioMensaje" class="form-control"></textarea></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="enviar_documento('interno')">Registrar envío</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalEncontrarDocumento" tabindex="-1" role="dialog" aria-labelledby="encontrarDocumento" aria-hidden="true" style="color:#3c763d">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="overflow: hidden;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title pull-left" id="encontrarDocumento">Buscar Documento Por: &nbsp;&nbsp;</h5>
                <div class="pull-left">
                    <select onchange="habilitar_busqueda(this.value);">
                        <option value="na" selected>-- Elegir --</option>
                        <option value="fecha">Fecha</option>
                        <option value="asunto">Asunto</option>
                        <option value="registro">Registro</option>
                        <option value="remitente">Remitente</option>
                    </select>
                </div>
            </div>
            <div class="modal-body" style="padding-top: 0px;">
                <form id="frmEncontrarDocFechas" class="frm-busqueda-rapida" method="post" action="doc/dates" style="display: none;">
                    {!! csrf_field() !!}
                    <!--<label class="control-label" style="display: block;">Fecha de Registro:</label>-->
                    <input type="hidden" name="nidConsulta" value="fechas">
                    <input type="hidden" name="nidFuncion" class="devolver_a">
                    <div class="row">
                        <div class="col-md-5">
                            <label class="lbl-frm">Desde:</label>
                            <div class="input-group input-sm date">
                                <input type="text" class="form-control" id="desdeFecha" name="startDate">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="lbl-frm">Hasta:</label>
                            <div class="input-group input-sm date">
                                <input type="text" class="form-control" id="hastaFecha" name="endDate">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="lbl-frm">&nbsp;</label>
                            <button type="button" class="btn btn-warning btn-sm" onclick="encontrar_documento('interno',$('#frmEncontrarDocFechas'))">Encontrar</button>
                        </div>
                    </div>
                </form>
                <form id="frmEncontrarDocAsunto" class="frm-busqueda-rapida" method="post" action="doc/subject" style="display: none;">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <input type="hidden" name="nidConsulta" value="asunto">
                        <input type="hidden" name="nidFuncion" class="devolver_a">
                        <label class="lbl-frm" style="display: block;">Asunto:</label>
                        <div class="row">
                            <div class="col-md-5">
                                <input type="text" class="form-control input-sm" id="descAsunto" name="ndescAsunto" autocomplete="off">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-warning btn-sm" onclick="encontrar_documento('interno',$('#frmEncontrarDocAsunto'))">Encontrar</button>
                            </div>
                        </div>
                    </div>
                </form>
                <form id="frmEncontrarDocRegistro" class="frm-busqueda-rapida" method="post" action="doc/reg" style="display: none;">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <input type="hidden" name="nidConsulta" value="codigo">
                        <input type="hidden" name="nidFuncion" class="devolver_a">
                        <label class="lbl-frm" style="display: block;">Registro del Documento:</label>
                        <div class="row">
                            <div class="col-md-5">
                                <input type="text" class="form-control input-sm" id="descRegistro" name="ndescRegistro" autocomplete="off">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-warning btn-sm" onclick="event.preventDefault(); encontrar_documento('interno',$('#frmEncontrarDocRegistro'))">Encontrar</button>
                            </div>
                        </div>
                    </div>
                </form>
                <form id="frmEncontrarDocRemitP" class="frm-busqueda-rapida" method="post" action="doc/sender" style="display: none;">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <input type="hidden" name="nidConsulta" value="remitp">
                        <input type="hidden" name="nidFuncion" class="devolver_a">
                        <label class="lbl-frm" style="display: block;">Remitente:</label>
                        <div class="row">
                            <div class="col-md-5">
                                <input type="text" class="form-control input-sm" id="descRemitP" name="ndescRemitP" autocomplete="off">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-warning btn-sm" onclick="encontrar_documento('interno',$('#frmEncontrarDocRemitP'))">Encontrar</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="tbl-resultado-encontrar" style="font-size: smaller;">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

</section>

<script>
$(document).ready(function(){

    $('#docSender').typeahead({
        onSelect: function(item){
            $('#docSenderId').val(item.value);
        },
        ajax:{
            url: 'settings/list_person',
            dataType: 'json',
            preDispatch: function (query) {
                return {
                    search: query
                }
            }
        }
    });

    $('#modalEncontrarDocumento').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var origen = button.data('origen');
        var modal = $(this);

        modal.find('.modal-body .devolver_a').val(origen);
    });

    $('#docEnvioDestino').select2();
    $('#docFecha').datetimepicker({
        format: "YYYY-MM-DD",
        showClear: true,
        showClose: true
    });
    $('#desdeFecha').datetimepicker({
        format: "YYYY-MM-DD",
        showClear: true,
        showClose: true
    });
    $('#hastaFecha').datetimepicker({
        useCurrent: false,
        format: "YYYY-MM-DD",
        showClear: true,
        showClose: true
    });
    $('#desdeFecha').on("dp.change",function(e){
        $('#hastaFecha').data("DateTimePicker").minDate(e.date);
    });
    $('#hastaFecha').on("dp.change", function(e){
        $('#desdeFecha').data("DateTimePicker").maxDate(e.date);
    });
            /*.datepicker({
        clearBtn: true,
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true,
        todayBtn: "linked",
        todayHighlight: true,
        enableOnReadonly: false
    });*/
    
    $('#docProy').select2({
        width: '100%'
    });

    $('input#dni_sender_input').keypress(function(evt){

        if(evt.which == 13)
        {
            evt.preventDefault();
            var dni = $('#dni_sender_input').val();

            if(dni == '') return;

            $.getJSON('doc/sender/' + dni,function(response){
                
                $('#name_sender_input').val(response.tdocSenderName);
                $('#patern_sender_input').val(response.tdocSenderPaterno);
                $('#matern_sender_input').val(response.tdocSenderMaterno);
            }).fail(function(){
                alert('Posiblemente no existe usuario');
                $('#name_sender_input').val('');
                $('#patern_sender_input').val('');
                $('#matern_sender_input').val('');
            });

        }

    });

    $('input#descRegistro').keypress(function(evt) {
        if(evt.which == 13)
        {
            evt.preventDefault();
            encontrar_documento('interno',$('#frmEncontrarDocRegistro'));
        }
    });

    $('input#descAsunto').keypress(function(evt) {
        if(evt.which == 13)
        {
            evt.preventDefault();
            encontrar_documento('interno',$('#frmEncontrarDocAsunto'));
        }
    });

    $('input#descRemitP').keypress(function(evt) {
        if(evt.which == 13)
        {
            evt.preventDefault();
            encontrar_documento('interno',$('#frmEncontrarDocRemitP'));
        }
    });

    $('#docProceso').change(function(event) {
        var pd = $(this).val();
        
        if(pd == 'si'){
            $('#con_referencia').show();
            $('#sin_referencia').hide();
        }else if(pd == 'no'){
            $('#con_referencia').hide();
            $('#sin_referencia').show();
        }else{
            $('#con_referencia').hide();
            $('#sin_referencia').hide();
        }

    });

});
</script>

@endsection