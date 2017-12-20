<table class="table table-condensed" cellspacing="0" width="100%" style="font-size: smaller;">
    <thead>
    <tr>
        <th colspan="11">DOCUMENTOS</th>
    </tr>
    <tr>
        <th>Reg.</th>
        <th>Doc</th>
        <th>Remitente.</th>
        <th>Fec.Reg</th>
        <th>Asunto</th>
        <th>Fec.Env</th>
        <th>Env.A</th>
        <th>Det.Env</th>
        <th>Ref.</th>
        <th>T.At.</th>
        <th>Proyecto</th>
    </tr>
    </thead>
    <tbody>
        @foreach($documentos as $doc)
            <tr>
                <td>{{ $doc->tdocRegistro }}</td>
                <td>
                    @if(is_null($doc->tdocPathFile))
                        {{ $doc->ttypDesc.' - '.$doc->tdocNumber }}
                    @else
                        <a href="{{ $doc->tdocPathFile.'/'.$doc->tdocFileName }}" target="_blank">
                        {{ $doc->ttypDesc.' - '.$doc->tdocNumber }}
                        </a>
                    @endif
                </td>
                <td>{{ $doc->tdocSender }}</td>
                <td>{{ Carbon\Carbon::parse($doc->thisDateTimeR)->format('Y-m-d H:i:s a') }}</td>
                <td>{{ $doc->tdocSubject }}</td>
                <td>{{ Carbon\Carbon::parse($doc->thisDateTimeD)->format('Y-m-d H:i:s a') }} </td>
                <td>{{ $doc->destino }}</td>
                <td>{{ $doc->thisDscD }}</td>
                <td>{{ $doc->ref }}</td>
                <td>{{ $doc->tiempo }}</td>
                <td>{{ $doc->tpyName }}</td>
            </tr>
        @endforeach
    </tbody>
</table>