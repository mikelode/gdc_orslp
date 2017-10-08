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
                    <div class="box-title">FECHA DE TRABAJO: {{ $dateDaily }}</div>
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
                            <th>GLOSA DE OPERACIÓN</th>
                        </tr>
                        <?php $i = 0; ?>
                        @foreach($daily as $key=>$r)
                            @if($r->thisDepT == Auth::user()->tusWorkDep)
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
                                        <td>
                                            <?php $dt = new DateTime($r->thisDateTimeR) ?>
                                            {{ $dt->format('H:i:s A') }}
                                        </td>
                                        <td>{{ $r->tdocSubject }}</td>
                                        <td></td>
                                    </tr>
                                    @if($r->thisFlagA)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $r->tdocId }}</td>
                                            <td>{{ $r->tdocType }}</td>
                                            <td>
                                                <span class="label label-success">
                                                    ATENDIDO
                                                </span>
                                            </td>
                                            <td></td>
                                            <td>
                                                <?php $dt = new DateTime($r->thisDateTimeA) ?>
                                                {{ $dt->format('H:i:s A') }}
                                            </td>
                                            <td>{{ $r->tdocSubject }}</td>
                                        </tr>
                                    @endif
                                @else
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $r->tdocId }}</td>
                                        <td>{{ $r->tdocType }}</td>
                                        <td>
                                            <span class="label label-danger">
                                                SIN RECEPCION
                                            </span>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $r->tdocSubject }}</td>
                                    </tr>
                                @endif
                            @else
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $r->tdocId }}</td>
                                <td>{{ $r->tdocType }}</td>
                                <td><span class="label label-primary">DERIVADO</span></td>
                                <td>
                                    Hacia:
                                    <ul style="margin-left: -25px;">
                                        <li>
                                            {{ $r->TargetDsc }}
                                            @if($r->thisFlagR)
                                                <small style="color: #0000ff">(Recepcionado)</small>
                                            @else
                                                <small style="color: red">(No Recepcionado)</small>
                                            @endif
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    <?php $dt = new DateTime($r->dateTimeD) ?>
                                    {{ $dt->format('H:i:s A') }}
                                </td>
                                <td></td>
                                <td>{{ $r->dscD }}</td>
                            </tr>
                            @endif
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection