@section('htmlheader_title')
    Consulta y Seguimiento Documentario
@endsection

@section('main-content')
<section class="content-header">
    <h1>
        Consulta y Seguimiento Documentario
        <small>@yield('contentheader_description')</small>
    </h1>
</section>
<section class="content" style="font-size: 12px">
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="row">
                <form id="frmBuscDoc" role="form">
                    {!! csrf_field() !!}
                    <div class="col-md-8">
                        <div class="form-group frm-group-custom">
                            <label class="control-label lbl-custom">Coordinador:</label>
                            <select id="docCoord" name="ndocCoord" class="form-control frm-control-custom">
                                <option value="all">--Todos--</option>
                                <option value="c1">Coordinador 1</option>
                                <option value="c2">Coordinador 2</option>
                            </select>
                        </div>
                        <div class="form-group frm-group-custom">
                            <label class="control-label lbl-custom">Asociación:</label>
                            <select name="ndocAsoc" id="docAsoc" class="form-control frm-control-custom">
                                <option value="all">--Todas--</option>
                                @foreach($asoc as $as)
                                    <option value="{{ $as->tasId }}">{{ $as->tasOrganizacion }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group frm-group-custom">
                            <label for="docReg" class="control-label lbl-custom">Nro de registro:</label>
                            <input type="search" id="docReg" name="ndocReg" class="form-control frm-control-custom">
                        </div>
                        <div class="form-group frm-group-custom">
                            <label for="docCodigo" class="control-label lbl-custom">Código del Doc:</label>
                            <input type="search" id="docCodigo" name="ndocCodigo" class="form-control frm-control-custom">
                        </div>
                        <div class="form-group frm-group-custom">
                            <label for="docTipo" class="control-label lbl-custom">Tipo de Doc:</label>
                            <select class="form-control frm-control-custom" id="docTipo" name="ndocTipo">
                                <option value="all"> -- Todos -- </option>
                                @foreach($tipos as $t)
                                    <option value="{{ $t->ttypDoc }}">{{ $t->ttypDesc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group frm-group-custom">
                            <label class="control-label lbl-custom">Fecha de Doc:</label>
                            <div style="display: inline;">
                                <label style="color: #000;">De:</label><input style="width: 30%" class="form-control frm-control-custom" type="date" id="docFechaIni" name="ndocFechaIni">
                                <label style="color: #000;">hasta:</label><input style="width: 30%" class="form-control frm-control-custom" type="date" id="docFechaFin" name="ndocFechaFin">
                            </div>
                        </div>
                        <div class="form-group frm-group-custom">
                            <label for="docAsunto" class="control-label lbl-custom">Asunto:</label>
                            <input class="form-control frm-control-custom" id="docAsunto" name="ndocAsunto">
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-md-8" style="text-align: center;">
                    <button class="btn btn-primary" type="button" id="btnBuscarDoc">Buscar</button>
                </div>
            </div>
        </div>
        <div class="box-body no-padding">
            <div id="resultRows">
                <table class="table" id="resultTable">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Documento</th>
                            <th>Registro</th>
                            <th>Tipo</th>
                            <th>Asunto</th>
                            <th>Fecha de Presentación</th>
                            <th>Estado</th>
                            <th>Detalle Seguimiento</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list_docs as $key=>$item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->tdocId }}</td>
                                <td>{{ $item->tdocRegistro }}</td>
                                <td>{{ $item->ttypDesc }}</td>
                                <td>{{ $item->tdocSubject }}</td>
                                <td>{{ $item->tdocDate }}</td>
                                <td>{{ $item->tdocStatus }}</td>
                                <td>
                                <?php $enlace = 'doc/tracking/'.$item->tdocId ?>
                                    <a href="javascript:void(0)" onclick="change_menu_to('{{ $enlace }}')">Ver</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

{!! Form::open(['route' => ['findExp',':EXP_ID'], 'method' => 'POST', 'id' => 'frm_findE']) !!}
{!! Form::close() !!}

{!! Form::open(['route' => ['findDoc',':DOC_ID'], 'method' => 'POST', 'id' => 'frm_findD']) !!}
{!! Form::close() !!}

<script>
$(document).ready(function(){

    $('#resultTable').DataTable({
        "language":{
            "url": "plugins/DataTables/Spanish.json"
        },
        "processing": true,
    });

    $('#docAsoc').select2();

    $('#btnBuscarDoc').click(function(e){
        e.preventDefault();
        var period = $('#period_sys').val();
        var url ="doc/find/" + period;
        var data = $('#frmBuscDoc').serialize();

        $.post(url, data, function(result){
            $('#resultRows').html(result);
        });
    });

    function getRecords(page, period)
    {
        $.ajax({
            url: 'doc/list?page=' + page + '&year=' + period
        }).done(function(data){
            $('#resultRows').html(data);
        });
    }

    $('input#codeExp').keypress(function(event){
        if(event.which == 13)
        {
            event.preventDefault();

            var id = $('#codeExp').val();

            if(id == '') return;

            var url = $('#frm_findE').attr('action').replace(':EXP_ID',id);
            var data = $('#frm_findE').serialize();

            get_result_for(url, data);
        }
    });

    $('input#codeDoc').keypress(function(evt){
        if(evt.which == 13)
        {
            evt.preventDefault();

            var id = $('#codeDoc').val();

            if(id == '') return;

            var url = $('#frm_findD').attr('action').replace(':DOC_ID',id);
            var data = $('#frm_findD').serialize();

            get_result_for(url, data);
        }
    });

    $('.btn_find').click(function(e){
        e.preventDefault();
        var action = $(this).data('xp');
        var id;
        var url;
        var data;

        if(action == 'exp')
        {
            id = $('#codeExp').val();
            url = $('#frm_findE').attr('action').replace(':EXP_ID',id);
            data = $('#frm_findE').serialize();
        }
        else if(action == 'doc')
        {
            id = $('#codeDoc').val();
            url = $('#frm_findD').attr('action').replace(':DOC_ID',id);
            data = $('#frm_findD').serialize();
        }

        get_result_for(url, data);

    });

    $('#frm_findS').submit(function(e){

        e.preventDefault();
        var url = $(this).attr('action');
        var data = $(this).serialize();

        get_result_forMany(url, data);

    });

    $('#frm_findDte').submit(function(evt){
        evt.preventDefault();
        var url = $(this).attr('action');
        var data = $(this).serialize();

        get_result_forMany(url, data);
    });

    $('#frm_findSnd').submit(function(evt){
        evt.preventDefault();
        var url = $(this).attr('action');
        var data = $(this).serialize();

        get_result_forMany(url, data);
    });

    $('#frm_findAtc').submit(function(evt){
        evt.preventDefault();
        var url = $(this).attr('action');
        var data = $(this).serialize();

        get_result_forMany(url, data);
    });
});
</script>

@endsection