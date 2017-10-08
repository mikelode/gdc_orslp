<table class="table" id="findTable">
    <thead>
        <tr>
            <th style="width: 10px">#</th>
            <th>Documento</th>
            <th>Registro</th>
            <th>Tipo</th>
            <th>Asunto</th>
            <th>Fecha de Presentación</th>
            <th>Estado</th>
            <th>Detalle Seguimiento</th>
        </tr>
    </thead>
    <tbody>
        @foreach($list_docs as $key=>$item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->tdocId }}</td>
                <td>{{ $item->tdocRegistro }}</td>
                <td>{{ $item->docTipo }}</td>
                <td>{{ $item->tdocSubject }}</td>
                <td>{{ $item->tdocDate }}</td>
                <td>{{ $item->tdocStatus }}</td>
                <td>
                <?php $enlace = 'doc/tracking/'.$item->tdocId ?>
                    <a href="javascript:void(0)" onclick="change_menu_to('{{ $enlace }}')">Ver</a>
                </td>
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