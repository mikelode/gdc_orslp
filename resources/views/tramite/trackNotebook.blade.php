
@section('htmlheader_title')
    Cuaderno de Trámite
@endsection

@section('main-content')

<section class="content-header">
    <h1>
        Cuaderno de Trámite
        <small>@yield('contentheader_description')</small>
    </h1>
</section>
<section class="content" style="font-size: 12px">

<div class="row">
    <div class="col-md-4">
        <form id="frm-make-day" class="form-inline" method="POST" target="_blank" action="{{ url('make/dayNoteBookPDF') }}">
        {!! csrf_field() !!}
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="box-title">Bitácora de Trabajo:</div>
                </div>
                <div class="box-body">
                    <input type="date" class="form-control" name="dateWork">
                    <a class="btn btn-primary" id="btn-print2">Ver</a>
                    <a class="btn btn-default pull-right  btn-print" data-id="2"><i class="fa fa-print"></i></a>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-4">
        <form id="frm-make-rep" class="form-inline" method="POST" target="_blank" action="{{ url('make/trackNotebookPDF') }}">
        {!! csrf_field() !!}
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="box-title">Estado de Documentos:</div>
                </div>
                <div class="box-body">
                    <input type="date" class="form-control" name="dateNotebook">
                    <a class="btn btn-primary" id="btn-print1">Ver</a>
                    <a class="btn btn-default pull-right btn-print" data-id="1"><i class="fa fa-print"></i></a> 
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="sub-content">
    </div>
</div>

</section>

<script>
$(document).ready(function(){

    $('#btn-print1').click(function(e){
        e.preventDefault();
        var url = 'make/trackNotebook';
        var data = $('#frm-make-rep').serialize();

        $.post(url,data,function(response){
            $('.sub-content').html(response);
        });
    });

    $('#btn-print2').click(function(e){
        e.preventDefault();
        var url = 'make/dayNoteBook';
        var data = $('#frm-make-day').serialize();

        $.post(url,data,function(response){
            $('.sub-content').html(response);
        });
    });

    $('.btn-print').bind('click',function(e){
        e.preventDefault();
        var id = $(this).data('id');

        if(id == 1)
        {
            $('form#frm-make-rep').submit();
        }
        else if(id == 2)
        {
            $('#frm-make-day').submit();
        }

    })

});
</script>

@endsection