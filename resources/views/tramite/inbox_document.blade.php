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
                    @if(Auth::user()->can(6))
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
                        <div class="col-md-2">
                            <div class="col-md-4"><label class="lbl-frm">Asunto:</label></div>
                            <div class="col-md-8">
                                <input type="tex" id="ldocAsu" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                    @endif
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
                                    <th>Asunto</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>D.Trans.</th>
                                    
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
                                    <td>{{ $doc->tdocSubject }}</td>
                                    <td>{{ $doc->tdocDate }}</td>
                                    <td>
                                        @if($doc->tarcStatus != 'atendido')
                                            @if($doc->plazo <= 4)
                                                <button type="button" class="btn btn-success btn-xs">{{ $doc->tarcStatus }}</button>
                                            @endif
                                            @if($doc->plazo > 4 && $doc->plazo <= 7)
                                                <button type="button" class="btn btn-warning btn-xs">{{ $doc->tarcStatus }}</button>
                                            @endif
                                            @if($doc->plazo > 7)
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
                                dom: 'Bfrtip',
                                buttons: [
                                    'excel', 'pdf', 'print'
                                ],
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

    $('#ldocAsu').keypress(function(event) {
        if(event.which == 13){
            event.preventDefault();
            var asu = $(this).val();
            var periodo = $('#period_sys').val();

            if($.trim(asu) == ''){
                change_menu_to('doc/menu');
                return;
            }

            $.get('doc/filtrar',{'key': asu, 'period': periodo, 'campo': 'asunto'}, function(data) {
                $('#tblBandeja').empty();
                $('#tblBandeja').html(data);
            });

        }
    });
});
</script>
@endsection