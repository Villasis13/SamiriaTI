

function add() {
    var valor = true;
    //Extraemos las variable según los valores del campo consultado
    var producto_nombre = $('#producto_nombre').val();
    var id_categoria = $('#id_categoria').val();
    var producto_codigo_barra = $('#producto_codigo_barra').val();
    var producto_descripcion = $('#producto_descripcion').val();
    var id_medida = $('#id_medida').val();
    var id_tipoafectacion = $('#id_tipoafectacion').val();
    var id_proveedor = $('#id_proveedor').val();
    var producto_stock = $('#producto_stock').val();
    var producto_precio_valor = $('#producto_precio_valor').val();
    var producto_precio_valor_xmayor = $('#producto_precio_valor_xmayor').val();
    var producto_precio_compra = $('#producto_precio_compra').val();

    //Validamos si los campos a usar no se encuentran vacios
    valor = validar_campo_vacio('producto_nombre', producto_nombre, valor);
    valor = validar_campo_vacio('id_categoria', id_categoria, valor);
    //valor = validar_campo_vacio('producto_descripcion', producto_descripcion, valor);
    //valor = validar_campo_vacio('id_medida', id_medida, valor);
    //valor = validar_campo_vacio('id_tipoafectacion', id_tipoafectacion, valor);
    //valor = validar_campo_vacio('id_proveedor', id_proveedor, valor);
    //valor = validar_campo_vacio('producto_stock', producto_stock, valor);
    valor = validar_campo_vacio('producto_precio_valor', producto_precio_valor, valor);
    //valor = validar_campo_vacio('producto_precio_compra', producto_precio_compra, valor);
    //Si var valor no ha cambiado de valor, procedemos a hacer la llamada de ajax
    if(valor){
        //Definimos el mensaje y boton a afectar
        var boton = "btn-agregar-producto";
        //Cadena donde enviaremos los parametros por POST
        var cadena = "producto_nombre=" + producto_nombre +
            "&id_categoria=" + id_categoria +
            "&producto_codigo_barra=" + producto_codigo_barra +
            "&producto_descripcion=" + producto_descripcion +
            "&id_medida=" + id_medida +
            "&id_tipoafectacion=" + id_tipoafectacion +
            "&id_proveedor=" + id_proveedor +
            "&producto_stock=" + producto_stock +
            "&producto_precio_valor=" + producto_precio_valor +
            "&producto_precio_valor_xmayor=" + producto_precio_valor_xmayor +
            "&producto_precio_compra=" + producto_precio_compra;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Inventario/guardar_producto_precio",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Guardando...", true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Producto Agregado Exitosamente!', 'success');
                        setTimeout(function () {
                            location.href = urlweb + 'Inventario/listarproductos';
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al agregar Producto', 'error');
                        break;
                    case 3:
                        respuesta('El Producto ya se encuentra registrado', 'warning');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}

function editar() {
    var valor = true;
    //Extraemos las variable según los valores del campo consultado
    var id_producto = $('#id_producto').val();
    var id_categoria = $('#id_categoria').val();
    var id_proveedor = $('#id_proveedor').val();
    var producto_nombre = $('#producto_nombre').val();
    var producto_codigo_barra = $('#producto_codigo_barra').val();
    var producto_precio_valor = $('#producto_precio_valor').val();
    var producto_stock = $('#producto_stock').val();

    //Validamos si los campos a usar no se encuentran vacios
    valor = validar_campo_vacio('producto_nombre', producto_nombre, valor);
    valor = validar_campo_vacio('id_categoria', id_categoria, valor);
    valor = validar_campo_vacio('producto_precio_valor', producto_precio_valor, valor);

    //Si var valor no ha cambiado de valor, procedemos a hacer la llamada de ajax
    if(valor){
        //Definimos el mensaje y boton a afectar
        var boton = "btn-editar-producto";
        //Cadena donde enviaremos los parametros por POST
        var cadena = "id_producto=" + id_producto +
            "&id_categoria=" + id_categoria +
            "&id_proveedor=" + id_proveedor +
            "&producto_stock=" + producto_stock +
            "&producto_nombre=" + producto_nombre +
            "&producto_codigo_barra=" + producto_codigo_barra +
            "&producto_precio_valor=" + producto_precio_valor;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Inventario/guardar_producto_precio",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Guardando...", true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Producto Editado Exitosamente!', 'success');
                        setTimeout(function () {
                            location.href = urlweb + 'Inventario/listarproductos';
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al editar Producto', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}

function eliminarproducto(id_producto, boton) {
    var valor = true;
    //Validamos si los campos a usar no se encuentran vacios
    if(valor) {
        var cadena = "id_producto=" + id_producto;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Inventario/eliminar_producto",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Eliminando...", true);
            },
            success: function (r) {
                cambiar_estado_boton(boton, "Eliminar Registro", false);
                switch (r.result.code) {
                    case 1:
                        $('#producto' + id_producto).remove();
                        respuesta('¡Producto Eliminado!', 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al eliminar producto', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}

function quitarproducto(id_producto, id_producto_precio, boton){
    var valor = true;
    //Validamos si los campos a usar no se encuentran vacios
    if(valor) {
        var cadena = "id_producto=" + id_producto + "&id_producto_precio=" + id_producto_precio;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Inventario/quitar_producto",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Eliminando...", true);
            },
            success: function (r) {
                cambiar_estado_boton(boton, "Eliminar Registro", false);
                switch (r.result.code) {
                    case 1:
                        $('#producto' + id_producto).remove();
                        respuesta('¡Producto Eliminado!', 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al eliminar producto', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}

function agregarstock(){
    var valor = true;
    //Extraemos las variable según los valores del campo consultado
    var producto_stock = $('#producto_stock').val();
    var id_producto = $('#id_producto').val();
    var producto_descripcion = $('#producto_descripcion').val();
    var id_proveedor = $('#id_proveedor').val();
    var producto_precio_compra = $('#producto_precio_compra').val();
    var producto_precio_valor = $('#producto_precio_valor').val();


    var stocklog_guide = $('#stocklog_guide').val();
    var stocklog_description = $('#stocklog_description').val();


    valor = validar_campo_vacio('id_producto', id_producto, valor);


    //Si var valor no ha cambiado de valor, procedemos a hacer la llamada de ajax
    if(valor){
        //Definimos el mensaje y boton a afectar
        var boton = "btn-editar-stock";
        //Cadena donde enviaremos los parametros por POST
        var cadena = "id_producto=" + id_producto +
            "&producto_stock=" + producto_stock +
            "&producto_descripcion=" + producto_descripcion +
            "&id_proveedor=" + id_proveedor +
            "&producto_precio_valor=" + producto_precio_valor +
            "&producto_precio_compra=" + producto_precio_compra +
            "&stocklog_guide=" + stocklog_guide +
            "&stocklog_description=" + stocklog_description;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Inventario/editar_stock",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Guardando...", true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Stock Agregado Exitosamente!', 'success');
                        setTimeout(function () {
                            location.href = urlweb + 'Inventario/listarproductos';
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al agregar stock', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}


function salida_stock(){
    var valor = true;
    //Extraemos las variable según los valores del campo consultado
    var id_producto = $('#id_producto').val();
    var stockout_out = $('#stockout_out').val();
    var stockout_guide = $('#stockout_guide').val();
    var stockout_description = $('#stockout_description').val();
    var stockout_ruc = $('#stockout_ruc').val();
    var stockout_origin = $('#stockout_origin').val();
    var stockout_destiny = $('#stockout_destiny').val();

    //Si var valor no ha cambiado de valor, procedemos a hacer la llamada de ajax
    if(valor){
        //Definimos el mensaje y boton a afectar
        var boton = "btn-salida_stock";
        //Cadena donde enviaremos los parametros por POST
        var cadena = "id_producto=" + id_producto +
            "&stockout_out=" + stockout_out +
            "&stockout_guide=" + stockout_guide +
            "&stockout_description=" + stockout_description +
            "&stockout_ruc=" + stockout_ruc +
            "&stockout_origin=" + stockout_origin +
            "&stockout_destiny=" + stockout_destiny;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Inventario/salidastock",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Guardando...", true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Se Realizo Exitosamente!', 'success');
                        setTimeout(function () {
                            location.href = urlweb + 'Inventario/listarproductos';
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al agregar stock', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}

