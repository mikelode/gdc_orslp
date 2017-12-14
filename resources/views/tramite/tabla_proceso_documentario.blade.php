<table class="table table-condensed" cellspacing="0" width="100%" style="font-size: smaller;">
    <thead>
    <tr>
        <th colspan="9">DOCUMENTOS</th>
    </tr>
    <tr>
        <th>Reg.</th>
        <th>Doc</th>
        <th>Remitente.</th>
        <th>Fec.Reg</th>
        <th>Fec.Env</th>
        <th>Env.A</th>
        <th>Det.Env</th>
        <th>Ref.</th>
        <th>Proyecto</th>
    </tr>
    </thead>
    <tbody>
        @foreach($documentos as $doc)
            <tr>
                <td>{{ $doc->tdocRegistro }}</td>
                <td>{{ $doc->ttypDesc.' - '.$doc->tdocNumber }}</td>
                <td>{{ $doc->tdocSender }}</td>
                <td>{{ $doc->thisDateTimeR }}</td>
                <td>{{ $doc->thisDateTimeD }} </td>
                <td>{{ $doc->destino }}</td>
                <td>{{ $doc->thisDscD }}</td>
                <td>{{ $doc->ref }}</td>
                <td>{{ $doc->tpyName }}</td>
            </tr>
        @endforeach
    </tbody>
</table>