@section('htmlheader_title')
    Cuaderno de Recepción
@endsection

@section('sub-content')
<section class="content" style="font-size: 12px">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-book"></i>
                    <div class="box-title">FECHA DE PRESENTACION DEL DOCUMENTO: {{ $dateDaily }}</div>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-striped">
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>DOCUMENTO</th>
                            <th>TIPO</th>
                            <th>OPERACIÓN</th>
                            <th>DEPENDENCIA</th>
                            <th>HORA</th>
                            <th>ASUNTO</th>
                            <th>ANEXOS</th>
                            <th>GLOSA DE OPERACIÓN</th>
                        </tr>
                        <?php $i = 0; ?>
                        @foreach($dailyR as $key=>$r)
                            @if($r->thisFlagR)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $r->tdocId }}</td>
                                <td>{{ $r->tdocType }}</td>
                                <td>
                                    <span class="label label-warning">
                                        {{ $r->thisDepS == $r->thisDepT ? 'REGISTRADO' : 'RECEPCIONADO' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $r->thisDepS == $r->thisDepT ? 'En:' : 'De:' }}
                                    <ul style="margin-left: -25px;">
                                        <li>{{ $r->SourceDsc }}</li>
                                    </ul>
                                </td>
                                <td>{{ $r->thisDateTimeR }}</td>
                                <td>{{ $r->tdocSubject }}</td>
                                <td>
                                    <ul style="margin-left: -25px;">
                                    @foreach($attaches as $at)
                                        @if($r->tdocId == $at->tdocId)
                                            <li>{{ $at->tdaTypAnx.'-'.$at->tdaNumAnex.':'.$at->tdaDsc }}</li>
                                        @endif
                                    @endforeach
                                    </ul>
                                </td>
                            </tr>
                                @if($r->thisFlagA)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $r->tdocId }}</td>
                                        <td>{{ $r->tdocType }}</td>
                                        <td><span class="label label-success">ATENDIDO</span></td>
                                        <td></td>
                                        <td>{{ $r->thisDateTimeA }}</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $r->thisDscA }}</td>
                                    </tr>
                                @elseif($r->thisFlagD)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $r->tdocId }}</td>
                                        <td>{{ $r->tdocType }}</td>
                                        <td><span class="label label-primary">DERIVADO</span></td>
                                        <td>
                                            Hacia:
                                            <ul style="margin-left: -25px;">
                                            @foreach($dailyD as $target)
                                                @if($r->thisId == $target->thisIdSourceD)
                                                    <li>{{ $target->TargetDsc }}</li>
                                                @endif
                                            @endforeach
                                            </ul>
                                        </td>
                                        <td>{{ $r->thisDateTimeD }}</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $r->thisDscD }}</td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection