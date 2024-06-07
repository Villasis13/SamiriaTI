<div class="modal fade" id="add_venta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 720px;">
            <div class="modal-header">
                <h5 class="modal-title">GENERAR COMPROBANTE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="tipo_venta">Tipo Comprobante</label>
                                <select id="tipo_venta" class="form-control" onchange = "selecttipoventa_(this.value)">
                                    <option value="">Seleccionar...</option>
                                    <option value="03" selected>BOLETA</option>
                                    <option value="01">FACTURA</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="serie">Serie</label>
                                <select name="serie" id="serie" class="form-control" onchange="ConsultarCorrelativo()">
                                    <option value="">Seleccionar</option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label for="numero">Numero</label>
                                <input class="form-control" type="text" id="numero" readonly>
                            </div>
                            <div class="col-lg-3">
                                <label for="caja">Caja</label>
                                <select class="form-control" id="caja">
                                    <?php
                                    foreach ($data_caja as $data_caj){
                                        echo "<option value='$data_caj->id_caja_cierre'>$data_caj->caja_nombre</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row factura" style="display: none">
                            <div class="col-lg-4">
                                <label for="ruc">RUC</label>
                                <input type="text" class="form-control" id="ruc">
                            </div>
                            <div class="col-lg-1">
                                <br><a id="botoncito" class="text-white btn btn-primary"><i class="fa fa-search"></i></a>
                            </div>
                            <div class="col-lg-7">
                                <label for="razon_social">Razón Social</label>
                                <input type="text" class="form-control" id="razon_social">
                            </div>
                            <div class="col-lg-12">
                                <label for="domicilio">Domicilio</label>
                                <input type="text" class="form-control" id="domicilio">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="tipo_pago">Tipo de pago</label>
                                <select class="form-control" type="text" id="tipo_pago" onchange="forma_pago_credito_3()">
                                    <?php
                                    foreach ($tipos_pago as $t){
                                        ($t->id_tipo_pago==3)?$selected_t="selected":$selected_t="";
                                        echo "<option $selected_t value='$t->id_tipo_pago'>$t->tipo_pago_nombre</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-3 no-show" id="div_tarjeta_3">
                                <label for="codigo_venta_3">Código</label>
                                <input type="text" id="codigo_venta_3" name="codigo_venta_3" class="form-control">
                            </div>
                            <div class="col-lg-3">
                                <label for="">Moneda</label>
                                <select type="text" class="form-control" id="id_moneda" name="id_moneda" onchange="calcular_dh(this.value)">
                                    <?php
                                    foreach ($tipos_moneda as $t){
                                        ($t->id_moneda==1)?$selected_t="selected":$selected_t="";
                                        echo "<option $selected_t value='$t->id_moneda'>$t->moneda</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-2" id="tcambio">
                                <label>T. Cambio</label>
                                <input type="text" class="form-control" name="tipo_cambio" id="tipo_cambio" onkeyup="validar_numeros_decimales_dos(this.id);convertir_d()">
                            </div>
                            <div class="col-lg-12">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Descripcion</th>
                                        <th>Cantidad</th>
                                        <th>Pr Unit</th>
                                        <th>Subtotal</th>
                                    </tr>
                                    </thead>
                                    <tbody id="generar_fact_tbody"></tbody>
                                </table>
                                <div class="col-lg-12 text-right">
                                    <h4 id="aqui_va_suma" style="color: var(--color-logo)"></h4>
                                    <input type="hidden" name="totalito" id="totalito">
                                </div>
                                <label for="comp_obs">Observaciones</label>
                                <textarea id="comp_obs" class="form-control"><?= $data_rack->cliente_nombre." - HAB ".$data_rack->habitacion_nro; ?></textarea><br>
                                <input type="checkbox" onchange="partir_pago()" id="partir_pago">
                                <label for="partir_pago">Partir pago</label>
                                <div id="div_partir_pago" style="display: none">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select class="form-control" id="partir_pago_tipo" onchange="cambiar_codigo()">
                                                <?php
                                                foreach ($tipos_pago as $t){
                                                    ($t->id_tipo_pago==3)?$selected_t="selected":$selected_t="";
                                                    echo "<option $selected_t value='$t->id_tipo_pago'>$t->tipo_pago_nombre</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" id="partir_pago_monto" class="form-control" value="0">
                                        </div>
                                        <div class="col-md-4" id="cp1">
                                            <input type="text" id="codigo" class="form-control" value="" placeholder="INGRESE CODIGO">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select class="form-control" id="partir_pago_tipo2" onchange="cambiar_codigo()">
                                                <?php
                                                foreach ($tipos_pago as $t){
                                                    ($t->id_tipo_pago==3)?$selected_t="selected":$selected_t="";
                                                    echo "<option $selected_t value='$t->id_tipo_pago'>$t->tipo_pago_nombre</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" id="partir_pago_monto2" class="form-control" value="0">
                                        </div>
                                        <div class="col-md-4" id="cp2">
                                            <input type="text" id="codigo2" class="form-control" value="" placeholder="INGRESE CODIGO">
                                        </div>
                                    </div>
                                    <input type="hidden" id="id_cliente_detalle" name="id_cliente_detalle">
                                </div><br>
                                <input type="checkbox" onchange="agrupar()" id="agrupar">
                                <label for="agrupar">Cambiar descripción</label>
                                <div id="div_agrupar" style="display: none">
                                    <input type="text" class="form-control" value="POR CONSUMO" id="agrupar_texto">
                                </div><br>
                                <input type="checkbox" id="mostrar_tp">
                                <label for="mostrar_tp">Mostrar Tipo de pago</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-agregar-venta" onclick="guardar_venta()"><i class="fa fa-save fa-sm text-white-50"></i> Generar Comprobante</button>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 text-center font-weight-bold text-primary" style="color: #6a0c0d !important;">HABITACION <?= $habitacion->habitacion_nro." - ". $habitacion->habitacion_tipo_nombre; ?></h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label for="desde">Desde</label>
                    <input id="desde" class="form-control" type="date" value="<?= $fecha_i ?>">
                </div>
                <div class="col-md-3">
                    <label for="hasta">Hasta</label>
                    <input id="hasta" class="form-control" type="date" value="<?= $fecha_f ?>">
                </div>
                <div class="col-md-3">
                    <br><button onclick="buscar()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                </div>
            </div>
            <div class="row">
            <?php
            if(count($racks)>0){
            foreach ($racks as $data_rack){
                $detalle_rack = $this->hotel->listar_detalle_rack($data_rack->id_rack);

                ?>
                <hr>
                <div class="row">
                    <div class="col-md-5">
                        <div class="main-content">
                            <div class="ticket">
                                <div class="ticket__main">
                                    <div class="header" style="font-size: small">ID: <?= $data_rack->id_rack; ?></div>
                                    <div class="info passenger">
                                        <div class="info__item">Huésped</div>
                                        <div class="info__detail"><?= ($data_rack->id_tipodocumento==4)?$data_rack->cliente_razonsocial:$data_rack->cliente_nombre; ?></div>

                                    </div>
                                    <div class="info departure">
                                        <div class="info__item">Documento</div>
                                        <div class="info__detail"><?= $data_rack->tipo_documento_abr.": ".$data_rack->cliente_numero; ?></div>
                                    </div>
                                    <div class="info arrival">
                                        <div class="info__item">Tipo de Habitación</div>
                                        <div class="info__detail"><?= $data_rack->habitacion_tipo_nombre; ?></div>
                                    </div>
                                    <div class="info date">
                                        <div class="info__item">Desde</div>
                                        <div class="info__detail"><?= $this->validar->obtener_nombre_fecha($data_rack->rack_in,"Date","Date","es"); ?></div>
                                    </div>
                                    <div class="info time">
                                        <div class="info__item">Hasta</div>
                                        <div class="info__detail"><?= $this->validar->obtener_nombre_fecha($data_rack->rack_out,"Date","Date","es"); ?></div>
                                    </div>
                                    <div class="info carriage">
                                        <div class="info__item">Noches</div>
                                        <div class="info__detail"><?= $data_rack->rack_noches; ?></div>
                                    </div>
                                    <div class="info seat">
                                        <div class="info__item">Desayunos</div>
                                        <div class="info__detail"><?= $data_rack->rack_desayuno; ?></div>
                                    </div>
                                    <div class="fineprint">
                                        <?php
                                        if(count($extras)>0){
                                            echo "<p>Personas extras:</p>";
                                            foreach ($extras as $extra){
                                                echo "<p>- $extra->extra_nombre</p>";
                                            }
                                        }
                                        ?>
                                        <p>Observaciones: <?= $data_rack->rack_observaciones; ?></p>
                                        <p>Agencia: <?= $data_rack->rack_agencia; ?></p><br>
                                        <p>Fecha Check out : <?= date('d-m-Y h:i:s',strtotime($data_rack->rack_checkout)); ?></p>

                                    </div>
                                    <div class="barcode">
                                        <div class="barcode__scan"></div>
                                        <div class="barcode__id"><?= $data_rack->rack_fecha; ?></div>
                                    </div>
                                    <div class="snack">
                                        <img src="<?= _SERVER_ ?>media/logo/logo_empresa.jpeg" style="width: inherit">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 text-center font-weight-bold text-primary" style="color: #6a0c0d !important;">Detalle del consumo</h6>
                            </div>
                            <div class="card-body">
                        <table class="table table-striped" style="font-size: small">
                            <thead>
                            <tr>
                                <th>Fact</th>
                                <th>Fecha</th>
                                <th>Descripcion</th>
                                <th>Cantidad</th>
                                <th>Precio Unit</th>
                                <th>Subtotal</th>
                                <th>Estado</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $total=0;
                            $total_p=0;
                            $fact = false;
                            foreach ($detalle_rack as $d) {
                                if($d->rack_detalle_estado_fact==0){
                                    if($d->rack_detalle_envio==1){
                                        $fact=false;
                                        $fact_boton = false;
                                        $a = "-";
                                        $estado_fact = "<span style=\"color: red;\"><i style='color: red;' class='fa fa-exclamation-triangle'></i> Sin facturar y Agregado a Cuenta</span>";
                                    }else{
                                        if($d->rack_detalle_estado_venta==2){
                                            $a = "-";
                                            $estado_fact = "<span style=\"color: red;\"><i style='color: red;' class='fa fa-exclamation-triangle'></i> Auspicio</span>";
                                        }else{
                                            $fact=true;
                                            $estado_fact = "<span style=\"color: red;\"><i style='color: red;' class='fa fa-exclamation-triangle'></i> Sin facturar</span>";
                                            $a="<input type='checkbox' id='fact_".$d->id_rack_detalle."' class='form-control'>";
                                            $eli="<button data-toggle='modal' data-target='#eliminar_detalle' onclick='poner_id_eliminar($d->id_rack_detalle)' class='btn btn-danger'>X</button>";
                                            $total_p+=$d->rack_detalle_subtotal;
                                        }
                                    }
                                }else{
                                    $total+=$d->rack_detalle_subtotal;
                                    $data_venta = $this->ventas->listar_venta_x_id($d->rack_detalle_estado_fact);
                                    $sunat=$data_venta->venta_serie."-".$data_venta->venta_correlativo;
                                    $fechita_pe = date('Ymd',strtotime($data_venta->venta_fecha));
                                    if(file_exists("media/comprobantes/".$sunat."-".$fechita_pe.".pdf")){
                                        $ruta_sunat=_SERVER_."media/comprobantes/".$sunat."-".$fechita_pe.".pdf";
                                    }else{
                                        $ruta_sunat=_SERVER_ . "Ventas/imprimir_pdf/" . $data_venta->id_venta;
                                    }
                                    if($data_venta->venta_estado_sunat==0){
                                        $sunat.=" <br><span style='color: orange'>Sin enviar a SUNAT</span>";
                                    }else{
                                        $sunat.=" <br>ENVIADO A SUNAT";
                                    }
                                    $estado_fact = "<span style=\"color: green;\"><i style='color: green;' class='fa fa-check-square'></i><a target='_blank' href='".$ruta_sunat."'> $sunat</a></span>";
                                    $a="-";
                                }
                                ?>
                                <tr>
                                    <td><?= $a; ?></td>
                                    <td><?= $d->rack_detalle_fecha; ?></td>
                                    <td><?= $d->producto_nombre?><?= ($d->rack_detalle_correlativo_comanda != Null)? ' / '.$d->rack_detalle_correlativo_comanda : ""?></td>
                                    <td><?= $d->rack_detalle_cantidad; ?></td>
                                    <td><?= $d->rack_detalle_preciounit; ?></td>
                                    <td><?= $d->rack_detalle_subtotal; ?></td>
                                    <td><?= $estado_fact; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td class="text-center"><input onchange="todos_('<?= $data_rack->id_rack;?>')" type="checkbox" id="todos<?= $data_rack->id_rack;?>" class="form-control"></td>
                                <td class="text-left"><label for="todos">Todos</label></td>
                                <td colspan="2" class="text-left" style="font-size: 12pt;font-weight: bold ">T. Pagado <?= $data_rack->simbolo." ".($total); ?></td>
                                <td colspan="2" class="text-left" style="font-size: 12pt;color: red">Pendiente <?= $data_rack->simbolo." ".($total_p); ?></td>
                                <?php
                                if($fact){
                                    ?>
                                    <td colspan="1"><button data-toggle="modal" onclick="get_fact();llenar_id(<?= $data_rack->id_cliente?>)" data-target="#add_venta" style="background: #6a0c0d" id="btn-comprobante" class="btn btn-primary"><i class="fa fa-cloud"></i><br> Generar Comprobante</button></td>
                                    <?php
                                }
                                ?>
                            </tr>
                            </tbody>
                        </table>
                            </div>
                        </div>
                    </div>
                </div>
                <hr><hr>
                <?php
            }}else{
                echo "<br><h3>No se encontraron resultados en esta fechas</h3>";
            }
            ?>
            </div>
        </div>
    </div>
</div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script>
    function forma_pago_credito_3(){
        let tipo_pago_3 = $('#tipo_pago').val();
        if(tipo_pago_3 == 1){
            $('#div_tarjeta_3').show();
        }else{
            $('#div_tarjeta_3').hide();
        }
    }


    var cadenita = "";
    var cadenita_textos = "";
    let variable = "";
    $(document).ready(function(){
        $("#tcambio").hide();
        $("#cp1").hide();
        $("#cp2").hide();
    });

    function cambiar_codigo(){
        let cod1 = $("#partir_pago_tipo").val();
        let cod2 = $("#partir_pago_tipo2").val();
        if(cod1==1){
            $("#cp1").show();
        }else{
            $("#cp1").hide();
        }
        if(cod2==1){
            $("#cp2").show();
        }else{
            $("#cp2").hide();
        }
    }

    function buscar() {
        var desde = $("#desde").val();
        var hasta = $("#hasta").val();
        location.href=urlweb + "Hotel/historial_habitacion/<?= $habitacion->id_habitacion ?>/"+desde+"al"+hasta;
    }
    $(function(){
        $('#botoncito').on('click', function(){
            var ruc = $('#ruc').val();
            if(ruc.length==11){
                if(!isNaN(ruc)){
                    var entrar_api=true;
                    $.ajax({
                        type: "POST",
                        url: urlweb + "api/Hotel/buscar_cliente",
                        data: "ruc="+ruc,
                        dataType: 'json',
                        success:function (r) {
                            if (r.result.code == 1) {
                                entrar_api=false;
                                $('#razon_social').val(r.result.razon_social);
                                $('#domicilio').val(r.result.domicilio);
                                respuesta("Datos encontrados","success");
                            }else{
                                if(entrar_api){
                                    var formData = new FormData();
                                    formData.append("token", "uTZu2aTvMPpqWFuzKATPRWNujUUe7Re1scFlRsTy9Q15k1sjdJVAc9WGy57m");
                                    formData.append("ruc", ruc);
                                    var request = new XMLHttpRequest();
                                    request.open("POST", "https://api.migo.pe/api/v1/ruc");
                                    request.setRequestHeader("Accept", "application/json");
                                    request.send(formData);
                                    respuesta("Cargando","warning");
                                    request.onload = function() {
                                        var data = JSON.parse(this.response);
                                        if(data.success){
                                            respuesta("Datos encontrados","success");
                                            if(data.condicion_de_domicilio=="NO HABIDO"){
                                                alert("Este ruc se encuentra como NO HABIDO.");
                                            }else{
                                                $('#razon_social').val(data.nombre_o_razon_social);
                                                $('#domicilio').val(data.direccion);
                                            }
                                        }else{
                                            respuesta(data.message,"error");
                                        }
                                    };
                                }
                            }
                        }
                    });
                }else{
                    respuesta("El ruc debe contener solo números.","error");

                }
            }else{
                respuesta("El ruc debe contener 11 dígitos.","error");

            }
        });
    });

    function llenar_id(id_cliente){
        $("#id_cliente_detalle").val(id_cliente);
        console.log(id_cliente);
    }

    function guardar_venta(){
        cadenita_textos="";
        var valor=true;
        var id_cliente = $("#id_cliente_detalle").val();
        var venta_tipo = $("#tipo_venta").val();
        var tipo_pago = $("#tipo_pago").val();
        var serie = $("#serie").val();
        var correlativo= $("#numero").val();
        var caja= $("#caja").val();
        var ruc= $("#ruc").val();
        var comp_obs= $("#comp_obs").val();
        var razon_social= $("#razon_social").val();
        var domicilio= $("#domicilio").val();
        var moneda= $("#id_moneda").val();
        var id_rack= $("#id_rack").val();
        let tipo_cambio = $("#tipo_cambio").val();
        let codigo_venta_3 = $("#codigo_venta_3").val();
        var boton = "btn-agregar-venta";
        //PA CODIGOS
        let codigo = $("#codigo").val();
        let codigo2 = $("#codigo2").val();
        var agrupar = 0;
        let mostrar_tp = 0;
        var agrupar_des="";
        if($("#agrupar").is(':checked')){
            agrupar=1;
            agrupar_des=$("#agrupar_texto").val();
        }
        if($("#mostrar_tp").is(':checked')){
            mostrar_tp=1;
        }
        var partir_pago = 0;
        var partir_pago_id="";
        var partir_pago_monto="";
        var partir_pago_id2="";
        var partir_pago_monto2="";
        if($("#partir_pago").is(':checked')){
            partir_pago=1;
            var totalito=$("#totalito").val() * 1;
            partir_pago_id=$("#partir_pago_tipo").val();
            partir_pago_id2=$("#partir_pago_tipo2").val();
            partir_pago_monto=$("#partir_pago_monto").val();
            partir_pago_monto2=$("#partir_pago_monto2").val();
            if(partir_pago_monto * 1 + partir_pago_monto2 * 1 != totalito){
                valor=false;
                respuesta("Suma incorrecta","error");
            }
        }
        var cadde = cadenita.split('--/');
        for (var i = 0;i<cadde.length - 1;i++){
            var aja = $("#cad_texto_"+cadde[i]).val();
            cadenita_textos+=aja+"-**-";
        }
        var cadena = "id_cliente=" + id_cliente +
                "&venta_tipo="+venta_tipo+
            "&tipo_pago="+tipo_pago+
            "&codigo_venta="+codigo_venta_3+
            "&serie="+serie+
            "&correlativo="+correlativo+
            "&moneda="+moneda+
            "&id_rack="+id_rack+
            "&caja="+caja+
            "&mostrar_tp="+mostrar_tp+
            "&ruc="+ruc+
            "&comp_obs="+comp_obs+
            "&razon_social="+razon_social+
            "&domicilio="+domicilio+
            "&agrupar="+agrupar+
            "&tipo_cambio="+tipo_cambio+
            "&agrupar_des="+agrupar_des+
            "&partir_pago="+partir_pago+
            "&codigo="+codigo+
            "&codigo2="+codigo2+
            "&partir_pago_id="+partir_pago_id+
            "&partir_pago_id2="+partir_pago_id2+
            "&partir_pago_monto="+partir_pago_monto+
            "&partir_pago_monto2="+partir_pago_monto2+
            "&cadenita="+cadenita+
            "&cadenita_textos="+cadenita_textos+
            "&variable="+JSON.stringify(variable)+
            "&id_turno=1";
        if(valor){
            $.ajax({
                type: "POST",
                url: urlweb + "api/Ventas/guardar_venta",
                data: cadena,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, 'Guardando...', true);
                },
                success:function (r) {
                    cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                    switch (r.result.code) {
                        case 1:
                            window.open(urlweb+r.result.ruta, "_blank");
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
    function selecttipoventa_(valor){
        selecttipoventa(valor);
        if (valor == "07" || valor == "08"){
            $('#credito_debito').show();

            if(valor == "07"){
                $('#notaCredito').show();
                $('#notaDebito').hide();
            }else{
                $('#notaCredito').hide();
                $('#notaDebito').show();
            }
            var tipo_comprobante =  valor;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Ventas/tipo_nota_descripcion",
                data: "tipo_comprobante="+tipo_comprobante,
                dataType: 'json',
                success:function (r) {
                    $("#nota_descripcion").html(r);
                }
            });
        } else{
            $('#credito_debito').hide();
            if(valor == "01"){
                $('#select_tipodocumento').val(4);
                $('#client_number').val('');
                $('#client_name').val('');
                $('.factura').show();
            }else{
                $('#select_tipodocumento').val(2);
                $('#client_number').val('11111111');
                $('#client_name').val('PÚBLICO EN GENERAL');
                $('.factura').hide();
            }

        }
    }
    function selecttipoventa(valor){
        Consultar_serie();
        var tipo_comprobante =  valor;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/tipo_nota_descripcion",
            data: "tipo_comprobante="+tipo_comprobante,
            dataType: 'json',
            success:function (r) {
                $("#nota_descripcion").html(r);
            }
        });
    }
    function Consultar_serie(){
        //var tipo_documento_modificar = $('#Tipo_documento_modificar').val();
        var tipo_venta =  $("#tipo_venta").val();
        var concepto = "LISTAR_SERIE";
        var cadena = "tipo_venta=" + tipo_venta +
            "&concepto=" + concepto;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/consultar_serie",
            data: cadena,
            dataType: 'json',
            success:function (r) {
                var series = "";
                //var series = "<option value='' selected>Seleccione</option>";
                for (var i=0; i<r.serie.length; i++){
                    series += "<option value='"+r.serie[i].id_serie+"'>"+r.serie[i].serie+"</option>"
                }
                $("#serie").html(series);
                ConsultarCorrelativo();
            }

        });
    }
    function ConsultarCorrelativo(){
        var id_serie =  $("#serie").val();
        var tipo_venta =  $("#tipo_venta").val();
        var concepto = "LISTAR_NUMERO";
        var cadena = "id_serie=" + id_serie +
            "&tipo_venta=" + tipo_venta+
            "&concepto=" + concepto;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/consultar_serie",
            data: cadena,
            dataType: 'json',
            success:function (r) {
                $("#numero").val(r.correlativo);
            }

        });
    }

    /*function get_fact_() {
        cadenita = "";
        <?php
        foreach ($detalle_rack as $da) {
        if($da->rack_detalle_estado_fact==0){
        ?>
        if($("#fact_<?= $da->id_rack_detalle; ?>").is(':checked')){
            cadenita += <?= $da->id_rack_detalle; ?>+"--/";
        }
        <?php
        }}
        ?>
        if(cadenita===""){
            respuesta("Seleccione al menos un item","error");
        }else{
            $.ajax({
                type: "POST",
                url: urlweb + "api/Hotel/consultar_rack_detalle",
                data: "cadena="+cadenita,
                dataType: 'json',
                success:function (r) {
                    $("#generar_fact_tbody").html(r);
                    $("#btn-agregar-venta").show();
                }
            });
        }
    }*/

    function calcular_dh(valor) {
        if(valor == 1){
            //AQUI SE VA CALCULAR PARA PASARA A DOLARES
            $("#tcambio").hide();
        }else{
            $("#tcambio").show();
            //AQUI SE VA CALCULAR PARA PASAR A SOLES
        }
    }

    function get_fact() {
        cadenita = "";
        <?php
        foreach ($racks as $data_rack){
        $detalle_rack = $this->hotel->listar_detalle_rack($data_rack->id_rack);
        foreach ($detalle_rack as $da) {
        if($da->rack_detalle_estado_fact==0){
        ?>
        if($("#fact_<?= $da->id_rack_detalle; ?>").is(':checked')){
            cadenita += <?= $da->id_rack_detalle; ?>+"--/";
            $("#moneda").val("<?= $data_rack->moneda; ?>");
            $("#id_rack").val("<?= $data_rack->id_rack; ?>");
            $("#comp_obs").val("<?= $data_rack->cliente_nombre.' - HAB '.$data_rack->habitacion_nro;?>");
                    }
                    <?php
                    }}}
                    ?>
        if(cadenita===""){
            respuesta("Seleccione al menos un item","error");
            variable="";
            armar_tabla();
            desactivarBoton();
        }else{
            $.ajax({
                type: "POST",
                url: urlweb + "api/Hotel/consultar_rack_detalle",
                data: "cadena="+cadenita,
                dataType: 'json',
                success:function (r) {
                    let datos_array = [];
                    datos_array = JSON.parse(JSON.stringify(r.result));
                    variable = datos_array;
                    //AQUI VA VENIR UN CODIGO PARA DISEÑAR UNA TABLA QUE SE MOSTRARA CON EL DETALLE DE LO SELECCIONADO
                    armar_tabla();

                    // $("#generar_fact_tbody").html(r);
                    // $("#btn-agregar-venta").show();
                }
            });
        }
    }
    function activarBoton() {
        var boton = document.getElementById("btn-agregar-venta");
        boton.style.display = "block"; // Cambia el estilo a "block" para mostrar el botón
    }

    function desactivarBoton() {
        var boton = document.getElementById("btn-agregar-venta");
        boton.style.display = "none"; // Cambia el estilo a "none" para ocultar el botón
    }

    function convertir_d(){
        let tipo_cambio = $("#tipo_cambio").val();
        let moneda = $("#moneda").val();
        let calcular_precio = "";
        let calcular_sub = "";
        if(moneda==1){
            //AQUI SE PASA A SOLES
            for (var i=0; i<variable.length; i++){
                calcular_precio = redondear(parseFloat(tipo_cambio) * parseFloat(variable[i]['precio']),3);
                calcular_sub = parseFloat(calcular_precio) * parseFloat(variable[i]['cantidad']);
                variable[i]['precio_final'] = calcular_precio;
                variable[i]['subtotal'] = redondear(calcular_sub,2);
            }
            armar_tabla()
        }else{
            //AQUI SE PASA A DOLARES
            for (var i=0; i<variable.length; i++){
                calcular_precio = redondear(parseFloat(variable[i]['precio']) / parseFloat(tipo_cambio),3);
                calcular_sub =calcular_precio * variable[i]['cantidad'];
                variable[i]['precio_final'] = calcular_precio;
                variable[i]['subtotal'] = redondear(calcular_sub,2);
            }
            armar_tabla()
        }
        //$("#totalito").html(redondear(calcular,2,false));
    }


    function  armar_tabla(){
        var tablas_e = "";
        let suma = 0;
        for (var i=0; i<variable.length; i++){
            tablas_e += "<tr > <td>" +variable[i]['id']+"</td>" +
                "<td>" +variable[i]['producto']+"</td>" +
                "<td>" +variable[i]['cantidad']+"</td>" +
                "<td>" +variable[i]['precio_final']+"</td>" +
                "<td>" +variable[i]['subtotal']+"</td> </tr>"
            suma = redondear(parseFloat(suma) + parseFloat(variable[i]['subtotal']),2);
        }
        $("#generar_fact_tbody").html(tablas_e);
        $("#aqui_va_suma").html("TOTAL : "+ parseFloat(suma,2));
        $("#totalito").val(parseFloat(suma,2));
        activarBoton()
    }

    function todos_(id_rack) {
        let id_rack_data = ''
        if($("#todos"+id_rack).is(':checked')){
            <?php
            foreach ($racks as $data_rack){
                ?>
                id_rack_data = <?= $data_rack->id_rack;?>;
                if(id_rack == id_rack_data){
                    <?php
                    $detalle_rack = $this->hotel->listar_detalle_rack($data_rack->id_rack);
                    foreach ($detalle_rack as $da) {
                        if($da->rack_detalle_estado_fact==0){
                        ?>
                            $("#fact_<?= $da->id_rack_detalle;?>").attr('checked','checked');
                        <?php
                        }
                    }
                    ?>
                }
                <?php
            }
            ?>
        }else{
            <?php
            foreach ($racks as $data_rack){
                ?>
                id_rack_data = <?= $data_rack->id_rack;?>;
                if(id_rack == id_rack_data){
                    <?php
                    $detalle_rack = $this->hotel->listar_detalle_rack($data_rack->id_rack);
                    foreach ($detalle_rack as $da) {
                        if($da->rack_detalle_estado_fact==0){
                            ?>
                            $("#fact_<?= $da->id_rack_detalle; ?>").removeAttr('checked');
                            <?php
                        }
                    }
                    ?>
                }
            <?php
            }
            ?>
        }
    }
    function poner_id_eliminar(id) {
        $("#eliminar_id").val(id);
    }
    function agrupar() {
        if($("#agrupar").is(':checked')){
            $("#div_agrupar").show();
        }else{
            $("#div_agrupar").hide();
        }
    }
    function partir_pago() {
        if($("#partir_pago").is(':checked')){
            $("#div_partir_pago").show();
        }else{
            $("#div_partir_pago").hide();
        }
    }
    function eliminar_detalle(){
        var eliminar_clave = $("#eliminar_clave").val();
        var eliminar_id = $("#eliminar_id").val();
        var eliminar_motivo = $("#eliminar_motivo").val();
        var boton = "btn-eliminar-detalle";
        var cadena = "eliminar_clave=" + eliminar_clave+
            "&eliminar_id="+eliminar_id+
            "&eliminar_motivo="+eliminar_motivo;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/eliminar_detalle",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'Guardando...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-trash fa-sm text-white-50\"></i> Eliminar", false);
                switch (r.result.code) {
                    case 1:location.reload();break;
                    case 2:respuesta('Error al guardar', 'error');break;
                    case 3:respuesta('Clave incorrecta', 'error');break;
                    default:respuesta('¡Ocurrió un error inesperado. Contactar con TI', 'error');break;
                }
            }
        });
    }
    $(document).ready(function(){
        var valor = $('#tipo_venta').val();
        selecttipoventa(valor);
    });
