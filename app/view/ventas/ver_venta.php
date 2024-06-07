<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 text-center font-weight-bold text-primary" style="color: #6a0c0d !important;">VENTA REALIZADA</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-4">
                    <p><i class="fa fa-calendar"></i> Fecha de Venta: <?php echo $sale->venta_fecha;?></p>
                    <p><i class="fa fa-user"></i> Nombre Del Cliente: <?php echo $sale->cliente_nombre.$sale->cliente_razonsocial;?></p>
                </div>
                <div class="col-lg-3" style="text-align: center">
                    <p>Serie y Correlativo: <strong><?php echo $sale->venta_serie.'-'.$sale->venta_correlativo?></strong></p>
                    <p>RUC ó DNI: <?php echo $sale->cliente_numero;?></p>
                </div>
                <div class="col-lg-3">
                    <?php
                    $id_turno = 1;
                    $idroleUser = $this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_);
                    if($sale->anulado_sunat == 0){
                        ?>
                        <p style="color: green; float: right;"><i class="fa fa-check-circle"></i> Venta Realizada Correctamente</p>
                        <?php
                        /*if ($idroleUser == 4){
                            if($id_turn == $sale->id_turn){ //si la venta no es del dia actual no se genera la anulacion
                                ?>
                                <a type="button" class="btn btn-xs btn-danger" style="float: right" onclick="preguntarSiNoA(<?php echo $sale->id_saleproduct;?>)"><i class="fa fa-times-circle"></i> Anular Venta</a>
                                <?php
                            }
                        } else{
                            ?>
                            <a type="button" class="btn btn-xs btn-danger" style="float: right" onclick="preguntarSiNoA(<?php echo $sale->id_saleproduct;?>)"><i class="fa fa-times-circle"></i> Anular Venta</a>
                            <?php
                        }       */
                    } else {
                        ?>
                        <p style="color: red; float: right;"><i class="fa fa-times-circle"></i> Esta Venta fue ANULADA</p>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-10">
                    <table class="table table-bordered table-hover" style="border-color: black">
                        <thead>
                        <tr style="background-color: #ebebeb">
                            <th>Cant</th>
                            <th>Descripción</th>
                            <th>Precio Unitario</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $totales = count($productssale);
                        $monto = 0;
                        if($totales == 0){
                            ?>
                            <center><h2>Aún no hay productos</h2></center>
                            <?php
                        } else {
                            foreach ($productssale as $p){
                                $subtotal = $p->venta_detalle_valor_total;
                                $monto +=$subtotal;
                                ?>
                                <tr>
                                    <!--<td><?php //echo $p->id_productforsale;?></td>-->
                                    <td><?php echo $p->venta_detalle_cantidad;?></td>
                                    <td><?php echo $p->venta_detalle_nombre_producto;?></td>
                                    <td>S/. <?php echo number_format($p->venta_detalle_valor_unitario,2);?></td>
                                    <td>S/. <?php echo number_format($subtotal,2);?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>
                        <!--<tfoot>
                            <tr>
                                <td colspan="3" style="text-align:right;">PRECIO TOTAL</td>
                                <?php $num_ = explode(".",$monto);
                        $dec = round($num_[1],2);
                        if(strlen($dec)==1){
                            $dec = $dec ."0";
                            ($dec==0) ? $monto = $monto.".00": $monto = $monto."0";
                        } ?>
                                <td style="background-color: #f9f17f; font-weight: bold">S/. <?php echo $monto;?></td>
                            </tr>
                        </tfoot>-->
                    </table>
                    <div class="row">

                        <div id="espacio" class = "col-lg-8">
                        </div>

                        <div class="col-lg-4">
                            <?php
                            if($sale->venta_totaldescuento > 0){
                            ?>
                            <h6>DESCUENTO(-): s/. <?php echo number_format($sale->venta_totaldescuento ,2);?></h6>
                            <?php
                            }
                            ?>
                            <h6>OP. GRATUITA: s/. <?php echo number_format($sale->venta_totalgratuita ,2);?></h6>
                            <h6>OP. EXONERADA: s/. <?php echo number_format($sale->venta_totalexonerada ,2);?></h6>
                            <h6>OP. INAFECTA: s/. <?php echo number_format($sale->venta_totalinafecta, 2);?></h6>
                            <h6>OP. GRAVADA: s/. <?php echo number_format($sale->venta_totalgravada , 2);?></h6>
                            <h6>IGV: s/. <?php echo number_format($sale->venta_totaligv , 2);?></h6>
                            <?php
                            if ($sale->venta_icbper > 0){ ?>
                                <h6>ICBPER: s/. <?php echo number_format($sale->venta_icbper , 2);?></h6>
                            <?php }
                            ?>
                            <h5>PRECIO TOTAL: s/. <?php echo number_format($sale->venta_total , 2);?></h5>
                        </div>
                    </div>
                    <div>
                        <?php
                        if($sale->id_tipo_pago == 5){
                            $cuotas = $this->ventas->listar_cuotas_x_venta($sale->id_venta);
                            ?>
                            <p><strong>TIPO DE PAGO - <?= $cuotas[0]->tipo_pago_nombre;?> : </strong>
                                <?php
                                foreach ($cuotas as $c){
                                    echo "CUOTA ". str_pad($c->venta_cuota_numero, 3, "00", STR_PAD_LEFT). " : S/. " .$c->venta_cuota_importe. ' - ' .$c->venta_cuota_fecha. ". ";
                                }
                                ?>
                            </p>
                        <?php
                        }
                        ?>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12" style="text-align: center">
                    <a class="btn btn-primary" target="_blank" href="<?php echo _SERVER_. 'Ventas/imprimir_pdf/' . $id;?>"><i class="fa fa-file-pdf-o"></i> Imprimir Comprabante</a>
                    <a id="imprimir_ticket" style="color: white;" class="btn btn-success" target="_blank" onclick="ticket_venta(<?= $id; ?>)"><i class="fa fa-print"></i> Ticket</a>
                    <!--<a id="imprimir_ticket" class="btn btn-success" style="color: white;" target="_blank" onclick="ticket_venta(<?= $id;?>)"><i class="fa fa-print"></i> Imprimir</a>-->
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script type="text/javascript">
    function ticket_venta(id){
        var boton = 'imprimir_ticket';
        $.ajax({
            type: "POST",
            url: urlweb + "Pedido/ticket_venta",
            data: "id=" + id,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'imprimiendo...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-print\"></i> Imprimir", false);
                console.log(r.result.code);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Éxito!...', 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 800);
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
</script>
