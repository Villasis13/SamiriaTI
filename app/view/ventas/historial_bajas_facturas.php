<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 text-center font-weight-bold text-primary" style="color: #6a0c0d !important;">LISTA DE COMUNICACIÓN DE BAJAS</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="<?= _SERVER_ ?>Ventas/historial_bajas_facturas">
                                <input type="hidden" id="enviar_registro" name="enviar_registro" value="1">
                                <div class="row">
                                    <!--<div class="col-lg-3">
                            <label>Estado de Comprobante</label>
                            <select  id="estado_cpe" name="estado_cpe" class="form-control">
                                <option <?= ($estado_cpe == "")?'selected':''; ?> value="">Seleccionar...</option>
                                <option <?= ($estado_cpe == "0")?'selected':''; ?> value="0">Sin Enviar</option>
                                <option <?= ($estado_cpe == "1")?'selected':''; ?> value="1">Enviado Sunat</option>
                            </select>
                        </div>-->
                                    <div class="col-lg-3"></div>
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
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
            <div class="col-lg-12">
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
                                            <th>Fecha de Emisión</th>
                                            <th>Fecha de Comprobantes</th>
                                            <th>Serie Y Correlativo</th>
                                            <th>XML</th>
                                            <th>CDR</th>
                                            <th>Estado Sunat</th>
                                            <th>Datos del Comprobante Anulado</th>
                                            <th>Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $a = 1;
                                        $total = 0;
                                        foreach ($bajas as $al){
                                            $stylee="style= 'text-align: center;'";

                                            if($al->venta_anulado_estado_sunat == NULL){
                                                $mensaje_consulta = "";
                                            }else{
                                                $mensaje_consulta = $al->venta_anulado_estado_sunat;
                                                $estilo_mensaje_consulta = "style= 'color: green; font-size: 14px;'";
                                            }
                                            ?>
                                            <tr <?= $stylee?>>
                                                <td><?= $a;?></td>
                                                <td><?= date('d-m-Y H:i:s', strtotime($al->venta_anulado_datetime));?></td>
                                                <td><?= date('d-m-Y', strtotime($al->venta_anulado_fecha));?></td>
                                                <td><?= $al->venta_anulado_serie. '-' .$al->venta_anulado_correlativo;?></td>
                                                <?php
                                                if(file_exists($al->venta_anulado_rutaXML)){ ?>
                                                    <td><center><a type="button" target='_blank' href="<?= _SERVER_.$al->venta_anulado_rutaXML;?>" style="color: blue" ><i class="fa fa-file-text"></i></a></center></td>
                                                    <?php
                                                }else{ ?>
                                                    <td>--</td>
                                                    <?php
                                                }
                                                if(file_exists($al->venta_anulado_rutaCDR)){ ?>
                                                    <td><center><a type="button" target='_blank' href="<?= _SERVER_.$al->venta_anulado_rutaCDR;?>" style="color: green" ><i class="fa fa-file"></i></a></center></td>
                                                    <?php
                                                }else{ ?>
                                                    <td>--</td>
                                                    <?php
                                                }
                                                ?>
                                                <td <?= $estilo_mensaje_consulta;?>><?= $mensaje_consulta;?></td>
                                                <td>
                                                    <a target="_blank" type="button" href="<?php echo _SERVER_. 'Ventas/ver_venta/' . $al->id_venta;?>"><?= $al->venta_serie.'-'.$al->venta_correlativo;?></a>
                                                </td>
                                                <td>
                                                    <a id="btn_consultar<?= $al->id_venta_anulado;?>" type="button" title="Consultar Resumen Diario" class="btn btn-sm btn-success btne" style="color: white" onclick="preguntar('¿Está seguro que desea Consutar este Resumen Diario?','consultar_ticket_resumen_anulado', 'Si', 'No', <?= $al->id_venta_anulado;?>)"><i class="fa fa-cloud-download"></i></a>
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
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>ventas.js"></script>
<script>
    function consultar_ticket_resumen_anulado(id_resumen_diario){
        var cadena = "id_resumen_diario=" + id_resumen_diario;
        var boton = 'btn_consultar'+id_resumen_diario;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/consultar_ticket_resumen_anula",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'enviando...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i style=\"font-size: 16pt;\" class=\"fa fa-check margen\"></i>", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡CDR descargado con Éxito!', 'success');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al Consultar', 'error');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 1000);
                        break;
                    case 3:
                        respuesta('Error, Sunat rechazó el comprobante', 'error');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 1000);
                        break;
                    case 4:
                        respuesta('Error de comunicacion con Sunat', 'error');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 1000);
                        break;
                    case 5:
                        respuesta('Error al guardar en base de datos', 'error');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 1000);
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 1000);
                        break;
                }
            }

        });
    }
</script>

