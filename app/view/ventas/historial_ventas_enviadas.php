
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
            <h6 class="m-0 text-center font-weight-bold text-primary" style="color: #6a0c0d !important;">LISTA DE VENTAS REGISTRADAS</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="<?= _SERVER_ ?>Ventas/historial_ventas_enviadas">
                                <input type="hidden" id="enviar_registro" name="enviar_registro" value="1">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <label for="">Origen</label>
                                        <select name="lugar" id="lugar" class="form-control">
                                            <option <?= ($lugar == "")?'selected':''; ?> value="">Todos...</option>
                                            <option <?= ($lugar == "1")?'selected':''; ?> value="1">RESTAURANTE</option>
                                            <option <?= ($lugar == "2")?'selected':''; ?> value="2">HOTEL</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Tipo de Venta</label>
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
                                    <!--<div class="col-lg-3" style="text-align: right;">
                                        <a class="btn btn-primary" style="margin-top: 34px; color: white;" type="button"  data-toggle="modal" data-target="#basicModal"><i class="fa fa-search"></i> Consutar CPE</a>
                                    </div>-->
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
                            <div class="card-header py-3">
                                <h5>TIPO COMPROBANTE: <span class='text-uppercase font-weight-bold'>
                                        <?php
                                if($tipo_venta == "03"){
                                    echo "BOLETA";
                                }elseif($tipo_venta == "01"){
                                    echo "FACTURA";
                                }elseif($tipo_venta == "07"){
                                    echo "NOTA DE CRÉDITO";
                                }elseif($tipo_venta == "08"){
                                    echo "NOTA DE DÉBITO";
                                }else{
                                    echo 'TODOS';
                                }
                                ?></span>
                                    | FECHA DEL: <span><?= (($fecha_ini != ""))?date('d-m-Y', strtotime($fecha_ini)):'--'; ?></span> AL <span><?= (($fecha_fin != ""))?date('d-m-Y', strtotime($fecha_fin)):'--'; ?></span>
                                    | Total SOLES: <span id="total_soles"></span> | Total DOLARES: <span id="total_dolares"></span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-striped table-earning" id="dataTable" width="100%" cellspacing="0">
                                        <thead class="text-capitalize">
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha de Emision</th>
                                            <th>Tipo de Envío</th>
                                            <th>Origen</th>
                                            <th>Comprobante</th>
                                            <th>Serie y Correlativo</th>
                                            <th>Cliente</th>
                                            <th>Forma de Pago</th>
                                            <th>Total</th>
                                            <th>PDF</th>
                                            <th>XML</th>
                                            <th>CDR</th>
                                            <th>Estado Sunat</th>
                                            <th>Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $a = 1;
                                        $total = 0;
                                        $total_soles = 0;
                                        $total_dolares = 0;
                                        foreach ($ventas as $al){
                                            $stylee="style= 'text-align: center;'";
                                            if ($al->anulado_sunat == 1){
                                                $stylee="style= 'text-align: center; background: #F98892'";
                                            }

                                            if($al->venta_tipo == "03"){
                                                $tipo_comprobante = "BOLETA";
                                                if($al->anulado_sunat == 0){
                                                    if($al->id_moneda == 1){
                                                    $total_soles = round($total_soles + $al->venta_total, 2);
                                                    }else{
                                                        $total_dolares = round($total_dolares + $al->venta_total, 2);
                                                    }
                                                }
                                            }elseif ($al->venta_tipo == "01"){
                                                $tipo_comprobante = "FACTURA";
                                                if($al->anulado_sunat == 0){
                                                    if($al->id_moneda == 1){
                                                    $total_soles = round($total_soles + $al->venta_total, 2);
                                                    }else{
                                                        $total_dolares = round($total_dolares + $al->venta_total, 2);
                                                    }
                                                }
                                            }elseif($al->venta_tipo == "07"){
                                                $tipo_comprobante = "NOTA DE CRÉDITO";
                                                /*if(($al->anulado_sunat == 0 AND $al->venta_codigo_motivo_nota != "01")){
                                                    $total_soles = round($total_soles - $al->venta_total, 2);
                                                }*/
                                            }elseif($al->venta_tipo == "08"){
                                                $tipo_comprobante = "NOTA DE DÉBITO";
                                                if($al->anulado_sunat == 0){
                                                    if($al->id_moneda == 1){
                                                    $total_soles = round($total_soles + $al->venta_total, 2);
                                                    }else{
                                                        $total_dolares = round($total_dolares + $al->venta_total, 2);
                                                    }
                                                }
                                            }else{
                                                $tipo_comprobante = "--";
                                            }
                                            if($al->venta_tipo_envio == 1){
                                                $tipo_envio = "DIRECTO";
                                            }else{
                                                $resumen = $this->ventas->listar_resumen_diario_x_id_venta($al->id_venta);
                                                $tipo_envio = "<a type=\"button\" target='_blank' href="._SERVER_.'Ventas/ver_detalle_resumen/'.$resumen->id_envio_resumen.">RESUMEN DIARIO</a>";
                                                //$tipo_envio = "RESUMEN DIARIO";
                                            }
                                            $estilo_mensaje = "";
                                            if($al->venta_estado_sunat == 1){
                                                if($al->venta_respuesta_sunat != ""){
                                                    $mensaje = $al->venta_respuesta_sunat;
                                                }else{
                                                    $mensaje = 'Aceptado por Resumen Diario';
                                                }

                                                $estilo_mensaje = "style= 'color: green; font-size: 14px;'";
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
                                                <td><?= $tipo_envio;?></td>
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
                                                    <?= $al->simbolo;?>
                                                    <?= $al->venta_total;?>
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
                                                <?php
                                                if($al->venta_tipo_envio == 1){?>
                                                    <td>
                                                        <center><a type="button" target='_blank' href="<?= _SERVER_.$al->venta_rutaXML;?>" style="color: blue;" ><i class="fa fa-file-text"></i></a></center>
                                                        <a class="text-dark" download="<?= $al->venta_rutaXML;?>" href="<?php echo _SERVER_ . $al->venta_rutaXML;?>" data-toggle="tooltip" title="Descargar"><i class="fa fa-download pdf"></i></a>
                                                    </td>
                                                    <td>
                                                        <center><a type="button" target='_blank' href="<?= _SERVER_.$al->venta_rutaCDR;?>" style="color: green" ><i class="fa fa-file"></i></a></center>
                                                        <a class="text-dark" download="<?= $al->venta_rutaCDR;?>" href="<?php echo _SERVER_ . $al->venta_rutaCDR;?>" data-toggle="tooltip" title="Descargar"><i class="fa fa-download pdf"></i></a>
                                                    </td>

                                                    <?php
                                                }else{ ?>
                                                    <td>--</td>
                                                    <td>--</td>
                                                <?php
                                                }
                                                ?>

                                                <td <?= $estilo_mensaje;?>><?= $mensaje;?></td>
                                                <td style="text-align: left">
                                                    <a target="_blank" type="button" title="Ver detalle" class="btn btn-sm btn-primary" style="color: white" href="<?php echo _SERVER_. 'Ventas/ver_venta/' . $al->id_venta;?>" ><i class="fa fa-eye ver_detalle"></i></a>
                                                    <?php
                                                    if($id_rol==2 || $id_rol==3){
                                                        ?>
                                                        <a id="btn_edittp<?= $al->id_venta;?>" class="btn btn-sm btn-secondary" onclick="id_venta_(<?= $al->id_venta;?>,'<?= $al->id_moneda?>')" type="button" data-toggle="modal" data-target="#editar_tipo_pago" title="EDITAR TIPO PAGO"><i class="fa fa-edit text-white"></i></a>
                                                        <?php
                                                    }
                                                    ?>
                                                    <?php
                                                    if($al->anulado_sunat == 0){
                                                        $date2 = new DateTime(date('Y-m-d H:i:s'));
                                                        $date1 = new DateTime($al->venta_fecha);
                                                        $diff = $date2->diff($date1);
                                                        $dias= $diff->days;
                                                        if($dias <= 20){
                                                            if($al->venta_tipo != "03"){
                                                                if($dias <= 3){
                                                                    if($al->tipo_documento_modificar != ""){
                                                                        if($al->tipo_documento_modificar == "01"){
                                                                            ?>
                                                                            <a target="_blank" type="button" title="Anular" id="btn_anular<?= $al->id_venta;?>" class="btn btn-sm btn-danger btne" style="color: white" onclick="preguntar('¿Está seguro que desea anular este Comprobante?','comunicacion_baja','Si','No',<?= $al->id_venta;?>)" ><i class="fa fa-ban"></i></a>
                                                                            <?php
                                                                        }
                                                                    }else{
                                                                        ?>
                                                                        <a target="_blank" type="button" title="Anular" id="btn_anular<?= $al->id_venta;?>" class="btn btn-sm btn-danger btne" style="color: white" onclick="preguntar('¿Está seguro que desea anular este Comprobante?','comunicacion_baja','Si','No',<?= $al->id_venta;?>)" ><i class="fa fa-ban"></i></a>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                            else{
                                                                if($al->venta_tipo_envio == "2"){
                                                                    ?>
                                                                    <a target="_blank" type="button" title="Anular" id="btn_anular<?= $al->id_venta;?>" class="btn btn-sm btn-danger btne" style="color: white" onclick="preguntar('¿Está seguro que desea anular este Comprobante?','anular_boleta_cambiarestado','Si','No',<?= $al->id_venta;?>, '3')" ><i class="fa fa-ban"></i></a>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                    }

                                                    if($al->anulado_sunat == 0 && ($al->venta_tipo == '01' || $al->venta_tipo == '03')){
                                                    ?>
                                                    <a type="button" style="color: white" class="btn btn-sm btn-success" title="GENERAR NOTA" href="<?= _SERVER_ ?>Ventas/generar_nota/<?= $al->id_venta; ?>" target="_blank" ><i class="fa fa-clipboard"></i></a>
                                                    <?php
                                                    } ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $a++;
                                            $total = $total + $al->venta_total;
                                        }
                                        ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-4">
                            <a id="btnExportar" href="<?= _SERVER_ ; ?>index.php?c=Ventas&a=excel_ventas_enviadas&tipo_venta=<?= $_POST['tipo_venta']?>&fecha_inicio=<?= $_POST['fecha_inicio']?>&fecha_final=<?= $_POST['fecha_final']?>&lugar=<?= $_POST['lugar']?>" target="_blank" class="btn btn-success" style="width: 100%"><i class="fa fa-download"></i> Generar Excel</a>
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
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>ventas.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var total_rs = <?= $total_soles; ?>;
        $("#total_soles").html("<b>"+total_rs+"</b>");
        var total_rd = <?= $total_dolares; ?>;
        $("#total_dolares").html("<b>"+total_rd+"</b>");
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
        var valor = true;
        var id_tipo_pago_1 = $('#id_tipo_pago_1').val();
        var id_tipo_pago_2 = $('#id_tipo_pago_2').val();
        var monto1 = $('#monto_1').val();
        var monto2 = $('#monto_2').val();
        var id_venta_detalle_pago_1 = $('#id_venta_detalle_pago_1').val();
        var id_venta_detalle_pago_2 = $('#id_venta_detalle_pago_2').val();
        let tipo_antiguo_1 = $('#tipo_antiguo_1').val();
        let tipo_antiguo_2 = $('#tipo_antiguo_2').val();
        let id_caja_cierre = $('#id_caja_cierre').val();
        let id_moneda = $('#id_moneda').val();
        let id_venta = $('#id_venta').val();
        if(valor) {
            var cadena = "id_tipo_pago_1=" + id_tipo_pago_1 + "&id_tipo_pago_2=" + id_tipo_pago_2 + "&monto1=" + monto1 +
                "&monto2=" + monto2 + "&id_venta_detalle_pago_1=" + id_venta_detalle_pago_1 + "&id_venta_detalle_pago_2=" + id_venta_detalle_pago_2 +
                "&tipo_antiguo_1=" + tipo_antiguo_1 + "&tipo_antiguo_2=" + tipo_antiguo_2 + "&id_caja_cierre=" + id_caja_cierre + "&id_moneda=" + id_moneda + "&id_venta=" + id_venta;
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
</script>
