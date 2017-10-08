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
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_bySubject" data-toggle="tab">Por Asunto</a></li>
                    <li><a href="#tab_byCode" data-toggle="tab">Por Código</a></li>
                    <li><a href="#tab_byDates" data-toggle="tab">Entre Fechas</a></li>
                    <li><a href="#tab_bySender" data-toggle="tab">Por Remitente</a></li>
                    <li><a href="#tab_byAsoc" data-toggle="tab">Por Asociación</a></li>
                    <li><a href="#tab_byCoord" data-toggle="tab">Por Coordinador</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_bySubject">
                        {!! Form::open(['route' => 'findSubject', 'method' => 'POST', 'id' => 'frm_findS']) !!}
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="subjectDoc" placeholder="Buscar por Asunto">
                                <span class="input-group-btn">
                                    <button class="btn btn-default btn-flat" type="submit">Buscar</button>
                                </span>
                            </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="tab-pane" id="tab_byCode">
                        <div class="input-group input-group-sm input-xs">
                            <input type="text" class="form-control" id="codeDoc" placeholder="Buscar por Código de Documento">
                            <span class="input-group-btn">
                                <button class="btn btn-default btn-flat btn_find" type="button" data-xp="doc">Buscar</button>
                            </span>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab_byDates">
                        {!! Form::open(['route' => 'findDates', 'method' => 'POST', 'id' => 'frm_findDte', 'class' => 'form-inline']) !!}
                            <b>Fecha Inicial:</b>
                            <input type="date" class="form-control input-sm" name="startDate">
                            &nbsp;&nbsp;&nbsp;<b>Fecha Final:</b>
                            <input type="date" class="form-control input-sm" name="endDate">
                            <button class="btn btn-default btn-flat" type="submit">Buscar</button>
                        {!! Form::close() !!}
                    </div>
                    <div class="tab-pane" id="tab_bySender">
                        {!! Form::open(['route' => 'findSender', 'method' => 'POST', 'id' => 'frm_findSnd', 'class' => 'form-inline']) !!}
                            <b>DNI:</b>
                            <input type="text" class="form-control input-sm" name="dniSender">
                            &nbsp;&nbsp;&nbsp;<b>Nombres:</b>
                            <input type="text" class="form-control input-sm" name="nameSender" style="width: 450px;">
                            <button class="btn btn-default btn-flat" type="submit">Buscar</button>
                        {!! Form::close() !!}
                    </div>
                    <div class="tab-pane" id="tab_byAttaches">
                        {!! Form::open(['route' => 'findAttach', 'method' => 'POST', 'id' => 'frm_findAtc']) !!}
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="attachesDoc" placeholder="Buscar por detalle de anexo">
                                <span class="input-group-btn">
                                    <button class="btn btn-default btn-flat" type="submit">Buscar</button>
                                </span>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body no-padding">
            <div id="resultRows">
                <table class="table table-hover table-striped">
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Documento</th>
                        <th>Tipo</th>
                        <th>Remitente</th>
                        <th>Asunto</th>
                        <th>Fecha de Presentación</th>
                        <th>Estado</th>
                        <th>Detalle Seguimiento</th>
                    </tr>
                    @foreach($list_docs as $key=>$item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->tdocId }}</td>
                            <td>{{ $item->ttypDesc }}</td>
                            <td>{{ $item->tdocDni }}</td>
                            <td>{{ $item->tdocSubject }}</td>
                            <td>{{ $item->tarcDatePres }}</td>
                            <td>{{ $item->tarcStatus }}</td>
                            <td>
                            <?php $enlace = 'doc/tracking/'.$item->tarcExp ?>
                                <a href="javascript:void(0)" onclick="change_menu_to('{{ $enlace }}')">Ver</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
                {!! $list_docs->render() !!}
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

    $(document).on('click','.pagination a',function(evt){
        evt.preventDefault();

        var page = $(this).attr('href').split('page=')[1];
        var period = $('#period_sys').val();

        getRecords(page,period)
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