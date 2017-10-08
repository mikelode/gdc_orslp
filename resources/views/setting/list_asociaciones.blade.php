@section('sub-content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Lista de Asociaciones</h3>
                <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#asocModal">Agregar Asociacion</a>
            </div>
            <div class="box-body no-padding">
                <div class="table-responsive mailbox-messages" style="height: auto;">
                    <table class="table table-hover table-striped table-responsive">
                        <tr>
                            <th>#</th>
                            <th>Año</th>
                            <th>Cut elegibilidad</th>
                            <th>Cut tecnologia</th>
                            <th>Convenio</th>
                            <th>Organización</th>
                            <th>Plan de negocios</th>
                            <th>RUC</th>
                            <th>Cadena</th>
                            <th>Dirección</th>
                            <th>Provincia</th>
                            <th>Distrito</th>
                            <th>DNI Presidente</th>
                            <th>DNI Coordinador</th>
                            <th>Inicio</th>
                            <th>Final</th>
                            <th></th>
                        </tr>
                        @foreach($list_asoc as $key=>$u)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $u->tasAnio }}</td>
                                <td>{{ $u->tasCutElig }}</td>
                                <td>{{ $u->tasCutTec }}</td>
                                <td>{{ $u->tasConvenio }}</td>
                                <td>{{ $u->tasOrganización }}</td>
                                <td>{{ $u->tasNegocio }}</td>
                                <td>{{ $u->tasRuc }}</td>
                                <td>{{ $u->tasCadena }}</td>
                                <td>{{ $u->tasDireccion }}</td>
                                <td>{{ $u->tasProv }}</td>
                                <td>{{ $u->tasDist }}</td>
                                <td>{{ $u->tasPresidente }}</td>
                                <td>{{ $u->tasCoordinador }}</td>
                                <td>{{ $u->tasVigenciaIni }}</td>
                                <td>{{ $u->tasVigenciaFin }}</td>

                                <td id="{{ $u->tasId }}">
                                    <a data-id="{{ $u->tasId }}">Editar</a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="asocModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Registrar Asociacion: <b></b> </h4>
            </div>
            <form class="form-horizontal" id="frm_add_asoc" name="addFrm">
                {!! csrf_field() !!}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-xs-4 control-label">Año</label>
                        <div class="col-xs-6">
                            <input type="number" name="asocAnio" id="input" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">CUT Elegibilidad</label>
                        <div class="col-xs-6">
                            <input type="text" name="asocCutelig" id="input" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">CUT Tecnologia</label>
                        <div class="col-xs-6">
                            <input type="text" name="asocCuttec" id="input" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">Convenio</label>
                        <div class="col-xs-6">
                            <input type="text" name="asocConv" id="input" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">Organización</label>
                        <div class="col-xs-6">
                            <input type="text" name="asocOrg" id="input" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">Negocio</label>
                        <div class="col-xs-6">
                            <input type="text" name="asocNeg" id="input" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">RUC</label>
                        <div class="col-xs-6">
                            <input type="text" name="asocRuc" id="input" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">Cadena</label>
                        <div class="col-xs-6">
                            <input type="text" name="asocCad" id="input" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">Dirección</label>
                        <div class="col-xs-6">
                            <input type="text" name="asocDir" id="input" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">Provincia</label>
                        <div class="col-xs-6">
                            <input type="text" name="asocProv" id="input" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">Distrito</label>
                        <div class="col-xs-6">
                            <input type="text" name="asocDist" id="input" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">DNI Presidente</label>
                        <div class="col-xs-6">
                            <input type="text" name="asocPresi" id="input" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">DNI Coordinador</label>
                        <div class="col-xs-6">
                            <input type="text" name="asocCoord" id="input" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">Inicio Vigencia</label>
                        <div class="col-xs-6">
                            <input type="date" name="asocVigini" id="input" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">Final Vigencia</label>
                        <div class="col-xs-6">
                            <input type="date" name="asocVigfin" id="input" class="form-control">
                        </div>
                    </div>
                </div>
            </form>
            <div class="box-footer">
                <button type="button" id="btnAddAsoc" class="btn btn-primary pull-right">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ueditModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Actualizar: <b></b> </h4>
            </div>
            <form class="form-horizontal" id="frm_update_profile" name="updateFrm">
                {!! csrf_field() !!}
                <div class="modal-body">
                    <div><input type="hidden" id="kyUser" name="kyUser"></div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">Dependencia</label>
                        <div class="col-xs-6">
                            <select class="form-control" name="work_dep">
                                
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-hover table-striped">
                        </table>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="button" id="btnUpdateProfile" class="btn btn-primary pull-right">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(function(){
    $('#ueditModal').on('show.bs.modal',function(e){
        var btn = $(e.relatedTarget);
        var id = btn.data('id');
        var idDp = btn.data('iddp');
        var modal = $(this);

        $.getJSON('getProfile/' + id).done(function(resp){

            var output = "<tr><th>#</th><th>Funcionalidad</th><th>Estado</th></tr>";
            var i;

            for(i=0;i<resp.length;i++)
            {
                output += "<tr>";
                output += "<td>" + (i+1) + "</td>";
                output += "<td>" + resp[i].tsysDescF + "</td>";

                if(resp[i].trolEnable == 1)
                    output += "<td><input name='stateF[]' type='checkbox' value='" + resp[i].trolIdSyst + "' checked></td>";
                else if(resp[i].trolEnable == 0)
                    output += "<td><input name='stateF[]' type='checkbox' value='" + resp[i].trolIdSyst + "'></td>";

                output += "</tr>";
            }

            modal.find('.modal-header b').text(id);
            modal.find('.modal-body #kyUser').val(id);
            modal.find('.modal-body select').val(idDp);
            modal.find('.modal-body table').html(output);
        });
    });

    $('#asocModal').on('show.bs.modal');

    $('#btnAddAsoc').click(function(e){
        e.preventDefault();
        var data = $('#frm_add_asoc').serialize();
        var url = 'settings/new_asoc';

        $.post(url, data, function(response){
            alert(response);
            $('#asocModal').modal('hide');
        });
    })



    
});
</script>
@endsection