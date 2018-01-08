<meta name="csrf-token" content="{{ csrf_token() }}" />
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#document" aria-controls="document" role="tab" data-toggle="tab">Datos del documento</a></li>
    <li role="presentation"><a href="#expedient" aria-controls="expedient" role="tab" data-toggle="tab">Proceso Documentario</a></li>
    <li role="presentation"><a href="#historial" aria-controls="historial" role="tab" data-toggle="tab">Historial de Derivación</a></li>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="document">
        <table class="table">
            <thead>
            <tr>
                <th colspan="11">DATOS DEL DOCUMENTO</th>
            </tr>
            <tr>
                <th>Nro</th>
                <th>Campo</th>
                <th>Valor</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td>01</td>
                    <td>Codigos del Documento</td>
                    <td>{{ $documento->tdocId.' - '.$documento->tdocCod.' - '.$documento->tdocExp }}</td>
                </tr>
                <tr>
                    <td>02</td>
                    <td>Remitente</td>
                    <td>
                        {{ $documento->tdocDependencia }} -
                        {{ $documento->tdocProject }} -
                        {{ $documento->tdocJobSender }} -
                        {{ $documento->tdocSender }} -
                        {{ $documento->tdocDni }}
                    </td>
                </tr>
                <tr>
                    <td>03</td>
                    <td>Documento</td>
                    <td>
                        {{ $documento->tdocType }} -
                        {{ $documento->tdocNumber }} -
                        {{ $documento->tdocRegistro }} -
                        {{ $documento->tdocDate }} -
                        {{ $documento->tdocFolio }} -
                        {{ $documento->tdocSubject }} - 
                        {{ $documento->tdocDetail }}
                    </td>
                </tr>
                <tr>
                    <td>04</td>
                    <td>Estado tramite del documento</td>
                    <td>
                        {{ $documento->tdocAccion }} -
                        {{ $documento->tdocStatus }}
                    </td>
                </tr>
                <tr>
                    <td>05</td>
                    <td>Referencia</td>
                    <td>
                        {{ $documento->tdocRef }}
                    </td>
                </tr>
                <tr>
                    <td>06</td>
                    <td>Archivo digital</td>
                    <td>
                        {{ $documento->tdocFileName }} -
                        {{ $documento->tdocPathFile }}
                    </td>
                </tr>
                <tr>
                    <td>07</td>
                    <td>Usuario registrador</td>
                    <td>
                        {{ $documento->tdocRegisterBy }}
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
    <div role="tabpanel" class="tab-pane" id="expedient">
        <table class="table">
            <thead>
            <tr>
                <th colspan="11">DATOS DEL PROCESO DOCUMENTARIO</th>
            </tr>
            <tr>
                <th>Nro</th>
                <th>Campo</th>
                <th>Valor</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td>01</td>
                    <td>Codigos del Proceso</td>
                    <td>{{ $expediente->tarcId.' - '.$expediente->tarcExp }}</td>
                </tr>
                <tr>
                    <td>02</td>
                    <td>Título</td>
                    <td>
                        {{ $expediente->tarcTitulo }}
                    </td>
                </tr>
                <tr>
                    <td>03</td>
                    <td>Fecha de Origen</td>
                    <td>
                        {{ $expediente->tarcDatePres }}
                    </td>
                </tr>
                <tr>
                    <td>04</td>
                    <td>Estado</td>
                    <td>
                        {{ $expediente->tarcStatus }}
                    </td>
                </tr>
                <tr>
                    <td>05</td>
                    <td>Proyecto</td>
                    <td>
                        {{ $expediente->tarcAsoc }}
                    </td>
                </tr>
                <tr>
                    <td>06</td>
                    <td>Carpeta del Proceso</td>
                    <td>
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>CUD</th><th>Registro</th><th>Asunto</th><th>Ref</th>
                                </tr>
                            </thead>
                            <tbody>
                        @foreach($carpetaExp as $i=>$c)
                            <tr>
                                <td>{{ $c->tdocId }}</td>
                                <td>{{ $c->tdocRegistro }}</td>
                                <td>{{ $c->tdocSubject }}</td>
                                <td>{{ $c->tdocRef }}</td>
                            </tr>
                        @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div role="tabpanel" class="tab-pane" id="historial">
        <table class="table">
            <thead>
            <tr>
                <th colspan="11">HISTORIAL DE DERIVACION DEL DOCUMENTO</th>
            </tr>
            <tr>
                <th>Nro</th>
                <th>Campo</th>
                <th>Valor</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td>01</td>
                    <td>Codigos del Historial - CUD</td>
                    <td>{{ $historialDoc[0]->thisId.' - '.$historialDoc[0]->thisDoc }}</td>
                </tr>
                <tr>
                    <td>02</td>
                    <td>Destino derivación</td>
                    <td>
                        {{ $historialDoc[0]->thisDepT }}
                    </td>
                </tr>
                <tr>
                    <td>03</td>
                    <td>Estado de Registro - Fecha</td>
                    <td>
                        {{ $historialDoc[0]->thisFlagR }} -
                        {{ $historialDoc[0]->thisDateTimeR }}
                    </td>
                </tr>
                <tr>
                    <td>04</td>
                    <td>Estado de Derivación - Fecha - Descripción</td>
                    <td>
                        {{ $historialDoc[0]->thisFlagD }} - 
                        {{ $historialDoc[0]->thisDateTimeD }} - 
                        {{ $historialDoc[0]->thisDscD }}
                    </td>
                </tr>
                <tr>
                    <td>05</td>
                    <td>Estado de Atención - Fecha - Descripción</td>
                    <td>
                        {{ $historialDoc[0]->thisFlagA }} - 
                        {{ $historialDoc[0]->thisDateTimeA }} - 
                        {{ $historialDoc[0]->thisDscA }}
                    </td>
                </tr>
                <tr>
                    <td>06</td>
                    <td>Referencia Id Historial</td>
                    <td>
                        {{ $historialDoc[0]->thisIdRef }}
                    </td>
                </tr>
                <tr>
                    <td>07</td>
                    <td colspan="2">Carpeta Historial</td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>CUH</th>
                                    <th>CUD</th>
                                    <th>Proceso</th>
                                    <th>Registrado</th>
                                    <th>Derivado</th>
                                    <th>Atendido</th>
                                    <th>Hist Referencia</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($carpetaExp as $i=>$c)
                                <tr>
                                    <td>{{ $c->thisId }}</td>
                                    <td>{{ $c->tdocId }}</td>
                                    <td>{{ $c->tdocExp }}</td>
                                    <td>{{ $c->thisDateTimeR }}</td>
                                    <td>{{ $c->thisDateTimeD }} - {{ $c->thisDscD }}</td>
                                    <td>{{ $c->thisDateTimeA }} - {{ $c->thisDscA }}</td>
                                    <td>{{ $c->thisIdRef }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<td>
</td>

<script>
$(function(){

    var token = $('meta[name="csrf-token"]').attr('content');

    $('.fncEstado').editable({
        url: 'settings/updt_profile',
        params: {_token: token},
        source: [
              {value: 'A', text: 'Asignado'},
              {value: 'B', text: 'No Asignado'}
           ],
        success: function(response, newValue){
            if(!response.success) return "Error en el intento de cambiar el estado";

            console.log(newValue);

        }
    });
});
</script>