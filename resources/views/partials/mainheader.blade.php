<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{{ url('/') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>AI</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>AGRO</b>IDEAS</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <small>
                {{ strtoupper(Auth::user()->workplace->depDsc) }}
                <input type="hidden" id="keydp" value="{{ Auth::user()->tusWorkDep }}">
            </small>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="alert-box">
                        <i class="fa fa-envelope-o"></i>
                        @if($notifications != 0)
                            <span class="label label-success">
                                <div class="alertMessages">{{ $notifications }}</div>
                            </span>
                        @endif
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"><b> Tiene @if($notifications != 0) <div class="alertMessages" style="display: inline;">{{ $notifications }}</div>@else 0 @endif documentos pendientes</b>
                        </li>
                        <li>
                            <!-- inner menu: contains the messages -->
                            <ul class="menu">
                                <li><!-- start message -->
                                    <a>
                                        <div class="pull-left">
                                            <!-- User Image -->
                                            <img src="{{ asset('img/agroideas.jpg') }}" class="img-circle" alt="MDV"/>
                                        </div>
                                        <!-- Message title and timestamp -->
                                        <h4>
                                            Atención
                                        </h4>
                                        <!-- The message -->
                                        <p>No olvide atender o derivar los <br>documentos</p>
                                    </a>
                                </li><!-- end message -->
                            </ul><!-- /.menu -->
                        </li>
                        <li class="footer"><a href="javascript:void(0)" onclick="change_menu_to('doc/menu')">Bandeja de Entrada</a></li>
                    </ul>
                </li><!-- /.messages-menu -->

                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="{{ asset('/img/usuario.png') }}" class="user-image" alt="AGROIDEAS"/>
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{ Auth::user()->tusNames }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{ asset('/img/agroideas2.jpg') }}" class="img-circle" alt="User Image" />
                            <p>
                                {{ Auth::user()->tusNames }}
                                <small>{{ Auth::user()->workplace->depDsc }}</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        {{--<li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="#">Followers</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Sales</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Friends</a>
                            </div>
                        </li>--}}
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="javascript:void(0)" onclick="change_menu_to('settings/updt_pass')" class="btn btn-default btn-flat">Contraseña</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ url('/auth/logout') }}" class="btn btn-default btn-flat">Salir</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-cloud"></i></a>
                </li>
            </ul>
        </div>
    </nav>

    {!! Form::open(['route' => ['alerts',':EXP_ID'], 'method' => 'POST', 'id' => 'form-alerts']) !!}
    {!! Form::close() !!}

</header>