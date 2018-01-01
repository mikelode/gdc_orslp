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
                                    <th>Titulo Proc.</th>
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
                                        <!--<a href="javascript:void(0)" onclick="showDocDetail('{{ $doc->tdocId }}')"></a>-->
                                        {{ $doc->tdocRegistro }}
                                    </td>
                                    <td>{{ $doc->tarcTitulo }}</td>
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

<script>
$(document).ready(function(){  

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