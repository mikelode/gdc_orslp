@section('sub-content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Lista de Usuarios</h3>
            </div>
            <div class="box-body no-padding">
                <div class="table-responsive mailbox-messages">
                    <table class="table table-hover table-striped">
                        <tr>
                            <th>#</th>
                            <th>DNI</th>
                            <th>Nombre Completo</th>
                            <th>Dependencia</th>
                            <th>Perfil</th>
                            <th>Accion</th>
                            <th>Estado</th>
                            <th>Contraseña</th>
                        </tr>
                        @foreach($list_users as $key=>$u)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $u->tusId }}</td>
                                <td>{{ $u->tusPaterno.' '.$u->tusMaterno.' '.$u->tusNames }}</td>
                                <td>{{ $u->Dependencia }}</td>
                                <td>
                                    @if($u->tusTypeUser == 'user1')
                                        Operador
                                    @elseif($u->tusTypeUser == 'user2')
                                        Operador
                                    @elseif($u->tusTypeUser == 'admin')
                                        Administrador
                                    @elseif($u->tusTypeUser == 'super')
                                        Super
                                    @endif
                                </td>
                                <td id="{{ $u->tusId }}">
                                    <a href="#" class="btnEdit" data-toggle="modal" data-target="#ueditModal" data-id="{{ $u->tusId }}" data-iddp="{{ $u->tusWorkDep }}" data-nmb="{{ $u->tusPaterno.' '.$u->tusMaterno.' '.$u->tusNames }}">Editar</a>
                                </td>
                                <td>
                                    @if($u->tusState)
                                    <a href="javascript:void(0)" class="changeState" data-state="{{ $u->tusState }}" data-dni="{{ $u->tusId }}">Desactivar</a>
                                    @else
                                    <a href="javascript:void(0)" class="changeState" data-state="{{ $u->tusState }}" data-dni="{{ $u->tusId }}">Activar</a>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-xs btn-danger resetPass" data-dni="{{ $u->tusId }}">Resetear</button>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ueditModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Editar Datos del Usuario: <b></b> </h4>
            </div>
            <form id="frm_update_profile" name="updateFrm">
                {!! csrf_field() !!}
                <div class="modal-body">
                    <input type="hidden" id="kyUser" name="kyUser">
                    <label class="col-xs-4 control-label">Dependencia</label>
                    <div class="col-xs-6">
                        <select class="form-control" name="work_dep">
                            @foreach($dependencies as $dep)
                                <option value="{{ $dep->depId }}"> {{ $dep->depDsc }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div id="profileTable">
                        
                    </div>
                </div>
                <div class="box-footer">
                    <!--<button type="submit" id="btnUpdateProfile" class="btn btn-primary pull-right">Guardar</button>-->
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cerrar</button>
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
        var nmb = btn.data('nmb');
        var idDp = btn.data('iddp');
        var modal = $(this);

        $.get('getProfile/' + id, function(resp){
            
            modal.find('.modal-header b').text(id + ' ' + nmb);
            modal.find('.modal-body #kyUser').val(id);
            modal.find('.modal-body select').val(idDp);
            modal.find('.modal-body #profileTable').html(resp);
        });
    });

    $('#btnUpdateProfile').click(function(e){
        e.preventDefault();
        $.post('settings/updt_profile',$('#frm_update_profile').serialize(), function(response){
            bootbox.alert(response);
            $('#ueditModal').modal('hide');
        }).fail(function(){
            bootbox.alert('Error en la actualización');
        });
    });

    $('.changeState').click(function(e){
        e.preventDefault();
        var state= $(this).data('state')?0:1;
        var dni = $(this).data('dni');
        var msg = state?'ACTIVAR':'DESACTIVAR';

        bootbox.confirm('¿Esta seguro de ' + msg + ' al Usuario: ' + dni + '?', function(rpta){

            if(rpta)
            {
                $.get('settings/updt_state',{active: state, id: dni},function(response){
                    change_to_submenu('settings/list_users');
                    alert(response);
                });
            }

        });

    });

    $('.resetPass').click(function(e){
        e.preventDefault();
        var dni = $(this).data('dni');
        bootbox.confirm('¿Esta seguro de resetear la contraseña del Usuario: ' + dni + '? <br> La nueva constraseña sera él número de DNI del usuario ', function(rpta){

            if(rpta)
            {
                $.get('settings/reset_pass',{idUser: dni}, function(response){
                    change_to_submenu('settings/list_users');
                    alert(response);
                });
            }

        });
    });
});
</script>
@endsection