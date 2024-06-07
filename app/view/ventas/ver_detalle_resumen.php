<?php
/**
 * Created by PhpStorm
 * User: LuisSalazar
 * Date: 30/05/2021
 * Time: 04:10 p. m.
 */
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 text-center font-weight-bold text-primary" style="color: #6a0c0d !important;">Serie y Correlativo <br> <?= $resumen->envio_resumen_serie .'-'. $resumen->envio_resumen_correlativo;?></b></h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row" style="text-align: center; font-size: 18px;">
                        <div class="col-lg-4">
                            <p>Fecha de Comprobantes</p>
                            <p><?= date('m-d-Y', strtotime($resumen->envio_resumen_fecha));?></p>
                        </div>
                        <div class="col-lg-4">
                            <p>Fecha de Emisión</p>
                            <p><?= date('m-d-Y', strtotime($resumen->envio_sunat_datetime));?></p>
                        </div>
                        <div class="col-lg-4">
                            <p>Nº Ticket: <?= $resumen->envio_resumen_ticket;?></p>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-borderless table-striped table-earning" id="dataTable" width="100%" cellspacing="0">
                            <thead class="text-capitalize">
                            <tr>
                                <th>#</th>
                                <th>Fecha de Emision</th>
                                <th>Comprobante</th>
                                <th>Serie y Correlativo</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Condición de Comprobante</th>
                                <th>PDF</th>
                                <th>Acción</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $a = 1;
                            $total = 0;
                            foreach ($detalle_resumen as $al){
                                $venta = $this->ventas->listar_venta_x_id($al->id_venta);
                                $stylee="style= 'text-align: center;'";

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
                                if($al->venta_condicion_resumen == 1){
                                    $estilo_mensaje = "style= 'color: green; font-size: 14px;'";
                                    $mensaje = "REGISTRADO";
                                }elseif($al->venta_condicion_resumen == 2){
                                    $estilo_mensaje = "style= 'color: blue; font-size: 14px;'";
                                    $mensaje = "MODIFICADO";
                                }else{
                                    $mensaje = "ANULADO";
                                }
                                if($al->id_tipodocumento == 4){
                                    $cliente = $venta->cliente_razonsocial;
                                }else{
                                    $cliente = $venta->cliente_nombre;
                                }                                            ?>
                                <tr <?= $stylee?>>
                                    <td><?= $a;?></td>
                                    <td><?= date('d-m-Y H:i:s', strtotime($al->venta_fecha));?></td>
                                    <td><?= $tipo_comprobante;?></td>
                                    <td><?= $al->venta_serie. '-' .$al->venta_correlativo;?></td>
                                    <td>
                                        <?= $venta->cliente_numero;?><br>
                                        <?= $cliente;?>
                                    </td>
                                    <td>
                                        <?= $venta->simbolo.' '.$al->venta_total;?>
                                    </td>
                                    <td <?= $estilo_mensaje;?>><?= $mensaje;?></td>
                                    <td><center><a type="button" target='_blank' href="<?= _SERVER_ . 'Ventas/imprimir_ticket_pdf/' . $al->id_venta ;?>" style="color: red" ><i class="fa fa-file-pdf-o"></i></a></center></td>
                                    <td>
                                        <a target="_blank" type="button" class="btn btn-sm btn-primary btne" href="<?php echo _SERVER_. 'Ventas/ver_venta/' . $al->id_venta;?>" ><i class="fa fa-eye ver_detalle"></i></a>
                                    </td>
                                </tr>
                                <?php
                                $a++;
                                $total = $total + $al->pago_total;
                            }
                            ?>
                            </tbody>

                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>ventas.js"></script>

