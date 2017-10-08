@extends('appError')

@section('htmlheader_title')
    Service unavailable
@endsection

@section('main-content')

    <div class="error-page">
        <h2 class="headline text-red">503</h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-red"></i> Oops! Algo esta yendo mal!.</h3>
            <p>
                Estamos trabajando para solucionarlo justo ahora
            </p>

            <img src="{{ asset('img/mantenimiento.png') }}">
        </div>
    </div><!-- /.error-page -->
@endsection