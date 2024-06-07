<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800"><?= $_SESSION['controlador'] . ' / ' . $_SESSION['accion'];?></h1>
            </div>
            <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <form method="post" action="<?= _SERVER_ ?>Reporte/reporte_por_producto">
                                    <input type="hidden" id="enviar_fecha" name="enviar_fecha" value="1">
                                    <div class="row">
                                        <div class="col-lg-3 col-xs-6 col-md-6 col-sm-6" style="display: none">
                                            <label for="">N° de Caja</label>
                                            <select class="form-control" id="id_caja_numero" name="id_caja_numero">
                                                <option value="">Seleccione...</option>
                                                <?php
                                                (isset($caja_))?$cajita=$caja_->id_caja_numero:$cajita=0;
                                                foreach($caja as $l){
                                                    ($l->id_caja_numero == $cajita)?$sele='selected':$sele='';
                                                    ?>
                                                    <option value="<?php echo $l->id_caja_numero;?>" <?= $sele; ?>><?php echo $l->caja_numero_nombre;?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="turno">Desde:</label>
                                            <input type="date" class="form-control" id="fecha_filtro" name="fecha_filtro" step="1" value="<?php echo $fecha_i;?>">
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="turno">Hasta:</label>
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
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="" style="border-color: black">
                                        <thead>
                                        <tr style="background-color: #ebebeb">
                                            <th style="text-align: center">PRODUCTO</th>
<!--                                            <th>FECHAS</th>-->
                                            <th>CANTIDAD VENDIDA</th>
                                            <th>CANTIDAD AUN NO COBRADA</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $ingresos_productos = 0;
                                        $sumasa = 0;
                                        $nuevo_valor_inicial = 0;
                                        $id_producto=0;
                                        $total=0;
                                        $productos_ = $this->reporte->reporte_productos_3($fecha_i,$fecha_f);
                                        foreach ($productos_ as $p){
                                            //agregar funcion que recibe el id producto, y consultas todos los que aun no han sido cobrados y esa cantidad le agregas en la siguiente columna
                                            $pedidos_sin_cancelar=$this->reporte->pedidos_sin_pagar($fecha_i,$fecha_f,$p->id_producto);
                                            ?>
                                            <tr>
                                                <td><?= $p->producto_nombre?></td>
<!--                                                <td>--><?php //echo $fecha_i?><!-- / --><?php //echo $fecha_f?><!--</td>-->
                                                <td><?= $p->total?></td>
                                                <td><?= $pedidos_sin_cancelar->total ?? 0?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="row">
                <div class="col-lg-12" style="text-align: center">
                    <a href="<?= _SERVER_ ; ?>index.php?c=Reporte&a=reporte_por_producto_pdf&fecha_filtro=<?= $_POST['fecha_filtro']?>&fecha_filtro_fin=<?= $_POST['fecha_filtro_fin']?>" target="_blank" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i> IMPRIMIR PDF</a>
                    <a id="imprimir_ticket_pro" style="color: white;" class="btn btn-primary mr-5" target="_blank" onclick="ticket_productos_2('<?= $fecha_i; ?>','<?= $fecha_f?>')"><i class="fa fa-print"></i> TICKET PRODUCTOS</a>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script>
    function ticket_productos_2(fecha_i,fecha_f){
        var boton = 'imprimir_ticket_pro';
        $.ajax({
            type: 'POST',
            url: urlweb + "api/Reporte/ticket_productos_2",
            data: "fecha_i=" + fecha_i + "&fecha_f=" + fecha_f,
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
</script>