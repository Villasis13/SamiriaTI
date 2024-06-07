
<!-- Begin Page Content -->
<div class="container-fluid">
    
    <div class="row">
        <div class="col-md-12">
            <?php
            foreach ($cajas as $c){
                foreach ($turnos as $t){
                    $movimientos = $this->hotel->listar_movimientos_mj($c->id_caja,$t->id_turno,$desde,$hasta);
                    $cierres = $this->hotel->listar_movimiento_cierre($c->id_caja,$t->id_turno,$desde,$hasta);
                    ?>
                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 text-center font-weight-bold text-primary" style="color: #6a0c0d !important;"><?= $c->caja_nombre." - TURNO ".$t->turno_nombre; ?></b></h6>
                            </div>
                            <div class="card-body">
                                <table border="1" >
                                    <thead>
                                    <tr>
                                        <th rowspan="2">Fecha</th>
                                        <th rowspan="2">Nro Doc</th>
                                        <th rowspan="2">Detalle</th>
                                        <th colspan="4" class="text-center">Efectivo</th>
                                        <th rowspan="2">Tarjeta</th>
                                        <th rowspan="2">Deposito</th>
                                    </tr>
                                    <tr>
                                        <th>Ingreso</th>
                                        <th>Egreso</th>
                                        <th>Ingreso $</th>
                                        <th>Egreso $</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $b_an=[];
                                    if(count($movimientos)>0){
                                        $a=1;$total_1_s=0;$total_1_d=0;$bb=0;$gastos=[];
                                        $total_col1=0;
                                        $total_col2=0;
                                        $total_col3=0;
                                        $total_col4=0;
                                        $total_col5=0;
                                        $total_col6=0;
                                        foreach ($categorias_gasto as $cce){
                                            $gastos[$cce->id_config_cat_egreso]=0;
                                        }
                                        foreach ($movimientos as $m){
                                            $nro_doc="-";
                                            $bbs="";if($m->id_moneda!=1&&$bb==0){$bbs="border-top:2px dotted gray;";$bb=1;}

                                            $detalle=$m->caja_movimiento_detalle;
                                            if($m->id_venta!=0){
                                                $data_venta = $this->ventas->listar_venta_x_id($m->id_venta);
                                                $nro_doc=$data_venta->venta_serie."-".$data_venta->venta_correlativo;
                                                $venta_detalle=$this->ventas->listar_venta_detalle_x_id_venta_pdf($m->id_venta);
                                                $detalle=$m->venta_observaciones."<br>";
                                                foreach ($venta_detalle as $vd){
                                                    $detalle.=$vd->producto_nombre."<br>";
                                                }
                                                
                                                if($m->venta_cancelar==0){
                                                    $ingreso="color: red;";
                                                    $obss =$m->caja_movimiento_observacion;
                                                    if($m->id_venta==0){
                                                        $obss ="";
                                                        $nro_doc=$m->caja_movimiento_observacion;
                                                    }
                                                    if(!in_array($nro_doc, $b_an)){
                                                        ?>
                                                    <tr style="<?= $ingreso.$bbs; ?>">
                                                    <td><?= $this->validar->obtener_nombre_fecha($m->caja_movimiento_datetime,"DateTime","DateTime","es"); ?></td>
                                                    <td><?= $nro_doc; ?></td>
                                                    <td>ANULADO</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    </tr>
                                                    <?php
                                                    $b_an[]=$nro_doc;
                                                    }
                                                }else{
                                                    if($m->tipo_paguito_new==3){
                                                    if($m->id_moneda==1){
                                                        if($m->caja_movimiento_tipo==1){
                                                            $ingreso="color: blue;";
                                                            $obss =$m->caja_movimiento_observacion;
                                                            if($m->id_venta==0){
                                                                $obss ="";
                                                                $nro_doc=$m->caja_movimiento_observacion;
                                                            }
                                                                        ?>
                                                            <tr style="<?= $ingreso.$bbs; ?>">
                                                            <td><?= $this->validar->obtener_nombre_fecha($m->caja_movimiento_datetime,"DateTime","DateTime","es"); ?></td>
                                                            <td><?= $nro_doc; ?></td>
                                                            <td><?= $detalle; ?></td>
                                                            <td><?= $m->simbolo." ".$m->caja_movimiento_monto; ?></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            </tr>
                                                            <?php
                                                            $total_col1+=$m->caja_movimiento_monto;
                                                        }else{
                                                            $ingreso="color: red;";
                                                            $obss =$m->caja_movimiento_observacion;
                                                            if($m->id_venta==0){
                                                                $obss ="";
                                                                $nro_doc=$m->caja_movimiento_observacion;
                                                            }
                                                            ?>
                                                            <tr style="<?= $ingreso.$bbs; ?>">
                                                            <td><?= $this->validar->obtener_nombre_fecha($m->caja_movimiento_datetime,"DateTime","DateTime","es"); ?></td>
                                                            <td><?= $nro_doc; ?></td>
                                                            <td><?= $detalle; ?></td>
                                                            <td></td>
                                                            <td><?= $m->simbolo." ".$m->caja_movimiento_monto; ?></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            </tr>
                                                            <?php
                                                            $total_col2+=$m->caja_movimiento_monto;
                                                        }
                                                        $total_1_s+=$m->caja_movimiento_monto;
                                                    }else{
                                                        if($m->caja_movimiento_tipo==1){
                                                            $ingreso="color: blue;";
                                                            $obss =$m->caja_movimiento_observacion;
                                                            if($m->id_venta==0){
                                                                $obss ="";
                                                                $nro_doc=$m->caja_movimiento_observacion;
                                                            }
                                                                        ?>
                                                            <tr style="<?= $ingreso.$bbs; ?>">
                                                            <td><?= $this->validar->obtener_nombre_fecha($m->caja_movimiento_datetime,"DateTime","DateTime","es"); ?></td>
                                                            <td><?= $nro_doc; ?></td>
                                                            <td><?= $detalle; ?></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td><?= $m->simbolo." ".$m->caja_movimiento_monto; ?></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            </tr>
                                                            <?php
                                                            $total_col3+=$m->caja_movimiento_monto;
                                                        }else{
                                                            ?>
                                                            <tr style="<?= $ingreso.$bbs; ?>">
                                                            <td><?= $this->validar->obtener_nombre_fecha($m->caja_movimiento_datetime,"DateTime","DateTime","es"); ?></td>
                                                            <td><?= $nro_doc; ?></td>
                                                            <td><?= $detalle; ?></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td><?= $m->simbolo." ".$m->caja_movimiento_monto; ?></td>
                                                            <td></td>
                                                            <td></td>
                                                            </tr>
                                                            <?php
                                                            $total_col4+=$m->caja_movimiento_monto;
                                                        }
                                                        $total_1_d+=$m->caja_movimiento_monto;
                                                    }
                                                }elseif($m->tipo_paguito_new==1){
                                                    $ingreso="color: blue;";
                                                    $obss =$m->caja_movimiento_observacion;
                                                    if($m->id_venta==0){
                                                        $obss ="";
                                                        $nro_doc=$m->caja_movimiento_observacion;
                                                    }
                                                                ?>
                                                    <tr style="<?= $ingreso.$bbs; ?>">
                                                    <td><?= $this->validar->obtener_nombre_fecha($m->caja_movimiento_datetime,"DateTime","DateTime","es"); ?></td>
                                                    <td><?= $nro_doc; ?></td>
                                                    <td><?= $detalle; ?></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><?= $m->simbolo." ".$m->caja_movimiento_monto; ?></td>
                                                    <td></td>
                                                    </tr>
                                                    <?php
                                                    $total_col5+=$m->caja_movimiento_monto;
                                                }else{
                                                    $ingreso="color: blue;";
                                                    $obss =$m->caja_movimiento_observacion;
                                                    if($m->id_venta==0){
                                                        $obss ="";
                                                        $nro_doc=$m->caja_movimiento_observacion;
                                                    }
                                                                ?>
                                                    <tr style="<?= $ingreso.$bbs; ?>">
                                                    <td><?= $this->validar->obtener_nombre_fecha($m->caja_movimiento_datetime,"DateTime","DateTime","es"); ?></td>
                                                    <td><?= $nro_doc; ?></td>
                                                    <td><?= $detalle; ?></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><?= $m->simbolo." ".$m->caja_movimiento_monto; ?></td>
                                                    </tr>
                                                    <?php
                                                    $total_col6+=$m->caja_movimiento_monto;
                                                }
                                                }
                                                
                                            }
                                        }
                                        ?>
                                        <tr>
                                            <td style="font-weight: bold;text-align: center;" colspan="3">TOTAL</td>
                                            <td style="font-weight: bold;background: yellow;"><?=$total_col1 ?></td>
                                            <td style="font-weight: bold;"><?=$total_col2 ?></td>
                                            <td style="font-weight: bold;"><?=$total_col3 ?></td>
                                            <td style="font-weight: bold;"><?=$total_col4 ?></td>
                                            <td style="font-weight: bold;"><?=$total_col5 ?></td>
                                            <td style="font-weight: bold;"><?=$total_col6 ?></td>
                                        </tr>
                                        <?php
                                    }else{
                                        echo "<tr><td colspan='5'>No se encontraron movimientos</td></tr>";
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
            <a style="width: 100%" onclick="cambiar_fecha_excel()" class="btn btn-success text-white"><i class="fa fa-file-excel-o"></i> Exportar a Excel</a>
        </div>
    </div>
</div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>