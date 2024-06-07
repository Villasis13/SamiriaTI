function quitarProducto(cod) {
    var cadena = "codigo=" + cod;
    $.ajax({
        type:"POST",
        url: urlweb + "api/Ventas/eliminar_producto",
        data : cadena,

        success:function (r) {
            if(r==1){
                respuesta('¡Producto Eliminado!', 'success');
                $('#tabla_productos').load(urlweb + 'Ventas/tabla_productos');
            } else {
                respuesta('Error al eliminar registro', 'error');
            }
        }
    });
}


function realizar_venta(){
    var valor = true;
    var boton = 'btn_generarventa';
    //datos cliente
    var client_number = $('#client_number').val();
    var client_name = $('#client_name').val();
    var saleproduct_direccion = $('#client_address').val();
    var client_telefono = $('#client_telefono').val();
    var select_tipodocumento = $('#select_tipodocumento').val();
    //var saleproductgas_telefono = $('#client_telefono').val();
    //datos venta
    var saleproduct_type = $('#tipo_venta').val();
    var serie = $('#serie').val();
    var numero = $('#numero').val();
    var tipo_moneda = $('#tipo_moneda').val();
    //var saleproduct_naturaleza = $('#naturaleza_sell').val();
    var id_tipo_pago = $('#id_tipo_pago').val();
    var total = $('#montototal').val();
    var gravada = $('#gravada').val();
    var igv = $('#igv').val();
    var saleproduct_inafecta = $('#inafecta').val();
    var saleproduct_exonerada = $('#exonerada').val();
    var saleproduct_icbper = $('#icbper').val();
    var saleproduct_total = total;
    var saleproduct_gravada = gravada;
    var saleproduct_igv = igv;
    var saleproduct_gratuita = $('#gratuita').val();
    var pago_con_ = $('#pago_con_').val();
    var vuelto_ = $('#vuelto_').val();
    var des_global = $('#descuento_global').val();
    var des_total = $('#des_total').val();
    var contenido_cuota = $('#contenido_cuota').val();

    var Tipo_documento_modificar = "";
    var serie_modificar = "";
    var numero_modificar = "";
    var notatipo_descripcion = "";
    if (saleproduct_type === "07" || saleproduct_type === "08"){
        Tipo_documento_modificar = $('#Tipo_documento_modificar').val();
        serie_modificar = $('#serie_modificar').val();
        numero_modificar = $('#numero_modificar').val();
        notatipo_descripcion = $('#notatipo_descripcion').val();
    }
    if(id_tipo_pago == 5){
        if(contenido_cuota == ""){
            respuesta('Falta agregar Cuotas a la Venta', 'error');
            valor=false;
        }else{
            //valor = validar_parametro_vacio(contenido_cuota, valor);
            var importe_cuota = $('#total_cuota').html();
            var total_temporal = $('#montototal_').html();
            if(importe_cuota != total_temporal){
                respuesta('El Total de las Cuotas '+importe_cuota+' no es igual al Total de la Venta '+total_temporal, 'error');
                valor=false;
            }
        }
    }

    valor = validar_campo_vacio('tipo_venta', saleproduct_type, valor);
    valor = validar_campo_vacio('serie', serie, valor);
    valor = validar_campo_vacio('numero', numero, valor);
    valor = validar_campo_vacio('client_number', client_number, valor);


    if(valor){
        var cadena = "cliente_number=" + client_number +
            "&cliente_name=" + client_name +
            "&cliente_direccion=" + saleproduct_direccion +
            "&cliente_telefono=" + client_telefono +
            "&select_tipodocumento=" + select_tipodocumento +
            "&saleproduct_type=" + saleproduct_type +
            "&serie=" + serie +
            "&correlativo=" + numero +
            "&tipo_moneda=" + tipo_moneda +
            "&id_tipo_pago=" + id_tipo_pago +
            "&saleproduct_exonerada=" + saleproduct_exonerada +
            "&saleproduct_inafecta=" + saleproduct_inafecta +
            "&saleproduct_icbper=" + saleproduct_icbper +
            "&saleproduct_total=" + saleproduct_total +
            "&saleproduct_gravada=" + saleproduct_gravada +
            "&notatipo_descripcion=" + notatipo_descripcion +
            "&serie_modificar=" + serie_modificar +
            "&numero_modificar=" + numero_modificar +
            "&Tipo_documento_modificar=" + Tipo_documento_modificar +
            "&saleproduct_gratuita=" + saleproduct_gratuita +
            "&pago_con_=" + pago_con_ +
            "&vuelto_=" + vuelto_ +
            "&des_global=" + des_global +
            "&des_total=" + des_total +
            "&saleproduct_igv=" + saleproduct_igv +
            "&contenido_cuota=" + contenido_cuota;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/guardar_venta",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'cobrando...', true);
            },
            success: function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-money\"></i> GENERAR VENTA", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Venta realizada correctamente!', 'success');
                        location.href = urlweb + 'Ventas/ver_venta/' + r.result.idventa;
                        break;
                    case 2:
                        respuesta('Error al generar Venta', 'error');
                        break;
                    case 5:
                        respuesta('Error al generar Venta, revisar Cliente', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }


}
function realizar_venta_nota(){
    var valor = true;
    var boton = 'btn_generarventa';
    var serie = $('#serie').val();
    var numero = $('#numero').val();
    var moneda = $('#tipo_moneda').val();
    var id_venta = $('#id_venta').val();
    var saleproduct_type = $('#tipo_venta').val();
    var Tipo_documento_modificar = $('#Tipo_documento_modificar').val();
    var serie_modificar = $('#serie_modificar').val();
    var numero_modificar = $('#numero_modificar').val();
    var notatipo_descripcion = $('#notatipo_descripcion').val();
    var notatipo_concepto = $('#notatipo_concepto').html();
    let contenido = $('#contenido').val();
    let total_factura = $('#total_factura').val();
    let total = $('#total').html();
    var total_gravada = $('#total_gravada').html();
    var total_igv = $('#total_igv').html();
    var total_exonerada = $('#total_exonerada').html();
    let valor_ = true
    if(total > total_factura){
        valor_ = false
    }
    valor = validar_campo_vacio('tipo_venta', saleproduct_type, valor);
    valor = validar_campo_vacio('contenido', contenido, valor);
    if(valor){
        if (valor_){
            var cadena = "id_venta=" + id_venta +
                "&venta_tipo=" + saleproduct_type +
                "&serie=" + serie +
                "&correlativo=" + numero +
                "&moneda=" + moneda +
                "&notatipo_descripcion=" + notatipo_descripcion +
                "&notatipo_concepto=" + notatipo_concepto +
                "&serie_modificar=" + serie_modificar +
                "&numero_modificar=" + numero_modificar +
                "&total_exonerada=" + total_exonerada +
                "&total_igv=" + total_igv +
                "&total_gravada=" + total_gravada +
                "&total=" + total +
                "&contenido=" + contenido +
                "&Tipo_documento_modificar=" + Tipo_documento_modificar;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Ventas/guardar_venta_nota",
                data: cadena,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, 'Guardando...', true);
                },
                success: function (r) {
                    cambiar_estado_boton(boton, "<i class=\"fa fa-money\"></i> GENERAR VENTA", false);
                    switch (r.result.code) {
                        case 1:
                            respuesta('¡Venta realizada correctamente!', 'success');
                            location.href = urlweb + 'Ventas/ver_venta/' + r.result.idventa;
                            break;
                        case 2:
                            respuesta('Error al generar Venta', 'error');
                            break;
                        case 4:
                            respuesta('No se puede Guadar sin Detalles', 'error');
                            break;
                        case 5:
                            respuesta('Error al generar Venta, revisar Cliente', 'error');
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        }else{
            respuesta('El monto toal no tiene que ser mayor que ' + total_factura, 'error');
        }

    }


}
function enviar_comprobante_sunat(id_venta) {
    var cadena = "id_venta=" + id_venta;
    var boton = 'btn_enviar'+id_venta;
    $.ajax({
        type: "POST",
        url: urlweb + "api/Ventas/crear_xml_enviar_sunat",
        data: cadena,
        dataType: 'json',
        beforeSend: function () {
            cambiar_estado_boton(boton, 'enviando...', true);
        },
        success:function (r) {
            cambiar_estado_boton(boton, "<i style=\"font-size: 16pt;\" class=\"fa fa-check margen\"></i>", false);
            switch (r.result.code) {
                case 1:
                    respuesta('¡Comprobante Enviado a Sunat!', 'success');
                    setTimeout(function () {
                        location.reload();
                        //location.href = urlweb +  'Pedido/gestionar';
                    }, 1000);
                    break;
                case 2:
                    respuesta('Error al generar el comprobante electronico', 'error');
                    setTimeout(function () {
                        location.reload();
                        //location.href = urlweb +  'Pedido/gestionar';
                    }, 1000);
                    break;
                case 3:
                    respuesta('Error, Sunat rechazó el comprobante', 'error');
                    setTimeout(function () {
                        location.reload();
                        //location.href = urlweb +  'Pedido/gestionar';
                    }, 1000);
                    break;
                case 4:
                    respuesta('Error de comunicacion con Sunat', 'error');
                    setTimeout(function () {
                        location.reload();
                        //location.href = urlweb +  'Pedido/gestionar';
                    }, 1000);
                    break;
                case 5:
                    respuesta('Error al guardar en base de datos', 'error');
                    setTimeout(function () {
                        location.reload();
                        //location.href = urlweb +  'Pedido/gestionar';
                    }, 1000);
                    break;
                default:
                    respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                    setTimeout(function () {
                        location.reload();
                        //location.href = urlweb +  'Pedido/gestionar';
                    }, 1000);
                    break;
            }
        }

    });
}
function comunicacion_baja(id_venta){
    var cadena = "id_venta=" + id_venta;
    var boton = 'btn_anular'+id_venta;
    $.ajax({
        type: "POST",
        url: urlweb + "api/Ventas/comunicacion_baja",
        data: cadena,
        dataType: 'json',
        beforeSend: function () {
            cambiar_estado_boton(boton, 'Anulando...', true);
        },
        success:function (r) {
            cambiar_estado_boton(boton, "ANULAR", false);
            switch (r.result.code) {
                case 1:
                    respuesta('¡Comprobante Enviado a Sunat!', 'success');
                    setTimeout(function () {
                        location.reload();
                        //location.href = urlweb +  'Pedido/gestionar';
                    }, 1000);
                    break;
                case 2:
                    respuesta('Error al generar el comprobante electronico', 'error');
                    setTimeout(function () {
                        location.reload();
                        //location.href = urlweb +  'Pedido/gestionar';
                    }, 1000);
                    break;
                case 3:
                    respuesta('Error, Sunat rechazó el comprobante', 'error');
                    setTimeout(function () {
                        location.reload();
                        //location.href = urlweb +  'Pedido/gestionar';
                    }, 1000);
                    break;
                case 4:
                    respuesta('Error de comunicacion con Sunat', 'error');
                    setTimeout(function () {
                        location.reload();
                        //location.href = urlweb +  'Pedido/gestionar';
                    }, 1000);
                    break;
                case 5:
                    respuesta('Error al guardar en base de datos', 'error');
                    setTimeout(function () {
                        location.reload();
                        //location.href = urlweb +  'Pedido/gestionar';
                    }, 1000);
                    break;
                default:
                    respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                    break;
            }
        }

    });
}
function anular_boleta_cambiarestado(id_venta, estado){
    var cadena = "id_venta=" + id_venta + "&estado=" + estado;
    var boton = 'btn_anular_boleta'+id_venta;
    $.ajax({
        type: "POST",
        url: urlweb + "api/Ventas/anular_boleta_cambiarestado",
        data: cadena,
        dataType: 'json',
        beforeSend: function () {
            cambiar_estado_boton(boton, 'Anulando...', true);
        },
        success:function (r) {
            cambiar_estado_boton(boton, "ANULAR", false);
            switch (r.result.code) {
                case 1:
                    respuesta('¡Comprobante Anulado, listo para ser enviado por Resumen Diario!', 'success');
                    setTimeout(function () {
                        location.reload();
                        //location.href = urlweb +  'Pedido/gestionar';
                    }, 1000);
                    break;
                case 2:
                    respuesta('Error al anular el comprobante electronico', 'error');
                    setTimeout(function () {
                        location.reload();
                        //location.href = urlweb +  'Pedido/gestionar';
                    }, 1000);
                    break;
                default:
                    respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                    break;
            }
        }

    });
}
function crear_enviar_resumen_sunat(){
    var fecha_post = $('#fecha_post').val();
    var cadena = "fecha=" + fecha_post;
    var boton = 'boton_enviar_resumen';
    $.ajax({
        type: "POST",
        url: urlweb + "api/Ventas/crear_enviar_resumen_sunat",
        data: cadena,
        dataType: 'json',
        beforeSend: function () {
            cambiar_estado_boton(boton, 'Enviando...', true);
        },
        success:function (r) {
            cambiar_estado_boton(boton, "Enviar Comprobantes", false);
            switch (r.result.code) {
                case 1:
                    respuesta('¡Resumen Creado y Enviado a Sunat!', 'success');
                    setTimeout(function () {
                        location.reload();
                        //location.href = urlweb +  'Ventas/envio_resumenes_diario';
                    }, 500);
                    break;
                case 2:
                    respuesta('Error al generar el Resumen Diario', 'error');
                    break;
                case 3:
                    respuesta('Error, Sunat rechazó el comprobante', 'error');
                    break;
                case 4:
                    respuesta(r.result.message, 'error');
                    break;
                default:
                    respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                    break;
            }
        }

    });
}