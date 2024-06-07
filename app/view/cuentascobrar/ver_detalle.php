<div class="modal fade" id="add_venta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 700px; !important;">
            <div class="modal-header">
                <h5 class="modal-title">GENERAR COMPROBANTE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="width: 700px; !important;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-5">
                            <label for="tipo_venta">Tipo Comprobante</label>
                            <select id="tipo_venta" class="form-control" onchange = "selecttipoventa_(this.value)">
                                <option value="">Seleccionar...</option>
                                <option value="03" selected>BOLETA</option>
                                <option value="01">FACTURA</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label for="serie">Serie</label>
                            <select name="serie" id="serie" class="form-control" onchange="ConsultarCorrelativo()">
                                <option value="">Seleccionar</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="numero">Numero</label>
                            <input class="form-control" type="text" id="numero" readonly>
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
                        <div class="col-lg-3">
                            <label for="tipo_pago">Tipo de pago</label>
                            <select class="form-control" type="text" id="tipo_pago" onchange="forma_pago_credito_2()">
                                <?php
                                foreach ($tipos_pago as $t){
                                    ($t->id_tipo_pago==3)?$selected_t="selected":$selected_t="";
                                    echo "<option $selected_t value='$t->id_tipo_pago'>$t->tipo_pago_nombre</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-3 no-show" id="div_tarjeta_2">
                            <label for="codigo_venta_2">Código</label>
                            <input type="text" id="codigo_venta_2" name="codigo_venta_2" class="form-control">
                        </div>
                        <div class="col-lg-3">
                            <label for="moneda">Moneda</label>
                            <select class="form-control" name="moneda" id="moneda" onchange="calcular(this.value)">
                                <option <?= ($sacar_simbolo->id_moneda==1)?'selected':'' ?> value="1">SOLES </option>
                                <option <?= ($sacar_simbolo->id_moneda==2)?'selected':'' ?> value="2">DOLARES </option>
                            </select>
                           <!--<input type="text" class="form-control" readonly id="moneda" value="SOLES">-->
                        </div>
                        <div class="col-lg-3" id="tcambio">
                            <label>T. Cambio</label>
                            <input type="text" class="form-control" value="<?= $data_caja_->tipo_cambio?>" name="tipo_cambio" id="tipo_cambio" onkeyup="validar_numeros_decimales_dos(this.id);convertir_d()">
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
                    <div class="row no-show" id="div_cuotas">
                        <div class="col-lg-12" style="text-align: center">
                            <h4>Asignación de Cuotas</h4>
                        </div>
                        <div class="col-lg-12">
                            <div class="row" id="cuotas">

                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="">Importe</label>
                                    <input type="text" class="form-control" id="importe_cuota" onkeyup="return validar_numeros_decimales_dos(this.id)">
                                </div>
                                <div class="col-lg-4">
                                    <label for="">Fecha de Cuota</label>
                                    <?php
                                    $hoy = date('d-m-Y');
                                    ?>
                                    <input type="date" value="<?= date('Y-m-d', strtotime($hoy.'+ 1 days'))?>" class="form-control" id="fecha_cuota">
                                    <input type="hidden" value="<?= date('Y-m-d', strtotime($hoy))?>" id="hoy">
                                </div>
                                <div class="col-lg-2">
                                    <a id="btn_agregar_cuota" type="button" title="Agregar Cuota" class="btn btn-success" style="color: white; margin-top: 30px;" onclick="agregar_cuota()"><i class="fa fa-check margen"></i> Agregar</a>
                                </div>
                            </div>
                            <div class="row" id="total_importe_cuotas">

                            </div>
                        </div>
                    </div>
                    <div class="row">
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
                        </div>
                        <div class="col-lg-12 text-right">
                            <h4 id="aqui_va_suma" style="color: var(--color-logo)"></h4>
                            <input type="hidden" name="totalito" id="totalito">
                        </div>
                        <div class="col-lg-12">
                            <input type="hidden" id="id_cliente_cargo" name="id_cliente_cargo" value="<?= $sacar_id_cliente->id_cliente?>">
                            <input type="hidden" id="id_cuenta" name="id_cuenta" value="<?= $id?>">
                            <label for="comp_obs">Observaciones</label>
                            <textarea id="comp_obs" class="form-control"><?= $sacar_id_cliente->cliente_nombre?> <?= $sacar_id_cliente->cliente_razonsocial?></textarea><br>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-agregar-venta_cargo" onclick="guardar_venta_cargo()"><i class="fa fa-save fa-sm text-white-50"></i> Generar Comprobante</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="eliminar_detalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ELIMINAR DETALLE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="eliminar_clave">Ingrese contraseña</label>
                            <input type="password" class="form-control" id="eliminar_clave">
                            <input type="hidden" id="id_cuenta_detalle">
                            <input type="hidden" id="id_cuenta">
                            <input type="hidden" id="id_rack_detalle">
                            <input type="hidden" id="id_comanda_detalle">
                            <input type="hidden" id="eliminar_id">
                        </div>
                        <div class="col-lg-12" style="display: none">
                            <label for="eliminar_motivo">Motivo de eliminación</label>
                            <textarea class="form-control" id="eliminar_motivo"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="btn_eliminar_detalle" onclick="preguntar('¿Está seguro que desea eliminar este registro?','eliminar_registro_cuenta','Si','No')"><i class="fa fa-trash fa-sm text-white-50"></i> Eliminar</button>
            </div>
        </div>
    </div>
</div>
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?= $_SESSION['controlador'] . ' / ' . $_SESSION['accion'];?></h1>
                    </div>
                </div>
                <div class="col-lg-2" style="text-align: right">
                    <a class="btn btn-secondary" href="<?php echo _SERVER_. 'Cuentascobrar/gestionar/'?>" role="button"><i class="fa fa-backward"></i> Regresar</a>
                </div>
            </div>
            <br>
            <div class="row" style="display: none">
                <div class="col-lg-12">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-striped table-earning" id="dataTable" width="100%" cellspacing="0">
                                        <thead class="text-capitalize">
                                        <tr>
                                            <th>#</th>
                                            <th style="text-align: center">Fecha de Envio</th>
                                            <th style="text-align: center">Usuario de Envio</th>
                                            <th style="text-align: center">Descripción</th>
                                            <th style="text-align: center">Cantidad</th>
                                            <th style="text-align: center">Precio Unitario</th>
                                            <th style="text-align: center">Precio Total</th>
                                            <th style="text-align: center">Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $a = 1;
                                        $total = 0;
                                        $pendiente_pago=0;
                                        foreach ($detalle_cuentas as $cc){
                                            ?>
                                            <tr>
                                                <td><?= $a;?></td>
                                                <td style="text-align: center"><?= date('d-m-Y H:i:s',strtotime($cc->cuentas_detalle_fecha_creacion))?></td>
                                                <td style="text-align: center"></td>
                                                <td style="text-align: center"></td>
                                                <td style="text-align: center"></td>
                                                <td style="text-align: center">S/. </td>
                                                <td style="text-align: center">S/. </td>
                                                <td style="text-align: center">
                                                </td>
                                            </tr>
                                            <?php
                                            $a++;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

            <!--TABLA NUEVA DE PRUEBA-->
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 text-center font-weight-bold text-primary" style="color: #6a0c0d !important;">Detalle del consumo </h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" style="font-size: small">
                            <thead>
                            <tr>
                                <th>Fact</th>
                                <th style="text-align: center">Fecha de Envio</th>
                                <th style="text-align: center">Usuario de Envio</th>
                                <th style="text-align: center">Descripción</th>
                                <th style="text-align: center">Cantidad</th>
                                <th style="text-align: center">Precio Unitario</th>
                                <th style="text-align: center">Precio Total</th>
                                <th style="text-align: center">Estado</th>
                                <th style="text-align: center">Acción</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $a = 1;
                            $total = 0;
                            $total_p = 0;
                            foreach ($detalle_cuentas as $cc){
                                $estadoooo = $cc->cuentas_detalle_estado_pago;
                                $usuario_envio="";
                                if(empty($cc->id_rack_detalle)){
                                    $buscar_comanda_detalle = $this->cuentascobrar->buscar_comanda_detalle($cc->id_comanda_detalle);
                                    $descripcion =$buscar_comanda_detalle->producto_nombre.' / '.$cc->cuentas_detalle_comanda_correlativo;
                                    $cantidad=$buscar_comanda_detalle->comanda_detalle_cantidad;
                                    $precio_unitario=$buscar_comanda_detalle->comanda_detalle_precio;
                                    $precio_total=$buscar_comanda_detalle->comanda_detalle_total;
                                    if($buscar_comanda_detalle->comanda_detalle_estado_venta==0 || $buscar_comanda_detalle->comanda_detalle_estado_venta==2 ){
                                        $a="<input type='checkbox' id='fact_$cc->id_cuenta_detalle' class='form-control'>";
                                        $estado_fact = "<span style=\"color: red;\"><i style='color: red;' class='fa fa-exclamation-triangle'></i> Sin facturar</span>";
                                        $total_p+=$buscar_comanda_detalle->comanda_detalle_total;
                                        $fact=true;
                                    }else{
                                        $a="-";
                                        $total+=$buscar_comanda_detalle->comanda_detalle_total;
                                        $data_venta = $this->ventas->listar_venta_x_id_comanda_detalle($cc->id_comanda_detalle);
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
                                    }
                                }else{
                                    $buscar_habitacion = $this->cuentascobrar->buscar_habitacion($cc->id_rack_detalle);
                                    $buscar_rack_detalle = $this->cuentascobrar->buscar_rack_detalle($cc->id_rack_detalle);
                                    $descripcion =$buscar_rack_detalle->producto_nombre.' - Habitación '.$buscar_habitacion->habitacion_nro;
                                    $cantidad=$buscar_rack_detalle->rack_detalle_cantidad;
                                    $precio_unitario=$buscar_rack_detalle->rack_detalle_preciounit;
                                    $precio_total=$buscar_rack_detalle->rack_detalle_subtotal;
                                    if($buscar_rack_detalle->rack_detalle_estado_fact==0){
                                        $a="<input type='checkbox' id='fact_$cc->id_cuenta_detalle' class='form-control'>";
                                        $estado_fact = "<span style=\"color: red;\"><i style='color: red;' class='fa fa-exclamation-triangle'></i> Sin facturar</span>";
                                        $total_p+=$buscar_rack_detalle->rack_detalle_subtotal;
                                        $fact=true;
                                    }else{
                                        $a="-";
                                        $total+=$buscar_rack_detalle->rack_detalle_subtotal;
                                        $data_venta = $this->ventas->listar_venta_x_id($buscar_rack_detalle->rack_detalle_estado_fact);
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
                                    }
                                }
                                //CODIGO QUE FALTA REVISAR PARA IMPLEMENTAR EN LA PARTE DE ARRIBA
                                /*if($d->rack_detalle_estado_fact==0){
                                    $fact=true;
                                    $eli="<button data-toggle='modal' data-target='#eliminar_detalle' onclick='poner_id_eliminar($d->id_rack_detalle)' class='btn btn-danger'>X</button>";
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
                                }*/
                                //FIN DEL CODIGO A REVISAR
                                ?>
                                <tr id="cuentilla<?= $cc->id_cuenta_detalle;?>">
                                    <td><?= $a; ?></td>
                                    <td style="text-align: center"><?= date('d-m-Y H:i:s',strtotime($cc->cuentas_detalle_fecha_creacion))?></td>
                                    <td style="text-align: center"><?= $usuario_envio?></td>
                                    <td style="text-align: center"><?= $descripcion?></td>
                                    <td style="text-align: center"><?= $cantidad?></td>
                                    <td style="text-align: center"><?= $moneda?> <?= $precio_unitario?></td>
                                    <td style="text-align: center"><?= $moneda?> <?= $precio_total?></td>
                                    <td style="text-align: center"><?= $estado_fact?></td>
                                    <td style="text-align: center">
                                        <?php
                                        if($estadoooo==0){
                                            ?>
                                            <!--<a class="btn btn-danger" type="button" id="btn-eliminar_pedido" onclick="preguntar('¿Está seguro de eliminar este registro?','eliminar_registro_cuenta','SÍ','NO',<?= $cc->id_cuenta_detalle?>,'<?= $cc->id_cuenta?>','<?= $cc->id_rack_detalle?>')" title='Eliminar'><i class='fa fa-trash text-white'></i></a>-->
                                            <a class="btn btn-danger" type="button" id="btn-eliminar_pedido" data-toggle='modal' data-target='#eliminar_detalle' onclick="llenar_datos_(<?= $cc->id_cuenta_detalle?>,'<?= $cc->id_cuenta?>','<?= $cc->id_rack_detalle?>','<?= $cc->id_comanda_detalle?>')" title='Eliminar'><i class='fa fa-trash text-white'></i></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td class="text-center"><input onchange="todos_nuevo()" type="checkbox" id="todos_nuevo" class="form-control"></td>
                                <td class="text-left"><label for="todos">Todos</label></td>
                                <td colspan="2" class="text-left" style="font-size: 14pt;font-weight: bold ">PAGADO <?= $moneda?> <?= ($total); ?></td>
                                <td colspan="1" class="text-center" style="font-size: 14pt;color: red">PENDIENTE</td>
                                <td colspan="1" style="font-size: 14pt;color: red"><?= $moneda?> <?= ($total_p); ?></td>
                                <td colspan="3"><?php
                                    if($fact){
                                        ?>
                                        <button data-toggle="modal" onclick="get_fact_cargo();llenar_id()" data-target="#add_venta" style="background: #15289a" id="btn-comprobante" class="btn btn-primary"><i class="fa fa-cloud"></i><br> GENERAR COMPROBANTE</button>
                                        <?php
                                    }
                                    ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script>
    function forma_pago_credito_2(){
        let tipo_pago_2 = $('#tipo_pago').val();
        if(tipo_pago_2 == 1){
            $('#div_tarjeta_2').show();
        }else{
            $('#div_tarjeta_2').hide();
        }
    }

    function llenar_datos_(id_cuenta_detalle,id_cuenta,id_rack_detalle,id_comanda_detalle) {
        $("#id_cuenta_detalle").val(id_cuenta_detalle);
        $("#id_cuenta").val(id_cuenta);
        $("#id_rack_detalle").val(id_rack_detalle);
        $("#id_comanda_detalle").val(id_comanda_detalle);
    }

    $(document).ready(function(){
        $("#cp1").hide();
        $("#cp2").hide();
        $("#tcambio").hide();
        var valor = $('#tipo_venta').val();
        selecttipoventa(valor);
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

    function llenar_id(id_cliente){
        $("#id_cliente_detalle").val(id_cliente);
        console.log(id_cliente);
    }

    function eliminar_registro_cuenta(){
        var valor = true;
        var id_cuenta_detalle  = $("#id_cuenta_detalle").val();
        var id_cuenta  = $("#id_cuenta").val();
        var id_rack_detalle  = $("#id_rack_detalle").val();
        var id_comanda_detalle  = $("#id_comanda_detalle").val();
        var eliminar_clave  = $("#eliminar_clave").val();
        if(valor) {
            var cadena = "id_cuenta_detalle=" + id_cuenta_detalle + "&id_cuenta=" + id_cuenta + "&id_rack_detalle=" + id_rack_detalle + "&eliminar_clave=" + eliminar_clave
                        + "&id_comanda_detalle=" + id_comanda_detalle;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Cuentascobrar/eliminar_cuenta_detalle",
                data: cadena,
                dataType: 'json',
                success: function (r) {
                    switch (r.result.code) {
                        case 1:
                            $('#cuentilla' + id_cuenta_detalle).remove();
                            respuesta('¡Detalle Eliminado Correctamente...!', 'success');
                            if(r.result.aviso == 0){
                                setTimeout(function () {
                                    location.reload()
                                }, 700);
                            }else{
                                setTimeout(function () {
                                    location.href = urlweb +  'Cuentascobrar/gestionar';
                                }, 1000);
                            }
                            break;
                        case 2:
                            respuesta('Error al eliminar el detalle de la cuenta', 'error');
                            break;
                        case 3:
                            respuesta('Clave incorrecta, debe generar otra clave de eliminación', 'error');
                            $('#eliminar_clave').css('border','solid red');
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        }
    }

    let cadenita = "";
    let cadenita_textos = "";
    let contenido_cuota = '';
    let variable = "";
    function get_fact_cargo() {
        cadenita = "";
        <?php
        foreach ($detalle_cuentas as $cc){
            if(empty($cc->id_rack_detalle)){
            $buscar_comanda_detalle = $this->cuentascobrar->buscar_comanda_detalle($cc->id_comanda_detalle);
            ?>
            if($("#fact_<?= $cc->id_cuenta_detalle; ?>").is(':checked')){
                cadenita += <?= $cc->id_cuenta_detalle; ?>+"--/";
            }
            <?php
            }else{
            $buscar_rack_detalle = $this->cuentascobrar->buscar_rack_detalle($cc->id_rack_detalle);
            ?>
                 if($("#fact_<?= $cc->id_cuenta_detalle; ?>").is(':checked')){
                cadenita += <?= $cc->id_cuenta_detalle; ?>+"--/";
                 }
                <?php
            }
        }
        ?>
        if(cadenita===""){
            respuesta("Seleccione al menos un item","error");
            variable="";
            armar_tabla();
            desactivarBoton();
        }else{
            $.ajax({
                type: "POST",
                url: urlweb + "api/Hotel/consultar_rack_detalle_cargo",
                data: "cadena="+cadenita,
                dataType: 'json',
                success:function (r) {
                    let datos_array = [];
                    datos_array = JSON.parse(JSON.stringify(r.result));
                    variable = datos_array;
                    console.log(variable)
                    //AQUI VA VENIR UN CODIGO PARA DISEÑAR UNA TABLA QUE SE MOSTRARA CON EL DETALLE DE LO SELECCIONADO
                    armar_tabla();
                    // $("#generar_fact_tbody").html(r);
                    // $("#generar_fact_tbody_cargo").html(r);
                    // $("#btn-agregar-venta").show();
                    // $("#btn-agregar-venta_cargo").show();
                }
            });
        }
    }
    function activarBoton() {
        var boton = document.getElementById("btn-agregar-venta_cargo");
        boton.style.display = "block"; // Cambia el estilo a "block" para mostrar el botón
    }
    function desactivarBoton() {
        var boton = document.getElementById("btn-agregar-venta_cargo");
        boton.style.display = "none"; // Cambia el estilo a "none" para ocultar el botón
    }
    function todos_nuevo() {
        if($("#todos_nuevo").is(':checked')){
            <?php
            foreach ($detalle_cuentas as $cc){
                if(empty($cc->id_rack_detalle)){
                $buscar_comanda_detalle = $this->cuentascobrar->buscar_comanda_detalle($cc->id_comanda_detalle);
                if($buscar_comanda_detalle->comanda_detall_estado_venta==0){
                    ?>
                    $("#fact_<?= $cc->id_cuenta_detalle; ?>").attr('checked','checked');
                    <?php
                    }
                }else{
                    $buscar_rack_detalle = $this->cuentascobrar->buscar_rack_detalle($cc->id_rack_detalle);
                    if($buscar_rack_detalle->rack_detalle_estado_fact==0){
                    ?>
                        $("#fact_<?= $cc->id_cuenta_detalle; ?>").attr('checked','checked');
                <?php
                    }
                }
            }
            ?>
        }else{
            <?php
            foreach ($detalle_cuentas as $cc){
            if(empty($cc->id_rack_detalle)){
            $buscar_comanda_detalle = $this->cuentascobrar->buscar_comanda_detalle($cc->id_comanda_detalle);
            if($buscar_comanda_detalle->comanda_detall_estado_venta==0){
            ?>
            $("#fact_<?= $cc->id_cuenta_detalle; ?>").removeAttr('checked');
            <?php
            }
            }else{
            $buscar_rack_detalle = $this->cuentascobrar->buscar_rack_detalle($cc->id_rack_detalle);
            if($buscar_rack_detalle->rack_detalle_estado_fact==0){
            ?>
            $("#fact_<?= $cc->id_cuenta_detalle; ?>").removeAttr('checked');
            <?php
            }
            }
            }
            ?>
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

    function partir_pago() {
        if($("#partir_pago").is(':checked')){
            $("#div_partir_pago").show();
        }else{
            $("#div_partir_pago").hide();
        }
    }

    function agrupar() {
        if($("#agrupar").is(':checked')){
            $("#div_agrupar").show();
        }else{
            $("#div_agrupar").hide();
        }
    }

    function guardar_venta_cargo(){
        cadenita_textos="";
        let valor=true;
        let valor_tipo = true
        let boton = "btn-agregar-venta_cargo";
        let codigo_venta_2 = $("#codigo_venta_2").val();
        let id_cliente = $("#id_cliente_cargo").val();
        let id_cuenta = $("#id_cuenta").val();
        let total = $("#totalito").val();
        let venta_tipo = $("#tipo_venta").val();
        let tipo_pago = $("#tipo_pago").val();
        let serie = $("#serie").val();
        let correlativo= $("#numero").val();
        let caja= $("#caja").val();
        let ruc= $("#ruc").val();
        let comp_obs= $("#comp_obs").val();
        let razon_social= $("#razon_social").val();
        let textoCodificado = encodeURIComponent(razon_social);
        let domicilio= $("#domicilio").val();
        let moneda= $("#moneda").val();
        //let id_rack= <?= $data_rack->id_rack; ?>;
        let agrupar = 0;
        //para tipo de cambio
        let tipo_cambio = $("#tipo_cambio").val();
        //FIN
        let mostrar_tp = 0;
        let agrupar_des="";
        if($("#agrupar").is(':checked')){
            agrupar=1;
            agrupar_des=$("#agrupar_texto").val();
        }
        if($("#mostrar_tp").is(':checked')){
            mostrar_tp=1;
        }
        //PA CODIGOS
        let codigo = $("#codigo").val();
        let codigo2 = $("#codigo2").val();
        var partir_pago = 0;
        var partir_pago_id="";
        var partir_pago_monto="";
        var partir_pago_id2="";
        var partir_pago_monto2="";
        if($("#partir_pago").is(':checked')){
            partir_pago=1;
            var totalito=$("#totalito").val();
            partir_pago_id=$("#partir_pago_tipo").val();
            partir_pago_id2=$("#partir_pago_tipo2").val();
            partir_pago_monto=$("#partir_pago_monto").val();
            partir_pago_monto2=$("#partir_pago_monto2").val();
            var suma = partir_pago_monto * 1 + partir_pago_monto2 * 1;
            console.log(totalito)
            if(suma != totalito){
                valor=false;
                respuesta("Suma incorrecta","error");
            }
        }
        var cadde = cadenita.split('--/');
        for (var i = 0;i<cadde.length - 1;i++){
            var aja = $("#cad_texto_"+cadde[i]).val();
            cadenita_textos+=aja+"-**-";
        }
        if(tipo_pago == 5){
            if(contenido_cuota == ""){
                respuesta('Falta agregar Cuotas a la Venta', 'error');
                valor=false;
            }else{
                //valor = validar_parametro_vacio(contenido_cuota, valor);
                let importe_cuota = $('#total_cuota_input').val();
                console.log(importe_cuota)
                let total_temporal = $('#totalito').text() * 1;
                console.log(total_temporal)
                if(importe_cuota != total_temporal){
                    respuesta('El Total de las Cuotas '+importe_cuota+' no es igual al Total de la Venta '+total_temporal, 'error');
                    valor=false;
                }
            }
        }
        //INICIO - VALIDAR SI LA SERIE COINCIDE CON EL TIPO DE VENTA
        let serie_text = $("select[name='serie'] option:selected").text();
        let array_serie = serie_text.split('')
        if(venta_tipo === '01'){
            if(array_serie[0] == 'F'){
                valor_tipo = true
            }else{
                valor_tipo = false
            }
        }else if(venta_tipo === '03'){
            if(array_serie[0] == 'B'){
                valor_tipo = true
            }else{
                valor_tipo = false
            }
        }
        console.log(array_serie[0])

        //FIN - VALIDAR SI LA SERIE COINCIDE CON EL TIPO DE VENTA
        var cadena = "id_cliente=" + id_cliente +
            "&id_cuenta="+id_cuenta+
            "&codigo_venta_2="+codigo_venta_2+
            "&venta_tipo="+venta_tipo+
            "&tipo_pago="+tipo_pago+
            "&serie="+serie+
            "&correlativo="+correlativo+
            "&moneda="+moneda+
            "&caja="+caja+
            "&mostrar_tp="+mostrar_tp+
            "&ruc="+ruc+
            "&codigo="+codigo+
            "&codigo2="+codigo2+
            "&tipo_cambio="+tipo_cambio+
            "&comp_obs="+comp_obs+
            //"&razon_social="+razon_social+
            "&razon_social="+textoCodificado+
            "&domicilio="+domicilio+
            "&agrupar="+agrupar+
            "&agrupar_des="+agrupar_des+
            "&partir_pago="+partir_pago+
            "&partir_pago_id="+partir_pago_id+
            "&partir_pago_id2="+partir_pago_id2+
            "&partir_pago_monto="+partir_pago_monto+
            "&partir_pago_monto2="+partir_pago_monto2+
            "&cadenita="+cadenita+
            "&cadenita_textos="+cadenita_textos+
            "&contenido_cuota="+contenido_cuota+
            "&total="+total+
            "&variable="+JSON.stringify(variable)+
            "&id_turno=1";
        if(valor_tipo){
            if(valor){
                $.ajax({
                    type: "POST",
                    url: urlweb + "api/Ventas/guardar_venta_cargo",
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
        }else{
            respuesta('La Serie no coincide con el tipo de Comprobante', 'error');
        }
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

    function  armar_tabla(){
        var tablas_e = "";
        let suma = 0;
        for (var i=0; i<variable.length; i++){
            tablas_e += "<tr > <td>" +variable[i]['id']+"</td>" +
                "<td data-nombre='"+ variable[i]['producto']+"'><input class='form-control' id='editar_' onchange='cambiar_descripcion_(event)' type='text' value='" +variable[i]['producto']+"'></td>" +
                "<td>" +variable[i]['cantidad']+"</td>" +
                "<td>" +variable[i]['precio_final']+"</td>" +
                "<td>" +variable[i]['subtotal']+"</td> </tr>"
            suma = redondear(parseFloat(suma) + parseFloat(variable[i]['subtotal']),2);
        }
        $("#generar_fact_tbody").html(tablas_e);
        $("#generar_fact_tbody_cargo").html(tablas_e);
        $("#aqui_va_suma").html("TOTAL : "+ parseFloat(suma,2));
        $("#aqui_va_suma_cargo").html("TOTAL : "+ parseFloat(suma,2));
        $("#totalito").val(parseFloat(suma,2));
        activarBoton()
        console.log(suma)
    }

    //FUNCION PARA CALCULAR VALORE DE DOLARS O SOLES
    function calcular(valor) {
        if(valor == ""){
            //AQUI SE VA CALCULAR PARA PASARA A DOLARES
            $("#tcambio").hide();
        }else{
            $("#tcambio").show();
            //AQUI SE VA CALCULAR PARA PASAR A SOLES
        }
        convertir_d()
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

    function cambiar_descripcion_(event) {
        // Get the name of the product and the new description
        var productName = event.target.parentNode.getAttribute('data-nombre');
        var newDescription = event.target.value;

        // Create a new text input element
        var newElement = document.createElement("input");
        newElement.setAttribute("id", "editar_");
        newElement.setAttribute("class", "form-control");
        newElement.setAttribute("type", "text");
        newElement.setAttribute("onchange", "cambiar_descripcion(event)");
        newElement.setAttribute("value", newDescription);
        console.log(newDescription)
        // Replace the original element with the new element
        event.target.parentNode.replaceChild(newElement, event.target);
        // Update the product name in the variable array
        var product = variable.find(function(item) {
            return item.producto === productName;
        });
        if (product) {
            product.producto = newDescription;
            console.log(product)
        }
        armar_tabla()
    }
</script>