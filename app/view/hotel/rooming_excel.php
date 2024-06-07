<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center mb-4">
        <label class="text-center" for="fecha_a_buscar">Rack de Habitaciones <?= $fecha_busqueda; ?></label>
    </div>
    <!-- Content Row -->
    <div class="row">
        <table class="table table-striped" border="1">
            <thead>
            <tr>
                <td>Hab</td>
                <td>Tipo</td>
                <td>Nombre</td>
                <td>Rate</td>
                <td>In</td>
                <td>Out</td>
                <td>Agencia</td>
                <td>Obs</td>
            </tr>
            </thead>
            <tbody>
        <?php
        foreach ($habitaciones as $h){
            $desha_des="";
            $ocupado = $this->hotel->buscar_estado_habitacion($fecha_busqueda,$h->id_habitacion);
            (isset($ocupado->id_habitacion))?$estado =$ocupado->habitacion_estado_estado:$estado=1;
            if($estado == 1){
                $colorcito="";
                $nombre="";
                $rate="";
                $in="";
                $out="";
                $agencia="";
                $obs="";
            }elseif ($estado == 2){
                $data_rack = $this->hotel->listar_rack_por_id_habitacion_($h->id_habitacion);
                $colorcito="";
                $nombre=$data_rack->cliente_nombre;
                $rate=$data_rack->rack_tarifa;
                $in=$data_rack->rack_in;
                $out=$data_rack->rack_out;
                $agencia=$data_rack->rack_agencia;
                $obs="";
            }else{
                $colorcito="background: yellow;";
                $desha=$this->hotel->listar_deshabilitado($h->id_habitacion,date("Y-m-d"));
                (isset($desha->deshabilitado_descripcion))?$desha_des=$desha->deshabilitado_descripcion:$desha_des="";
                $nombre=$desha_des;
                $rate="";
                $in="";
                $out="";
                $agencia="";
                $obs="";
            }
            ?>
            <tr style="<?= $colorcito; ?>">
                <td><?= $h->habitacion_nro ?></td>
                <td><?= $h->habitacion_tipo_nombre ?></td>
                <td><?= $nombre ?></td>
                <td><?= $rate ?></td>
                <td><?= $in ?></td>
                <td><?= $out ?></td>
                <td><?= $agencia ?></td>
                <td><?= $obs ?></td>
            </tr>
            <?php
        }
        ?>
            </tbody>
        </table>
    </div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script>
    function getid(id,hab,tipo,tarifa) {
        limpiar();
        $("#id_habitacion").val(id);
        $("#label_titulo").html(hab + " - "+tipo);
        $("#label_titulo2").html(hab + " - "+tipo);
        $("#rack_tarifa").val(tarifa);
    }
    function desha() {
        if($("#menu_estado").val()==0){
            $(".container_grupo").hide();
        }else{
            $(".container_grupo").show();
        }
    }
    function gestionar_rack(){
        var valor = true;
        var boton = "btn-agregar-rack";
        var id_habitacion = $('#id_habitacion').val();
        var rack_nombre = $('#rack_nombre').val();
        var tipo_doc = $('#tipo_doc').val();
        var rack_dni = $('#rack_dni').val();
        var rack_in = $('#rack_in').val();
        var rack_out = $('#rack_out').val();
        var rack_noches = $('#rack_noches').val();
        var rack_tarifa = $('#rack_tarifa').val();
        var rack_moneda = $('#rack_moneda').val();
        var rack_agencia = $('#rack_agencia').val();
        var rack_desayuno = $('#rack_desayuno').val();
        var rack_desayuno_costo = $('#rack_desayuno_costo').val();
        var rack_observaciones = $('#rack_observaciones').val();
        var menu_estado = $('#menu_estado').val();
        if(menu_estado==0){
            valor = validar_campo_vacio('rack_in', rack_in, valor);
            valor = validar_campo_vacio('rack_out', rack_out, valor);
            valor = validar_campo_vacio('rack_observaciones', rack_observaciones, valor);
        }else{
            valor = validar_campo_vacio('rack_nombre', rack_nombre, valor);
            valor = validar_campo_vacio('tipo_doc', tipo_doc, valor);
            valor = validar_campo_vacio('rack_dni', rack_dni, valor);
            valor = validar_campo_vacio('rack_in', rack_in, valor);
            valor = validar_campo_vacio('rack_out', rack_out, valor);
            valor = validar_campo_vacio('rack_noches', rack_noches, valor);
            valor = validar_campo_vacio('rack_tarifa', rack_tarifa, valor);
            valor = validar_campo_vacio('rack_agencia', rack_agencia, valor);
            valor = validar_campo_vacio('rack_desayuno', rack_desayuno, valor);
        }
        var cant = $("#rack_cant").val();
        var resp = "";
        if(cant>1){
            var j = 1;
            for (var i = 1;i<cant;i++){
                var cont = j+i;
                var person = $("#extra_"+cont).val();
                resp+=person+"//--";
            }
        }
        if(valor){
            //Cadena donde enviaremos los parametros por POST
            var cadena = "id_habitacion=" + id_habitacion +
                "&rack_nombre=" + rack_nombre +
                "&tipo_doc=" + tipo_doc +
                "&rack_dni=" + rack_dni +
                "&rack_in=" + rack_in +
                "&rack_out=" + rack_out +
                "&extras=" + resp +
                "&rack_noches=" + rack_noches +
                "&rack_tarifa=" + rack_tarifa +
                "&rack_moneda=" + rack_moneda +
                "&rack_agencia=" + rack_agencia +
                "&rack_desayuno=" + rack_desayuno +
                "&rack_desayuno_costo=" + rack_desayuno_costo +
                "&rack_observaciones=" + rack_observaciones +
                "&menu_estado=" + menu_estado;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Hotel/guardar_rack",
                data: cadena,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, 'Guardando...', true);
                },
                success:function (r) {
                    cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                    switch (r.result.code) {
                        case 1:
                            location.reload();
                            break;
                        case 2:
                            respuesta('Error al guardar', 'error');
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        }
    }
    function limpiar() {
        $('#id_habitacion').val("");
        $('#rack_nombre').val("");
        $('#rack_in').val("<?= date("Y-m-d"); ?>");
        $('#rack_out').val("<?= date("Y-m-d"); ?>");
        $('#rack_noches').val("1");
        $('#rack_tarifa').val("");
        $('#rack_agencia').val("DIRECTO");
        $('#rack_desayuno').val("0");
        $('#rack_observaciones').val("");
        $('#menu_estado').val(1);
        $("#menu_estado option[value='1']").attr("selected");
        cambiar_color_estado("menu_estado");
        $(".container_grupo").show();
    }
    function desha2(des) {
        $("#deshabilitado_obs").html(des);
    }
    function cambiar_fecha() {
        var fecha = $("#fecha_a_buscar").val();
        var tipo = $("#tipo_hab").val();
        var est = $("#estado_hab").val();
        var criterio = fecha+"999"+tipo+"999"+est;
        location.href=urlweb+"Hotel/inicio/"+criterio;
    }
    function ver_historial(id) {
        window.open(urlweb+"Hotel/historial_habitacion/"+ id + "/<?= date('Y-m-d'); ?>al<?= date('Y-m-d') ?>","_blank");
    }
    function cant_personas() {
        var cant = $("#rack_cant").val();
        var resp = "";
        if(cant>1){
            var j = 1;
            for (var i = 1;i<cant;i++){
                var cont = j+i;
                resp+="<label>Persona "+cont+" </label><input type='text' id='extra_"+cont+"' class='form-control'>";
            }
        }
        $("#extras").html(resp);
    }
    function mostrar(id) {
        $("#habs_"+id).show();
    }
</script>