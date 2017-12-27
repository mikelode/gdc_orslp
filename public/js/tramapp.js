/* JAVASCRIPT PARA EL SISTEMA DE TRAMITE *//**
 * Created by HP i5 on 16/07/2015.
 */

function showDocDetail(code)
{
    $.getJSON('doc/detail/' + code).done( function(response){
        var output = "<dl><dt>Código:</dt><dd>" + response.documento[0].tdocId + "</dd>";
        output += "<dt>Remitente:</dt><dd>" + response.documento[0].tdocSenderName + " " + response.documento[0].tdocSenderPaterno + " " + response.documento[0].tdocSenderMaterno + "</dd>";
        output += "<dt>Asunto:</dt><dd>" + response.documento[0].tdocSubject + "</dd>";
        output += "<dt>Folio:</dt><dd>" + response.documento[0].tdocFolio + "</dd>";
        output += "<dt>Referencias:<dt><dd>" + response.documento[0].tdocRef + "</dd>";
        output += "<dt>Anexos:</dt>";
        output += "<dd><ul>";
        
        $.each(response.anexos, function(i, anexo){
            output += "<li>" + anexo.tdaTypAnx + "&nbsp;-&nbsp;" + anexo.tdaNumAnex + "&nbsp;:&nbsp;" + anexo.tdaDsc + "</li>";
        });

        output += "</ul></dd>";

        $('#detail_document').html(output);
    });
}

function showDerivDetail(code, hisId)
{
    $.getJSON('derivation/detail/' + hisId).done( function(response){
        
        var output = "<table><tr><th>En atención al documento:</th><td>" + code + "</td></tr>" ;
        if(response.tipodoc != 'Sin documento')
        {
            output += "<tr><th>Se derivó con el " + response.tipodoc.ttypDesc + " de:</th><td></td></tr>";
            output += "<tr><td colspan='2'><table class='table-hover' style='border: dashed 2px lightblue;'><tr><th>Código</th><td>" + response.withdoc.tdocId + "</td></tr>"
            output += "<tr><th>Folio</th><td>" + response.withdoc.tdocFolio + "</td></tr>";
            output += "<tr><th>Con asunto:</th><td>" + response.withdoc.tdocSubject + "</td></tr>";
            output += "<tr><th>Estado:</th><td>" + response.arcdoc[0].tarcStatus + "</td></tr>";
            output += "</table><td></tr>";
        }
        else
        {
            output += "<tr><th colspan='2'>Se derivó sin la intermediación de un documento</th></tr>";
        }
        
        output += "<tr><th>Fecha de derivación:</th><td>" + response.historial.thisDateTimeD + "</td></tr>";
        output += "<tr><th>Detalle u observación:</th><td>" + response.historial.thisDscD + "</td></tr>";
        output += "</table><dt>Destinatarios:</dt>";
        output += "<dd><ul>";
        
        $.each(response.destinos, function(i, destino){
            output += "<li>" + destino.thisDestino + "</li>";
        });

        output += "</ul></dd>";
        $('#detail_document').html(output);
    });
}

function showOperationDetail(codeId)
{

    $.getJSON('operation/detail/' + codeId, function(response){
        
        var output = "";

        if(response[0].thisFlagA == true)
        {
            output += "<p>DESCRIPCION DE LA ATENCION:</p>";
            output += "<h4>" + response[0].thisDscA + "</h4>";
        }
        else if(response[0].thisFlagD == true)
        {
            output += "<p>DESCRIPCIÓN DE LA DERIVACIÓN POR PARTE DE LA OFICINA REMITENTE:</p>";
            output += "<h4>" + response[0].thisDscD + "</h4>";
        }
        else
        {
            output += "El documento no ha sido atendido ó derivado."
        }

        $('#detail_operation').html(output);
    });

}

function showTrackingDoc(code)
{
    $.get('tracking/' + code);
}

