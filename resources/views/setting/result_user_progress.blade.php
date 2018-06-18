<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">Acciones realizadas por el usuario: {{ $usuario->tusNames . ' ' . $usuario->tusPaterno . ' ' . $usuario->tusMaterno }}</h4>
            </div>
            <div class="box-body">
                <table class="table" style="font-size: 13px;">
                    <thead>
                    <tr>
                        <th>Documentos</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($registrados as $i => $registro)
                    <tr>
                        <td>Registrados</td>
                        <td>{{ $registro->numRegistrados }}</td>
                        <td>{{ $registro->fecRegistrados }}</td>
                    </tr>
                    @endforeach
                    @foreach($editados as $i => $editado)
                    <tr>
                        <td>Editados y/o editados</td>
                        <td>{{ $editado->numEditados }}</td>
                        <td>{{ $editado->fecEditados }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>