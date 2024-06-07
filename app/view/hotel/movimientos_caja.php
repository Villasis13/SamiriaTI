<div class="modal fade" id="add_venta_" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 640px;">
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
                        <div class="col-lg-3">
                            <label for="tipo_pago">Tipo de pago</label>
                            <select class="form-control" type="text" id="tipo_pago" onchange="forma_pago_credito_new()">
                                <?php
                                foreach ($tipos_pago as $t){
                                    ($t->id_tipo_pago==3)?$selected_t="selected":$selected_t="";
                                    echo "<option $selected_t value='$t->id_tipo_pago'>$t->tipo_pago_nombre</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-3 no-show" id="div_tarjeta_3">
                            <label for="codigo_venta_3">Código</label>
                            <input type="text" id="codigo_venta_3" name="codigo_venta_3" class="form-control">
                        </div>
                        <div class="col-lg-3">
                            <label for="moneda">Moneda</label>
                            <select class="form-control" id="moneda">
                                <?php
                                foreach ($monedas as $m){
                                    echo "<option value='$m->id_moneda'>$m->moneda</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-3">
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
                    <div class="row no-show" id="div_cuotas">
                        <div class="col-lg-12" style="text-align: center">
                            <h4>Asignación de Cuotas</h4>
                        </div>
                        <div class="col-lg-12">
                            <div class="row" id="cuotas">

                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="">Importe</label>
                                    <input type="text" class="form-control" id="importe_cuota" onkeyup="return validar_numeros_decimales_dos(this.id)">
                                </div>
                                <div class="col-lg-4">
                                    <label for="">Fecha de Cuota</label>
                                    <?php
                                    $hoy = date('d-m-Y');
                                    ?>
                                    <input type="date" value="<?= date('Y-m-d', strtotime($hoy.'+ 1 days'))?>" class="form-control" id="fecha_cuota">
                                    <input type="hidden" value="<?= date('Y-m-d', strtotime($hoy))?>" id="hoy">
                                </div>
                                <div class="col-lg-2">
                                    <a id="btn_agregar_cuota" type="button" title="Agregar Cuota" class="btn btn-success" style="color: white; margin-top: 30px;" onclick="agregar_cuota_new()"><i class="fa fa-check margen"></i> Agregar</a>
                                </div>
                            </div>
                            <div class="row" id="total_importe_cuotas">

                            </div>
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
                        <div class="col-lg-4">
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
                        <div class="col-lg-3" style="margin-top: 32px">
                            <button onclick="agregar()" class="btn btn-primary">+</button>
                        </div>
                        <div class="col-lg-1"></div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>¿Vale Consumo?</label>
                                <select class="form-control" name="venta_piscina" id="venta_piscina" onchange="habi_campito()">
                                    <option value="0" selected>Seleccione</option>
                                    <!--Tiene que validarse jue,vier,sab de 7pm a 11:30pm, sab y dom de 12:30 pm a 5 pm-->
                                    <?php
                                    $dia_actual = strtolower(date("l"));
                                    $hora_actual = date("H:i");
                                    //$dias_permitidos = ["monday"];
                                    $dias_permitidos = ["thursday", "friday", "saturday"];
                                    $dias_permitidos_2 = ["saturday","sunday"];
                                    $hora_inicio = "19:00"; // Hora de inicio permitida
                                    $hora_fin = "23:30";   // Hora de fin permitida
                                    $hora_inicio_f = "12:30";
                                    $hora_fin_f = "17:00";
                                    // Hacer algo si es jueves, viernes o sábado
                                    //Ahora se debe validar la hora para los dias correspondientes
                                    //if ($dia_actual == "thursday" || $dia_actual == "friday" || $dia_actual == "saturday") {
                                    if (in_array($dia_actual, $dias_permitidos) && ($hora_actual >= $hora_inicio && $hora_actual <= $hora_fin)){
                                        ?>
                                        <option value="4">BARRA LIBRE</option>
                                    <?php
                                    } elseif(in_array($dia_actual, $dias_permitidos_2) && ($hora_actual >= $hora_inicio_f && $hora_actual <= $hora_fin_f)){
                                       ?>
                                        <option value="4">BARRA LIBRE</option>
                                    <?php
                                    }
                                    ?>
                                    <option value="1">PISCINA</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4" id="dc">
                            <div class="form-group">
                                <label>Vale Consumo</label>
                                <input type="text" class="form-control" id="venta_consumo_valido" name="venta_consumo_valido" onkeyup="validar_numeros_decimales_dos(this.id)">
                            </div>
                        </div>
                        <div></div>

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
                                    <div class="col-md-4">
                                        <select class="form-control" id="partir_pago_tipo" onchange="cambiar_codigo()">
                                            <?php
                                            foreach ($tipos_pago as $t){
                                                ($t->id_tipo_pago==3)?$selected_t="selected":$selected_t="";
                                                echo "<option $selected_t value='$t->id_tipo_pago'>$t->tipo_pago_nombre</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" id="partir_pago_monto" class="form-control" value="0">
                                    </div>
                                    <div class="col-md-4" id="cp1">
                                        <input type="text" id="codigo" class="form-control" value="" placeholder="INGRESE CODIGO">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <select class="form-control" id="partir_pago_tipo2" onchange="cambiar_codigo()">
                                            <?php
                                            foreach ($tipos_pago as $t){
                                                ($t->id_tipo_pago==3)?$selected_t="selected":$selected_t="";
                                                echo "<option $selected_t value='$t->id_tipo_pago'>$t->tipo_pago_nombre</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" id="partir_pago_monto2" class="form-control" value="0">
                                    </div>
                                    <div class="col-md-4" id="cp2">
                                        <input type="text" id="codigo2" class="form-control" value="" placeholder="INGRESE CODIGO">
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
                <button type="button" class="btn btn-success" id="btn-agregar-venta" onclick="guardar_venta_new()"><i class="fa fa-save fa-sm text-white-50"></i> Generar Comprobante</button>
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
    <div class="card shadow mb-2">
        <div class="card-body">
            <div class="d-sm-flex align-items-center mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <label for="desde">Desde</label>
                        <input type="date" class="form-control" value="<?= $desde; ?>" id="desde">
                    </div>
                    <div class="col-md-4">
                        <label for="hasta">Hasta</label>
                        <input type="date" class="form-control" value="<?= $hasta; ?>" id="hasta">
                    </div>
                    <div class="col-md-4 mt-2">
                        <br>
                        <button class="btn btn-primary" onclick="cambiar_fecha()"><i class="fa fa-search"></i> Buscar</button>
                    </div>

                    <div class="col-md-12 mt-3 text-center">
                        <button data-toggle="modal" data-target="#add_venta_" class="btn btn-info"><i class="fa fa-cart-plus"></i> Registrar Venta</button>


                        <button data-toggle="modal" data-target="#add_egreso_" class="btn btn-danger"><i class="fa fa-arrow-down"></i> Registrar egreso</button>

                        <button data-toggle="modal" data-target="#add_ingreso_" class="btn btn-warning"><i class="fa fa-arrow-up"></i> Registrar Ingreso</button>

                        <a href="<?= _SERVER_ ?>Hotel/movimientos_caja_detalle" target="_blank" class="btn btn-success"><i class="fa fa-eye"></i> Vista detallada</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <?php
            foreach ($cajas as $c){
                foreach ($turnos as $t){
                    $movimientos = $this->hotel->listar_movimientos($c->id_caja,$t->id_turno,$desde,$hasta);
                    $cierres = $this->hotel->listar_movimiento_cierre($c->id_caja,$t->id_turno,$desde,$hasta);
                    $buscar_usuario = $this->hotel->buscar_usuarios_caja($c->id_caja,$t->id_turno,$desde,$hasta);
                    $nombre = $buscar_usuario->persona_nombre.' '.$buscar_usuario->persona_apellido_paterno;
                    ?>
                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 text-center font-weight-bold text-primary" style="color: #6a0c0d !important;"><?= $c->caja_nombre." - TURNO ".$t->turno_nombre; ?><?= (empty($buscar_usuario)?'':' || USUARIO : '.$nombre)?></b></h6>
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
                                        <th>Obs</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(count($movimientos)>0){
                                        $a=1;$total_1_s=0;$total_1_d=0;$bb=0;$gastos=[];
                                        foreach ($categorias_gasto as $cce){
                                            $gastos[$cce->id_config_cat_egreso]=0;
                                        }
                                        foreach ($movimientos as $m){
                                            $nro_doc="-";
                                            ($m->id_moneda==1)?$total_1_s+=$m->caja_movimiento_monto:$total_1_d+=$m->caja_movimiento_monto;
                                            if($m->caja_movimiento_tipo==1){
                                                $ingreso="color: blue;";
                                                $obss =$m->caja_movimiento_observacion;
                                                if($m->id_venta==0){
                                                    $obss ="";
                                                    $nro_doc=$m->caja_movimiento_observacion;
                                                }
                                            }else{
                                                $ingreso="color:red;";
                                                $data_obs=explode("//--//",$m->caja_movimiento_observacion);
                                                $data_obs_=$this->hotel->config_cat_egreso($data_obs[0]);
                                                $obss=$data_obs_->config_cat_egreso_nombre;
                                                $nro_doc=$data_obs[1];
                                                $valor_actual = $gastos[$data_obs[0]];
                                                $gastos[$data_obs[0]] = $valor_actual + $m->caja_movimiento_monto;
                                            }
                                            $bbs="";if($m->id_moneda!=1&&$bb==0){$bbs="border-top:2px dotted gray;";$bb=1;}

                                            $detalle=$m->caja_movimiento_detalle;
                                            if($m->id_venta!=0){
                                                $data_venta = $this->ventas->listar_venta_x_id($m->id_venta);
                                                $nro_doc=$data_venta->venta_serie."-".$data_venta->venta_correlativo;
                                                $venta_detalle=$this->ventas->listar_venta_detalle_x_id_venta_pdf($m->id_venta);
                                                $detalle="";
                                                foreach ($venta_detalle as $vd){
                                                    $detalle.=$vd->producto_nombre."<br>";
                                                }
                                            }
                                            $codigo_p="";
                                            //BUSCAR CODIGO DE VENTA
                                            if(!empty($m->codigo_pago)){
                                                $codigo_p = " - ".$m->codigo_pago;
                                            }
                                            ?>
                                            <tr style="<?= $ingreso.$bbs; ?>">
                                            <td><?= $a; ?></td>
                                            <td><?= $this->validar->obtener_nombre_fecha($m->caja_movimiento_datetime,"DateTime","DateTime","es"); ?></td>
                                            <td><?= $nro_doc; ?></td>
                                            <td><?= $detalle; ?></td>
                                            <td><?= $m->simbolo." ".$m->caja_movimiento_monto; ?></td>
                                            <td><?= $m->tipo_pago_nombre; ?> <?= $codigo_p?></td>
                                            <td><?= $obss; ?></td>
                                            </tr>
                                            <?php
                                            $a++;
                                        }
                                        foreach ($tipos_pago as $tp){
                                            ($tp->id_tipo_pago==1)?$sty="style='border-top:2px solid black;'":$sty="";
                                            echo "<tr $sty><td>TOTAL</td><td>$tp->tipo_pago_nombre</td>";
                                            foreach ($monedas as $mon){
                                                $data_a = $this->nav->listar_caja_monto_por_caja_cierre_moneda_tipo_pago($m->id_caja_cierre,$mon->id_moneda,$tp->id_tipo_pago);
                                                echo "<td>$mon->moneda</td>";
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
        </div>
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 text-center font-weight-bold text-primary" style="color: #6a0c0d !important;">PRODUCTOS MÁS VENDIDOS</h6>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Suma</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $aaa=1;
                        foreach ($top_productos as $tp){
                            echo "<tr><td>$aaa</td><td>$tp->producto_nombre</td><td>$tp->cantidad</td><td>$tp->suma</td></tr>";
                            $aaa++;
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <br><div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 text-center font-weight-bold text-primary" style="color: #6a0c0d !important;">GASTOS POR CATEGORIA</h6>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Categoría</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $aaa=1;
                        for($ig=1;$ig<count($gastos);$ig++){
                            foreach ($categorias_gasto as $c){
                                if($c->id_config_cat_egreso == $ig){
                                    echo "<tr><td>$aaa</td><td>$c->config_cat_egreso_nombre</td><td>$gastos[$ig]</td></tr>";
                                }
                            }
                            $aaa++;
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <a  onclick="cambiar_fecha_excel()" class="btn btn-block btn-success text-white"><i class="fa fa-file-excel-o"></i> Exportar a Excel</a>

        </div>
    </div>
</div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script>
    function validarVenta() {
        var select = document.getElementById("venta_piscina");
        var selectedOption = select.options[select.selectedIndex].value;

        var diasPermitidos = ["jueves", "viernes", "sabado"];
        var fechaActual = new Date();
        var diaActual = fechaActual.toLocaleDateString('es-ES', { weekday: 'long' }).toLowerCase(); // Obtener el día actual en formato texto

        // Verificar si la opción seleccionada es "BARRA LIBRE" y si el día actual está en la lista permitida
        if (selectedOption === "4" && diasPermitidos.includes(diaActual)) {
            alert("Opción válida: " + select.options[select.selectedIndex].text + " hoy es " + diaActual);
            // Aquí puedes realizar otras acciones según la opción seleccionada
        } else {
            alert("Selecciona una opción válida (Barra Libre solo disponible los días Jueves, Viernes y Sábado).");
            // Puedes restablecer la selección a un valor predeterminado si lo deseas
            // select.value = "0";
        }
    }
</script>
<script>
    var contenido="";
    let contenido_cuota_new = '';
    $(document).ready(function(){
        var valor = $('#tipo_venta').val();
        selecttipoventa(valor);
        ver_productos();
        $("#producto").select2();
        $("#dc").hide();
        $("#cp1").hide();
        $("#cp2").hide();
    });

    function habi_campito(){
        let valor = $("#venta_piscina").val();
        if(valor!=0){
            $("#dc").show();
        }else{
            $("#dc").hide();
        }
    }

    //INICIO - FORMA DE PAGO
    function forma_pago_credito_new(){
        let tipo_pago = $('#tipo_pago').val();
        if(tipo_pago == 5){
            $('#div_cuotas').show();
        }else{
            $('#div_cuotas').hide();
        }

        let tipo_pago_2 = $('#tipo_pago').val();
        if(tipo_pago_2 == 1){
            $('#div_tarjeta_3').show();
        }else{
            $('#div_tarjeta_3').hide();
        }
    }

    function limpiar_cuotas_new(){
        $("#cuotas").html('');
        $("#contenido_cuota").val('');
        $("#importe_cuota").val('');
        $("#fecha_cuota").val('');
        $("#total_importe_cuotas").html('');
        contenido_cuota_new = '';
    }

    function agregar_cuota_new(){
        let valor = true;
        let importe = $('#importe_cuota').val();
        let fecha = $('#fecha_cuota').val();
        let fecha_hoy = $('#hoy').val();
        valor = validar_campo_vacio('importe_cuota', importe, valor);
        valor = validar_campo_vacio('fecha_cuota', fecha, valor);
        if(valor){
            if(importe != "" & fecha != ""){
                if(fecha_hoy < fecha) {
                    contenido_cuota_new += importe + "-.-." + fecha + "/./.";
                    $('#contenido_cuota').val(contenido_cuota_new);
                    show_new();
                    clean_cuotas_new();
                }else{
                    respuesta('La fecha debe ser mayor a Hoy','error');
                }
            }else{
                respuesta('Debe llenar todos los campos','error');
            }
        }
    }

    function show_new(){
        let llenar = "";
        let llenar_total = "";
        let conteo_c = 1;
        let total = 0;
        if (contenido_cuota_new.length > 0){
            let filas = contenido_cuota_new.split('/./.');
            if (filas.length>0){
                for(let i=0; i<filas.length - 1; i++){
                    let celdas = filas[i].split('-.-.');
                    llenar += "<div class='col-lg-1'>" +
                        "<label>Cuota 0"+conteo_c+"</label>" +
                        "       </div>" +
                        "<div class='col-lg-1'></div>" +
                        "<div class='col-lg-4'>" +
                        "<label>Importe</label>" +
                        "<input type='text' class='form-control' value = "+celdas[0]+" readonly></div>"+
                        "<div class='col-lg-4'>"+
                        "<label >Fecha de Cuota</label>"+
                        "<input type='date' class='form-control' value = "+celdas[1]+" readonly>"+
                        "</div>"+
                        "<div class='col-lg-2'>"+
                        "<a id='btn_eliminar_cuota' type='button' title='Eliminar Cuota' class='btn btn-danger' style='color: white; margin-top: 30px;' onclick='quitar_cuota("+i+")'><i class='fa fa-ban'></i> Eliminar</a>"+
                        "</div>";
                    total = total + celdas[0] * 1;
                    conteo_c++;
                }
                llenar_total = "<div class='col-lg-6'>" +
                    "<label>TOTAL IMPORTE DE CUOTAS:</label>" +
                    "       </div>" +
                    "<div class='col-lg-4'>" +
                    "<label>S/. <span id='total_cuota'>"+total.toFixed(2)+"</span></label>" +
                    "<input type='hidden' id='total_cuota_input' value='"+total.toFixed(2)+"'>";
            }
            $("#cuotas").html(llenar);
            $("#total_importe_cuotas").html(llenar_total);
        }else{
            $("#cuotas").html('');
            $("#total_importe_cuotas").html('');
        }
    }
    function clean_cuotas_new(){
        $('#importe_cuota').val('');
    }

    function cambiar_codigo(){
        let cod1 = $("#partir_pago_tipo").val();
        let cod2 = $("#partir_pago_tipo2").val();
        if(cod1==1){
            $("#cp1").show();
        }else{
            $("#cp1").hide();
        }
        if(cod2==1){
            $("#cp2").show();
        }else{
            $("#cp2").hide();
        }
    }

    function quitar_cuota_new(ind) {
        let contenido_artificio ="";
        if (contenido_cuota_new.length>0){
            let filas=contenido_cuota_new.split('/./.');
            if(filas.length>0){
                console.log(filas.length)
                for(let i=0;i<filas.length-1;i++){
                    if(i!=ind){
                        let celdas =filas[i].split('-.-.');
                        contenido_artificio += celdas[0] + "-.-."+celdas[1] + "/./.";
                    }else{
                        let celdas =filas[i].split('-.-.');
                    }
                }
            }
        }
        contenido_cuota_new = contenido_artificio;
        show_new();
    }

    //FIN - FORMA DE PAGO

    function guardar_venta_new(){
        var valor = true;
        let valor_tipo = true;
        var venta_tipo = $("#tipo_venta").val();
        var tipo_pago = $("#tipo_pago").val();
        var codigo_venta_3 = $("#codigo_venta_3").val();
        var serie = $("#serie").val();
        var correlativo= $("#numero").val();
        var caja= $("#caja").val();
        var moneda= $("#moneda").val();
        var nro_doc_boleta= $("#nro_doc_boleta").val();
        var nombre_boleta= $("#nombre_boleta").val();
        var ruc= $("#ruc").val();
        var comp_obs= $("#comp_obs").val();
        var razon_social= $("#razon_social").val();
        let textoCodificado = encodeURIComponent(razon_social);
        var domicilio= $("#domicilio").val();
        var venta_piscina= $("#venta_piscina").val();

        var venta_consumo_valido= $("#venta_consumo_valido").val();
        var boton = "btn-agregar-venta";
        //PA CODIGOS
        let codigo = $("#codigo").val();
        let codigo2 = $("#codigo2").val();
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
        valor = validar_campo_vacio('venta_piscina', venta_piscina, valor);

        if(venta_piscina == 0){
            respuesta("Debe seleccionar vale de consumo", 'error');
            valor = false;
        }
        if (contenido===""){valor=false;respuesta("Seleccione al menos un producto o servicio","error");}
        let datos = ""
        let kk = ""
        let vari = contenido.split('---//')
        let contador = 1
        vari.forEach( (el, index) => {
            console.log(kk)
            kk = el.split('...//')
            if(kk != [] && kk != "") {
                datos += kk[0] + '...//' + $('#nombre_pro_'+contador).val() + '...//' + kk[2] + '...//' + kk[3] + '---//'
                contador++;
            }
        } )
        //INICIO - VALIDAR SI LA SERIE COINCIDE CON EL TIPO DE VENTA
        let serie_text = $("select[name='serie'] option:selected").text();
        let array_serie = serie_text.split('')
        if(venta_tipo === '01'){
            if(array_serie[0] == 'F'){
                valor_tipo = true
            }else{
                valor_tipo = false
            }
        }else if(venta_tipo === '03'){
            if(array_serie[0] == 'B'){
                valor_tipo = true
            }else{
                valor_tipo = false
            }
        }
        console.log(array_serie[0])


        // SI ES BOLETA ES OBLIGATORIO EL NUM DOCUMENTO
        if(venta_tipo === '03'){
            valor = validar_campo_vacio('nro_doc_boleta', nro_doc_boleta, valor);
        }

        //FIN - VALIDAR SI LA SERIE COINCIDE CON EL TIPO DE VENTA
        if(valor_tipo){
            if(valor) {
                var cadena = "id_cliente=" + 1 +
                    "&venta_tipo=" + venta_tipo +
                    "&codigo_venta_3=" + codigo_venta_3 +
                    "&tipo_pago=" + tipo_pago +
                    "&serie=" + serie +
                    "&correlativo=" + correlativo +
                    "&moneda=" + moneda +
                    "&detalle=" + datos +
                    "&nro_doc_boleta=" + nro_doc_boleta +
                    "&nombre_boleta=" + nombre_boleta +
                    "&ruc=" + ruc +
                    "&razon_social=" + textoCodificado +
                    "&domicilio=" + domicilio +
                    "&comp_obs=" + comp_obs +
                    "&agrupar="+agrupar+
                    "&codigo="+codigo+
                    "&codigo2="+codigo2+
                    "&agrupar_des="+agrupar_des+
                    "&partir_pago="+partir_pago+
                    "&partir_pago_id="+partir_pago_id+
                    "&partir_pago_id2="+partir_pago_id2+
                    "&partir_pago_monto="+partir_pago_monto+
                    "&partir_pago_monto2="+partir_pago_monto2+
                    "&mostrar_tp="+mostrar_tp+
                    "&caja="+ caja+
                    "&venta_piscina="+venta_piscina+
                    "&venta_consumo_valido=" + venta_consumo_valido+
                    "&contenido_cuota=" + contenido_cuota_new+
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
        }else{
            respuesta('La Serie no coincide con el tipo de Comprobante', 'error');
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
                "&area=" + cat_egreso +
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
        location.href=urlweb+"Hotel/movimientos_caja/"+desde+"al"+hasta;
    }
    function cambiar_fecha_excel() {
        var desde = $("#desde").val();
        var hasta = $("#hasta").val();
        location.href=urlweb+"Hotel/movimientos_caja_excel/"+desde+"al"+hasta;
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
                                                $('#domicilio').val(data.direccion);
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
        var conteo=1;
        for(var i=0;i<conte.length - 1;i++){
            var deta = conte[i].split("...//");
            var subt = deta[2]*deta[3];
            mos += "<tr><td><input class='form-control' id='nombre_pro_"+conteo+"' name='nombre_pro_"+conteo+"' value='"+deta[1]+"'></td><td>"+deta[2]+"</td><td>"+deta[3]+"</td><td>"+subt+"</td><td><button onclick='quitar_conte("+i+")' class='btn btn-sm btn-danger'>X</button></td></tr>";
            suma+=subt;
            conteo++;
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