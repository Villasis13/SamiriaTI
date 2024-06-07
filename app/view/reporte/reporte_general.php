
<div class="modal fade" id="detalle_habitacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 70% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row" id="detalle">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detalle_cargo_cuenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 70% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row" id="detalle_cargo">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800"><?= $_SESSION['controlador'] . ' / ' . $_SESSION['accion'];?></h1>
            </div>

            <form method="post" action="<?= _SERVER_ ?>Reporte/reporte_general">
                <input type="hidden" id="enviar_fecha" name="enviar_fecha" value="1">
                <div class="row">
                    <div class="col-lg-3 col-xs-6 col-md-6 col-sm-6" style="display:none;">
                        <label for="">Caja</label>
                        <select class="form-control" id="id_caja_numero" name="id_caja_numero">
                            <?php
                            (isset($caja_))?$cajita=$caja_->id_caja_numero:$cajita=0;
                            foreach($caja as $l){
                                ($l->id_caja_numero == $cajita)?$sele='selected':$sele='';
                                ?>
                                <option value="<?php echo $l->id_caja;?>" <?= $sele; ?>><?php echo $l->caja_nombre;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-3 col-xs-6 col-md-6 col-sm-6">
                        <label for="">Usuario</label>
                        <select class="form-control" id="id_usuario" name="id_usuario">
                            <?php
                            (isset($usuario_))?$user=$usuario_->id_usuario:$user=0;
                            foreach($usuarios as $l){
                                ($l->id_usuario == $user)?$sele='selected':$sele='';
                                ?>
                                <option value="<?php echo $l->id_usuario;?>" <?= $sele; ?>><?php echo $l->persona_nombre;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-3 col-xs-6 col-md-6 col-sm-6">
                        <label for="">Desde:</label>
                        <input type="date" class="form-control" id="fecha_filtro" name="fecha_filtro" step="1" value="<?php echo $fecha_i;?>">
                    </div>
                    <div class="col-lg-3 col-xs-6 col-md-6 col-sm-6">
                        <label for="">Hasta:</label>
                        <input type="date" class="form-control" id="fecha_filtro_fin" name="fecha_filtro_fin" value="<?php echo $fecha_f;?>">
                    </div>
                    <div class="col-lg-3">
                        <button style="margin-top: 35px;" class="btn btn-success" ><i class="fa fa-search"></i> Buscar Registro</button>
                    </div>
                </div>
            </form>
            <br>
            <?php
            if($datos){
            ?>
            <div class="row">
<!--                --><?php
//                $id_caja_cierre="";
//                foreach ($cajas_totales as $ct){
//                    $id_caja_cierre=$ct->id_caja_cierre;
//                    $datitos = $this->reporte->datitos_caja($ct->id_caja_cierre);
//                    ?>
                    <div class="col-lg-6">
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="table">
                                       <!--<p>Apertura : <?= $datitos->caja_cierre_apertura_datetime;?> // Cierre : <?= $datitos->caja_cierre_cierre_datetime?> // Monto Cierre : <?= $datitos->caja_cierre;?></p>-->
                                        <?php
                                            $fecha_ini_caja = $datitos->caja_cierre_apertura_datetime;
                                            if($datitos->caja_fecha_cierre==NULL){
                                                $fecha_fin_caja = date('Y-m-d H:i:s');
                                            }else{
                                                $fecha_fin_caja = $datitos->caja_cierre_cierre_datetime;
                                            }
                                            //N° DE VENTAS POR TIPO
                                            $n_ventas_salon = $this->reporte->n_ventas_salon($fecha_i, $fecha_f, $id_usuario);

                                            $monto_caja_apertura = $this->reporte->reporte_caja_x_caja($fecha_i, $fecha_f, $id_usuario);
                                            //REPORTE DE VENTAS POR SALON
                                            $ventas_efectivo_salon_soles = $this->reporte->ventas_efectivo($fecha_i, $fecha_f, $id_usuario);
                                            $ventas_efectivo_salon_dolares = $this->reporte->ventas_efectivo_d($fecha_i, $fecha_f, $id_usuario);

                                            $ventas_tarjeta_salon_soles = $this->reporte->ventas_tarjeta($fecha_i, $fecha_f, $id_usuario);
                                            $ventas_tarjeta_salon_dolares = $this->reporte->ventas_tarjeta_d($fecha_i, $fecha_f, $id_usuario);

                                            $ventas_trans_soles = $this->reporte->ventas_trans_plin($fecha_i, $fecha_f, $id_usuario);
                                            $ventas_trans_dolares = $this->reporte->ventas_trans_plin_d($fecha_i, $fecha_f, $id_usuario);

//                                            $ventas_trans_yape_soles = $this->reporte->ventas_trans_yape($fecha_i, $fecha_f, $id_usuario);
//                                            $ventas_trans_yape_dolares = $this->reporte->ventas_trans_yape($fecha_i, $fecha_f, $id_usuario);

//                                            $ventas_trans_otros_soles = $this->reporte->ventas_trans_otros($fecha_i, $fecha_f, $id_usuario);
//                                            $ventas_trans_otros_dolares = $this->reporte->ventas_trans_otros($fecha_i, $fecha_f, $id_usuario);

                                            //monto de apertura de caja
                                            $monto_caja_apertura_soles = $monto_caja_apertura->caja_cierre_apertura_mon_s;
                                            $monto_caja_apertura_dolares = $monto_caja_apertura->caja_cierre_apertura_mon_d;
                                            //FUNCIONES PARA VENTAS SALON
                                            $ventas_efectivo_soles  = $ventas_efectivo_salon_soles->total;
                                            $ventas_efectivo_dolares  = $ventas_efectivo_salon_dolares->total;

                                            $ventas_tarjeta_s  = $ventas_tarjeta_salon_soles->total;
                                            $ventas_tarjeta_d  = $ventas_tarjeta_salon_dolares->total;

                                            $ventas_trans_s  = $ventas_trans_soles->total;
                                            $ventas_trans_d  = $ventas_trans_dolares->total;

//                                            $ventas_trans_yape_s  = $ventas_trans_yape_soles->total;
//                                            $ventas_trans_yape_d  = $ventas_trans_yape_dolares->total;
//
//                                            $ventas_trans_otros_s = $ventas_trans_otros_soles->total;
//                                            $ventas_trans_otros_d = $ventas_trans_otros_dolares->total;

                                            //FUNCIONES PARA DESGLOSAR SALIDA DE CAJA

                                            $ingresos_total_de_ventas_soles = $ventas_efectivo_soles + $ventas_tarjeta_s + $ventas_trans_s ;
                                            $ingresos_total_de_ventas_dolares = $ventas_efectivo_dolares + $ventas_tarjeta_d + $ventas_trans_d ;
                                            //INGRESOS TOTAL DE VENTAS
                                            $ingresos_totales_salon_soles = $ventas_efectivo_soles + $ventas_trans_s + $ventas_tarjeta_s;
                                            $ingresos_totales_salon_dolares = $ventas_efectivo_dolares + $ventas_trans_d + $ventas_tarjeta_d;
                                            //INGRESOS - EGRESOS
                                            $ingresos_generales_soles = $ventas_efectivo_soles + $ventas_trans_s  + $ventas_tarjeta_s + $monto_caja_apertura_soles   ;
                                            $ingresos_generales_dolares = $ventas_efectivo_dolares + $ventas_trans_d  + $ventas_tarjeta_d + $monto_caja_apertura_dolares   ;

                                            $diferencia_soles = $monto_caja_apertura_soles  + $ventas_efectivo_soles   ;
                                            $diferencia_dolares = $monto_caja_apertura_dolares  + $ventas_efectivo_dolares   ;



                                        ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>- TOTAL DE VENTAS DEL DIA:</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right"> S/.<?= $ingresos_total_de_ventas_soles ?? 0?></label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right"> $.<?= $ingresos_total_de_ventas_dolares ?? 0?></label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>- INGRESOS - EGRESOS:</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right"> S/.<?= $ingresos_generales_soles ?? 0?></label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right"> $. <?= $ingresos_generales_dolares ?? 0?></label>
                                            </div>
                                        </div>
                                        <p style="border-bottom: 1px solid red"></p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>- Apertura de Caja</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right"> S/.<?= $monto_caja_apertura_soles ?? 0?></label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right"> $.<?= $monto_caja_apertura_dolares ?? 0?></label>
                                            </div>
                                        </div>
                                        <div class="row" style="display: none">
                                            <div class="col-md-6">
                                                <label>- Ingresos caja chica</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> S/.<?= $ingreso_caja_chica ?? 0?></label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> $.<?= $ingreso_caja_chica ?? 0?></label>
                                            </div>
                                        </div>
                                        <p style="border-bottom: 1px solid red"></p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>- TOTAL VENTAS:</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> S/.<?= $ingresos_totales_salon_soles ?? 0?></label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> $.<?= $ingresos_totales_salon_dolares ?? 0?></label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>- Pagos Efectivo:</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> S/.<?= $ventas_efectivo_soles ?? 0?></label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> $.<?= $ventas_efectivo_dolares ?? 0?></label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>- Pagos Tarjeta:</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> S/.<?= $ventas_tarjeta_s ?? 0?></label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> $.<?= $ventas_tarjeta_d ?? 0?></label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>- Pagos Transferencia:</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> S/.<?= $ventas_trans_s ?? 0?></label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> $.<?= $ventas_trans_d ?? 0?></label>
                                            </div>
                                        </div>
<!--                                        <div class="row">-->
<!--                                            <div class="col-md-6">-->
<!--                                                <label>- Pagos Transferencia Yape:</label>-->
<!--                                            </div>-->
<!--                                            <div class="col-md-3">-->
<!--                                                <label style="text-align: right;"> S/.--><?//= $ventas_trans_yape_s ?? 0?><!--</label>-->
<!--                                            </div>-->
<!--                                            <div class="col-md-3">-->
<!--                                                <label style="text-align: right;"> $.--><?//= $ventas_trans_yape_d ?? 0?><!--</label>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                        <div class="row">-->
<!--                                            <div class="col-md-6">-->
<!--                                                <label>- Pagos Transferencia Otros:</label>-->
<!--                                            </div>-->
<!--                                            <div class="col-md-3">-->
<!--                                                <label style="text-align: right;"> S/.--><?//= $ventas_trans_otros_s ?? 0?><!--</label>-->
<!--                                            </div>-->
<!--                                            <div class="col-md-3">-->
<!--                                                <label style="text-align: right;"> $.--><?//= $ventas_trans_otros_d ?? 0?><!--</label>-->
<!--                                            </div>-->
<!--                                        </div>-->
                                        <p style="border-bottom: 1px solid red"></p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>- TOTAL EGRESOS :</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> S/.<?= $egresos_totales ?? 0?></label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> $.<?= $egresos_totales ?? 0?></label>
                                            </div>
                                        </div>
                                        <div class="row" style="display: none">
                                            <div class="col-md-6">
                                                <label>- Orden de Compras:</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> S/.<?= $orden_pedido_total ?? 0?></label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> $.<?= $orden_pedido_total ?? 0?></label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>- Salida caja chica :</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> S/.<?= $salida_caja_chica ?? 0?></label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> $.<?= $salida_caja_chica ?? 0?></label>
                                            </div>
                                        </div>
                                        <p style="border-bottom: 1px solid red"></p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>- GASTOS PERSONAL</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> S/.<?= $sumar_datos_p ?? 0?></label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> $.<?= $sumar_datos_p ?? 0?></label>
                                            </div>
                                        </div>
                                        <?php
                                        $fecha_ini_caja = $datitos->caja_fecha_apertura;
                                        if ($datitos->caja_fecha_cierre == NULL) {
                                            $fecha_fin_caja = date('Y-m-d H:i:s');
                                        } else {
                                            $fecha_fin_caja = $datitos->caja_fecha_cierre;
                                        }
                                        ?>
                                        <p style="border-bottom: 1px solid red"></p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>- N° VENTAS SALON :</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> <?= $n_ventas_salon->total ?? 0?></label>
                                            </div>
                                            <div class="col-md-3"></div>
                                        </div>
                                        <p style="border-bottom: 1px solid red"></p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>- TOTAL EFECTIVO :</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> S/.<?= $diferencia_soles ?? 0?></label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="text-align: right;"> $.<?= $diferencia_dolares ?? 0?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="table">
                                       <!--<p>Apertura : <?= $datitos->caja_cierre_apertura_datetime;?> // Cierre : <?= $datitos->caja_cierre_cierre_datetime?> // Monto Cierre : <?= $datitos->caja_cierre;?></p>-->
                                        <?php

                                            $hab_x_cobrar = $this->reporte->habitacion_comandas_x_cobrar($fecha_i, $fecha_f);
                                            $hab_cobrado = $this->reporte->habitacion_comandas_cobradas($fecha_i, $fecha_f);
                                            $cuenta_x_cobrar = $this->reporte->comandas_pendientes_cuentas_cobrar($fecha_i, $fecha_f);
                                            $cuenta_cobrado = $this->reporte->comandas_pagadas_cuentas_cobrar($fecha_i, $fecha_f);
                                        ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>- HABITACION POR COBRAR:</label>
                                            </div>
                                            <div class="col-md-3">
                                                <a style="color: blue;" type="button" data-toggle="modal" data-target="#detalle_habitacion" onclick="mostrar_detalle('1', '<?= $fecha_i?>', '<?= $fecha_f?>')"><label style="text-align: right"> S/.<?= $hab_x_cobrar->total ?? 0?></label></a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>- HABITACION COBRADO:</label>
                                            </div>
                                            <div class="col-md-3">
                                                <a style="color: blue;" type="button" data-toggle="modal" data-target="#detalle_habitacion" onclick="mostrar_detalle('2', '<?= $fecha_i?>', '<?= $fecha_f?>')"><label style="text-align: right"> S/.<?= $hab_cobrado->total ?? 0?></label></a>
                                            </div>
                                        </div>
                                        <p style="border-bottom: 1px solid red"></p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>- CUENTAS POR COBRAR:</label>
                                            </div>
                                            <div class="col-md-3">
                                                <a style="color: blue;" type="button" data-toggle="modal" data-target="#detalle_cargo_cuenta" onclick="mostrar_detalle_cargo('1', '<?= $fecha_i?>', '<?= $fecha_f?>')"><label style="text-align: right"> S/.<?= $cuenta_x_cobrar->total ?? 0?></label></a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>- CUENTAS COBRADO:</label>
                                            </div>
                                            <div class="col-md-3">
                                                <a style="color: blue;" type="button" data-toggle="modal" data-target="#detalle_cargo_cuenta" onclick="mostrar_detalle_cargo('2', '<?= $fecha_i?>', '<?= $fecha_f?>')"><label style="text-align: right"> S/.<?= $cuenta_cobrado->total ?? 0?></label></a>
                                            </div>
                                        </div>
                                        <p style="border-bottom: 1px solid red"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                    <br>
                    <div class="col-lg-6">
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" style="border-color: black">
                                        <thead>
                                        <tr style="background-color: #ebebeb">
                                            <th>PRODUCTO</th>
<!--                                            <th>FECHAS</th>-->
                                            <th>CANT.</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $fecha_ini_caja = $datitos->caja_fecha_apertura;
                                            if($datitos->caja_fecha_cierre==NULL){
                                                $fecha_fin_caja = date('Y-m-d H:i:s');
                                            }else{
                                                $fecha_fin_caja = $datitos->caja_fecha_cierre;
                                            }
                                            $productos = $this->reporte->reporte_productos_2($fecha_i,$fecha_f, $id_usuario);
                                            foreach ($productos as $p){
                                                ?>
                                                <tr>
                                                    <td><?= $p->producto_nombre?></td>
<!--                                                    <td>--><?php //echo $fecha_i?><!-- / --><?php //echo $fecha_f?><!--</td>-->
                                                    <td><?= $p->total?></td>
                                                </tr>
                                                <?php
                                            }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <br>
            </div>
            <div class="col-lg-12  text-center">
                <a id="imprimir_ticket" style="color: white;" class="btn btn-primary mr-5" target="_blank" onclick="ticket_venta('<?= $fecha_i; ?>','<?= $fecha_f?>','<?= $id_usuario?>')"><i class="fa fa-print"></i> TICKET VENTAS</a>
                <a id="imprimir_ticket_productos" style="color: white;" class="btn btn-primary mr-5" target="_blank" onclick="ticket_productos('<?= $fecha_i; ?>','<?= $fecha_f?>','<?= $id_usuario?>')"><i class="fa fa-print"></i> TICKET PRODUCTOS</a>
                <a href="<?= _SERVER_ ; ?>index.php?c=Reporte&a=reporte_general_pdf&fecha_filtro=<?= $_POST['fecha_filtro']?>&fecha_filtro_fin=<?= $_POST['fecha_filtro_fin']?>&id_usuario=<?= $_POST['id_usuario']?>" target="_blank" class="btn btn-primary ml-2"><i class="fa fa-print"></i> VENTAS PDF</a>
                <a href="<?= _SERVER_ ; ?>index.php?c=Reporte&a=habitaciones_pdf&fecha_filtro=<?= $_POST['fecha_filtro']?>&fecha_filtro_fin=<?= $_POST['fecha_filtro_fin']?>" target="_blank" class="btn btn-primary ml-2"><i class="fa fa-print"></i> HABITACIONES PDF</a>
                <a href="<?= _SERVER_ ; ?>index.php?c=Reporte&a=cuentas_cobrar_pdf&fecha_filtro=<?= $_POST['fecha_filtro']?>&fecha_filtro_fin=<?= $_POST['fecha_filtro_fin']?>" target="_blank" class="btn btn-primary ml-2"><i class="fa fa-print"></i> CUENTAS COBRAR PDF</a>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script>
    function ticket_venta(fecha_i,fecha_f,id_usuario){
        var boton = 'imprimir_ticket';

        $.ajax({
            type: 'POST',
            url: urlweb + "api/Reporte/ticket_reporte",
            data: "fecha_i=" + fecha_i + "&fecha_f=" + fecha_f + "&id_usuario=" + id_usuario,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'imprimiendo...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-print\"></i> Imprimir", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Éxito!...', 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 400);
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }

    function ticket_productos(fecha_i,fecha_f,id_usuario){
        var boton = 'imprimir_ticket_productos';

        $.ajax({
            type: 'POST',
            url: urlweb + "api/Reporte/ticket_productos",
            data: "fecha_i=" + fecha_i + "&fecha_f=" + fecha_f + "&id_usuario=" + id_usuario,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'imprimiendo...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-print\"></i> Imprimir", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Éxito!...', 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 400);
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }

    function mostrar_detalle(valor, fecha_i, fecha_f){
        var cadena = "valor=" + valor + "&fecha_i="+ fecha_i + "&fecha_f=" + fecha_f;
        $.ajax({
            type: 'POST',
            url: urlweb + "api/Reporte/mostrar_detalle_habitacion",
            data: cadena,
            dataType: 'json',
            success:function (r) {
                switch (r.result.code) {
                    case 1:
                        //respuesta('¡Éxito!...', 'success');
                        $('#detalle').html(r.result.row);
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }

    function mostrar_detalle_cargo(valor, fecha_i, fecha_f){
        var cadena = "valor=" + valor + "&fecha_i="+ fecha_i + "&fecha_f=" + fecha_f;
        $.ajax({
            type: 'POST',
            url: urlweb + "api/Reporte/mostrar_detalle_cargo_cuenta",
            data: cadena,
            dataType: 'json',
            success:function (r) {
                switch (r.result.code) {
                    case 1:
                        //respuesta('¡Éxito!...', 'success');
                        $('#detalle_cargo').html(r.result.row);
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
</script>