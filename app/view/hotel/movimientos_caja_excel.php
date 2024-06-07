    <?php
    foreach ($cajas as $c){
        foreach ($turnos as $t){
            $movimientos = $this->hotel->listar_movimientos($c->id_caja,$t->id_turno,$desde,$hasta);
            $cierres = $this->hotel->listar_movimiento_cierre($c->id_caja,$t->id_turno,$desde,$hasta);
            ?>
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 text-center font-weight-bold text-primary" style="color: #6a0c0d !important;"><?= utf8_decode($c->caja_nombre." - TURNO ".$t->turno_nombre); ?></b></h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Nro Doc</th>
                                <th>Detalle</th>
                                <th>Monto</th>
                                <th>Tipo</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(count($movimientos)>0){
                                $a=1;$total_1_s=0;$total_1_d=0;$bb=0;
                                foreach ($movimientos as $m){
                                    ($m->id_moneda==1)?$total_1_s+=$m->caja_movimiento_monto:$total_1_d+=$m->caja_movimiento_monto;
                                    ($m->caja_movimiento_tipo==1)?$ingreso="color: blue;":$ingreso="color:red;";
                                    $bbs="";if($m->id_moneda!=1&&$bb==0){$bbs="border-top:2px dotted gray;";$bb=1;}
                                    $nro_doc="-";
                                    if($m->id_venta!=0){
                                        $data_venta = $this->ventas->listar_venta_x_id($m->id_venta);
                                        $nro_doc=$data_venta->venta_serie."-".$data_venta->venta_correlativo;
                                    }
                                    ?>
                                    <tr style="<?= $ingreso.$bbs; ?>">
                                    <td><?= $a; ?></td>
                                    <td><?= $m->caja_movimiento_datetime; ?></td>
                                    <td><?= $nro_doc; ?></td>
                                    <td><?= utf8_decode($m->caja_movimiento_detalle); ?></td>
                                    <td><?= $m->simbolo." ".$m->caja_movimiento_monto; ?></td>
                                    <td><?= $m->tipo_pago_nombre; ?></td>
                                    <td><?= $m->caja_movimiento_observacion; ?></td>
                                    </tr>
                                    <?php
                                    $a++;
                                }
                                foreach ($tipos_pago as $tp){
                                    ($tp->id_tipo_pago==1)?$sty="style='border-top:2px solid black;'":$sty="";
                                    echo "<tr $sty><td>TOTAL</td><td>$tp->tipo_pago_nombre</td>";
                                    foreach ($monedas as $mon){
                                        $data_a = $this->nav->listar_caja_monto_por_caja_cierre_moneda_tipo_pago($m->id_caja_cierre,$mon->id_moneda,$tp->id_tipo_pago);
                                        echo utf8_decode("<td>$mon->moneda</td>");
                                        echo "<td style='font-weight: bold'>$data_a->caja_monto_monto</td>";
                                    }
                                    echo "</tr>";
                                }
                                if(count($cierres)>0){
                                    foreach ($cierres as $ci){
                                        ?>
                                        <tr style="color: #6a0c0d">
                                            <td>-</td>
                                            <td><?= $ci->caja_movimiento_datetime; ?></td>
                                            <td>-</td>
                                            <td><?= $ci->caja_movimiento_detalle; ?></td>
                                            <td><?= $ci->simbolo." ".$ci->caja_movimiento_monto; ?></td>
                                            <td><?= $ci->tipo_pago_nombre; ?></td>
                                            <td><?= $ci->caja_movimiento_observacion; ?></td>
                                        </tr>
                                <?php
                                    }}
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