function change_menu_register(path)
{
    var year = $('#period_sys').val();

    //console.log(year);

    $.get(path, {period : year}, function(data){

        if(data == '401')
        {
            bootbox.alert('USTED NO ESTA AUTORIZADO PARA INGRESAR A ESTA SECCION.');
        }
        else
        {
            $('.content-wrapper').html(data.view);

            /* llenando el formulario */
            //pantallazo_documento(data.lastdoc);
            mostrar_documento(data.lastdoc[0].tdocId,'busqueda');
        }
    });
}

function change_menu_to(path)
{
    var year = $('#period_sys').val();

    //console.log(year);

    $.get(path, {period : year}, function(data){

        if(data == '401')
        {
            bootbox.alert('USTED NO ESTA AUTORIZADO PARA INGRESAR A ESTA SECCION.');
        }
        else
        {
            $('.content-wrapper').html(data);
        }
    });
}

function change_to_submenu(path)
{
    $.get(path, function(data){
        $('.sub-content').html(data);
    });
}

function fntest()
{

}

function get_result_forMany(url, data)
{
    $.post(url,data,function(result){

        if(result.length == 0)
        {
            bootbox.alert('EL DOCUMENTO CON EL ASUNTO BUSCADO NO ES ENCONTRADO');
            return;
        }

        var path = '';
        var output = "<table class='table table-hover table-striped'>";
        output += "<tr><th style='width: 10px'>#</th><th>Documento</th><th>Tipo</th><th>Remitente</th><th>Asunto</th><th>Fecha de Presentación</th><th>Estado</th><th>Detalle Seguimiento</th></tr>";

        $.each(result, function(i, item){

            path = 'doc/tracking/' + item.tarcExp;
            output += "<tr>";
            output += "<td>" + (i+1) +"</td>";
            output += "<td>" + item.tdocId + "</td>";
            output += "<td>" + item.ttypDesc + "</td>";
            output += "<td>" + item.tdocDni + "</td>";
            output += "<td>" + item.tdocSubject + "</td>";
            output += "<td>" + item.tarcDatePres + "</td>";
            output += "<td>" + item.tarcStatus + "</td>";
            output += "<td><a href='javascript:void(0)' onclick='change_menu_to(\"" + path + "\")'> Ver </td>";
            output += "</tr>";
        })
        output += "</table>";

        $('#resultRows').empty();
        $('#resultRows').html(output);
    }).fail(function(){
        bootbox.alert('Error. Revise su Conexión.');
    });
}

function get_result_for(url, data)
{
    $.post(url,data,function(result){

        if(result == "")
        {
            bootbox.alert('EL DOCUMENTO BUSCADO NO EXISTE');
            return;
        }

        var path = 'doc/tracking/' + result[0]['tarcExp'];
        var output = "<table class='table table-hover table-striped'>";
        output += "<tr><th style='width: 10px'>#</th><th>Documento</th><th>Tipo</th><th>Remitente</th><th>Asunto</th><th>Fecha de Presentación</th><th>Estado</th><th>Detalle Seguimiento</th></tr>";
        output += "<tr>";
        output += "<td>1</td>";
        output += "<td>" + result[0]['tdocId'] + "</td>";
        output += "<td>" + result[0]['ttypDesc'] + "</td>";
        output += "<td>" + result[0]['tdocDni'] + "</td>";
        output += "<td>" + result[0]['tdocSubject'] + "</td>";
        output += "<td>" + result[0]['tarcDatePres'] + "</td>";
        output += "<td>" + result[0]['tarcStatus'] + "</td>";
        output += "<td><a href='javascript:void(0)' onclick='change_menu_to(\"" + path + "\")'> Ver </td>";
        output += "</tr>";
        output += "</table>";

        $('#resultRows').empty();
        $('#resultRows').html(output);

    }).fail(function(){
        bootbox.alert('Revise los datos ingresados');
    });
}

