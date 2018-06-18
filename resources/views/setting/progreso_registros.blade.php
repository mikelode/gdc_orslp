<div class="container-fluid">
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h5 class="panel-title">Opciones:</h5>
                </div>
                <div class="panel-body">
                    <form action="progress/consult" id="frmConsultProgress">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="">Usuario</label>
                            <select name="nslcUser" class="form-control" id="slcUser">
                                <option value="NA">--Seleccione--</option>
                                @foreach($usuarios as $i=>$usuario)
                                    <option value="{{ $usuario->tusId }}">{{ $usuario->tusNames . ' ' . $usuario->tusPaterno . ' ' . $usuario->tusMaterno }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Desde</label>
                            <input type="date" class="form-control" name="ntxtDateFrom" id="txtDateFrom">
                        </div>
                        <div class="form-group">
                            <label for="">Hasta</label>
                            <input type="date" class="form-control" name="ntxtDateTo" id="txtDateTo">
                        </div>
                    </form>
                </div>
                <div class="panel-footer">
                    <button type="button" class="btn btn-primary" onclick="get_progress_user($('#frmConsultProgress'))">Consultar</button>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div id="content-progress">

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var hoy = new Date();
    var dd = hoy.getDate();
    var mm = hoy.getMonth()+1; //January is 0!
    var yyyy = hoy.getFullYear();

    if(dd<10) {
        dd = '0'+dd
    }

    if(mm<10) {
        mm = '0'+mm
    }

    today = yyyy + '-' + mm + '-' + dd;

    $('#txtDateTo').val(today);
    $('#txtDateFrom').val('2018-06-18');

</script>