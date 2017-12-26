@section('sub-content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Registrar Nuevo Usuario</h3>
            </div>
            <form class="form-horizontal" id="frm_reg_user">
                {!! csrf_field() !!}
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Perfil de Usuario</label>
                        <div class="col-md-10">
                            <select class="form-control imput-sm" name="profile_user" id="profUser">
                                <option value="user1" selected>Operador</option>
                                <option value="user2">Superior</option>
                                <option value="admin">Administrador del Sistema</option>
                                <!-- <option value="super">Super</option> -->
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Documento</label>
                        <div class="col-md-10">
                            <input type="text" name="dni_user" id="dni_user_input" class="form-control input-sm" placeholder="DNI del Usuario">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nombres</label>
                        <div class="col-md-10">
                            <input type="text" name="name_user" id="name_user_input" class="form-control input-sm text-uppercase" placeholder="Nombres">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Apellido Paterno</label>
                        <div class="col-md-10">
                            <input type="text" name="patern_user" id="patern_user_input" class="form-control input-sm text-uppercase" placeholder="Apellido Paterno">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Apellido Materno</label>
                        <div class="col-md-10">
                            <input type="text" name="matern_user" id="matern_user_input" class="form-control input-sm text-uppercase" placeholder="Apellido Materno">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Dependencia</label>
                        <div class="col-md-10">
                            <select class="form-control input-sm" name="dependency_user">
                                @foreach($dependencys as $dep)
                                    <option value="{{ $dep->depId }}">{{ $dep->depDsc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" id="btnSubmitUser" class="btn btn-primary pull-right">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(function(){
    $('#btnSubmitUser').click(function(e){
        e.preventDefault();
        $.post('settings/new_user',$('#frm_reg_user').serialize(), function(response){
            bootbox.alert(response);
            $('#profUser').val('user1');
            $('#dni_user_input').val('');
            $('#name_user_input').val('');
            $('#patern_user_input').val('');
            $('#matern_user_input').val('');
        }).fail(function(result, textStatus, xhr){
            var errors = result.responseJSON;
            console.log(errors);
            bootbox.alert('Error: <br> Revise los campos ingresados. <br> Código: ' + xhr);
        });
    });
});
</script>

@endsection