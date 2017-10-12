
@section('htmlheader_title')
    Registrar
@endsection

@section('main-content')

<section class="content-header">
    <div class="row">
        <div class="col-md-4">
            <h4>Modificar Datos del Documento de Código:</h4>
        </div>
        <div class="col-md-2">
            <input type="text" name="doc_id" id="doc_id" class="form-control">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-primary" id="btnGetDocumentData">
                <span class="glyphicon glyphicon-search"></span> Encontrar
            </button>
        </div>
        <div class="col-md-4"></div>
    </div>
</section>
<section class="content">

    @include('partials.messages')

    <div id="detail_document">
        
        <form id="frm_edit_doc" enctype="multipart/form-data">
        {!! csrf_field() !!}
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Datos del Documento</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>N° de Registro</label>
                                    <input type="number" name="nreg_doc" id="nreg" class="form-control input-sm" placeholder="Registro en el cuaderno">
                                </div>
                                <div class="col-md-6">
                                    <label>Fecha de Presentación (*)</label>
                                    <input type="date" name="date_doc" id="date" class="form-control input-sm">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Folio</label>
                                    <input type="number" name="folio_doc" id="folio" class="form-control input-sm" placeholder="Cantidad de Hojas">
                                </div>
                                <div class="col-md-6">
                                    <label>Tipo (*)</label>
                                    <select name="type_doc" id="tipo" class="form-control input-sm">
                                        @foreach($tipos as $t)
                                            <option value="{{ $t->ttypDoc }}">{{ $t->ttypDesc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="subject_doc">Asunto</label>
                                    <textarea name="subject_doc" id="subject" class="form-control text-uppercase" rows="3" placeholder="ASUNTO del documento (*)"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <small>Los campos con un (*) son obligatorios</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Datos del remitente</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <select name="asoc_doc" id="asocId">
                                        @foreach($assoc as $as)
                                            <option value="{{ $as->tasId }}">{{ $as->tasOrganizacion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 20px;">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="dni_sender" id="dni_sender_input" class="form-control input-sm" placeholder="DNI del Remitente">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="name_sender" id="name_sender_input" class="form-control input-sm text-uppercase" placeholder="Nombres (*)">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="patern_sender" id="patern_sender_input" class="form-control input-sm text-uppercase" placeholder="Apellido Paterno (*)">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="matern_sender" id="matern_sender_input" class="form-control input-sm text-uppercase" placeholder="Apellido Materno (*)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12">
                        <input type="file" class="form-control" name="file_doc" accept="application/pdf">
                        <progress class="form-control" value="0"></progress>
                    </div>
                </div>
            </div>
            <div class="row" style="text-align: center;">
                <div class="col-md-12">
                    <!--<button type="submit" id="btnSubmit" class="btn btn-primary btn-lg">Registrar</button>-->
                    <input type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary btn-lg" value="Registrar"    >
                </div>
            </div>
        </form>

    </div>
</section>

<script>
$(document).ready(function(){
    
    $('#asocId').select2({
        width: '100%'
    });

    $('#btnGetDocumentData').click(function(e){
        e.preventDefault();

        var doc = $('#doc_id').val();
        var url = 'doc/detail/' + $.trim(doc);

        $.get(url, function(data){
            $('#nreg').val(data.tdocRegistro);
            $('#date').val();
            $('#folio').val();
            $('#tipo').val();
            $('#subject').val();
            $('#asocId').val();
            $('#dni_sender_input').val();
            $('#patern_sender_input').val();
            $('#matern_sender_input').val();
        });
    });

    $('#frm_reg_doc').submit(function(e){

        e.preventDefault();

        var form = $('#frm_reg_doc')[0];
        var formdata = new FormData(form);

        $.ajax({
            type: 'post',
            url: 'doc/register',
            data: formdata,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response){
                bootbox.alert(response);
                change_menu_to('doc/outbox');
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

        /*

        e.preventDefault();


        $.post('doc/register',$('#frm_reg_doc').serialize(), function(response){
            bootbox.alert(response);
            change_menu_to('doc/outbox');
        }).fail(function(result, textStatus, xhr){
            var errors = result.responseJSON;
            console.log(errors);
            bootbox.alert({
                message: 'Error:<br>Revise los campos ingresados.<br>Código: ' + xhr
            });
        });*/
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
});
</script>

@endsection