function close_chat_box(id)
{
    $('#chat-box-' + id).remove();
}

function updateScroll(id){
    var element = $('#direct-chat-messages-' + id);
    element[0].scrollTop = element[0].scrollHeight;
}

function change_source_doc_to(source)
{
    switch (source)
    {
        case 'ext':
            $('#dni_sender_input').val('');
            $('#name_sender_input').val('');
            $('#patern_sender_input').val('');
            $('#matern_sender_input').val('');
            break;
        case 'int':
            //change_menu_to('doc/register');
            $.getJSON('doc/manager',function(manager){
                if(manager.length == 0) return;
                $('#dni_sender_input').val(manager[0].trepDni);
                $('#name_sender_input').val(manager[0].trepName);
                $('#patern_sender_input').val(manager[0].trepPaterno);
                $('#matern_sender_input').val(manager[0].trepMaterno);
            });
            break;
    }
}

function min_chat_box(id)
{
    $('#chat-body-box-' + id).slideToggle('slow');
}

function open_chat_box(id, user)
{
    /*$('#chat-box-mdv').show();*/

    var id_html = '#chat-box-' + id;

    if($(id_html).length != 0) return;

    var chatbox = '';

    chatbox += '<div class="chat-box" id="chat-box-'+ id + '">';
    chatbox += '<div class="box box-primary direct-chat direct-chat-primary">';
    chatbox += '<div class="box-header with-border">';
    chatbox += '<h3 class="box-title">' + user + '</h3>';
    chatbox += '<div class="box-tools pull-right">';
    chatbox += '<button class="btn btn-box-tool" onclick="min_chat_box(\'' + id + '\')"><i class="fa fa-minus"></i></button>';
    chatbox += '<button class="btn btn-box-tool" onclick="close_chat_box(\'' + id + '\');"><i class="fa fa-times"></i></button>'
    chatbox += '</div></div><div id="chat-body-box-' + id + '">';
    chatbox += '<div class="box-body">';
    chatbox += '<div class="direct-chat-messages" id="direct-chat-messages-' + id + '">';
    chatbox += '</div></div>';
    chatbox += '<div class="box-footer"><form id="send-message-' + id + '"><div class="input-group">';
    chatbox += '<input type="text" id="' + id + '" name="message" placeholder="Mensaje ..." class="input-chat form-control" style="width: 230px" autocomplete="off" />';
    chatbox += '<span class="input-group-btn">';
    chatbox += '</span></div></form></div></div></div></div>';

    //console.log('Antes de agregar el chat box');

    $('#chat-box-container').append(chatbox);
}

function nuevo_documento(origen)
{
    var estado = $('#btnNuevoDoc').html();

    if(estado == "Nuevo")
    {
        $('#btnNuevoDoc').html('Cancelar');
        $('#btnNuevoDoc').prop('class','btn btn-warning');
        $('#btnEditarDoc').hide();
        $('#btnEliminarDoc').hide();

        $('#operacionEnviar').hide();

        $('#docId').val('');
        $('#docDepend').prop('disabled', false).val(1);
        $('#docProy').prop('disabled', false).val(1).trigger('change');
        $('#docSender').prop('readonly', false).val('');
        $('#docJob').prop('readonly', false).val('');
        $('#docReg').prop('readonly', false).val('');        
        $('#docTipo').prop('disabled', false);
        $('#docNro').prop('readonly', false).val('');
        $('#docFecha').prop('readonly', false).val('');
        $('#docAsunto').prop('readonly', false).val('');
        $('#docDetalle').prop('readonly', false).val('');
        $('#docFolio').prop('readonly', false).val('');

        $('#withDigFile').empty();
        $('#docFile').prop('disabled', false);
        $('#withoutDigFile').show();

        $('#docProceso').prop('disabled', false).val('na').trigger('change');
        $('#docTitulo').prop('readonly', false).val('');
        $('#docAccion').prop('disabled', false).val('');
        $('#docRefRegistro').prop('readonly', false).val('');
        $('#docReferencia').prop('readonly', false).val('');

        $('#btnGuardarDoc').show();
    }
    else
    {
        change_menu_register('doc/register');
    }

}

