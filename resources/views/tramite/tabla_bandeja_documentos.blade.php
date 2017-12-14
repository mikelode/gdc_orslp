<table class="display compact" cellspacing="0" width="100%" id="docBandeja">
    <thead>
        <tr>
            <th></th>
            <th>Reg.</th>
            <th>Doc.</th>
            <th>Remitente</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Plazo</th>
            
        </tr>
    </thead>
    <tbody>
        @foreach ($inbox as $doc)
        <tr data-keys = "{{ $doc->tdocId.'-'.$doc->tdocExp }}">
            <td class="details-control" onclick="historial(this)">
            </td>
            <td>
                <a href="javascript:void(0)" onclick="showDocDetail('{{ $doc->tdocId }}')">{{ $doc->tdocRegistro }}</a>
            </td>
            <td>{{ $doc->ttypDesc.' - '.$doc->tdocNumber }}</td>
            <td>{{ $doc->tdocSender }}</td>
            <td>{{ $doc->tdocDate }}</td>
            <td>{{ $doc->tarcStatus }}</td>
            <td>
                @if($doc->tarcStatus != 'atendido')
                    @if($doc->plazo <= 4)
                        <button type="button" class="btn btn-success btn-xs">Vigente</button>
                    @endif
                    @if($doc->plazo > 4 && $doc->plazo <= 7)
                        <button type="button" class="btn btn-warning btn-xs">Por vencerse</button>
                    @endif
                    @if($doc->plazo >= 7)
                        <button type="button" class="btn btn-danger btn-xs">Vencido</button>
                    @endif
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<script>
    $('#docBandeja').DataTable({
        "language":{
            "url": "plugins/DataTables/Spanish.json"
        }
    });
</script>