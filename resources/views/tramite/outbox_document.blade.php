@section('htmlheader_title')
    Bandeja de Salida
@endsection

@section('main-content')

<section class="content-header">
    <h1>
        Bandeja de Salida de Documentos
        <small>@yield('contentheader_description')</small>
    </h1>
</section>

<section class="content" style="font-size: 12px">
    <div class="row">
        <div class="col-md-5">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Documentos Creados</h3>
                </div>
                <div class="box-body no-padding">
                    <div class="mailbox-controls">
                        <button class="btn btn-default btn-sm" onclick="change_menu_to('doc/outbox')"><i class="fa fa-refresh"></i></button>
                        <div class="pull-right">
                            -
                            <div class="btn-group">
                                <button class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                                <button class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-hover table-striped">
                            <tbody>
                                <tr>
                                    <th>#</th>
                                    <th>Estado</th>
                                    <th>Documento</th>
                                    <th>Fecha de Presentación</th>
                                    <th>Tarea</th>
                                    <th></th>
                                </tr>
                                @foreach ($documents as $i=>$doc)
                                    <tr>
                                        <input type="hidden" id="{{ 'id'.$doc->thisId.$doc->thisExp }}" value="{{ $doc->thisId }}">
                                        <input type="hidden" id="{{ 'xp'.$doc->thisId.$doc->thisExp }}" value="{{ $doc->thisExp }}">
                                        <input type="hidden" id="{{ 'dc'.$doc->thisId.$doc->thisExp }}" value="{{ $doc->tarpDoc }}">
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <?php $link = 'doc/tracking/'.$doc->tdocId ?>
                                            <a href="javascript:void(0)" onclick="change_menu_to('{{ $link }}')">{{ $doc->tarcStatus }}</a>
                                        </td>
                                        <td><a href="javascript:void(0)" onclick="showDocDetail('{{ $doc->tarpDoc }}')">{{ $doc->tarpDoc }}</a></td>
                                        <td>{{ $doc->tarcDatePres }}</td>
                                        <td id="{{ 'row'.$doc->thisId }}">
                                        @if($doc->tarpFlagD == false)
                                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#sendModal" data-whatever="{{ $doc->thisId.$doc->thisExp }}" id="btnDerivar">Derivar</button>
                                        @else
                                            <a href="#" class="btn btn-success btn-xs">Derivado</a>
                                        @endif
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" data-keydoc="{{ $doc->thisId }}" class="ctrl_z_doc">
                                                @if($doc->tarpFlagD)
                                                    Anular
                                                @endif
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="sendModal" tabindex="-1" role="dialog" aria-labelledby="Send" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="form-horizontal" id="sendForm" name="sendForm">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Derivar Documento</h4>
                            </div>
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
                                            <option value="{{ Auth::user()->workplace->depId }}">{{ Auth::user()->workplace->depDsc }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-5 control-label">Unidad Destino (*):</label>
                                    <div class="col-xs-6">
                                        <select id="select2" class="form-control select2" multiple="multiple" data-placeholder="Agregar destinos" name="dep_target[]" style="width: 100%">
                                            @foreach($dependencys as $dep)
                                                <option value="{{ $dep->depId }}"> {{ $dep->depDsc }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-5 control-label">Detalle u observación:</label>
                                    <div class="col-xs-6">
                                        <textarea name="nota_derivado" id="input" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary" id="save">Enviar y Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Detalles Documento</h3>
                </div>
                <div class="panel-body">
                    <div id="detail_document">

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{!! Form::open(['route' => ['undo_doc',':EXP_ID'], 'method' => 'POST', 'id' => 'form-undo-doc']) !!}
{!! Form::close() !!}

<script>
$(function(){
    $('#sendModal').on('show.bs.modal', function(event){

        var button = $(event.relatedTarget);
        var recipient = button.data('whatever');
        var dc = $('#dc' + recipient).val();
        var xp = $('#xp' + recipient).val();
        var id = $('#id' + recipient).val();
        var modal = $(this);
        modal.find('.modal-body #expedientId').val(xp);
        modal.find('.modal-body #kyId').val(id);
        modal.find('.modal-body #documentId').val(dc);
        modal.find('.modal-body #dsc_derived').val("");
        modal.find('.modal-body #select2').select2('val','');

    });

    $('button#save').click(function(e){
        e.preventDefault();

        var form = $('form#sendForm');
        var kyId = $('form#sendForm input[name=kyId]').val(); // Row's number
        $.ajax({
            type:'POST',
            url:'fhist/register',
            data: form.serialize(),
            success: function(msg){
                $('td#row'+kyId).html('<a href="#" class="btn btn-success btn-xs">Derivado</a>');
                $('#sendModal').modal('hide');
            },
            error: function(e, textStatus, xhr){
                bootbox.alert('Error: <br> Revise los campos ingresados. <br> Código: ' + xhr);
                //alert('Error en registrar la derivación del documento' + e);
            }
        });
    });

    $('.ctrl_z_doc').click(function(e){

        e.preventDefault();
        var id = $(this).data('keydoc');
        var url = $('#form-undo-doc').attr('action').replace(':EXP_ID',id);
        var data = $('#form-undo-doc').serialize();
        console.log(id);
        bootbox.confirm("¿Está seguro de deshacer la operación?", function(rpta){
            if(rpta)
            {
                $.post(url,data, function(response){
                    bootbox.alert(response);
                    change_menu_to('doc/outbox');
                }).fail(function(){
                    bootbox.alert('Error. La operación de anular no se ha completado correctamente');
                });
            }
        });
    });

    $('#select2').select2();
});
</script>
@endsection