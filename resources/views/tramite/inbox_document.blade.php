@section('htmlheader_title')
    Bandeja de Entrada
@endsection

@section('main-content')
<section class="content" style="font-size: 12px">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Buscar Documento</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="col-md-2"><label class="lbl-frm">Proyecto:</label></div>
                            <div class="col-md-10">
                                <select class="form-control" name="ndocProy" id="ldocProy">
                                    <option value="all" selected>-- Todos los proyectos --</option>
                                    @foreach($proyectos as $py)
                                        <option value="{{ $py->tpyId }}">{{ $py->tpyName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="col-md-4"><label class="lbl-frm">Registro:</label></div>
                            <div class="col-md-8">
                                <input type="tex" id="ldocReg" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                </div>
            </div>
        </div>            
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Bandeja de Documentos</h3>
                    <div class="box-tools pull-right">
                        <div class="has-feedback">
                            <div class="mailbox-controls">
                                <label class="lbl-frm">Vigente:&nbsp</label><button class="btn btn-success btn-sm"></button>
                                <label class="lbl-frm">Por vencer:&nbsp</label><button class="btn btn-warning btn-sm"></button>
                                <label class="lbl-frm">Vencido:&nbsp</label><button class="btn btn-danger btn-sm"></button>
                                <button class="btn btn-default btn-sm" onclick="change_menu_to('doc/menu')"><i class="fa fa-refresh"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div id="tblBandeja">
                        <table class="display compact" cellspacing="0" width="100%" id="docBandeja">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Reg.</th>
                                    <th>Doc.</th>
                                    <th>Remitente</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Plazo</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inbox as $doc)
                                <tr data-keys = "{{ $doc->tdocId.'-'.$doc->tdocExp }}">
                                    <td class="details-control" onclick="historial(this)">
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" onclick="showDocDetail('{{ $doc->tdocId }}')">{{ $doc->tdocRegistro }}</a>
                                    </td>
                                    <td>{{ $doc->ttypDesc.' - '.$doc->tdocNumber }}</td>
                                    <td>{{ $doc->tdocSender }}</td>
                                    <td>{{ $doc->tdocDate }}</td>
                                    <td>
                                        @if($doc->tarcStatus != 'atendido')
                                            @if($doc->plazo <= 4)
                                                <button type="button" class="btn btn-success btn-xs">{{ $doc->tarcStatus }}</button>
                                            @endif
                                            @if($doc->plazo > 4 && $doc->plazo <= 7)
                                                <button type="button" class="btn btn-warning btn-xs">{{ $doc->tarcStatus }}</button>
                                            @endif
                                            @if($doc->plazo >= 7)
                                                <button type="button" class="btn btn-danger btn-xs">{{ $doc->tarcStatus }}</button>
                                            @endif
                                        @else
                                            <button type="button" class="btn btn-default btn-xs">{{ $doc->tarcStatus }}</button>
                                        @endif

                                    </td>
                                    <td>{{ $doc->plazo }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <script>
                            $('#docBandeja').DataTable({
                                "language":{
                                    "url": "plugins/DataTables/Spanish.json"
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="sendModal" tabindex="-1" role="dialog" aria-labelledby="Send" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Derivar</h4>
                </div>
                <form class="form-horizontal" id="sendForm" name="sendForm">
                    <div class="modal-body">
                        {!! csrf_field() !!}
                        <div><input type="hidden" id="kyId" name="kyId"></div>
                        <div class="form-group">
                            <label class="col-xs-5 control-label">Documento Nro:</label>
                            <div class="col-xs-6">
                                <input type="text" class="form-control" name="document" id="documentId" readonly>
                                <input type="hidden" name="expedient" id="expedientId">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-5 control-label">Unidad Origen:</label>
                            <div class="col-xs-6">
                                <select class="form-control" name="dep_source">
                                    <option value="{{ Auth::user()->workplace->depID }}">{{ Auth::user()->workplace->depDsc }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-5 control-label">Unidad Destino:</label>
                            <div class="col-xs-6">
                                <select id="select2" class="form-control" multiple="multiple" data-placeholder="Elegir destino" name="dep_target[]" style="width: 100%">
                                    @foreach($dependencys as $dep)
                                        <option value="{{ $dep->depID }}"> {{ $dep->depDsc }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-5 control-label">Detalle u Observación:</label>
                            <div class="col-xs-6">
                                <textarea class="form-control text-uppercase" id="dsc_derived" name="dsc_derived" style="height: 100px; max-height: 100px; max-width: 270px" placeholder="Mensaje, nota o alguna observación"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="save">Enviar y Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="sendModalDc" tabindex="-1" role="dialog" aria-labelledby="Send" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Atender y derivar mediante documento</h4>
                </div>
                <form class="form-horizontal" id="sendFormDc" name="sendFormDc" enctype="multipart/form-data">
                    <div class="modal-body">
                        {!! csrf_field() !!}
                        <div><input type="hidden" id="kyIdDc" name="kyId"></div>
                        <div class="form-group">
                            <label class="col-xs-5 control-label">En atención al documento:</label>
                            <div class="col-xs-6">
                                <input type="text" class="form-control" name="document" id="documentIdDc" readonly>
                                <input type="hidden" name="expedient" id="expedientIdDc">
                                <input type="hidden" name="asociacion" id="documentAsocDc">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12" style="text-align: center;">Se atiende y deriva con el siguiente documento:</label>
                            <input type="hidden" name="dep_source" value="{{ Auth::user()->workplace->depID }}">
                        </div>
                        <div class="form-group">
                            <label class="col-xs-5 control-label">Tipo de documento:</label>
                            <div class="col-xs-6">
                                <select name="type_doc" class="form-control input-sm">
                                    @foreach($tipos as $t)
                                        <option value="{{ $t->ttypDoc }}">{{ $t->ttypDesc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-5 control-label">Número del documento:</label>
                            <div class="col-xs-6">
                                <input type="text" class="form-control" name="nrodc" id="nroDocId">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-5 control-label">Folio:</label>
                            <div class="col-xs-6">
                                <input type="number" class="form-control" name="folio_doc" id="folioId">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-5 control-label">Unidad Destino:</label>
                            <div class="col-xs-6">
                                <select id="select2Dc" class="form-control" multiple="multiple" data-placeholder="Elegir destino" name="dep_target[]" style="width: 100%">
                                    @foreach($dependencys as $dep)
                                        <option value="{{ $dep->depID }}"> {{ $dep->depDsc }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-5 control-label">Asunto:</label>
                            <div class="col-xs-6">
                                <textarea class="form-control text-uppercase" id="sbj_derivedDc" name="sbj_derived" style="height: 50px; max-height: 100px; max-width: 270px" placeholder="Asunto del documento"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-5 control-label">Detalle u Observación:</label>
                            <div class="col-xs-6">
                                <textarea class="form-control text-uppercase" id="nta_derivedDc" name="nta_derived" style="height: 100px; max-height: 100px; max-width: 270px" placeholder="Mensaje, nota o alguna observación"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-9">
                                <input type="file" class="form-control" name="file_derived" accept="application/pdf"></input>
                            </div>
                            <div class="col-xs-3"><progress class="form-control" value="0"></progress></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="saveDc">Enviar y Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal modal-success fade" id="attendModal" tabindex="-1" role="dialog" aria-labelledby="Send" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Cerrar trámite del documento</h4>
                </div>
                {!! Form::open(['route' => ['attend',':EXP_ID'], 'method' => 'PUT', 'id' => 'form-attend', 'class' => 'form-horizontal']) !!}
                    <div class="modal-body">
                        {!! csrf_field() !!}
                        <div><input type="hidden" id="kyId_1" name="kyId"></div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Expediente Nro:</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="document_to_attend" id="documentId_1" readonly>
                                <input type="hidden" name="expedient_to_attend" id="expedientId_1" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Descripción:</label>
                            <div class="col-md-8">
                                <textarea class="form-control text-uppercase" id="dsc_attend" name="dsc_attend" style="height: 100px; max-height: 100px; max-width: 400px" placeholder="Descripción de las tareas realizadas"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-outline" id="attend">Atender y Guardar</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="modal modal-primary fade" id="showDetailOperation" tabindex="-1" role="dialog" aria-labelledby="Show" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">MENSAJE</h4>
                </div>
                <div class="modal-body">
                    <div id="detail_operation"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</section>

{!! Form::open(['route' => ['receive',':EXP_ID'], 'method' => 'PUT', 'id' => 'form-update']) !!}
{!! Form::close() !!}

{!! Form::open(['route' => ['undo',':EXP_ID'], 'method' => 'POST', 'id' => 'form-undo']) !!}
{!! Form::close() !!}

<script>
$(document).ready(function(){  

    $('#sendModal').on('show.bs.modal', function(event){
        var button = $(event.relatedTarget);
        var recipient = button.data('whatever');
        var xp = $('#xp' + recipient).val();
        var id = $('#id' + recipient).val();
        var dc = $('#dc' + recipient).val();
        var modal = $(this);
        modal.find('.modal-body #expedientId').val(xp);
        modal.find('.modal-body #documentId').val(dc);
        modal.find('.modal-body #kyId').val(id);
        modal.find('.modal-body #dsc_derived').val("");
        modal.find('.modal-body #select2').select2('val','');
    });

    $('#sendModalDc').on('show.bs.modal', function(event){
        var button = $(event.relatedTarget);
        var recipient = button.data('whatever');
        var xp = $('#xp' + recipient).val();
        var id = $('#id' + recipient).val();
        var dc = $('#dc' + recipient).val();
        var sc = $('#sc' + recipient).val();
        var modal = $(this);
        modal.find('.modal-body #expedientIdDc').val(xp);
        modal.find('.modal-body #documentIdDc').val(dc);
        modal.find('.modal-body #documentAsocDc').val(sc);
        modal.find('.modal-body #kyIdDc').val(id);
        modal.find('.modal-body #dsc_derivedDc').val("");
        modal.find('.modal-body #select2Dc').select2('val','');
    });

    $('#attendModal').on('show.bs.modal', function(event){
        var btn = $(event.relatedTarget);
        var rowId = btn.data('any');
        var xp = $('#xp' + rowId).val();
        var id = $('#id' + rowId).val();
        var dc = $('#dc' + rowId).val();
        var modal = $(this);
        modal.find('.modal-body #expedientId_1').val(xp);
        modal.find('.modal-body #kyId_1').val(id);
        modal.find('.modal-body #documentId_1').val(dc);
        modal.find('.modal-body #dsc_attend').val("");
    });

    $('#showDetailOperation').on('show.bs.modal');

    $('button#save').click(function(e){
        e.preventDefault();
        var rowId = $('form#sendForm input[name=kyId]').val(); // Row's number

        $.ajax({
            type:'POST',
            url:'hist/register',
            data: $('form#sendForm').serialize(),
            success: function(msg){
                //$('td#row' + rowId).html('DERIVADO');
                $('#sendModal').modal('hide');
                change_menu_to('doc/menu');
            },
            error: function(e, textStatus, xhr){
                bootbox.alert('Error: <br> Revise los campos ingresados. <br> Código: ' + xhr);
            }
        });
    });

    $('button#saveDc').click(function(e){
        
        e.preventDefault();
        
        var rowId = $('form#sendFormDc input[name=kyId]').val(); // Row's number
        var form = $('#sendFormDc')[0];
        var formdata = new FormData(form);

        $.ajax({
            type:'POST',
            url:'hist/registerdc',
            data: formdata,
            cache: false,
            contentType: false,
            processData: false,
            success: function(msg){
                //$('td#row' + rowId).html('DERIVADO');
                $('#sendModalDc').modal('hide');
                change_menu_to('doc/menu');
            },
            error: function(e, textStatus, xhr){
                bootbox.alert('Error: <br> Revise los campos ingresados. <br> Código: ' + xhr);
            },
            xhr: function(){
                var myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload){
                    myXhr.upload.addEventListener('progress', function(ev){
                        if(ev.lengthComputable){
                            $('progress').attr({
                                value: ev.loaded,
                                max: ev.total,
                            });
                        }
                    }, false);
                }
                return myXhr;
            },
        });
    });

    $('button.btn-warning').click(function(e){

        e.preventDefault();
        var row = $(this).parents('tr');
        var id = row.data('id');
        var form = $('#form-update');
        var url = form.attr('action').replace(':EXP_ID',id);
        var data = form.serialize();

        bootbox.confirm("¿Está seguro de aceptar el documento? <br> <small>Nota: Esta operación se realiza cuando el documento físico ha sido recibido</small>", function(rpta){
            if(rpta)
            {
                $.post(url, data, function(result){
                    if(result == 'updated')
                    {
                        change_menu_to('doc/menu');
                    }
                }).fail(function(){
                    bootbox.alert('Error, No es posible aceptar el documento seleccionado');
                });
            }
        });
    });

    $('button#attend').click(function(){

        var exp = $('#kyId_1').val();
        var url = $('#form-attend').attr('action').replace(':EXP_ID',exp);
        var data = $('#form-attend').serialize();

        $.post(url, data, function(result){
            if(result == 'attended')
            {
                //$('#ky' + exp).html('ATENDIDO');
                $('#attendModal').modal('hide');
                change_menu_to('doc/menu');
            }
        }).fail(function(){
            bootbox.alert('Error en registrar la atención del documento');
        });
    });

    $('.ctrl_z_task').click(function(e){

        e.preventDefault();
        var id = $(this).data('keydoc');
        var url = $('#form-undo').attr('action').replace(':EXP_ID',id);
        var data = $('#form-undo').serialize();

        bootbox.confirm("¿Está seguro de deshacer la operación?", function(rpta){
            if(rpta)
            {
                $.post(url,data, function(response){
                    
                    if(response.codec == 200)
                    {
                        change_menu_to('doc/menu');
                    }
                    alert('-' + response.msg);
                    
                }).fail(function(){
                    bootbox.alert('Error. La operación de deshacer no se ha completado correctamente');
                });
            }
        });
    });

    $('#select2').select2();
    $('#select2Dc').select2();
    $('#ldocProy').select2().on('change', function(){
        var py = $(this).val();
        var periodo = $('#period_sys').val();

        if(py == 'all'){
            change_menu_to('doc/menu');
            return;
        }

        $.get('doc/filtrar',{'key': py, 'period': periodo, 'campo': 'proyecto'}, function(data) {
            $('#tblBandeja').empty();
            $('#tblBandeja').html(data);
        });
    });

    $('#ldocReg').keypress(function(event) {
        if(event.which == 13){
            event.preventDefault();
            var reg = $(this).val();
            var periodo = $('#period_sys').val();

            if(reg == 0){
                change_menu_to('doc/menu');
                return;
            }

            $.get('doc/filtrar',{'key': reg, 'period': periodo, 'campo': 'registro'}, function(data) {
                $('#tblBandeja').empty();
                $('#tblBandeja').html(data);
            });

        }
    });
});
</script>
@endsection