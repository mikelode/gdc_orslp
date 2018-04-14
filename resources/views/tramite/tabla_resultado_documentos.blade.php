<table class="display compact" cellspacing="0" width="100%" id="docEncontrado">
    <thead>
    <tr>
        <th colspan="7">DOCUMENTOS</th>
    </tr>
    <tr>
        <th>CUD</th>
        <th>Reg.</th>
        <th>Doc</th>
        <th>Num.</th>
        <th>Fec.</th>
        <th>Remitente</th>
        <th>Asunto</th>
    </tr>
    </thead>
    <tbody>
        @foreach($docs as $Fila)
            <tr>
                <td>{{ $Fila['tdocId'] }}</td>
                <td>
                    <a href="javascript:void(0)" onclick="mostrar_documento('{{ $Fila['tdocId'] }}','{{ $funcion }}')">
                        {{ $Fila['tdocRegistro'] }}
                    </a>
                </td>
                <td>{{ $Fila['ttypDesc'] }}</td>
                <td>{{ $Fila['tdocNumber'] }}</td>
                <td>{{ $Fila['tdocDate'] }}</td>
                <td>{{ $Fila['tdocSender'] }} </td>
                <td>{{ $Fila['tdocSubject'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('#docEncontrado').DataTable({
        "language":{
            "url": "plugins/DataTables/Spanish.json"
        }
    });
</script>