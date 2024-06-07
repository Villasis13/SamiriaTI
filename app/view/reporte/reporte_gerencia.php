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
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h6><b>Informe General <?= $fecha_formateada ?></b></h6>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card shadow">
                        <div class="card-body">
                            <h6>Ocupación de Habitaciones</h6>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header">
                                    <h6>Hoy</h6>
                                </div>
                                <div class="card-body" style="color: black">
                                    <div style="display: flex;justify-content: space-between;">
                                        <span style="color: black;">TOTAL</span>
                                        <span><b><?= $habitaciones->total ?></b></span>
                                    </div>
                                    <div style="display: flex;justify-content: space-between;">
                                        <span style="color: black;">Libres</span>
                                        <span><b><?= $habitaciones->libres ?></b> <span style="font-size: 8pt;">(<?= $habitaciones->porcentaje_libres ?>%)</span></span>
                                    </div>
                                    <div style="display: flex;justify-content: space-between;">
                                        <span style="color: black;">Ocupadas</span>
                                        <span><b><?= $habitaciones->ocupadas ?></b> <span style="font-size: 8pt;">(<?= $habitaciones->porcentaje_ocupadas ?>%)</span></span>
                                    </div>
                                    <div style="display: flex;justify-content: space-between;">
                                        <span style="color: black;">Deshabitadas</span>
                                        <span><b><?= $habitaciones->deshabilitadas ?></b> <span style="font-size: 8pt;">(<?= $habitaciones->porcentaje_deshabilitadas ?>%)</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card shadow-sm mb-4">
                                <div class="card-body py-3 text-center">
                                    <label>Porcentaje de ocupación hoy</label>
                                    <canvas id="myPieChart" width="500" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="card-header">
                                <h6>Ocupación última semana</h6>
                            </div>
                            <div class="card-body" style="color: black">
                                <table class="table table-striped" style="font-size: 9pt;color: black">
                                    <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Ocupadas</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                <?php
                                foreach ($ocupaciones as $ocu){
                                    ?>
                                    <tr>
                                        <td><?= $ocu["fecha"] ?></td>
                                        <td><?= $ocu["cant"]->ocupadas ?> <span style="font-size: 8pt">(<?= $ocu["cant"]->porcentaje_ocupadas ?> %)</span></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="card shadow-sm mb-4">
                                <div class="card-body py-3 text-center">
                                    <label>Ocupación última semana</label>
                                    <canvas id="myChartLine" width="500" height="400"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow">
                        <div class="card-body">
                            <h6>Ingresos y Salidas</h6>
                        </div>
                    </div>
                    <br>
                    <div class="card shadow">
                        <div class="card-body">
                    <table class="table table-striped" style="font-size:9pt">
                        <thead>
                        <tr>
                            <td>Turno</td>
                            <td>Ingreso Efectivo (S/.)</td>
                            <td>Salida efectivo (S/.)</td>
                            <td>Ingreso efectivo ($)</td>
                            <td>Salida efectivo ($)</td>
                            <td>Tarjeta</td>
                            <td>Depósito</td>
                        </tr>
                        </thead>
                        <tbody>
                    <?php
                    $total_total_col1=$total_total_col2=$total_total_col3=$total_total_col4=$total_total_col5=$total_total_col6=0;
                    foreach ($cajas as $c) {
                        foreach ($turnos as $t) {
                            $movimientos = $this->hotel->listar_movimientos_mj($c->id_caja, $t->id_turno, $desde, $hasta);
                            $a=1;$total_1_s=0;$total_1_d=0;$bb=0;$gastos=[];
                            $total_col1=$total_col2=$total_col3=$total_col4=$total_col5=$total_col6=0;
                            foreach ($movimientos as $m){
                                if($m->id_venta!=0){
                                    if($m->venta_cancelar==1) {
                                        if ($m->tipo_paguito_new == 3) {
                                            if ($m->id_moneda == 1) {
                                                if ($m->caja_movimiento_tipo == 1) {
                                                    $total_col1 += $m->caja_movimiento_monto;
                                                } else {
                                                    $total_col2 += $m->caja_movimiento_monto;
                                                }
                                                $total_1_s += $m->caja_movimiento_monto;
                                            } else {
                                                if ($m->caja_movimiento_tipo == 1) {
                                                    $total_col3 += $m->caja_movimiento_monto;
                                                } else {
                                                    $total_col4 += $m->caja_movimiento_monto;
                                                }
                                                $total_1_d += $m->caja_movimiento_monto;
                                            }
                                        } elseif ($m->tipo_paguito_new == 1) {
                                            $total_col5 += $m->caja_movimiento_monto;
                                        } else {
                                            $total_col6 += $m->caja_movimiento_monto;
                                        }

                                    }
                                }
                            }
                            ?>
                            <tr>
                                <td><?= $c->caja_nombre." - TURNO ".$t->turno_nombre ?></td>
                                <td style="font-weight: bold;"><?=$total_col1 ?></td>
                                <td style="font-weight: bold;"><?=$total_col2 ?></td>
                                <td style="font-weight: bold;"><?=$total_col3 ?></td>
                                <td style="font-weight: bold;"><?=$total_col4 ?></td>
                                <td style="font-weight: bold;"><?=$total_col5 ?></td>
                                <td style="font-weight: bold;"><?=$total_col6 ?></td>
                            </tr>
                            <?php
                            $total_total_col1+=$total_col1;
                            $total_total_col2+=$total_col2;
                            $total_total_col3+=$total_col3;
                            $total_total_col4+=$total_col4;
                            $total_total_col5+=$total_col5;
                            $total_total_col6+=$total_col6;
                        }
                    }
                    ?>
                    <tr>
                        <td>Total</td>
                        <td style="font-weight: bold;"><?=$total_total_col1 ?></td>
                        <td style="font-weight: bold;"><?=$total_total_col2 ?></td>
                        <td style="font-weight: bold;"><?=$total_total_col3 ?></td>
                        <td style="font-weight: bold;"><?=$total_total_col4 ?></td>
                        <td style="font-weight: bold;"><?=$total_total_col5 ?></td>
                        <td style="font-weight: bold;"><?=$total_total_col6 ?></td>
                    </tr>
                    </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?= _SERVER_ . _STYLES_ADMIN_;?>vendor/chart.js/Chart.js"></script>
<script>
    var ctx2 = document.getElementById("myPieChart");
    var myPieChart = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: ["Libres","Ocupadas","Deshabilitadas"],
            datasets: [{
                data: [<?php echo $habitaciones->libres.",".$habitaciones->ocupadas.",".$habitaciones->deshabilitadas; ?>],
                backgroundColor: ['green', 'red', 'yellow'],
                hoverBackgroundColor: ['green', 'red', 'yellow'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: true,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: true
            },
            cutoutPercentage: 80,
        },
    });
    const ctx3 = document.getElementById('myChartLine');
    const myChart3 = new Chart(ctx3, {
        type: 'line',
        data: {
            labels: [<?php foreach ($ocupaciones as $ocu){echo "'".$ocu["fecha"]."',";} ?>],
            datasets: [{
                label: 'Ocupación última semana',
                data: [<?php foreach ($ocupaciones as $ocu){echo $ocu["cant"]->ocupadas.",";} ?>],
                backgroundColor: ["blue","red","green","yellow","skyblue","purple","cyan"],
                borderColor: ["blue","red","green","yellow","skyblue","purple","cyan"],
                borderWidth: 1
            }]
        },
        options: {scales: {y: {beginAtZero: true}}}
    });
</script>