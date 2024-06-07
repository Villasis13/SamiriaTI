function guardar(){
    var valor = true;
    var cliente_nombre = $('#cliente_nombre').val();
    let textoCodificado = encodeURIComponent(cliente_nombre);
    var cliente_direccion = $('#cliente_direccion').val();
    var cliente_telefono = $('#cliente_telefono').val();
    var cliente_correo = $('#cliente_correo').val();
    var cliente_numero = $('#cliente_numero').val();
    var id_tipodocumento = $('#id_tipo_documento').val();
    if(valor){
        var boton = "btn-agregar";
        var cadena = "cliente_nombre=" + textoCodificado + "&id_tipodocumento=" + id_tipodocumento + "&cliente_numero=" + cliente_numero + "&cliente_correo=" + cliente_correo + "&cliente_direccion=" + cliente_direccion + "&cliente_telefono=" + cliente_telefono;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Clientes/guardar_cliente",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Guardando...", true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "Guardando...", false);
                switch (r.result.code) {
                    case 1:respuesta('¡Cliente Agregado Exitosamente...Recargando!!!', 'success');location.reload();break;
                    case 2:respuesta('Error al agregar cliente', 'error');break;
                    case 5:respuesta('El DNI o RUC ya se encuentra registrado', 'error');$('#cliente_numero').css('border','solid red');break;
                    default:respuesta('¡Algo catastrofico ha ocurrido!', 'error');break;
                }
            }
        });
    }
}
function guardar_editar(){
    var valor = true;
    var cliente_nombre = $('#cliente_nombre_e').val();
    let textoCodificado = encodeURIComponent(cliente_nombre);
    var cliente_direccion = $('#cliente_direccion_e').val();
    var cliente_telefono = $('#cliente_telefono_e').val();
    var cliente_correo = $('#cliente_correo_e').val();
    var cliente_numero = $('#cliente_numero_e').val();
    var id_tipodocumento = $('#id_tipo_documento_e').val();
    var id_cliente = $('#id_cliente_e').val();
    if(valor){
        var boton = "btn-agregar";
        var cadena = "cliente_nombre=" + textoCodificado +
            "&id_tipodocumento=" + id_tipodocumento +
            "&id_cliente=" + id_cliente +
            "&cliente_numero=" + cliente_numero +
            "&cliente_correo=" + cliente_correo +
            "&cliente_direccion=" + cliente_direccion +
            "&cliente_telefono=" + cliente_telefono;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Clientes/guardar_cliente",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {cambiar_estado_boton(boton, "Guardando...", true);},
            success:function (r) {
                cambiar_estado_boton(boton, "Guardando...", false);
                switch (r.result.code) {
                    case 1:respuesta('¡Cliente Agregado Exitosamente...Recargando!!!', 'success');location.reload();break;
                    case 2:respuesta('Error al agregar cliente', 'error');break;
                    case 5:respuesta('El DNI o RUC ya se encuentra registrado', 'error');$('#cliente_numero').css('border','solid red');break;
                    default:respuesta('¡Algo catastrofico ha ocurrido!', 'error');break;
                }
            }
        });
    }
}
function eliminarcliente(id_cliente, boton) {
    var valor = true;
    if(valor) {
        var cadena = "id_cliente=" + id_cliente;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Clientes/eliminar_cliente",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Eliminando...", true);
            },
            success: function (r) {
                cambiar_estado_boton(boton, "Eliminar Registro", false);
                switch (r.result.code) {
                    case 1:
                        $('#cliente' + id_cliente).remove();
                        respuesta('¡Cliente Eliminado!', 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al eliminar Cliente', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}
function cambiar_estado(id_cliente){
    var cadena = "id_cliente=" + id_cliente;
    $.ajax({
        type:"POST",
        url: urlweb + "api/Clientes/cambiar_estado_cliente",
        data : cadena,
        dataType: 'json',
        success:function (r) {
            switch (r.result.code) {
                case 1:
                    respuesta('¡Eliminado Exitosamente! Recargando...', 'success');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                    break;
                case 2:
                    respuesta('Error al cambiar estado, vuelva a intentarlo', 'error');
                    break;
                default:
                    respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                    break;
            }
        }
    });
}