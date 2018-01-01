@extends('auth.auth')

@section('htmlheader_title')
    DOCS-SUPERVISION
@endsection

@section('content')
<body class="login-page">
    <div class="container">
        <div class="panel panel-primary" style="margin-top: 8%">
            <div class="panel-heading" style="background-color: #555758">
                <div class="panel-title"><h3 class="loginTitle"><a href="#">Oficina Regional de Supervisión y Liquidación de Obras</a></h3></div>
            </div>
            <div class="panel-body">
                <div class="col-md-6">
                    <div class="backgore"></div>
                </div>
                <div class="col-md-6">
                    <div id="loginbox" style="margin-top:10%;"> <!-- class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">-->
                        <div class="panel panel-info" >
                            <div class="panel-heading">
                                <div class="panel-title">Login - Gestión Documentaria ORSYLP</div>
                                <div style="float:right; font-size: 80%; position: relative; top:-10px"></div>
                            </div>
                            <div style="padding-top:20px" class="panel-body" >
                                <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                                <div class="col-md-4">
                                    <img src="{{ asset('img/gore2.png') }}" width="150" height="150" />
                                </div>
                                <div class="col-md-8">
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <strong>Plop!</strong> Hay algún problema con sus datos.<br>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                    <form id="loginform" class="form-horizontal" role="form" method="post" action="{{ url('/auth/login') }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div style="margin-top:10px;margin-bottom: 15px" class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                            <input id="login-username" type="text" class="form-control" name="tusNickName" value="" placeholder="Nombre de Usuario">
                                        </div>
                                        <div style="margin-bottom: 15px;" class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                            <input id="login-password" type="password" class="form-control" name="password" placeholder="Contraseña">
                                        </div>
                                        <div style="margin-bottom: 30px;" class="form-group">
                                            <div class="col-sm-12 controls">
                                                <button type="submit" id="btn-login" class="btn btn-success"><i class="icon-hand-right"></i> &nbsp; Entrar</button>
                                                <a id="btn-refresh" href="#" class="btn btn-primary">Refrescar </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                 <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12 control">
                                            <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                                                symva-2017 &copy; copyright
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>

$(document).ready(function(){

    $('#login-username').keypress(function(e){

        if(e.which == 13)
        {
            $('#login-password').focus();
            $('#login-password').select();
            e.preventDefault();
        }

    });

});

</script>

@endsection
