
function cambiarestado(id_medida, estado){
    var valor = true;
    valor = validar_parametro_vacio(id_medida, valor);
    if(valor) {
        var cadena = "id_medida=" + id_medida +
            "&estado=" + estado;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Unidadmedida/cambiarestado",
            data: cadena,
            dataType: 'json',
            success: function (r) {
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Registro Cambiado!', 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al cambiar registro', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}