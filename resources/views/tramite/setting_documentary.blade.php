@section('htmlheader_title')
    Configuración del Sistema
@endsection

@section('main-content')
<section class="content-header">
    <h1>
        Configuración del Sistema
        <small>@yield('contentheader_description')</small>
    </h1>
</section>
<section class="content" style="font-size: 12px">
    <div class="row">
        <div class="col-md-3">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Herramientas</h3>
                    <div class="box-tools">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="javascript:void(0)" onclick="change_to_submenu('settings/new_user')"><i class="fa fa-user-plus"></i>Nuevo Usuario</a></li>
                        <li><a href="javascript:void(0)" onclick="change_to_submenu('settings/list_users')"><i class="fa fa-users"></i>Relación de Usuarios</a></li>
                        <!--<li><a href="javascript:void(0)"><i class="fa fa-suitcase"></i>Agregar Personal</a></li>-->
                        <li><a href="javascript:void(0)" onclick="change_to_submenu('settings/add_doc')"><i class="fa fa-file-pdf-o"></i>Crear Tipos de Documento</a></li>
                        <li><a href="javascript:void(0)" onclick="change_to_submenu('settings/list_asoc')"><i class="fa  fa-university"></i>Listar Asociaciones</a></li>
                        <li><a href="javascript:void(0)" onclick="change_to_submenu('settings/new_afil')"><i class="fa fa fa-address-card-o"></i>Registrar Afiliado</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="sub-content">

            </div>
        </div>
    </div>
</section>

<script>
$(function(){
});
</script>
@endsection