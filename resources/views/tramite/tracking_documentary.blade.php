@section('htmlheader_title')
    Consulta y Seguimiento Documentario
@endsection

@section('main-content')

<section class="content-header">
    <h1>
        Consulta y Seguimiento Documentario
        <small>@yield('contentheader_description')</small>
    </h1>
</section>
<section class="content" style="font-size: 12px">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">Hoja de Ruta</div>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Documento</th>
                            <th>Dep. Origen</th>
                            <th>Dep. Destino</th>
                            <th>Operaciones</th>
                            <th>Fecha y Hora</th>
                            <th style="width: 25px">Duración</th>
                        </tr>
                        @foreach($time_line as $key=>$item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->thisDoc }}</td>
                            <td>{{ $item->SourceDsc }}</td>
                            <td>{{ $item->TargetDsc }}</td>
                            <td>
                                @if($item->thisFlagR)
                                    {{ $item->thisDepS == $item->thisDepT?'Registrado':'Recepcionado' }}
                                    @if($item->thisFlagD)
                                        <br> Derivado 
                                        <br> ({{ $item->DocuD==''?'Simple':'Con el:'.$item->DocuD }})
                                    @elseif($item->thisFlagA)
                                        <br> Atendido
                                    @endif
                                @else
                                    Sin Acción
                                @endif
                            </td>
                            <td>
                                @if($item->thisFlagR)
                                    R: {{ $item->thisDateTimeR }}
                                    @if($item->thisFlagD)
                                        <br> D: {{ $item->thisDateTimeD }}
                                    @elseif($item->thisFlagA)
                                        <br> A: {{ $item->thisDateTimeA }}
                                    @endif
                                @else
                                    Sin Acción
                                @endif
                            </td>
                            <td>
                                @if($item->thisFlagD)
                                    {{ $item->DuraD }}
                                @elseif($item->thisFlagA)
                                    {{ $item->DuraA }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection