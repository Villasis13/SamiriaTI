<div class="modal fade" id="add_venta_" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 600px;">
            <div class="modal-header">
                <h5 class="modal-title">GUARDAR VENTA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                            <div class="col-lg-5">
                                <label for="tipo_venta">Tipo Comprobante</label>
                                <select id="tipo_venta" class="form-control" onchange="selecttipoventa_(this.value)">
                                    <option value="">Seleccionar...</option>
                                    <option value="03" selected>BOLETA</option>
                                    <option value="01">FACTURA</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="serie">Serie</label>
                                <select name="serie" id="serie" class="form-control" onchange="ConsultarCorrelativo()">
                                    <option value="">Seleccionar</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="numero">Numero</label>
                                <input class="form-control" type="text" id="numero" readonly>
                            </div>
                </div>
                    <div class="row boleta">
                        <div class="col-lg-4">
                            <label for="nro_doc_boleta">Nro Documento</label>
                            <input type="text" class="form-control" id="nro_doc_boleta">
                        </div>
                        <div class="col-lg-8">
                            <label for="nombre_boleta">Nombre</label>
                            <input type="text" class="form-control" id="nombre_boleta">
                        </div>
                    </div>
                    <div class="row factura" style="display: none">
                        <div class="col-lg-4">
                            <label for="ruc">RUC</label>
                            <input type="text" class="form-control" id="ruc">
                        </div>
                        <div class="col-lg-1">
                            <br><a id="botoncito" class="text-white btn btn-primary"><i class="fa fa-search"></i></a>
                        </div>
                        <div class="col-lg-7">
                            <label for="razon_social">Razón Social</label>
                            <input type="text" class="form-control" id="razon_social">
                        </div>
                        <div class="col-lg-12">
                            <label for="domicilio">Domicilio</label>
                            <input type="text" class="form-control" id="domicilio">
                        </div>
                    </div>
                    <div class="row" style="padding-bottom: 10px;">
                        <div class="col-lg-4">
                            <label for="tipo_pago">Tipo de pago</label>
                            <select class="form-control" type="text" id="tipo_pago">
                                <?php
                                foreach ($tipos_pago as $t){
                                    ($t->id_tipo_pago==3)?$selected_t="selected":$selected_t="";
                                    echo "<option $selected_t value='$t->id_tipo_pago'>$t->tipo_pago_nombre</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label for="moneda">Moneda</label>
                            <select class="form-control" id="moneda">
                                <?php
                                foreach ($monedas as $m){
                                    echo "<option value='$m->id_moneda'>$m->moneda</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label for="caja">Caja</label>
                            <select class="form-control" id="caja">
                                <option value="">Seleccione</option>
                                <?php
                                $g_=1;
                                foreach ($data_caja as $data_caj){
                                    ($g_==1)?$g_s="selected":$g_s="";
                                    echo "<option $g_s value='$data_caj->id_caja_cierre'>$data_caj->caja_nombre</option>";
                                    $g_++;
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="border-top: 1px solid black;padding-top: 10px;">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="producto">Item</label>
                                <input type="hidden" id="id_pro">
                                <select onchange="precio_por_producto()" style="width: 100%;" class="select2 form-control" name="producto" id="producto">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($productos as $d){
                                        echo "<option value='$d->id_producto///$d->producto_precio_valor'>$d->producto_nombre</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!--<div class="col-lg-6">
                            <div class="form-group">
                                <label for="descripcion">Descripcion</label>
                                <select onchange="precio_por_producto()" class="select2 form-control" name="descripcion" id="descripcion">

                                </select>
                            </div>
                        </div>-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="precio_unit">Precio Unit</label>
                                <input onchange="calc_subtotal()" class="form-control" value="0" id="precio_unit">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="cantidad">Cantidad</label>
                                <input onchange="calc_subtotal()" class="form-control" type="number" min="1" max="100000" value="1" id="cantidad">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <br><br><button onclick="agregar()" class="btn btn-primary">+</button>
                        </div>
                        <div class="col-lg-12">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Descripcion</th>
                                    <th>Cantidad</th>
                                    <th>Pr Unit</th>
                                    <th>Subtotal</th>
                                    <th>Acción</th>
                                </tr>
                                </thead>
                                <tbody id="tr_res"></tbody>
                            </table>
                            <label for="comp_obs">Observaciones</label>
                            <textarea id="comp_obs" class="form-control"></textarea><br>
                            <input type="checkbox" onchange="partir_pago()" id="partir_pago">
                            <label for="partir_pago">Partir pago</label>
                            <div id="div_partir_pago" style="display: none">
                                <div class="row">
                                    <div class="col-md-6">
                                        <select class="form-control" id="partir_pago_tipo">
                                            <?php
                                            foreach ($tipos_pago as $t){
                                                ($t->id_tipo_pago==3)?$selected_t="selected":$selected_t="";
                                                echo "<option $selected_t value='$t->id_tipo_pago'>$t->tipo_pago_nombre</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" id="partir_pago_monto" class="form-control" value="0">
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" id="partir_pago_tipo2">
                                            <?php
                                            foreach ($tipos_pago as $t){
                                                ($t->id_tipo_pago==3)?$selected_t="selected":$selected_t="";
                                                echo "<option $selected_t value='$t->id_tipo_pago'>$t->tipo_pago_nombre</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" id="partir_pago_monto2" class="form-control" value="0">
                                    </div>
                                </div>
                            </div><br>
                            <input type="checkbox" onchange="agrupar()" id="agrupar">
                            <label for="agrupar">Cambiar descripción</label>
                            <div id="div_agrupar" style="display: none">
                                <input type="text" class="form-control" value="POR CONSUMO" id="agrupar_texto">
                            </div><br>
                            <input type="checkbox" id="mostrar_tp">
                            <label for="mostrar_tp">Mostrar Tipo de pago</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-agregar-venta" onclick="guardar_venta()"><i class="fa fa-save fa-sm text-white-50"></i> Generar Comprobante</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="add_egreso_" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">GUARDAR EGRESO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row" style="padding-bottom: 10px;">
                        <div class="col-lg-4">
                            <label for="moneda_egreso">Moneda</label>
                            <select class="form-control" id="moneda_egreso">
                                <?php
                                foreach ($monedas as $m){
                                    echo "<option value='$m->id_moneda'>$m->moneda</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-8">
                            <label for="cat_egreso">Categoría de gasto</label>
                            <select class="form-control" id="cat_egreso">
                                <?php
                                foreach ($categorias_gasto as $cce){
                                    echo "<option value='$cce->id_config_cat_egreso'>$cce->config_cat_egreso_nombre</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label for="caja_egreso">Caja</label>
                            <select class="form-control" id="caja_egreso">
                                <option value="">Seleccione</option>
                                <?php
                                $g_=1;
                                foreach ($data_caja as $data_caj){
                                    ($g_==1)?$g_s="selected":$g_s="";
                                    echo "<option $g_s value='$data_caj->id_caja_cierre'>$data_caj->caja_nombre</option>";
                                    $g_++;
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="monto_egreso">Monto</label>
                                <input type="text" class="form-control" value="0" id="monto_egreso">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="nro_doc">Nro Doc</label>
                                <input type="text" class="form-control" id="nro_doc">
                            </div>
                        </div>
                        <div class="col-lg-12">
                        <div class="form-group">
                            <label for="descripcion_egreso">Descripción</label>
                            <textarea id="descripcion_egreso" class="form-control"></textarea>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-agregar-egreso" onclick="guardar_egreso()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="add_ingreso_" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">GUARDAR INGRESO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row" style="padding-bottom: 10px;">
                        <div class="col-lg-4">
                            <label for="moneda_ingreso">Moneda</label>
                            <select class="form-control" id="moneda_ingreso">
                                <?php
                                foreach ($monedas as $m){
                                    echo "<option value='$m->id_moneda'>$m->moneda</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label for="caja_ingreso">Caja</label>
                            <select class="form-control" id="caja_ingreso">
                                <option value="">Seleccione</option>
                                <?php
                                $g_=1;
                                foreach ($data_caja as $data_caj){
                                    ($g_==1)?$g_s="selected":$g_s="";
                                    echo "<option $g_s value='$data_caj->id_caja_cierre'>$data_caj->caja_nombre</option>";
                                    $g_++;
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="nro_doc_ingreso">Nro Doc</label>
                                <input type="text" class="form-control" id="nro_doc_ingreso">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="monto_ingreso">Monto</label>
                                <input type="text" class="form-control" value="0" id="monto_ingreso">
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label for="descripcion_ingreso">Concepto</label>
                                <textarea id="descripcion_ingreso" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-agregar-ingreso" onclick="guardar_ingreso()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center mb-4">
        <div class="col-md-3">
            <label for="desde">Desde</label>
            <input type="date" class="form-control" value="<?= $desde; ?>" id="desde">
        </div>
        <div class="col-md-3">
            <label for="hasta">Hasta</label>
            <input type="date" class="form-control" value="<?= $hasta; ?>" id="hasta">
        </div>
        <div class="col-md-1">
            <br><button class="btn btn-primary" style="background: #6a0c0d" onclick="cambiar_fecha()"><i class="fa fa-search"></i> Buscar</button>
        </div>
        <div class="col-md-1">
            <button data-toggle="modal" data-target="#add_venta_" class="btn btn-primary"><i class="fa fa-cart-plus"></i> Registrar Venta</button>
        </div>
        <div class="col-md-1">
            <button data-toggle="modal" data-target="#add_egreso_" class="btn btn-danger"><i class="fa fa-arrow-down"></i> Registrar egreso</button>
        </div>
        <div class="col-md-1">
            <button data-toggle="modal" data-target="#add_ingreso_" class="btn btn-warning"><i class="fa fa-arrow-up"></i> Registrar Ingreso</button>
        </div>
        <div class="col-md-1">
            <a href="<?= _SERVER_ ?>Hotel/movimientos_caja" target="_blank" class="btn btn-primary"><i class="fa fa-check"></i> Vista general</a>
        </div>
    </div>
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
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th rowspan="2">Fecha</th>
                                        <th rowspan="2">Nro Doc</th>
                                        <th rowspan="2">Detalle</th>
                                        <th colspan="4" class="text-center">Efectivo</th>
                                        <th rowspan="2">Tarjeta</th>
                                        <th rowspan="2">Depósito</th>
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
                                                    }
                                                    else{
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
<script>
    var contenido="";
    $(document).ready(function(){
        var valor = $('#tipo_venta').val();
        selecttipoventa(valor);
        ver_productos();
        $("#producto").select2();
    });
    function guardar_venta(){
        var valor = true;
        var venta_tipo = $("#tipo_venta").val();
        var tipo_pago = $("#tipo_pago").val();
        var serie = $("#serie").val();
        var correlativo= $("#numero").val();
        var caja= $("#caja").val();
        var moneda= $("#moneda").val();
        var nro_doc_boleta= $("#nro_doc_boleta").val();
        var nombre_boleta= $("#nombre_boleta").val();
        var ruc= $("#ruc").val();
        var comp_obs= $("#comp_obs").val();
        var razon_social= $("#razon_social").val();
        var domicilio= $("#domicilio").val();
        var boton = "btn-agregar-venta";
        var agrupar = 0;
        var mostrar_tp = 0;
        var agrupar_des="";
        if($("#agrupar").is(':checked')){
            agrupar=1;
            agrupar_des=$("#agrupar_texto").val();
        }
        if($("#mostrar_tp").is(':checked')){
            mostrar_tp=1;
        }
        var partir_pago = 0;
        var partir_pago_id="";
        var partir_pago_monto="";
        var partir_pago_id2="";
        var partir_pago_monto2="";
        if($("#partir_pago").is(':checked')){
            partir_pago=1;
            var totalito=$("#totalito").html();
            partir_pago_id=$("#partir_pago_tipo").val();
            partir_pago_id2=$("#partir_pago_tipo2").val();
            partir_pago_monto=$("#partir_pago_monto").val();
            partir_pago_monto2=$("#partir_pago_monto2").val();
            if(partir_pago_monto * 1 + partir_pago_monto2 * 1 != totalito){
                valor=false;
                respuesta("Suma incorrecta","error");
            }
        }
        valor = validar_campo_vacio('caja', caja, valor);
        if (contenido===""){valor=false;respuesta("Seleccione al menos un producto o servicio","error");}
        if(valor) {
            var cadena = "id_cliente=" + 1 +
                "&venta_tipo=" + venta_tipo +
                "&tipo_pago=" + tipo_pago +
                "&serie=" + serie +
                "&correlativo=" + correlativo +
                "&moneda=" + moneda +
                "&detalle=" + contenido +
                "&nro_doc_boleta=" + nro_doc_boleta +
                "&nombre_boleta=" + nombre_boleta +
                "&ruc=" + ruc +
                "&razon_social=" + razon_social +
                "&domicilio=" + domicilio +
                "&comp_obs=" + comp_obs +
                "&agrupar="+agrupar+
                "&agrupar_des="+agrupar_des+
                "&partir_pago="+partir_pago+
                "&partir_pago_id="+partir_pago_id+
                "&partir_pago_id2="+partir_pago_id2+
                "&partir_pago_monto="+partir_pago_monto+
                "&partir_pago_monto2="+partir_pago_monto2+
                "&mostrar_tp="+mostrar_tp+
                "&caja=" + caja +
                "&id_turno=1";
            $.ajax({
                type: "POST",
                url: urlweb + "api/Ventas/guardar_venta_ingreso",
                data: cadena,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, 'Guardando...', true);
                },
                success: function (r) {
                    cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                    switch (r.result.code) {
                        case 1:
                            window.open(urlweb+r.result.ruta, "_blank");
                            location.reload();
                            break;
                        case 2:
                            respuesta('Error al guardar', 'error');
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        }
    }
    function guardar_egreso(){
        var valor = true;
        var moneda_egreso = $("#moneda_egreso").val();
        var caja_egreso = $("#caja_egreso").val();
        var monto_egreso = $("#monto_egreso").val();
        var cat_egreso = $("#cat_egreso").val();
        var nro_doc = $("#nro_doc").val();
        var descripcion_egreso= $("#descripcion_egreso").val();
        var boton = "btn-agregar-egreso";
        valor = validar_campo_vacio('monto_egreso', monto_egreso, valor);
        valor = validar_campo_vacio('descripcion_egreso', descripcion_egreso, valor);
        valor = validar_campo_vacio('cat_egreso', cat_egreso, valor);
        valor = validar_campo_vacio('nro_doc', nro_doc, valor);
        if(valor) {
            var cadena = "moneda_egreso=" + moneda_egreso +
                "&caja_egreso=" + caja_egreso +
                "&monto_egreso=" + monto_egreso +
                "&obs=" + cat_egreso +"//--//"+nro_doc+
                "&descripcion_egreso=" + descripcion_egreso;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Ventas/guardar_egreso",
                data: cadena,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, 'Guardando...', true);
                },
                success: function (r) {
                    cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                    switch (r.result.code) {
                        case 1:
                            location.reload();
                            break;
                        case 2:
                            respuesta('Error al guardar', 'error');
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        }
    }
    function guardar_ingreso(){
        var valor = true;
        var moneda_ingreso = $("#moneda_ingreso").val();
        var caja_ingreso = $("#caja_ingreso").val();
        var monto_ingreso = $("#monto_ingreso").val();
        var nro_doc_ingreso = $("#nro_doc_ingreso").val();
        var descripcion_ingreso= $("#descripcion_ingreso").val();
        var boton = "btn-agregar-ingreso";
        valor = validar_campo_vacio('moneda_ingreso', moneda_ingreso, valor);
        valor = validar_campo_vacio('caja_ingreso', caja_ingreso, valor);
        valor = validar_campo_vacio('monto_ingreso', monto_ingreso, valor);
        valor = validar_campo_vacio('nro_doc_ingreso', nro_doc_ingreso, valor);
        valor = validar_campo_vacio('descripcion_ingreso', descripcion_ingreso, valor);
        if(valor) {
            var cadena = "moneda_ingreso=" + moneda_ingreso +
                "&caja_ingreso=" + caja_ingreso +
                "&monto_ingreso=" + monto_ingreso +
                "&obs=" + nro_doc_ingreso+
                "&descripcion_ingreso=" + descripcion_ingreso;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Ventas/guardar_ingreso",
                data: cadena,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, 'Guardando...', true);
                },
                success: function (r) {
                    cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                    switch (r.result.code) {
                        case 1:
                            location.reload();
                            break;
                        case 2:
                            respuesta('Error al guardar', 'error');
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        }
    }
    function cambiar_fecha() {
        var desde = $("#desde").val();
        var hasta = $("#hasta").val();
        location.href=urlweb+"Hotel/movimientos_caja_detalle/"+desde+"al"+hasta;
    }
    function cambiar_fecha_excel() {
        var desde = $("#desde").val();
        var hasta = $("#hasta").val();
        location.href=urlweb+"Hotel/movimientos_caja_excel_det/"+desde+"al"+hasta;
    }
    function selecttipoventa_(valor){
        selecttipoventa(valor);
        if (valor == "07" || valor == "08"){
            $('#credito_debito').show();

            if(valor == "07"){
                $('#notaCredito').show();
                $('#notaDebito').hide();
            }else{
                $('#notaCredito').hide();
                $('#notaDebito').show();
            }
            var tipo_comprobante =  valor;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Ventas/tipo_nota_descripcion",
                data: "tipo_comprobante="+tipo_comprobante,
                dataType: 'json',
                success:function (r) {
                    $("#nota_descripcion").html(r);
                }
            });
        } else{
            $('#credito_debito').hide();
            if(valor == "01"){
                $('#select_tipodocumento').val(4);
                $('#client_number').val('');
                $('#client_name').val('');
                $('.factura').show();
                $('.boleta').hide();
            }else{
                $('#select_tipodocumento').val(2);
                $('#client_number').val('11111111');
                $('#client_name').val('PÚBLICO EN GENERAL');
                $('.factura').hide();
                $('.boleta').show();
            }

        }
    }
    function selecttipoventa(valor){
        Consultar_serie();
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/tipo_nota_descripcion",
            data: "tipo_comprobante="+valor,
            dataType: 'json',
            success:function (r) {
                $("#nota_descripcion").html(r);
            }
        });
    }
    function Consultar_serie(){
        //var tipo_documento_modificar = $('#Tipo_documento_modificar').val();
        var tipo_venta =  $("#tipo_venta").val();
        var concepto = "LISTAR_SERIE";
        var cadena = "tipo_venta=" + tipo_venta +
            "&concepto=" + concepto;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/consultar_serie",
            data: cadena,
            dataType: 'json',
            success:function (r) {
                var series = "";
                //var series = "<option value='' selected>Seleccione</option>";
                for (var i=0; i<r.serie.length; i++){
                    series += "<option value='"+r.serie[i].id_serie+"'>"+r.serie[i].serie+"</option>"
                }
                $("#serie").html(series);
                ConsultarCorrelativo();
            }

        });
    }
    function ConsultarCorrelativo(){
        var id_serie =  $("#serie").val();
        var tipo_venta =  $("#tipo_venta").val();
        var concepto = "LISTAR_NUMERO";
        var cadena = "id_serie=" + id_serie +
            "&tipo_venta=" + tipo_venta+
            "&concepto=" + concepto;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/consultar_serie",
            data: cadena,
            dataType: 'json',
            success:function (r) {
                $("#numero").val(r.correlativo);
            }

        });
    }
    function ver_productos() {
        var tipo = $("#tipo").val();
        if(tipo!=""){
            $.ajax({
                type: "POST",
                url: urlweb + "api/Hotel/ver_productos",
                data: "tipo="+tipo,
                dataType: 'json',
                success:function (r) {
                    $("#descripcion").html(r);
                }
            });
        }
    }
    $(function(){
        $('#botoncito').on('click', function(){
            var ruc = $('#ruc').val();
            if(ruc.length==11){
                if(!isNaN(ruc)){
                    var entrar_api=true;
                    $.ajax({
                        type: "POST",
                        url: urlweb + "api/Hotel/buscar_cliente",
                        data: "ruc="+ruc,
                        dataType: 'json',
                        success:function (r) {
                            if (r.result.code == 1) {
                                entrar_api=false;
                                $('#razon_social').val(r.result.razon_social);
                                $('#domicilio').val(r.result.domicilio);
                                respuesta("Datos encontrados","success");
                            }else{
                                if(entrar_api){
                                    var formData = new FormData();
                                    formData.append("token", "uTZu2aTvMPpqWFuzKATPRWNujUUe7Re1scFlRsTy9Q15k1sjdJVAc9WGy57m");
                                    formData.append("ruc", ruc);
                                    var request = new XMLHttpRequest();
                                    request.open("POST", "https://api.migo.pe/api/v1/ruc");
                                    request.setRequestHeader("Accept", "application/json");
                                    request.send(formData);
                                    respuesta("Cargando","warning");
                                    request.onload = function() {
                                        var data = JSON.parse(this.response);
                                        if(data.success){
                                            respuesta("Datos encontrados","success");
                                            if(data.condicion_de_domicilio=="NO HABIDO"){
                                                alert("Este ruc se encuentra como NO HABIDO.");
                                            }else{
                                                $('#razon_social').val(data.nombre_o_razon_social);
                                                $('#domicilio').val(data.domicilio);
                                            }
                                        }else{
                                            respuesta(data.message,"error");
                                        }
                                    };
                                }
                            }
                        }
                    });
                }else{
                    respuesta("El ruc debe contener solo números.","error");

                }
            }else{
                respuesta("El ruc debe contener 11 dígitos.","error");

            }
        });
    });
    function precio_por_producto() {
        var des = $("#producto").val();
        var spl = des.split("///");
        var subto = spl[1] * $("#cantidad").val();
        $("#id_pro").val(spl[0]);
        $("#precio_unit").val(spl[1]);
        $("#subtotal").val(subto);
    }
    function calc_subtotal() {
        var subto = $("#cantidad").val() * $("#precio_unit").val();
        $("#subtotal").val(subto);
    }
    function agregar() {
        var id_pro = $("#id_pro").val();
        var producto = $("#producto option:selected").html();
        var precio_unit = $("#precio_unit").val();
        var cantidad = $("#cantidad").val();
        if(id_pro=="" || cantidad<=0){
            respuesta("Seleccione un producto y cantidad correcta","error");
        }else{
            contenido+=id_pro+"...//"+producto+"...//"+precio_unit+"...//"+cantidad+"---//";
            mostrar_contenido();
        }
    }
    function mostrar_contenido() {
        var conte = contenido.split("---//");
        var mos ="";
        var suma=0;
        for(var i=0;i<conte.length - 1;i++){
            var deta = conte[i].split("...//");
            var subt = deta[2]*deta[3];
            mos += "<tr><td>"+deta[1]+"</td><td>"+deta[2]+"</td><td>"+deta[3]+"</td><td>"+subt+"</td><td><button onclick='quitar_conte("+i+")' class='btn btn-sm btn-danger'>X</button></td></tr>";
            suma+=subt;
        }
        mos+="<tr><td colspan=\"3\" class=\"text-center\" style=\"font-size: 18pt;font-weight: bold\">TOTAL</td><td id='totalito' style=\"font-size: 18pt;font-weight: bold\">"+suma+"</td></tr>";
        $("#tr_res").html(mos);
    }
    function quitar_conte(id) {
    	var majo = "";
        var conte = contenido.split("---//");
        var mos ="";
        var suma=0;
        for(var i=0;i<conte.length - 1;i++){
            var deta = conte[i].split("...//");
            var subt = deta[2]*deta[3];
            if(id!=i){
            	majo+=deta[0]+"...//"+deta[1]+"...//"+deta[2]+"...//"+deta[3]+"---//";
            }
        }
        contenido=majo;
        mostrar_contenido();
    }
    function agrupar() {
        if($("#agrupar").is(':checked')){
            $("#div_agrupar").show();
        }else{
            $("#div_agrupar").hide();
        }
    }
    function partir_pago() {
        if($("#partir_pago").is(':checked')){
            $("#div_partir_pago").show();
        }else{
            $("#div_partir_pago").hide();
        }
    }
</script>