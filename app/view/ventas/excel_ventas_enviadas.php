<?php
/**
 * Created by PhpStorm
 * User: LuisSalazar
 * Date: 04/06/2021
 * Time: 02:30 p. m.
 */
?>
<!--<img src="<?= _SERVER_?>/media/logo/logo_empresa.png" alt="" style="margin-right: 50px">-->
<br><br><br><br><br><br><br><br><br>
<div class="row">
    <div class="col-lg-12">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="" width="100%" cellspacing="0" border="1">

                        <h2 style="">HISTORIAL DE VENTAS ENVIADAS A LA SUNAT // TIPO DE VENTA : <?= utf8_decode($tipo_comprobante); ?> // FECHA : <strong><?= $fecha_vacio; ?></strong></h2>

                        <thead class="text-capitalize">
                        <br><br>
                        <tr style="background: deepskyblue;">
                            <th>#</th>
                            <th>COMPROBANTE</th>
                            <th>SERIE</th>
                            <th>CORRELATIVO</th>
                            <th>ORIGEN</th>
                            <th>CLIENTE DOC</th>
                            <th>CLIENTE NOMBRE</th>
                            <th>FECHA DE EMISION</th>
                            <th>FECHA DE VENCIMIENTO</th>
                            <th>FECHA DE CREACION</th>
                            <th>USUARIO</th>
                            <th>MONEDA</th>
                            <th>DESCUENTO</th>
                            <th>GRAVADO</th>
                            <th>EXONERADO</th>
                            <th>INAFECTO</th>
                            <th>GRATUITO</th>
                            <th>IGV</th>
                            <th>ICBPER</th>
                            <th>TOTAL</th>
                            <th>ANULADO</th>
                            <th>ESTADO SUNAT</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $a = 1;
                        $total = 0;
                        $total_dolares = 0;
                        foreach ($ventas as $m){

                            if($m->id_tipodocumento == 4){
                                $cliente = $m->cliente_razonsocial;
                            }else{
                                $cliente = $m->cliente_nombre;
                            }
                            if($m->venta_tipo == "03"){
                                $venta_tipo = "BOLETA";
                                if($m->anulado_sunat == 0){
                                    if($m->id_moneda == 1){          
                                        $total = round($total + $m->venta_total, 2);
                                    }else{
                                        $total_dolares = round($total_dolares + $m->venta_total, 2);
                                    }
                                }
                            }elseif ($m->venta_tipo == "01"){
                                $venta_tipo = "FACTURA";
                                if($m->anulado_sunat == 0){
                                    if($m->id_moneda == 1){          
                                        $total = round($total + $m->venta_total, 2);
                                    }else{
                                        $total_dolares = round($total_dolares + $m->venta_total, 2);
                                    }

                                }
                            }elseif($m->venta_tipo == "07"){
                                $venta_tipo = "NOTA DE CRÉDITO";
                            }elseif($m->venta_tipo == "08"){
                                $venta_tipo= "NOTA DE DÉBITO";
                                if($m->anulado_sunat == 0){
                                    if($m->id_moneda == 1){          
                                        $total = round($total + $m->venta_total, 2);
                                    }else{
                                        $total_dolares = round($total_dolares + $m->venta_total, 2);
                                    }

                                }
                            }else{
                                $venta_tipo = "TODOS";
                            }
                            $stylee= "style='text-align: center;'";
                            if($m->anulado_sunat == 1){
                                $stylee="style= 'text-align: center; background-color: #FF6B70'";
                                //$total = $total;
                                $anulado = "SI";
                            }else{
                                $anulado = "NO";
                            }
                            if($m->venta_tipo_envio == 1){
                                $tipo_envio = "ENVIO DIRECTO";
                            }else{
                                $tipo_envio = "ENVIO EN RESUMEN DIARIO";
                            }
                            $nombre_usuario = $m->persona_nombre. ' ' .$m->persona_apellido_paterno. ' ' .$m->persona_apellido_materno;
                            //VALORES PARA LA ZONA
                            if($m->id_caja_numero==1){
                                $zona="RESTAURANTE";
                            }else{
                                $zona="HOTEL";
                            }
                            //FIN DE VALORES
                            ?>
                            <tr <?=$stylee?>>
                                <td><?= $a;?></td>
                                <td><?= utf8_decode($venta_tipo);?></td>
                                <td><?= $m->venta_serie;?></td>
                                <td><?= $m->venta_correlativo;?></td>
                                <td><?= $zona;?></td>
                                <td><?= utf8_decode($m->cliente_numero);?></td>
                                <td><?= utf8_decode($cliente);?></td>
                                <td><?= date('d-m-Y h:i:s', strtotime($m->venta_fecha_envio));?></td>
                                <td>-</td>
                                <td><?= date('d-m-Y h:i:s', strtotime($m->venta_fecha));?></td>
                                <td><?= utf8_decode($nombre_usuario);?></td>
                                <td><?= utf8_decode($m->abrstandar);?></td>
                                <td><?= $m->venta_totaldescuento;?></td>
                                <td><?= $m->venta_totalgravada;?></td>
                                <td><?= $m->venta_totalexonerada;?></td>
                                <td><?= $m->venta_totalinafecta;?></td>
                                <td><?= $m->venta_totalgratuita;?></td>
                                <td><?= $m->venta_totaligv;?></td>
                                <td><?= $m->venta_icbper;?></td>
                                <td><?= number_format($m->venta_total,2);?></td>
                                <td><?= $anulado;?></td>
                                <td><?= $tipo_envio;?></td>
                            </tr>

                            <?php
                            $a++;
                        }
                        ?>
                        </tbody>
                        <tfooter>
                            <tr>
                                <td></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;">TOTAL SOLES:</td>
                                <td style="text-align: center;"><?= number_format($total,2);?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;">TOTAL DOLARES:</td>
                                <td style="text-align: center;"><?= number_format($total_dolares,2);?></td>
                            </tr>
                        </tfooter>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="" width="100%" cellspacing="0" border="1">

                        <h2 style="">MONTO DETALLADO DE INGRESOS SEGUN TIPO DE MONEDA</h2>

                        <thead class="text-capitalize">
                        <br><br>
                        <tr style="background: deepskyblue;">

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $total_e_soles=0;
                        $total_t_soles=0;
                        $total_tr_soles=0;
                        $total_e_dolares=0;
                        $total_t_dolares=0;
                        $total_tr_dolares=0;
                        $bv_e=0;
                        $bv_f=0;
                        $bv_t=0;
                        $bv_d=0;
                        $bv_df=0;
                        $bv_dt=0;
                        foreach ($ventas as $m){
                            if($m->anulado_sunat == 0 && $m->id_caja_cierre!=0){
                                $buscar_valor = $this->ventas->buscar_pagos_caja_movimiento($m->id_venta);
                                $buscar_valor_ts = $this->ventas->buscar_pagos_caja_movimiento_ts($m->id_venta);
                                $buscar_valor_trs = $this->ventas->buscar_pagos_caja_movimiento_trs($m->id_venta);
                                $buscar_valor_d = $this->ventas->buscar_pagos_caja_movimiento_d($m->id_venta);
                                $buscar_valor_td = $this->ventas->buscar_pagos_caja_movimiento_td($m->id_venta);
                                $buscar_valor_trd = $this->ventas->buscar_pagos_caja_movimiento_trd($m->id_venta);
                                //CREO QUE SE TIENE QUE BUSCAR EN LA TABLA CAJA MOVIMIENTOS EL ID DE LA VENTA
                                $total_e_soles = $total_e_soles + $buscar_valor->total;
                                $total_e_dolares = $total_e_dolares + $buscar_valor_d->total;
                                $total_t_soles = $total_t_soles + $buscar_valor_ts->total;
                                $total_t_dolares = $total_t_dolares + $buscar_valor_td->total;
                                $total_tr_soles = $total_tr_soles + $buscar_valor_trs->total;
                                $total_tr_dolares = $total_tr_dolares + $buscar_valor_trd->total;
                            }

                            if($m->id_caja_cierre == 0 && $m->anulado_sunat == 0){
                                if($m->id_moneda==1){
                                    $bv= $this->ventas->buscar_vdp($m->id_venta);
                                    $bv_ts = $this->ventas->buscar_vdp_ts($m->id_venta);
                                    $bv_trs = $this->ventas->buscar_vdp_trs($m->id_venta);
                                    $bv_e = $bv_e + $bv->total;
                                    $bv_f = $bv_f + $bv_ts->total;
                                    $bv_t = $bv_t + $bv_trs->total;
                                }else{
                                    $bv_do = $this->ventas->buscar_vdp_d($m->id_venta);
                                    $bv_td = $this->ventas->buscar_vdp_td($m->id_venta);
                                    $bv_trd = $this->ventas->buscar_vdp_trd($m->id_venta);
                                    $bv_d = $bv_d + $bv_do->total;
                                    $bv_df = $bv_df + $bv_td->total;
                                    $bv_dt = $bv_dt + $bv_trd->total;
                                }
                            }
                        }
                        $suma_efectivo_soles = $total_e_soles + $bv_e;
                        $suma_efectivo_tarjete =$total_t_soles + $bv_f;
                        $suma_efectivo_transferencia = $total_tr_soles + $bv_t;

                        $suma_dolares = $total_e_dolares + $bv_d;
                        $suma_dolares_tarjeta = $total_t_dolares+ $bv_df;
                        $suma_dolares_transferencia = $total_tr_dolares + $bv_dt;
                        ?>
                        </tbody>
                        <tfooter>
                            <tr>
                                <td style="text-align: center;">TOTAL EFECTIVO EN SOLES:</td>
                                <td style="text-align: center;"><?= number_format($suma_efectivo_soles,2);?></td>
                                <td style="text-align: center;">TOTAL TARJETA EN SOLES:</td>
                                <td style="text-align: center;"><?= number_format($suma_efectivo_tarjete,2);?></td>
                                <td style="text-align: center;">TOTAL TRANSFERENCIA EN SOLES:</td>
                                <td style="text-align: center;"><?= number_format($suma_efectivo_transferencia,2);?></td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">TOTAL EFECTIVO EN DOLARES:</td>
                                <td style="text-align: center;"><?= number_format($suma_dolares,2);?></td>
                                <td style="text-align: center;">TOTAL TARJETA EN DOLARES:</td>
                                <td style="text-align: center;"><?= number_format($suma_dolares_tarjeta,2);?></td>
                                <td style="text-align: center;">TOTAL TRANSFERENCIA EN DOLARES:</td>
                                <td style="text-align: center;"><?= number_format($suma_dolares_transferencia,2);?></td>
                            </tr>
                        </tfooter>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
