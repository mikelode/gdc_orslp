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
	        </div>
	        <div class="box-header-img"></div>
	    </div>
	    <div class="body-pdf">
            <div class="subtitle-pdf">
                <div>FECHA DE PRESENTACION DEL DOCUMENTO: {{ $dateDaily }}</div>
            </div>
            <div class="content-pdf">
                <table class="tablePDF">
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
                                <span>
                                    {{ $r->thisDepS == $r->thisDepT ? 'REGISTRADO' : 'RECEPCIONADO' }}
                                </span>
                            </td>
                            <td>
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
                            <td></td>
                        </tr>
                            @if($r->thisFlagA)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $r->tdocId }}</td>
                                    <td>{{ $r->tdocType }}</td>
                                    <td><span>ATENDIDO</span></td>
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
                                    <td><span>DERIVADO</span></td>
                                    <td>
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
        <div class="footer-pdf">
            <p class="page">Página </p>
        </div>
	</body>
</html>