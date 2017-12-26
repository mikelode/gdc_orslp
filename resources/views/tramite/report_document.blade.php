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
                <form id="frmReportDoc" role="form" action="make/report">
                    {!! csrf_field() !!}
                    <div class="col-md-8">
                        <div class="form-group frm-group-custom">
                            <label class="control-label lbl-custom">Proyecto:</label>
                            <select name="ndocPy" id="docPy" class="form-control frm-control-custom">
                                <option value="all">--Todos--</option>
                                @foreach($proy as $py)
                                    <option value="{{ $py->tpyId }}">{{ $py->tpyCU . ' ' . $py->tpyName }}</option>
                                @endforeach
                            </select>
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
                            <label class="control-label lbl-custom">Personal Supervision:</label>
                            <select class="form-control frm-control-custom" id="docPers" name="ndocPers">
                                <option value="all"> -- Todos -- </option>
                                @foreach($pers as $p)
                                    <option value="{{ $p->tprId }}">{{ $p->tprDni.' '.$p->tprFulName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!--<div class="col-md-4" style="text-align: center;">
                        <button class="btn btn-primary" type="button" id="btnGenerarRep">Generar</button>
                    </div>-->
                </form>
            </div>
        </div>
        <div class="box-body no-padding">
            <div id="resultRows">
                <table class="table" id="resultTable">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Proyecto</th>
                            <th>Registro</th>
                            <th>Tipo</th>
                            <th>Documento</th>
                            <th>Remitente</th>
                            <th>Fecha de Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list_docs as $key=>$item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->tpyName }}</td>
                                <td>{{ $item->tdocRegistro }}</td>
                                <td>{{ $item->ttypDesc }}</td>
                                <td>{{ $item->tdocNumber }}</td>
                                <td>{{ $item->tdocSender }}</td>
                                <td>{{ $item->tdocDate }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function(){

    $('#resultTable').DataTable({
        "language":{
            "url": "plugins/DataTables/Spanish.json"
        },
        "processing": true,
    });

    $('#docPy').select2().on('change', function(){
        var period = $('#period_sys').val();
        var url = $('#frmReportDoc').prop('action');
        var data = $('#frmReportDoc').serializeArray();
        
        data.push({name: 'perio', value: period});

        console.log(data);

        procesar_reporte(url, data);
    });

    $('#docTipo').change(function() {
        var period = $('#period_sys').val();
        var url = $('#frmReportDoc').prop('action');
        var data = $('#frmReportDoc').serializeArray();
        
        data.push({name: 'perio', value: period});

        procesar_reporte(url, data);
    });

    $('#docPers').select2().on('change', function(){
        var period = $('#period_sys').val();
        var url = $('#frmReportDoc').prop('action');
        var data = $('#frmReportDoc').serializeArray();
        
        data.push({name: 'perio', value: period});

        procesar_reporte(url, data);
    });



    $('#btnGenerarRep').click(function(e){
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