function editar_documento(origen)
{
    var estado = $('#btnEditarDoc').html();

    if(estado == "Editar")
    {
        $('#btnEditarDoc').html('Cancelar');
        $('#btnEditarDoc').prop('class','btn btn-warning');

        $('#operacionEnviar').hide();

        $('#docDepend').prop('disabled', false);
        $('#docProy').prop('disabled', false);
        $('#docSender').prop('readonly', false);
        $('#docJob').prop('readonly', false);
        $('#docReg').prop('readonly', false);
        $('#docTipo').prop('disabled', false);
        $('#docNro').prop('readonly', false);
        $('#docFecha').prop('readonly', false);
        $('#docAsunto').prop('readonly', false);
        $('#docDetalle').prop('readonly', false);
        $('#docFolio').prop('readonly', false);

        $('#withDigFile').empty();
        $('#docFile').prop('disabled', false);
        $('#withoutDigFile').show();

        /* para evitar que edite si pertenece o no a un proceso*/
        $('#docProceso').prop('disabled', false);
        $('#docTitulo').prop('readonly', false);
        $('#docAccion').prop('disabled', false);
        $('#docRefRegistro').prop('readonly', true);
        $('#docReferencia').prop('readonly', false);

        $('#btnGuardarEdicionDoc').show();
    }
    else
    {
        change_menu_register('doc/register');
    }
}

function guardar_documento(origen)
{
    var form = $('#frm_reg_doc')[0];
    var formdata = new FormData(form);

    /*var verificar = validar_formulario(form, origen);
    verificar = $.parseJSON(verificar);

    if(!verificar.valido)
    {
        $.each(verificar.elemento,function(i,elem){
            var grupo = $('#' + elem).closest('div.form-group');
            var mensaje = grupo.find('div.error-message').show();
        });

        return;
    }*/

    $.ajax({
        type: 'post',
        url: 'doc/register',
        data: formdata,
        cache: false,
        contentType: false,
        processData: false,
        success: function(response){
            bootbox.alert(response);
            change_menu_register('doc/register');
        },
        xhr: function(){
            var myXhr = $.ajaxSettings.xhr();
            if(myXhr.upload){
                myXhr.upload.addEventListener('progress', function(ev){
                    if(ev.lengthComputable){
                        $('progress').attr({
                            value: ev.loaded,
                            max: ev.total,
                        });
                    }
                }, false);
            }
            return myXhr;
        },
    })
    .fail(function(result, textStatus, xhr) {
        var errors = result.responseJSON;
        var rpta = '<ul>';

        $.each(errors, function(i, er){
            rpta = rpta + '<li>' + er[0] + '</li>';
        });

        rpta = rpta + '</ul>';

        bootbox.alert({
            message: 'Error:<br>Revise los campos ingresados.<br>' + rpta
        });
    });
    
    
}

function terminar_edicion_documento(origen)
{
    var form = $('#frm_reg_doc')[0];
    var formdata = new FormData(form);
    var id = $('#docId').val();

    $.ajax({
        type: 'post',
        url: 'doc/edit',
        data: formdata,
        cache: false,
        contentType: false,
        processData: false,
        success: function(response){
            if(response.idMsg == 200)
            {
                alert(response.msg);
                //pantallazo_documento(res.cadena);
                $('#btnEditarDoc').html('Editar');
                $('#btnEditarDoc').prop('class','btn btn-info');
                $('#btnGuardarEdicionDoc').hide();
                //$('#operacionEnviar').show();

                mostrar_documento(id,'busqueda');
            }
            else
            {
                alert(response.msg);
            }
        },
        xhr: function(){
            var myXhr = $.ajaxSettings.xhr();
            if(myXhr.upload){
                myXhr.upload.addEventListener('progress', function(ev){
                    if(ev.lengthComputable){
                        $('progress').attr({
                            value: ev.loaded,
                            max: ev.total,
                        });
                    }
                }, false);
            }
            return myXhr;
        },
    });
}

