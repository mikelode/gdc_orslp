<table class="table" id="findTable">
    <thead>
        <tr>
            <th style="width: 10px">#</th>
            <th>Proyecto</th>
            <th>Registro</th>
            <th>Tipo</th>
            <th>Documento</th>
            <th>Remitente</th>
            <th>Fecha de Registro</th>
        </tr>
    </thead>
    <tbody>
        @foreach($list_docs as $key=>$item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->tpyName }}</td>
                <td>{{ $item->tdocRegistro }}</td>
                <td>{{ $item->ttypDesc }}</td>
                <td>{{ $item->tdocNumber }}</td>
                <td>{{ $item->tdocSender }}</td>
                <td>{{ $item->tdocDate }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $(document).ready(function(){
        $('#findTable').DataTable({
            "language":{
                "url": "plugins/DataTables/Spanish.json"
            },
            "processing": true,
        });
    });
</script>