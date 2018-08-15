<table class="table table-condensed" cellspacing="0" width="100%" style="font-size: smaller;">
    <thead>
    <tr>
        <th colspan="11">DOCUMENTOS <small>({{ $documentos[0]->tdocExp.' - '.$documentos[0]->tdocExp1 }})</small> </th>
    </tr>
    <tr>
        <th>Reg.</th>
        <th>Doc</th>
        <th>Remitente.</th>
        <th>Fec.Rec</th>
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
                <td>{{ $doc->tdocJobSender.' '.$doc->tdocSender. ' ('.$doc->dep.')' }}</td>
                <td>{{ $doc->tdocDate ? Carbon\Carbon::parse($doc->tdocDate)->format('d-m-Y') : null }}
                    {{-- $doc->thisDateTimeR ? Carbon\Carbon::parse($doc->thisDateTimeR)->format('d-m-Y H:i a') : null --}}
                </td>
                <td>{{ $doc->tdocSubject }}</td>
                <td>{{ $doc->thisDateTimeD ? Carbon\Carbon::parse($doc->thisDateTimeD)->format('d-m-Y H:i a') : null }} </td>
                <td>{{ $doc->destino }}</td>
                <td>{{ $doc->thisDscD }}</td>
                <td>{{ $doc->ref }}</td>
                <td>{{ $doc->tiempo }}</td>
                <td>{{ $doc->tpyName }}</td>
            </tr>
        @endforeach
    </tbody>
</table>