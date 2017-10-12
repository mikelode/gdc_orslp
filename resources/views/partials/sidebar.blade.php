<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{asset('/img/usuario.png')}}" class="img-circle" alt="MDV" />
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->tusNames }}</p><br>
                <!-- Status -->
                <a href="javascript:void(0)" id="btn_connect_chat" data-id="{{ Auth::user()->tusId }}" data-nick="{{ Auth::user()->tusNames }}" data-dep="{{ Auth::user()->workplace->depDsc }}"><i class="fa fa-circle text-success"></i>Conectado</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        <div class="sidebar-form">
            <div class="input-group">
                <input type="text" id="period_sys" class="form-control" value="{{ Carbon\Carbon::now()->year }}" style="text-align: center;" />
              <span class="input-group-btn">
                <div name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-calendar"></i></div>
              </span>
            </div>
        </div>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">MENU</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="active"><a href="javascript:void(0)" onclick="change_menu_to('homei')"><i class='fa fa-home'></i> <span>Inicio</span></a></li>

            @if(Auth::user()->can(1))
            <li><a href="javascript:void(0)" onclick="change_menu_to('doc/register')"><i class='glyphicon glyphicon-edit'></i> <span>Registrar Documento</span></a></li>
            @endif
            @if(Auth::user()->can(8))
            <li><a href="javascript:void(0)" onclick="change_menu_to('doc/edit')"><i class='glyphicon glyphicon-list-alt'></i> <span>Modificar Documento</span></a></li>
            @endif
            @if(Auth::user()->can(2))
            <li><a href="javascript:void(0)" onclick="change_menu_to('doc/menu')"><i class='glyphicon glyphicon-inbox'></i> <span>Bandeja de Entrada</span></a></li>
            @endif
            @if(Auth::user()->can(3))
            <li><a href="javascript:void(0)" onclick="change_menu_to('doc/outbox')"><i class='glyphicon glyphicon-send'></i> <span>Bandeja de Salida</span></a></li>
            @endif
            @if(Auth::user()->can(4))
            <li><a href="javascript:void(0)" onclick="change_menu_to('doc/consult')"><i class='glyphicon glyphicon-search'></i> <span>Consulta y Seguimiento</span></a></li>
            @endif
            <!--
            <li class="treeview">
                <a href="#"><i class='fa fa-link'></i> <span>Multilevel</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="#">Link in level 2</a></li>
                    <li><a href="#">Link in level 2</a></li>
                </ul>
            </li> -->
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