</script>
<style>
    body {
        font-family: "Questrial", sans-serif;
    }

    aside.context {
        text-align: center;
        color: #333;
        line-height: 1.7;
    }
    aside.context a {
        text-decoration: none;
        color: #333;
        padding: 3px 0;
        border-bottom: 1px dashed;
    }
    aside.context a:hover {
        border-bottom: 1px solid;
    }
    aside.context .explanation {
        max-width: 700px;
        margin: 4em auto 0;
    }

    .main-content {
        /*margin: 4em auto 0;*/
        width: 100%;
        text-transform: uppercase;
    }

    .ticket {
        display: grid;
        grid-template-columns: auto 5px;
        background: #f3f1c9;
        border-radius: 10px;
        border: 2px solid #222;
        cursor: default;
    }
    .ticket__main {
        display: grid;
        grid-template-columns: repeat(6, 1fr) 120px;
        grid-template-rows: repeat(4, min-content) auto;
        padding: 10px;
    }

    .header {
        grid-area: title;
        grid-column: span 7;
        grid-row: 1;
        font: 900 25px "Montserrat", sans-serif;
        padding: 5px 0 5px 20px;
        letter-spacing: 6px;
        background: #6a0c0d;
        color: #f3f1c9;
    }

    .info {
        border: 3px solid;
        border-width: 0 3px 3px 0;
        padding: 8px;
    }
    .info__item {
        font: 400 13px "Questrial", sans-serif;
        letter-spacing: 0.5px;
        color: #6a0c0d;
    }
    .info__detail {
        font: 700 16px/1 "Jura";
        letter-spacing: 1px;
        margin: 4px 0;
        color: #6a0c0d;
    }

    .passenger {
        grid-column: 1/span 7;
    }

    .departure {
        grid-column-start: span 4;
    }
    .arrival {
        grid-column-start: span 3;
    }

    .passenger, .departure, .date {
        border-left: 3px solid;
    }

    .date {
        grid-column-start: span 3;
    }
    .time {
        grid-column-start: span 2;
    }

    .fineprint {
        grid-column-start: span 5;
        font-size: 14px;
        font-family: "Inconsolata";
        line-height: 1;
        margin-top: 10px;
        padding-right: 5px;
    }
    .fineprint p:nth-child(2) {
        margin: 4px 4px 0 0;
        padding-top: 4px;
        border-top: 1.5px dotted;
        font: 11px/1 "Inconsolata";
    }

    .snack {
        grid-column-start:span 1;
        width: 115px;
        margin: 10px 10px 0 0;
        position: relative;
        background: #fff;
        padding: 6px 0 2px;
        text-align: center;
        border-radius: 5px;
    }
    .snack svg {
        fill: #f3f1c9;
        width: 36px;
    }
    .snack__name {
        color: #f3f1c9;
        font-size: 12px;
    }

    .barcode {
        grid-column: 6/span 1;
        display: grid;
        margin: 10px 0 0;
        grid-template-rows: 1fr min-content;
    }
    .barcode__scan {
        background: linear-gradient(to right, #333 2%, #333 4%, transparent 4%, transparent 5%, #333 5%, #333 6%, transparent 6%, #333 6%, #333 8%, transparent 8%, transparent 9%, #333 9%, #333 10.5%, transparent 10.5%, transparent 11%, #333 11%, #333 12%, transparent 12%, transparent 13.5%, #333 13.5%, #333 15%, #333 17%, transparent 17%, transparent 19%, #333 19%, #333 20%, transparent 20%, transparent 21%, #333 21%, #333 22%, transparent 22%, transparent 23.5%, #333 23.5%, #333 25%, transparent 25%, transparent 26.5%, #333 26.5%, #333 27.5%, transparent 27.5%, transparent 28.5%, #333 28.5%, #333 30%, transparent 30%, transparent 32%, #333 32%, #333 34%, #333 36%, transparent 36%, transparent 37.5%, #333 37.5%, #333 40%, transparent 40%, transparent 41.5%, #333 41.5%, #333 43%, transparent 43%, transparent 46%, #333 46%, #333 48%, transparent 48%, transparent 49%, #333 49%, transparent 49%, transparent 50%, #333 50%, #333 51%, transparent 51%, transparent 53%, #333 53%, #333 54.5%, transparent 54.5%, transparent 56%, #333 56%, #333 58%, transparent 58%, transparent 59%, #333 59%, #333 60%, #333 62.5%, transparent 62.5%, transparent 64%, #333 64%, #333 64%, #333 67%, transparent 67%, transparent 69%, #333 69%, #333 70%, transparent 70%, transparent 71%, #333 71%, #333 72%, transparent 72%, transparent 73.5%, #333 73.5%, #333 76%, transparent 76%, transparent 79%, #333 79%, #333 80%, transparent 80%, transparent 82%, #333 82%, #333 82.5%, transparent 82.5%, transparent 84%, #333 84%, #333 87%, transparent 87%, transparent 89%, #333 89%, #333 91%, transparent 91%, transparent 92%, #333 92%, #333 95%, transparent 95%);
    }
    .barcode__id {
        letter-spacing: 1px;
        padding: 2px 0 0;
        color: #c02a28;
        font: 700 13px/1 "Jura";
    }

    .ticket__side {
        background: rgba(192, 42, 40, 0.2);
        box-sizing: border-box;
        border-left: 1.5px dashed #111;
        display: grid;
        grid-template-rows: repeat(2, 124px) 60px;
        grid-template-columns: 40px repeat(2, 45px);
        border-radius: 0 10px 10px 0;
    }
    .ticket__side .logo {
        text-align: center;
        background: #c02a28;
        padding: 10px 5px 10px 0px;
        margin: 10px 0 0 10px;
        font: 900 16px/1 "Montserrat";
        letter-spacing: 1.5px;
        grid-column: 1/span 1;
        grid-row: 1/span 2;
        position: relative;
        color: #fff;
        writing-mode: vertical-rl;
    }
    .ticket__side .logo p {
        transform: rotate(180deg);
    }
    .ticket__side .info {
        border: 3px solid #c02a28;
        border-width: 3px 3px 0;
        grid-column-start: 2;
        writing-mode: vertical-rl;
        transform: rotate(180deg);
    }
    .ticket__side .info.side-arrive {
        margin-top: 10px;
        border-width: 3px;
    }
    .ticket__side .info.side-date {
        grid-column-start: 3;
        border-right: none;
    }
    .ticket__side .info.side-time {
        grid-column: 3/span 1;
        grid-row: 1;
        margin-top: 10px;
        border-width: 3px 0 3px 3px;
    }
    .ticket__side .info__item {
        font-size: 11px;
        color: #c02a28;
    }
    .ticket__side .info__detail {
        font-size: 12px;
        margin: 0 2px 0 0;
        letter-spacing: 0px;
    }
    .ticket__side .barcode {
        grid-template-rows: 30px min-content;
        grid-row-start: 3;
        grid-column: 1/span 3;
        margin: 9px 0 0 10px;
        text-align: center;
    }
</style>