function habilitar_busqueda(tipo)
{
    $('form.frm-busqueda-rapida').hide();
    if(tipo == 'fecha')
    {
        $('form#frmEncontrarDocFechas').show();
    }
    else if(tipo == 'asunto')
    {
        $('form#frmEncontrarDocAsunto').show();
    }
    else if(tipo == 'registro')
    {
        $('form#frmEncontrarDocRegistro').show();
    }
    else if(tipo == 'remitente')
    {
        $('form#frmEncontrarDocRemitP').show();
    }
}

function encontrar_documento(origen, frm)
{
    var period = $('#period_sys').val();
    var url = frm.prop('action');
    var data = frm.serializeArray();

    data.push({name: 'period', value: period});

    $.post(url, data, function(response){
        
        //var response = $.parseJSON(response);
        
        if(response.Respuesta == 200 )
        {
            $('#tbl-resultado-encontrar').html(response.resultado);
        }
        else if(response.Respuesta == 500)
        {
            alert(response.msg);
        }
    });
}

function mostrar_documento(pos, origen) //(docId|anterior|posterior, busqueda|referencia)
{
    var actual = $('#docId').val();
    var url = 'doc/show';
    var data = {'posicion': pos, 'docActual': actual, 'origen': origen};

    //if(actual == '') return;
    if($('#btnEditarDoc').html() == 'Cancelar' && origen == 'busqueda') return;

    $.get(url, data, function(response){
        
        if(origen == "referencia"){
            $('#docRefRegistro').val(response.docElegido[0].tdocRegistro);
            $('#docReferencia').val(response.docElegido[0].tdocId);
            $('#docProy').val(response.docElegido[0].tdocProject).trigger('change');// change cambia tmbn las acciones de docProy | change.select2 solo cambia el select
        }
        else{
            pantallazo_documento(response);
        }
        $('#modalEncontrarDocumento').modal('hide');
        //$('body').removeClass('modal-open');
        //$('.modal-backdrop').remove();
    });
}

function registrar_persona()
{
    var url = $('#frmAddPerson').prop('action');
    var data = $('#frmAddPerson').serialize();

    $('#prsId').val('');
    $('#prsName').val('');
    $('#prsPaterno').val('');
    $('#prsMaterno').val('');
    $('#prsCel').val('');
    $('#prsJob').val('');

    $.post(url, data, function(data) {

        if(data.msgId == '200'){
            alert(data.msg);
            $('#modalAgregarPersona').modal('hide');
        }
        else{
            alert(data.msg);
        }
    });
}

