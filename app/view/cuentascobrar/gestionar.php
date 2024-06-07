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
                    <a class="btn btn-secondary" href="javascript:history.back()" role="button"><i class="fa fa-backward"></i> Regresar</a>
                </div>
            </div>

            <br>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="<?= _SERVER_ ?>Cuentascobrar/gestionar">
                                <input type="hidden" id="enviar_registro" name="enviar_registro" value="1">
                                <div class="row">
                                    <div class="col-lg-2"></div>
                                    <div class="col-lg-2">
                                        <label for="">Estado</label>
                                        <select class="form-control" name="pago_filtro" id="pago_filtro">
                                            <option <?= ($pago_filtro == "")?'selected':''; ?> value="">TODOS</option>
                                            <option <?= ($pago_filtro == "0")?'selected':''; ?> value="0" >Pendiente de Pago</option>
                                            <option <?= ($pago_filtro == "1")?'selected':''; ?> value="1" >Pagado Parcialmente</option>
                                            <option <?= ($pago_filtro == "2")?'selected':''; ?> value="2" >Cancelados</option>
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
                                        <button style="margin-top: 32px;" class="btn btn-success" ><i class="fa fa-search"></i> BUSCAR REGISTRO</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <br>
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
                                            <th style="text-align: center">Fecha Creación</th>
                                            <th style="text-align: center">DNI / RUC</th>
                                            <th style="text-align: center">Cliente</th>
                                            <th style="text-align: center">Cuenta Total</th>
                                            <th style="text-align: center">Total Pagado</th>
                                            <th style="text-align: center">Pendiente de Pago</th>
                                            <th style="text-align: center">Comprobantes Relacionados</th>
                                            <th style="text-align: center">Estado</th>
                                            <th style="text-align: center">Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $a = 1;
                                        $total = 0;
                                        $pendiente_pago=0;
                                        foreach ($cuentas_por_cobrar as $cc){
                                            $buscar_comprobantes = $this->cuentascobrar->buscar_comprobantes($cc->id_cuenta);
                                            $pendiente_pago = $cc->cuentas_total - $cc->cuentas_total_pagado;
                                            if($cc->cuenta_cancelado==0){
                                                $estado='PENDIENTE DE PAGO';
                                                $style="";
                                            }elseif($cc->cuenta_cancelado==1){
                                                $estado='CANCELADO PARCIALMENTE';
                                            }else{
                                                $estado='CANCELADO';
                                            }
                                            if($cc->id_moneda==1){
                                                $simbolo = "S/. ";
                                            }else{
                                                $simbolo = "$. ";
                                            }
                                            ?>
                                            <tr>
                                                <td><?= $a;?></td>
                                                <td style="text-align: center"><?= date('d-m-Y H:i:s',strtotime($cc->cuenta_fecha_creacion))?></td>
                                                <td style="text-align: center"><?= $cc->cliente_numero?></td>
                                                <td style="text-align: center"><?= $cc->cliente_razonsocial?> <?= $cc->cliente_nombre?></td>
                                                <td style="text-align: center; color: black"><?=$simbolo?> <?= $cc->cuentas_total?></td>
                                                <td style="text-align: center; color: blue"><?=$simbolo?> <?= $cc->cuentas_total_pagado?></td>
                                                <td style="text-align: center; color: red"><?=$simbolo?> <?= number_format($pendiente_pago,2) ?></td>
                                                <td style="text-align: center">
                                                    <?php
                                                    foreach ($buscar_comprobantes as $fb){
                                                    ?>
                                                        <a target="_blank" href="<?php echo _SERVER_. 'Ventas/ver_venta/' . $fb->id_venta;?>"><?= $fb->venta_serie.'-'.$fb->venta_correlativo?></a>
                                                        <br>
                                                    <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td style="text-align: center"><?= $estado?></td>
                                                <td style="text-align: center">
                                                    <a type="button" target="_blank" class="btn btn-primary" href="<?php echo _SERVER_. 'Cuentascobrar/ver_detalle/' . $cc->id_cuenta;?>" title="Ver Detalle"><i class="fa fa-eye"></i></a>
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
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>