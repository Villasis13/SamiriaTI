<div class="modal fade" id="editar_tipo_pago" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 35% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Tipo de Pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <input type="hidden" id="id_venta_tp" name="id_venta_tp">
                    <div id="tabla"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btn_edittp" onclick="edit_tipo_pago()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 text-center font-weight-bold text-primary" style="color: #6a0c0d !important;">LISTA DE VENTAS REGISTRADAS SIN ENVIAR A SUNAT</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="<?= _SERVER_ ?>Ventas/historial_ventas">
                                <input type="hidden" id="enviar_registro" name="enviar_registro" value="1">
                                <div class="row">
                                    <div class="col-lg-2"></div>
                                    <div class="col-lg-2">
                                        <label>Tipo de Comprobante</label>
                                        <select  id="tipo_venta" name="tipo_venta" class="form-control">
                                            <option <?= ($tipo_venta == "")?'selected':''; ?> value="">Seleccionar...</option>
                                            <option <?= ($tipo_venta == "03")?'selected':''; ?> value="03">BOLETA</option>
                                            <option <?= ($tipo_venta == "01")?'selected':''; ?> value="01">FACTURA</option>
                                            <option <?= ($tipo_venta == "07")?'selected':''; ?> value= "07">NOTA DE CRÉDITO</option>
                                            <option <?= ($tipo_venta == "08")?'selected':''; ?> value= "08">NOTA DE DÉBITO</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="">Fecha de Inicio</label>
                                        <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="<?= $fecha_ini; ?>">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="">Fecha Final</label>
                                        <input type="date" id="fecha_final" name="fecha_final" class="form-control" value="<?= $fecha_fin; ?>">
                                    </div>
                                    <div class="col-lg-2">
                                        <button style="margin-top: 34px;" class="btn btn-success" ><i class="fa fa-search"></i> Buscar Registro</button>
                                    </div>
                                    <div class="col-lg-3">
                                    </div>
                                    <div class="col-lg-12" style="text-align: center">
                                        <label for="" style="margin-top: 20px;color: black;">COMPROBANTES SIN ENVIAR: <span style="color: red;"><?= count($ventas_cant);?></span><br>
                                            <span style="font-size: 12px;"><strong>*</strong> ENVIAR MÁXIMO 3 DIAS DESPUES LA FECHA DE EMISIÓN</span></label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow mb-4">
                        <?php
                        if($filtro) {
                            ?>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-striped table-earning" id="dataTable" width="100%" cellspacing="0">
                                        <thead class="text-capitalize">
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha de Emision</th>
                                            <th>Origen</th>
                                            <th>Comprobante</th>
                                            <th>Serie y Correlativo</th>
                                            <th>Cliente</th>
                                            <th>Forma de Pago</th>
                                            <th>Total</th>
                                            <th>PDF</th>
                                            <th>Estado Sunat</th>
                                            <th>Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $a = 1;
                                        $total = 0;
                                        foreach ($ventas as $al){
                                            $stylee="style= 'text-align: center;'";
                                            if ($al->anulado_sunat == 1){
                                                $stylee="style= 'text-align: center; text-decoration: line-through'";
                                            }
                                            if($al->venta_tipo == "03"){
                                                $tipo_comprobante = "BOLETA";
                                            }elseif ($al->venta_tipo == "01"){
                                                $tipo_comprobante = "FACTURA";
                                            }elseif($al->venta_tipo == "07"){
                                                $tipo_comprobante = "NOTA DE CRÉDITO";
                                            }elseif($al->venta_tipo == "08"){
                                                $tipo_comprobante = "NOTA DE DÉBITO";
                                            }else{
                                                $tipo_comprobante = "--";
                                            }
                                            $estilo_mensaje = "style= 'color: red; font-size: 14px;'";
                                            if($al->venta_respuesta_sunat == NULL){
                                                $mensaje = "Sin Enviar a Sunat";

                                            }else{
                                                $mensaje = $al->venta_respuesta_sunat;
                                            }
                                            if($al->id_tipodocumento == 4){
                                                $cliente = $al->cliente_razonsocial;
                                            }else{
                                                $cliente = $al->cliente_nombre;
                                            }
                                            //VALORES PARA LA ZONA
                                            if($al->id_caja_numero==1){
                                                $zona="RESTAURANTE";
                                            }else{
                                                $zona="HOTEL";
                                            }
                                            //FIN DE VALORES
                                            ?>
                                            <tr <?= $stylee?>>
                                                <td><?= $a;?></td>
                                                <td><?= date('d-m-Y H:i:s', strtotime($al->venta_fecha));?></td>
                                                <td><?= $zona;?></td>
                                                <td><?= $tipo_comprobante;?></td>
                                                <td><?= $al->venta_serie. '-' .$al->venta_correlativo;?></td>
                                                <td>
                                                    <?= $al->cliente_numero;?><br>
                                                    <?= $cliente;?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $detalle_pago = $this->ventas->listar_detalle_pago_x_id_venta($al->id_venta);
                                                    foreach ($detalle_pago as $da){
                                                        echo "- $da->tipo_pago_nombre S/. $da->venta_detalle_pago_monto <br>";
                                                           if($al->codigo_venta != null && $da->id_tipo_pago==1){
                                                           echo "(Cod. ".$al->codigo_venta.")";
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?= $al->simbolo.' '.$al->venta_total;?>
                                                </td>
                                                <?php
                                                $fechita_pe = date('Ymd',strtotime($al->venta_fecha));
                                                if(file_exists("media/comprobantes/".$al->venta_serie."-".$al->venta_correlativo."-".$fechita_pe.".pdf")){
                                                    $ruta_sunat=_SERVER_."media/comprobantes/".$al->venta_serie."-".$al->venta_correlativo."-".$fechita_pe.".pdf";
                                                }else{
                                                    $ruta_sunat=_SERVER_ . "Ventas/imprimir_pdf/" . $al->id_venta;
                                                }
                                                ?>
                                                <td><center>
                                                        <a type="button" target='_blank' href="<?= $ruta_sunat ;?>" style="color: red" ><i class="fa fa-file-pdf-o"></i></a>
                                                    </center></td>

                                                <td <?= $estilo_mensaje;?>><?= $mensaje;?></td>
                                                <td style="text-align: left">
                                                    <a target="_blank" type="button" title="Ver detalle" class="btn btn-sm btn-primary" style="color: white" href="<?php echo _SERVER_. 'ventas/ver_venta/' . $al->id_venta;?>" ><i class="fa fa-eye ver_detalle"></i></a>
                                                    <?php
                                                    if($id_rol==2 || $id_rol==3){
                                                        ?>
                                                        <a id="btn_edittp<?= $al->id_venta;?>" class="btn btn-sm btn-secondary" onclick="id_venta_(<?= $al->id_venta;?>,'<?= $al->id_moneda?>')" type="button" data-toggle="modal" data-target="#editar_tipo_pago" title="EDITAR TIPO PAGO"><i class="fa fa-edit text-white"></i></a>
                                                        <?php
                                                    }
                                                    ?>

                                                    <?php
                                                    if($al->anulado_sunat == "0" && ($al->venta_tipo_envio == "0" || $al->venta_tipo_envio == "1") AND $al->venta_tipo != "03"){ ?>
                                                        <a id="btn_enviar<?= $al->id_venta;?>" type="button" title="Enviar a Sunat" class="btn btn-sm btn-success" style="color: white" onclick="preguntar('¿Está seguro que desea enviar a la Sunat este Comprobante?','enviar_comprobante_sunat','Si','No',<?= $al->id_venta;?>)"><i class="fa fa-check margen"></i></a>
                                                        <?php
                                                    }
                                                    if($al->venta_tipo == "03" and $al->anulado_sunat == "0"){
                                                        ?>
                                                        <a target="_blank" type="button" id="btn_anular_anular<?= $al->id_venta;?>" class="btn btn-sm btn-danger" style="color: white" onclick="preguntar('¿Está seguro que desea anular este Comprobante?','anular_boleta_cambiarestado','Si','No',<?= $al->id_venta;?>, '1')" ><i class="fa fa-ban"></i></a>

                                                        <?php
                                                    }else{
                                                        if($al->anulado_sunat == "1"){ ?>
                                                            <h5 style="color: red">ANULADO, ir a resumen diario para enviar a sunat</h5>
                                                            <?php
                                                        }
                                                    }
                                                    //boton para cambiar de estado si sale error 1033 (informado anteriormente)
                                                    $error1 = '1033';
                                                    $error2 = '1032';
                                                    $respuesta = $al->venta_respuesta_sunat;
                                                    $error1033 = strrpos($respuesta, $error1);
                                                    $error1032 = strrpos($respuesta, $error2);
                                                    if($error1033){
                                                        ?>
                                                        <a target="_blank" type="button" id="btn_actualizar_estado<?= $al->id_venta;?>" class="btn btn-sm btn-warning btne" style="color: white" onclick="cambiarestado_enviado(<?= $al->id_venta ?>)" ><i class="fa fa-circle-o-notch"></i></a>
                                                    <?php
                                                    }elseif($error1032){
                                                        ?>
                                                        <a target="_blank" type="button" id="btn_actualizar_estado<?= $al->id_venta;?>" class="btn btn-sm btn-warning btne" style="color: white" onclick="cambiarestado_anulado(<?= $al->id_venta ?>)" ><i class="fa fa-circle-o-notch"></i></a>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $a++;
                                            $total += $al->venta_total;
                                        }
                                        ?>
                                        </tbody>

                                    </table>

                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>ventas.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

    });
    function id_venta_(id_venta,id_moneda){
        $("#id_venta_tp").val(id_venta);
        $("#id_moneda_oculta").val(id_moneda);
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/tipo_pagos_editar",
            data: "id_venta="+id_venta,
            dataType: 'json',
            success:function (r) {
                console.log(r)
                $("#tabla").html(r);
            }
        });
    }

    function edit_tipo_pago(){
        let valor = true;
        let id_tipo_pago_1 = $('#id_tipo_pago_1').val();
        let id_tipo_pago_2 = $('#id_tipo_pago_2').val();
        let monto1 = $('#monto_1').val();
        let monto2 = $('#monto_2').val();
        let id_venta_detalle_pago_1 = $('#id_venta_detalle_pago_1').val();
        let id_venta_detalle_pago_2 = $('#id_venta_detalle_pago_2').val();
        let tipo_antiguo_1 = $('#tipo_antiguo_1').val();
        let tipo_antiguo_2 = $('#tipo_antiguo_2').val();
        let id_caja_cierre = $('#id_caja_cierre').val();
        let id_venta = $('#id_venta').val();
        let id_moneda = $('#id_moneda').val();

        if(valor) {
            var cadena = "id_tipo_pago_1=" + id_tipo_pago_1 + "&id_tipo_pago_2=" + id_tipo_pago_2 + "&monto1=" + monto1 +
                         "&monto2=" + monto2 + "&id_venta_detalle_pago_1=" + id_venta_detalle_pago_1 + "&id_venta_detalle_pago_2=" + id_venta_detalle_pago_2 +
                         "&tipo_antiguo_1=" + tipo_antiguo_1 + "&tipo_antiguo_2=" + tipo_antiguo_2 + "&id_caja_cierre=" + id_caja_cierre + "&id_moneda=" + id_moneda
                + "&id_venta=" + id_venta;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Ventas/guardar_edicion_tp",
                data: cadena,
                dataType: 'json',
                success: function (r) {
                    switch (r.result.code) {
                        case 1:
                            respuesta('¡Guardado Correctamente!', 'success');
                            setTimeout(function () {
                                location.reload();
                            }, 800);
                            break;
                        case 2:
                            respuesta('Error al guardar, comuniquese con Bufeo Tec', 'error');
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        }
    }

    function cambiarestado_enviado(id){
        var boton = "btn_actualizar_estado" + id;
        var accion = "1033";
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/cambiarestado_enviado",
            data: 'id=' + id + "&accion=" + accion,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'actualizando...', true);
            },
            success:function (r) {

                switch (r.result.code) {
                    case 1:
                        respuesta('¡Fue actualizada como enviada y aceptada!', 'success');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 300);
                        break;
                    case 2:
                        respuesta('Error al actualizar', 'error');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 300);
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }

        });
    }
    function cambiarestado_anulado(id){
        var boton = "btn_actualizar_estado" + id;
        var accion = "1032";
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/cambiarestado_enviado",
            data: 'id=' + id + "&accion=" + accion,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'actualizando...', true);
            },
            success:function (r) {

                switch (r.result.code) {
                    case 1:
                        respuesta('¡Fue actualizada como enviada y aceptada!', 'success');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 300);
                        break;
                    case 2:
                        respuesta('Error al actualizar', 'error');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 300);
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }

        });
    }
</script>