function pantallazo_documento(cadena)
{   
    console.log('pantallazo');
    /* SENDER DATA */
    $('#docId').prop('readonly',true).val(cadena.docElegido[0].tdocId);
    $('#docDepend').prop('disabled',true).val(cadena.docElegido[0].tdocDependencia);
    $('#docProy').prop('disabled',true).val(cadena.docElegido[0].tdocProject).trigger('change');// change cambia tmbn las acciones de docProy | change.select2 solo cambia el select
    $('#docSender').prop('readonly',true).val(cadena.docElegido[0].tdocSender);
    $('#docSenderId').prop('readonly',true).val(cadena.docElegido[0].tdocDni);
    $('#docJob').prop('readonly',true).val(cadena.docElegido[0].tdocJobSender);

    /* DOC SENDER */
    $('#docReg').prop('readonly',true).val(cadena.docElegido[0].tdocRegistro);
    $('#docTipo').prop('disabled',true).val(cadena.docElegido[0].tdocType);
    $('#docNro').prop('readonly',true).val(cadena.docElegido[0].tdocNumber);
    $('#docFecha').prop('readonly',true).val(cadena.docElegido[0].tdocDate);
    $('#docAsunto').prop('readonly',true).val(cadena.docElegido[0].tdocSubject);
    $('#docDetalle').prop('readonly',true).val(cadena.docElegido[0].tdocDetail);
    $('#docFolio').prop('readonly',true).val(cadena.docElegido[0].tdocFolio);
    //para el input file podemos cambiar file a text y poner el link al documento ahi
    if(cadena.docElegido[0].tdocFileName != null){
        $('#withoutDigFile').hide();
        $('#withDigFile').html('<a href="'+cadena.docElegido[0].tdocPathFile+'/'+cadena.docElegido[0].tdocFileName+'" target="_blank">Ver documento</a>')
    }
    else{
        $('#withoutDigFile').show();
        $('#withDigFile').empty();
    }

    /* REFERENCIA DATA */
    if(cadena.docElegido[0].tdocRef == null){
        $('#docProceso').prop('disabled',true).val('no').trigger('change');
        $('#docTitulo').prop('readonly',true).val(cadena.docElegido[0].tarcTitulo);
    }
    else{
        $('#docProceso').prop('disabled',true).val('si').trigger('change');
        $('#docAccion').prop('disabled',true).val(cadena.docElegido[0].tdocAccion);
        $('#docRefRegistro').prop('readonly',true).val(cadena.docReferencia.tdocRegistro);
        $('#docReferencia').prop('readonly',true).val(cadena.docElegido[0].tdocRef);
    }


    $("#docEnvioExp").html(cadena.docElegido[0].tdocRegistro);
    $("#hdocEnvioExp").val(cadena.docElegido[0].tdocId);

    if(cadena.docElegido[0].tdocStatus == 'registrado'){
        $('#operacionEnviar').show();
        $('#btnEditarDoc').show();
        $('#btnEliminarDoc').show();
    }
    else{
        $('#operacionEnviar').hide();
        //$('#operacionEnviar').empty();
        //$('#operacionEnviar').html('<div class="alert alert-info">El documento ha sido derivado para su correspondiente atención.</div>');
        $('#btnEditarDoc').hide();
        $('#btnEliminarDoc').hide();
    }
}

function enviar_documento(origen)
{
    var url = $('#frmEnvDoc').prop('action');
    var data = $('#frmEnvDoc').serialize();
    var id = $('#hdocEnvioExp').val();

    if(id == ''){
        alert('Lo lamentamos, actualice la página por favor presionando ctrl + F5');
        return;
    }

    $.post(url, data, function(response){
        
        if(response.idMsg == '200')
        {
            alert(response.msg);
            $('#modalOperacionEnviar').modal('hide');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            mostrar_documento(id,'busqueda');
            
            //cargarSubmodulo(1,'.html/modulo1/m1submod1.html');
        }
        else
        {
            alert(response.msg);
        }

    });

}

function historial(fila){
    var tr = $(fila).closest('tr');
    var row = $('#docBandeja').DataTable().row(tr);
    var claves = tr.data('keys');

    if(row.child.isShown()){
        row.child.hide();
        tr.removeClass('shown');
    }
    else{
        $.get('doc/expediente',{'claves': claves}, function(data) {
            row.child(data).show();
            tr.addClass('shown');
        });
    }
}

function procesar_reporte(url, data)
{
    $.post(url,data,function(result){
        $('#resultRows').html(result);
    });
}

function eliminar_documento(origen)
{
    var confirma = confirm('¿Está seguro de eliminar el documento seleccionado?');
    if(!confirma) return;

    var data = $('#frm_reg_doc').serialize();
    var url = 'doc/delete';

    $.post(url, data, function(response){
        
        alert(response.msg);
        if(response.msgId == 200)
        {   
            change_menu_register('doc/register');
        }
    });
}