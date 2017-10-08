<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title></title>
        <!-- CSS Personalizado -->
        <link href="{{ asset('/css/tramappPDF.css') }}" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="header-pdf">
            <div class="box-header-img">
                <img src="{{ asset('img/a.png') }}" class="img-circle" style="height: 100px; width: 100px;">
            </div>
            <div class="box-header-title">
                <h3>MUNICIPALIDAD DISTRITAL DE VILCABAMBA</h3>
                <h4>{{ Auth::user()->workplace->depDsc }}</h4>
                <div class="subtitle-pdf">
                    FECHA DE TRABAJO: {{ $dateDaily }}
                </div>
            </div>
            <div class="box-header-img"></div>
        </div>
        <div class="body-pdf">
            <table class="tablePDF">
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
                                <td></td>
                            </tr>
                        @endif
                    @else
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $r->tdocId }}</td>
                        <td>{{ $r->tdocType }}</td>
                        <td><span class="label label-primary">DERIVADO</span></td>
                        <td>
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
        <div class="footer-pdf">
            <p class="page">Página </p>
        </div>
    </body>
</html>