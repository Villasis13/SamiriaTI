<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">AGREGAR A HABITACION <?= $data_rack->habitacion_nro; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="producto">Item</label>
                                <input type="hidden" id="id_pro">
                                <select onchange="precio_por_producto()" style="width: 100%;" class="select2 form-control" name="producto" id="producto">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($productos as $d){
                                        echo "<option value='$d->id_producto///$d->producto_precio_valor'>$d->producto_nombre ($d->categoria_nombre)</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="precio_unit">Precio Unit</label>
                                <input onchange="calc_subtotal()" class="form-control" value="0" id="precio_unit">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="cantidad">Cantidad</label>
                                <input onchange="calc_subtotal()" class="form-control" type="number" min="1" max="100000" value="1" id="cantidad">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="moneda_">Moneda</label>
                                <input readonly class="form-control" value="<?= $data_rack->moneda; ?>" type="text" id="moneda_">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="subtotal">Subtotal</label>
                                <input readonly class="form-control" type="text" id="subtotal">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-agregar-detalle" onclick="guardar_detalle()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="add_venta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title">GENERAR COMPROBANTE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="tipo_venta">T. Comprobante</label>
                                <select id="tipo_venta" class="form-control" onchange = "selecttipoventa_(this.value)">
                                    <option value="">Seleccionar</option>
                                    <option value="03" selected>BOLETA</option>
                                    <option value="01">FACTURA</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="serie">Serie</label>
                                <select name="serie" id="serie" class="form-control" onchange="ConsultarCorrelativo()">
                                    <option value="">Seleccionar</option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label for="numero">N° Serie</label>
                                <input class="form-control" type="text" id="numero" readonly>
                            </div>
                            <div class="col-lg-3">
                                <label for="caja">Caja</label>
                                <select class="form-control" id="caja">
                                    <?php
                                    foreach ($data_caja as $data_caj){
                                        echo "<option value='$data_caj->id_caja_cierre'>$data_caj->caja_nombre</option>";
                                    }
                                    ?>
                                </select>
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
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="tipo_pago">Tipo de pago</label>
                                <select class="form-control" type="text" id="tipo_pago" onchange="forma_pago_credito()">
                                    <?php
                                    foreach ($tipos_pago as $t){
                                        ($t->id_tipo_pago==3)?$selected_t="selected":$selected_t="";
                                        echo "<option $selected_t value='$t->id_tipo_pago'>$t->tipo_pago_nombre</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-3 no-show" id="div_tarjeta">
                                <label for="codigo_venta">Código</label>
                                <input type="text" id="codigo_venta" name="codigo_venta" class="form-control">
                            </div>
                            <!--AQUI SE TIENE QUE HACER UN SELECT DE LAS MONDEDAS PARA PODER CAMBIAR Y ASI HACER LA OPERACION PARA LOS DOLARES PERO SERA MAÑANA YA-->
                            <div class="col-lg-3">
                                <label for="moneda">Moneda</label>
                                <select class="form-control" name="moneda" id="moneda" onchange="calcular(this.value)">
                                        <option <?= ($data_rack->id_moneda==1)?'selected':'' ?> value="1">SOLES </option>
                                        <option <?= ($data_rack->id_moneda==2)?'selected':'' ?> value="2">DOLARES </option>
                                </select>

                                <!--<input type="text" class="form-control" readonly id="moneda" value="<?= $data_rack->moneda; ?>">-->
                            </div>
                            <div class="col-lg-2" id="tcambio">
                                <label>T. Cambio</label>
                                <input type="text" class="form-control" value="<?= $data_caja_->tipo_cambio?>" name="tipo_cambio" id="tipo_cambio" onkeyup="validar_numeros_decimales_dos(this.id);convertir_d()">
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
                                        <a id="btn_agregar_cuota" type="button" title="Agregar Cuota" class="btn btn-success" style="color: white; margin-top: 30px;" onclick="agregar_cuota()"><i class="fa fa-check margen"></i> Agregar</a>
                                    </div>
                                </div>
                                <div class="row" id="total_importe_cuotas">

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Descripcion</th>
                                        <th>Cantidad</th>
                                        <th>Pr Unit</th>
                                        <th>Subtotal</th>
                                    </tr>
                                    </thead>
                                    <tbody id="generar_fact_tbody"></tbody>
                                </table>
                            </div>
                            <div class="col-lg-12 text-right">
                                <h4 id="aqui_va_suma" style="color: var(--color-logo)"></h4>
                                <input type="hidden" name="totalito" id="totalito">
                            </div>
                        </div>
                        <div class="col-lg-12">
                        <label for="comp_obs">Observaciones</label>
                        <textarea id="comp_obs" class="form-control"><?= $data_rack->cliente_nombre." - HAB ".$data_rack->habitacion_nro; ?></textarea><br>
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
                                <div class="col-md-4" id="c1">
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
                                <div class="col-md-4" id="c2">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-agregar-venta" onclick="desabilitarBoton()" style="display: none"><i class="fa fa-save fa-sm text-white-50"></i> Generar Comprobante</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="eliminar_detalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ELIMINAR DETALLE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="eliminar_clave">Ingrese contraseña</label>
                            <input type="password" class="form-control" id="eliminar_clave">
                            <input type="hidden" id="eliminar_id">
                        </div>
                        <div class="col-lg-12">
                            <label for="eliminar_motivo">Motivo de eliminación</label>
                            <textarea class="form-control" id="eliminar_motivo"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="btn_eliminar_detalle" onclick="preguntar('¿Está seguro que desea eliminar este registro?','eliminar_detalle_','Si','No')"><i class="fa fa-trash fa-sm text-white-50"></i> Eliminar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="add_checkout" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 40%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">AUMENTAR ESTADÍA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4">
                        <?php
                        $fecha_ = strtotime($data_rack->rack_out);
                        $fecha_+=86400;
                        $new_fecha = date('Y-m-d',$fecha_);
                        ?>
                        <input type="hidden" id="fecha_para_calculo" value="<?= $data_rack->rack_out;?>">
                        <label for="new_checkout">Nueva fecha de salida</label>
                        <input type="date" class="form-control" id="new_checkout" onchange="validar_fecha_new()" value="<?= $new_fecha ?>" min="<?= $new_fecha ?>">
                    </div>
                    <div class="col-lg-4">
                        <label>N° Noches</label>
                        <input type="text" class="form-control" id="noches_new" name="noches_new" readonly>
                    </div>
                    <div class="col-lg-4">
                        <label for="new_precio">Precio Unit</label>
                        <input type="text" class="form-control" id="new_precio" value="0.00">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-agregar-new-checkout" onclick="guardar_add_checkout()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar cambios</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cambiohab" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">CAMBIAR HABITACIÓN</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label for="new_hab">Seleccione Habitación</label>
                        <select class="form-control" id="new_hab">
                            <?php
                            $ini = strtotime($data_rack->rack_in);
                            $fifi = strtotime($data_rack->rack_out);
                            foreach ($habs_dis as $hd){
                                $puede=true;
                                for($ij = $ini;$ij<=$fifi;$ij+=86400){
                                    $data_puede = $this->hotel->habitacion_por_fecha(date('Y-m-d',$ij),$hd->id_habitacion);
                                    if(!isset($data_puede->id_habitacion)){
                                        $puede=false;
                                    }
                                }
                                if($puede) {
                                    echo "<option value='$hd->id_habitacion'>$hd->habitacion_nro - $hd->habitacion_tipo_nombre</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-agregar-cambiohab" onclick="guardar_cambiohab()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar cambios</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editar_cliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">EDITAR CLIENTE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="cliente_tipo_doc">Tipo Doc</label>
                                <select class="form-control" id="cliente_tipo_doc" name="cliente_tipo_doc">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($tipo_doc as $td) {
                                        ($td->id_tipodocumento==$data_cliente->id_tipodocumento)?$selected="selected":$selected="";
                                        echo "<option $selected value='$td->id_tipodocumento'>$td->tipodocumento_identidad</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="cliente_numero">Nro Doc</label>
                                <input class="form-control" type="text" id="cliente_numero" maxlength="12" value="<?= $data_cliente->cliente_numero; ?>">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="cliente_nombre">Nombre</label>
                                <input type="text" class="form-control" id="cliente_nombre" value="<?= ($data_cliente->id_tipodocumento==2)?$data_cliente->cliente_nombre:$data_cliente->cliente_razonsocial; ?>">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="cliente_direccion">Dirección</label>
                                <input type="text" class="form-control" id="cliente_direccion" value="<?= $data_cliente->cliente_direccion ?>">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for="cliente_telefono">Teléfono</label>
                                <input type="text" class="form-control" id="cliente_telefono" value="<?= $data_cliente->cliente_telefono ?>">
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-group">
                                <label for="cliente_correo">Correo</label>
                                <input type="email" class="form-control" id="cliente_correo" value="<?= $data_cliente->cliente_correo ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-editar-cliente" onclick="guardar_cliente()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cargar_cuenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 60% !important;">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title">CARGAR A CUENTA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <label>Cliente</label>
                            <select class="form-control" name="id_cliente_cargo" id="id_cliente_cargo" style="width: 100%">
                                <?php
                                foreach ($clientes_cuenta as $h){?>
                                    <option value="<?=$h->id_cliente?>"><?=$h->cliente_razonsocial?> <?=$h->cliente_nombre?> || <?= $h->cliente_numero?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-lg-12">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Descripcion</th>
                                    <th>Cantidad</th>
                                    <th>Pr Unit</th>
                                    <th>Subtotal</th>
                                </tr>
                                </thead>
                                <tbody id="generar_fact_tbody_cargo"></tbody>
                            </table>
                        </div>
                        <div class="col-lg-12 text-right">
                            <h4 id="aqui_va_suma_cargo" style="color: var(--color-logo)"></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-agregar-venta_cargo" onclick="cargar_datos_cuenta()"><i class="fa fa-save fa-sm text-white-50"></i> Cargar a Cuenta</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editar_precio" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Precio de la Habitación <?= $data_rack->habitacion_nro; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="precio_unit">Precio Anterior</label>
                                <input class="form-control" id="precio_anterior" name="precio_anterior" readonly>
                                <input type="hidden" id="id_rack_detalle_editar" name="id_rack_detalle_editar">
                                <input type="hidden" id="cantidad_editada" name="cantidad_editada">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="cantidad">Precio Actual</label>
                                <input class="form-control" type="text" id="precio_actual" name="precio_actual" onkeyup="validar_numeros_decimales_dos(this.id)">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Contraseña</label>
                                <input type="password" id="contra_editar" name="contra_editar" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-editar_precio" onclick="preguntar('¿Está seguro que desea editar el precio?','guardar_precio_editado','Si','No')"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cargar_comple" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 60% !important;">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Descripcion</th>
                                    <th>Cantidad</th>
                                    <th>Pr Unit</th>
                                    <th>Subtotal</th>
                                </tr>
                                </thead>
                                <tbody id="generar_fact_tbody_comple"></tbody>
                            </table>
                        </div>
                        <div class="col-lg-12 text-right">
                            <h4 id="aqui_va_suma_comple" style="color: var(--color-logo)"></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btn_agregar_venta_comple" onclick="cargar_datos_comple()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-5">
            <div class="business-card middle">
                <div class="front">
                    <h5><?= ($cliente->id_tipodocumento==4)?$cliente->cliente_razonsocial:$cliente->cliente_nombre; ?></h5>
                    <span>HABITACION <?= $data_rack->habitacion_nro; ?></span>
                    <div class="row">
                        <div class="col-lg-6">
                            <ul class="contact-info">
                                <li>
                                    <i class="fa fa-address-card"></i> <?= $cliente->tipo_documento_abr.": ".$cliente->cliente_numero; ?>
                                </li>

                                <li>
                                    <i class="fa fa-calendar"></i> Desde: <?= $this->validar->obtener_nombre_fecha($data_rack->rack_in,"Date","Date","es"); ?>
                                </li>
                                <li>
                                    <i class="fa fa-moon-o"></i> Noches: <?= $data_rack->rack_noches; ?>
                                </li>

                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <ul class="contact-info" >
                                <li>
                                    <i class="fa fa-bed"></i>Tipo: <?= $data_rack->habitacion_tipo_nombre; ?>
                                </li>
                                <li>
                                    <i class="fa fa-calendar"></i> Hasta : <?= $this->validar->obtener_nombre_fecha($data_rack->rack_out,"Date","Date","es"); ?>
                                </li>
                                <li>
                                    <i class="fa fa-briefcase"></i> Desayuno: <?= $data_rack->rack_noches; ?>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-12">
                            <ul class="contact-info">
                                <li>
                                    <i class="fa fa-eye"></i> Observaciones:
                                </li>
                                <li>
                                    <?= $data_rack->rack_observaciones; ?>
                                </li>
                                <li>
                                    Agencia: <?= $data_rack->rack_agencia; ?>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <div class="snack">
                                <img src="<?= _SERVER_ ?>media/logo/logo_empresa.jpeg" style="width: inherit">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="barcode">
                                <div class="barcode__scan"></div>
                                <div class="barcode__id"><?= $fecha; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="col-md-5">
            <div class="main-content">
                <div class="ticket">
                    <div class="ticket__main">
                        <div class="header">HABITACION <?/*= $data_rack->habitacion_nro; */?></div>
                        <div class="info passenger">
                            <div class="info__item">Huésped</div>
                            <div class="info__detail"><?/*= ($cliente->id_tipodocumento==4)?$cliente->cliente_razonsocial:$cliente->cliente_nombre; */?></div>
                        </div>
                        <div class="info departure">
                            <div class="info__item">Documento</div>
                            <div class="info__detail"><?/*= $cliente->tipo_documento_abr.": ".$cliente->cliente_numero; */?></div>
                        </div>
                        <div class="info arrival">
                            <div class="info__item">Tipo de Habitación</div>
                            <div class="info__detail"><?/*= $data_rack->habitacion_tipo_nombre; */?></div>
                        </div>
                        <div class="info date">
                            <div class="info__item">Desde</div>
                            <div class="info__detail"><?/*= $this->validar->obtener_nombre_fecha($data_rack->rack_in,"Date","Date","es"); */?></div>
                        </div>
                        <div class="info time">
                            <div class="info__item">Hasta</div>
                            <div class="info__detail"><?/*= $this->validar->obtener_nombre_fecha($data_rack->rack_out,"Date","Date","es"); */?></div>
                        </div>
                        <div class="info carriage">
                            <div class="info__item">Noches</div>
                            <div class="info__detail"><?/*= $data_rack->rack_noches; */?></div>
                        </div>
                        <div class="info seat">
                            <div class="info__item">Desayunos</div>
                            <div class="info__detail"><?/*= $data_rack->rack_desayuno; */?></div>
                        </div>
                        <div class="fineprint">
                            <?php
/*                            if(count($extras)>0){
                                echo "<p>Personas extras:</p>";
                                foreach ($extras as $extra){
                                    echo "<p>- $extra->extra_nombre</p>";
                                }
                            }
                            */?>
                            <p>Observaciones: <?/*= $data_rack->rack_observaciones; */?></p>
                            <p>Agencia: <?/*= $data_rack->rack_agencia; */?></p>
                        </div>
                        <div class="barcode">
                                <div class="barcode__scan"></div>
                            <div class="barcode__id"><?/*= $fecha; */?></div>
                        </div>
                        <div class="snack">
                            <img src="<?/*= _SERVER_ */?>media/logo/logo_empresa.jpeg" style="width: inherit">
                        </div>
                    </div>
                </div>
            </div><br><br>
            <div class="row">
                <?php
/*                if($data_rack->rack_checkout == null){
                    */?>
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                        <button id="btn-checkout" class="btn btn-primary" onclick="preguntar('¿Está seguro que desea hacer el checkout?','guardar_checkout','Si','No',0)"><i class="fa fa-check-square"></i><br>Registrar CheckOut</button>
                    </div>
                    <div class="col-md-2">
                        <button id="btn-add-checkout" class="btn btn-warning" data-toggle="modal" data-target="#add_checkout"><i class="fa fa-plus"></i><br> Aumentar estadía</button>
                    </div>
                    <div class="col-md-2">
                        <button id="btn-add-cambiohab" style="background: seagreen" class="btn btn-warning" data-toggle="modal" data-target="#cambiohab"><i class="fa fa-chain"></i><br> Cambiar Habitación</button>
                    </div>
                    <div class="col-md-2">
                        <button id="btn-edit-cliente" style="background: #f9d99a;color: black" class="btn btn-warning" data-toggle="modal" data-target="#editar_cliente"><i class="fa fa-pencil"></i><br> Editar Cliente</button>
                    </div>
                    <div class="col-md-2">
                        <button id="btn-cargar" style="background: #e38279;color: black" class="btn btn-warning" data-toggle="modal" onclick="llenar_id_cargo_c();get_fact()" data-target="#cargar_cuenta"><i class="fa fa-folder"></i><br> Cargar a Cuenta</button>
                    </div>
                    <?php
/*                }else{
                    echo "<span style='color: blue'>Check out realizado el $data_rack->rack_checkout</span>";
                }
                */?>
            </div>
            <hr>
            <?php
/*            $fecha_actual = date('Y-m-d');
            $hora_actual = date('H:i:s');
            $hora_limite_out = '13:00:00';
            $early_fecha = date('Y-m-d',strtotime($buscar_->rack_detalle_datetime));
            $early_hora = date('H:i:s',strtotime($buscar_->rack_detalle_datetime));
            $hora_limite_in = '13:00:00';
            if($data_rack->rack_out == $fecha_actual && $hora_actual > $hora_limite_out){
                */?>
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h4 style="color: red;"> * No olvides revisar si es necesario la cobranza de Late Checkout</h4>
                    </div>
                </div>
            <?php
/*            }
            if($fecha_actual == $early_fecha && $early_hora < $hora_limite_in){
                */?>
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h4 style="color: red;"> * No olvides revisar si es necesario la cobranza de Early Check in</h4>
                    </div>
                </div>
            <?php
/*            }
            */?>
        </div>-->
        <div class="col-md-7">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 text-left font-weight-bold text-color-base" >
                        Detalle del consumo
                        <a type="button" target="_blank" id="imprimir" style="float: right;" href="<?= _SERVER_ ?>index.php?c=Hotel&a=ticket_detalle&id_h=<?= $id?>&fecha=<?= $fecha?>" class="btn btn-danger mr-1"><i class="fa fa-file-pdf-o"></i> Impimir</a>
                        <button style="float: right;" data-toggle="modal" data-target="#add" class="btn btn-warning mr-1"><i class="fa fa-plus-square"></i> Agregar</button></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-striped" style="font-size: small">
                                <thead>
                                <tr>
                                    <th>Fact</th>
                                    <th>Fecha</th>
                                    <th>Descripcion</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unit</th>
                                    <th>Subtotal</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $total=0;
                                $total_p=0;
                                $fact = false;
                                $fact_boton = false;
                                $contador=0;
                                $fechaaaaaa = date('Y-m-d');
                                foreach ($detalle_rack as $d) {
                                    $eli = "-";
                                    $edi = "-";
                                    if($d->rack_detalle_estado_fact==0){
                                        if($d->rack_detalle_envio==1){
                                            $fact=false;
                                            $fact_boton = false;
                                            $a = "-";
                                            $estado_fact = "<span style=\"color: red;\"><i style='color: red;' class='fa fa-exclamation-triangle'></i> Sin facturar y Agregado a Cuenta</span>";
                                        }else{
                                            if($d->rack_detalle_estado_venta==2){
                                                $a = "-";
                                                $estado_fact = "<span style=\"color: red;\"><i style='color: red;' class='fa fa-exclamation-triangle'></i> Auspicio</span>";
                                            }else{
                                                $fact=true;
                                                $estado_fact = "<span style=\"color: red;\"><i style='color: red;' class='fa fa-exclamation-triangle'></i> Sin facturar</span>";
                                                $a="<input type='checkbox' id='fact_$d->id_rack_detalle' class='form-control'>";
                                                if($d->id_producto=='211'){
                                                    if($d->rack_detalle_fecha > $fechaaaaaa){
                                                        $eli="<button data-toggle='modal' data-target='#eliminar_detalle' onclick='poner_id_eliminar($d->id_rack_detalle)' class='btn btn-sm btn-danger'><i class='fa fa-times'></i></button>";
                                                    }
                                                }else{
                                                    $eli="<button data-toggle='modal' data-target='#eliminar_detalle' onclick='poner_id_eliminar($d->id_rack_detalle)' class='btn btn-sm btn-danger'><i class='fa fa-times'></i></button>";
                                                }
                                                $edi = "<button data-toggle='modal' data-target='#editar_precio' onclick=\"llenar_editar('$d->id_rack_detalle', '$d->rack_detalle_preciounit', '$d->rack_detalle_cantidad')\" class='btn btn-sm btn-success' title='Editar Precio'><i class='fa fa-edit'></i></button>";
                                                $total_p+=$d->rack_detalle_subtotal;
                                                $contador++;
                                            }

                                        }
                                    }else{
                                        $total+=$d->rack_detalle_subtotal;
                                        $data_venta = $this->ventas->listar_venta_x_id($d->rack_detalle_estado_fact);
                                        $sunat=$data_venta->venta_serie."-".$data_venta->venta_correlativo;
                                        $fechita_pe = date('Ymd',strtotime($data_venta->venta_fecha));
                                        if(file_exists("media/comprobantes/".$sunat."-".$fechita_pe.".pdf")){
                                            $ruta_sunat=_SERVER_."media/comprobantes/".$sunat."-".$fechita_pe.".pdf";
                                        }else{
                                            $ruta_sunat=_SERVER_ . "Ventas/imprimir_pdf/" . $data_venta->id_venta;
                                        }
                                        if($data_venta->venta_estado_sunat==0){
                                            $sunat.=" <br><span style='color: orange'>Sin enviar a SUNAT</span>";
                                        }else{
                                            $sunat.=" <br>ENVIADO A SUNAT";
                                        }
                                        $estado_fact = "<span style=\"color: green;\"><i style='color: green;' class='fa fa-check-square'></i><a target='_blank' href='".$ruta_sunat."'> $sunat</a></span>";
                                        $a="-";
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $a; ?></td>
                                        <td><?= $d->rack_detalle_fecha; ?></td>
                                        <td><?= $d->producto_nombre?><?= ($d->rack_detalle_correlativo_comanda != Null)? ' / '.$d->rack_detalle_correlativo_comanda : ""?></td>
                                        <td><?= $d->rack_detalle_cantidad; ?></td>
                                        <td><?= $data_rack->simbolo." ".$d->rack_detalle_preciounit; ?></td>
                                        <td><?= $data_rack->simbolo." ".$d->rack_detalle_subtotal; ?></td>
                                        <td><?= $estado_fact; ?></td>
                                        <td><?= $eli; ?>
                                            <button data-toggle="modal" data-target="#add" onclick="majito('<?= $d->id_producto?>','<?= $d->id_producto?>///<?=$d->producto_precio_valor; ?>','<?= $d->rack_detalle_cantidad; ?>','<?= $d->rack_detalle_preciounit ?>')" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></button>
                                            <?= $edi; ?>
                                        </td>
                                    </tr>
                                    <?php

                                }
                                ?>
                                <tr>
                                    <td class="text-center" colspan="1"><input onchange="todos()" type="checkbox" id="todos" class="form-control"></td>
                                    <td class="text-left" colspan="5"><label for="todos">Seleccionar Todos</label></td>

                                </tr>
                                <tr>
                                    <?php
                                    if($data_rack->abreviado=='sol'){
                                        $simbolo1 = 'S/. ';
                                        $simbolo2 = '$ ';
                                        $totalsillo = $total_p;
                                        $totalsillo2 = $total_p / $data_caja_->tipo_cambio;
                                        $total1=$total;
                                        $total2=$total / $data_caja_->tipo_cambio;
                                    }else{
                                        $simbolo1 = '$. ';
                                        $simbolo2 = 'S/. ';
                                        $totalsillo = $total_p;
                                        $totalsillo2 = $total_p * $data_caja_->tipo_cambio;
                                        $total1=$total;
                                        $total2=$total * $data_caja_->tipo_cambio;
                                    }
                                    ?>
                                    <td colspan="3" class="text-left" style="font-size: 12pt;font-weight: bold">PAGADO <?= $simbolo1." ".($total1) ."<span style='width: 70px;display: inline-block;'></span>".$simbolo2." ".(number_format($total2 ?? 0,2)); ?></td>
                                    <td colspan="2" class="text-center" style="font-size: 12pt;color: red">PENDIENTE</td>

                                    <td colspan="3" style="font-size: 12pt;color: red"><?= $simbolo1."".($totalsillo)."<span style='width: 70px;display: inline-block;'></span>".$simbolo2."".(number_format($totalsillo2 ?? 0,2)); ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div><div class="col-lg-6"></div>
                        <div class="col-lg-6 text-right">
                            <?php
                            if($contador>0){
                                ?>
                                <button data-toggle="modal" onclick="get_fact()" data-target="#add_venta" style="background: #6a0c0d;border: none" id="btn-comprobante" class="btn btn-block btn-primary"><i class="fa fa-file-text"></i> Generar Comprobante</button>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="row">
                <?php
                if($data_rack->rack_checkout == null){
                    ?>
                    <div class="col-lg-12 mt-3 text-center">
                        <button id="btn-checkout" class="btn btn-primary" onclick="preguntar('¿Está seguro que desea hacer el checkout?','guardar_checkout','Si','No',0)"><i class="fa fa-check-square"></i>Registrar CheckOut</button>
                        <button id="btn-add-checkout" class="btn btn-warning" data-toggle="modal" data-target="#add_checkout"><i class="fa fa-plus"></i> Aumentar estadía</button>
                        <button id="btn-add-cambiohab"  class="btn btn-success" data-toggle="modal" data-target="#cambiohab"><i class="fa fa-chain"></i> Cambiar Habitación</button>
                        <button id="btn-edit-cliente" class="btn btn-dark" data-toggle="modal" data-target="#editar_cliente"><i class="fa fa-pencil"></i> Editar Cliente</button>
                        <button id="btn-cargar" class="btn btn-info" data-toggle="modal" onclick="llenar_id_cargo_c();get_fact()" data-target="#cargar_cuenta"><i class="fa fa-folder"></i> Cargar a Cuenta</button>
                        <button id="btn-comple" class="btn btn-light" data-toggle="modal" onclick="get_fact()" data-target="#cargar_comple"><i class="fa fa-clipboard"></i> Complementary</button>

                    </div>

                    <?php
                }else{
                    echo "<span style='color: var(--color-logo)'>Check out realizado el $data_rack->rack_checkout</span>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>

<script>
    let cadenita = "";
    let cadenita_textos = "";
    let contenido_cuota = '';
    let precio_antiguo ="";
    let variable = "";
    function desabilitarBoton(){
        let boton = "btn-agregar-venta";
        cambiar_estado_boton(boton, 'Guardando...', true);
        setTimeout(function() {
            guardar_venta(); // Llamar a la función guardar_venta() después de 2 segundos
        }, 2000);
    }

    $(document).ready(function(){
        var valor = $('#tipo_venta').val();
        selecttipoventa(valor);
        ver_productos();
        $("#producto").select2();
        llenar_id_cargo_c();
        $("#id_cliente_cargo").select2();
        validar_fecha_new();
        $("#tcambio").hide();
        $("#c1").hide();
        $("#c2").hide();
    });
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
    function guardar_venta(){
        // desabilitarBoton()
        let boton = "btn-agregar-venta";
        cambiar_estado_boton(boton, 'Guardando...', true);
        cadenita_textos="";
        let valor=true;
        let valor_tipo = true
        let venta_tipo = $("#tipo_venta").val();
        let tipo_pago = $("#tipo_pago").val();
        let codigo_venta = $("#codigo_venta").val();
        let serie = $("#serie").val();
        let correlativo= $("#numero").val();
        let caja= $("#caja").val();
        let ruc= $("#ruc").val();
        let comp_obs= $("#comp_obs").val();
        let razon_social= $("#razon_social").val();
        let textoCodificado = encodeURIComponent(razon_social);
        let domicilio= $("#domicilio").val();
        //let moneda= <?= $data_rack->id_moneda; ?>;
        let moneda= $("#moneda").val();
        let id_rack= <?= $data_rack->id_rack; ?>;
        let agrupar = 0;
        //para tipo de cambio
        let tipo_cambio = $("#tipo_cambio").val();
        //fin
        let mostrar_tp = 0;
        let agrupar_des="";
        if($("#agrupar").is(':checked')){
            agrupar=1;
            agrupar_des=$("#agrupar_texto").val();
        }
        if($("#mostrar_tp").is(':checked')){
            mostrar_tp=1;
        }
        //PARA CODIGOS
        let codigo=$("#codigo").val();
        let codigo2=$("#codigo2").val();
        //FIN DE LOS CODIGOS
        var partir_pago = 0;
        var partir_pago_id="";
        var partir_pago_monto="";
        var partir_pago_id2="";
        var partir_pago_monto2="";
        if($("#partir_pago").is(':checked')){
            partir_pago=1;
            var totalito=$("#totalito").val() * 1;
            partir_pago_id=$("#partir_pago_tipo").val();
            partir_pago_id2=$("#partir_pago_tipo2").val();
            partir_pago_monto=$("#partir_pago_monto").val();
            partir_pago_monto2=$("#partir_pago_monto2").val();
            var suma = partir_pago_monto * 1 + partir_pago_monto2 * 1;
            console.log(totalito)
            console.log(suma)
            if(suma != totalito){
                valor=false;
                respuesta("Suma incorrecta","error");
            }
        }
        var cadde = cadenita.split('--/');
        for (var i = 0;i<cadde.length - 1;i++){
            var aja = $("#cad_texto_"+cadde[i]).val();
            cadenita_textos+=aja+"-**-";
        }
        if(tipo_pago == 5){
            if(contenido_cuota == ""){
                respuesta('Falta agregar Cuotas a la Venta', 'error');
                valor=false;
            }else{
                //valor = validar_parametro_vacio(contenido_cuota, valor);
                let importe_cuota = $('#total_cuota_input').val();
                console.log(importe_cuota)
                let total_temporal = $('#totalito').val() * 1;
                console.log(total_temporal)
                if(importe_cuota != total_temporal){
                    respuesta('El Total de las Cuotas '+importe_cuota+' no es igual al Total de la Venta '+total_temporal, 'error');
                    valor=false;
                }
            }
        }
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
        //FIN - VALIDAR SI LA SERIE COINCIDE CON EL TIPO DE VENTA
        var cadena = "id_cliente=" + <?= $data_rack->id_cliente; ?> +
            "&venta_tipo="+venta_tipo+
            "&codigo_venta="+codigo_venta+
            "&tipo_pago="+tipo_pago+
            "&serie="+serie+
            "&correlativo="+correlativo+
            "&moneda="+moneda+
            "&id_rack="+id_rack+
            "&caja="+caja+
            "&mostrar_tp="+mostrar_tp+
            "&ruc="+ruc+
            "&comp_obs="+comp_obs+
            //"&razon_social="+razon_social+
            "&razon_social="+textoCodificado+
            "&domicilio="+domicilio+
            "&agrupar="+agrupar+
            "&codigo="+codigo+
            "&codigo2="+codigo2+
            "&tipo_cambio="+tipo_cambio+
            "&agrupar_des="+agrupar_des+
            "&partir_pago="+partir_pago+
            "&partir_pago_id="+partir_pago_id+
            "&partir_pago_id2="+partir_pago_id2+
            "&partir_pago_monto="+partir_pago_monto+
            "&partir_pago_monto2="+partir_pago_monto2+
            "&cadenita="+cadenita+
            "&cadenita_textos="+cadenita_textos+
            "&contenido_cuota="+contenido_cuota+
            "&variable="+JSON.stringify(variable)+
            "&id_turno=1";
        if(valor_tipo){
            if(valor){
                $.ajax({
                    type: "POST",
                    url: urlweb + "api/Ventas/guardar_venta",
                    data: cadena,
                    dataType: 'json',
                    beforeSend: function () {
                        cambiar_estado_boton(boton, 'Guardando...', true);
                    },
                    success:function (r) {
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

    function majito(id_1,id,cant,prec){
    	$("#producto").val(id).trigger('change');
    	$("#cantidad").val(cant);
    	$("#precio_unit").val(prec);
        $("#id_pro").val(id_1);
    	calc_subtotal();
    }

    function llenar_editar(id_rack_detalle,precio_viejo,cantidad){
        $("#id_rack_detalle_editar").val(id_rack_detalle);
        $("#precio_anterior").val(precio_viejo);
        $("#cantidad_editada").val(cantidad);
    }

    function guardar_precio_editado(){
        var valor = true;
        var boton = "btn-editar_precio";
        var precio_actual = $('#precio_actual').val();
        var id_rack_detalle_editar = $('#id_rack_detalle_editar').val();
        var contra_editar = $('#contra_editar').val();
        var cantidad_editada = $('#cantidad_editada').val();
        valor = validar_campo_vacio('precio_actual', precio_actual, valor);
        valor = validar_campo_vacio('contra_editar', contra_editar, valor);
        if(valor){
            //Cadena donde enviaremos los parametros por POST
            var cadena = "precio_actual=" + precio_actual +
                "&id_rack_detalle_editar=" + id_rack_detalle_editar +
                "&cantidad_editada=" + cantidad_editada +
                "&contra_editar=" + contra_editar;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Hotel/guardar_precio_editado",
                data: cadena,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, 'Guardando...', true);
                },
                success:function (r) {
                    cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                    switch (r.result.code) {
                        case 1:
                            respuesta('Guardado con exito', 'success');
                            location.reload();
                            break;
                        case 2:
                            respuesta('Error al guardar', 'error');
                            break;
                        case 3:
                            respuesta('Clave incorrecta', 'error');
                            $('#contra_editar').css('border','solid red');
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        }
    }

    function validar_fecha_new() {
        var f_i = new Date($("#fecha_para_calculo").val());
        var f_f = new Date($("#new_checkout").val());
        if(f_f<f_i){
            alert("La fecha de salida debe ser mayor a la fecha de entrada");
            $("#new_checkout").val($("#fecha_para_calculo").val());
        }else{
            var difference= Math.abs(f_f-f_i);
            var resta = difference/(1000 * 3600 * 24)
            $("#noches_new").val(resta);
        }
    }
    function guardar_detalle(){
        var valor = true;
        var boton = "btn-agregar-detalle";
        var id_pro = $('#id_pro').val();
        var precio_unit = $('#precio_unit').val();
        var cantidad = $('#cantidad').val();
        valor = validar_campo_vacio('precio_unit', precio_unit, valor);
        valor = validar_campo_vacio('cantidad', cantidad, valor);
        valor = validar_campo_vacio('producto', producto, valor);
        if(valor){
            //Cadena donde enviaremos los parametros por POST
            var cadena = "id_pro=" + id_pro +
                "&id_rack=" + <?= $data_rack->id_rack; ?> +
                "&precio_unit=" + precio_unit +
                "&cantidad=" + cantidad;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Hotel/guardar_detalle_rack",
                data: cadena,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, 'Guardando...', true);
                },
                success:function (r) {
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
    function guardar_cambiohab(){
        var valor = true;
        var boton = "btn-agregar-cambiohab";
        var new_hab = $('#new_hab').val();
        valor = validar_campo_vacio('new_hab', new_hab, valor);
        if(valor){
            var cadena = "new_hab=" + new_hab +
                "&id_rack=" + <?= $data_rack->id_rack; ?>;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Hotel/cambiar_habitacion",
                data: cadena,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, 'Guardando...', true);
                },
                success:function (r) {
                    cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar Cambios", false);
                    switch (r.result.code) {
                        case 1:
                            location.href=urlweb+"Hotel/inicio";
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
    function guardar_cliente(){
        var valor = true;
        var boton = "btn-editar-cliente";
        var cliente_tipo_doc = $('#cliente_tipo_doc').val();
        var cliente_numero = $('#cliente_numero').val();
        var cliente_nombre = $('#cliente_nombre').val();
        var cliente_direccion = $('#cliente_direccion').val();
        var cliente_telefono = $('#cliente_telefono').val();
        var cliente_correo = $('#cliente_correo').val();
        valor = validar_campo_vacio('cliente_tipo_doc', cliente_tipo_doc, valor);
        valor = validar_campo_vacio('cliente_numero', cliente_numero, valor);
        valor = validar_campo_vacio('cliente_nombre', cliente_nombre, valor);
        if(valor){
            var cadena = "id_tipodocumento=" + cliente_tipo_doc +
                "&cliente_numero=" + cliente_numero +
                "&cliente_nombre=" + cliente_nombre +
                "&cliente_razonsocial=" + cliente_nombre +
                "&cliente_direccion=" + cliente_direccion +
                "&cliente_telefono=" + cliente_telefono +
                "&cliente_correo=" + cliente_correo +
                "&id_cliente=" + <?= $data_cliente->id_cliente; ?>;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Clientes/guardar_cliente",
                data: cadena,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, 'Guardando...', true);
                },
                success:function (r) {
                    cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar Cambios", false);
                    switch (r.result.code) {
                        case 1:location.reload();break;
                        case 2:respuesta('Error al guardar', 'error');break;
                        default:respuesta('¡Algo catastrofico ha ocurrido!', 'error');break;
                    }
                }
            });
        }
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
    function guardar_checkout(){
        var boton = "btn-checkout";
        var cadena = "id_habitacion=" + <?= $data_rack->id_habitacion; ?> +
            "&id_rack=" + <?= $data_rack->id_rack; ?>;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Hotel/guardar_checkout",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'Guardando...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        setTimeout(function () {
                            location.href = urlweb +  'Hotel/Inicio';
                        }, 800);
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
    function guardar_add_checkout(){
        var new_checkout = $("#new_checkout").val();
        var new_precio = $("#new_precio").val();
        var noches_new = $("#noches_new").val() * 1;
        var boton = "btn-agregar-new-checkout";
        var cadena = "id_habitacion=" + <?= $data_rack->id_habitacion; ?> +
            "&new_checkout=" + new_checkout+
            "&new_precio=" + new_precio+
            "&noches_new=" + noches_new+
            "&id_rack=" + <?= $data_rack->id_rack; ?>;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Hotel/guardar_add_checkout",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'Guardando...', true);
            },
            success:function (r) {
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
    function llenar_id_cargo_c() {
       $("#id_cliente_cargo").val(<?= $data_rack->id_cliente; ?>);
    }

    function cargar_datos_cuenta() {
        let valor=true;
        let boton = "btn-agregar-venta_cargo";
        let id_rack = <?= $data_rack->id_rack; ?>;
        let id_cliente = $("#id_cliente_cargo").val();

        var cadena = "id_cliente=" + id_cliente +
            "&id_rack=" + id_rack +
            "&id_habitacion=" + <?= $data_rack->id_habitacion; ?> +
            "&cadenita=" + cadenita +
            "&variable="+JSON.stringify(variable);

        if(valor){
            $.ajax({
                type: "POST",
                url: urlweb + "api/Hotel/cargar_datos_cuenta",
                data: cadena,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, 'Guardando...', true);
                },
                success:function (r) {
                    cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                    switch (r.result.code) {
                        case 1:
                            respuesta('Enviado Exitosamente', 'success');
                            if(r.result.envio == 0){
                                setTimeout(function () {
                                    location.reload()
                                }, 800);
                            }else{
                                setTimeout(function () {
                                    location.href = urlweb +  'Hotel/inicio';
                                }, 800);
                            }
                            break;
                        case 2:
                            respuesta('Error al guardar', 'error');
                            break;
                        case 7:
                            respuesta('Enviado correctamente pero aun falta mas items por enviar', 'success');
                            setTimeout(function () {
                                location.reload()
                            }, 800);
                            break;
                        case 9:
                            respuesta('Fallo al hacer el checkout automatico', 'error');
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        }
    }

    function cargar_datos_comple(){
        let valor=true;
        let boton = "btn-agregar-venta_cargo";
        let id_rack = <?= $data_rack->id_rack; ?>;
        let id_cliente = $("#id_cliente_cargo").val();

        var cadena = "id_cliente=" + id_cliente +
            "&id_rack=" + id_rack +
            "&id_habitacion=" + <?= $data_rack->id_habitacion; ?> +
            "&cadenita=" + cadenita +
            "&variable="+JSON.stringify(variable);

        if(valor){
            $.ajax({
                type: "POST",
                url: urlweb + "api/Hotel/cargar_datos_comple",
                data: cadena,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, 'Guardando...', true);
                },
                success:function (r) {
                    cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                    switch (r.result.code) {
                        case 1:
                            respuesta('Guardado Exitosamente', 'success');
                            if(r.result.envio == 0){
                                setTimeout(function () {
                                    location.reload()
                                }, 800);
                            }else{
                                setTimeout(function () {
                                    location.href = urlweb +  'Hotel/inicio';
                                }, 800);
                            }
                            break;
                        case 2:
                            respuesta('Error al guardar', 'error');
                            break;
                        case 9:
                            respuesta('Fallo al hacer el checkout automatico', 'error');
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        }
    }

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
            }else{
                $('#select_tipodocumento').val(2);
                $('#client_number').val('11111111');
                $('#client_name').val('PÚBLICO EN GENERAL');
                $('.factura').hide();
            }

        }
    }
    function selecttipoventa(valor){
        Consultar_serie();
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

    function get_fact() {
        cadenita = "";
        <?php
        foreach ($detalle_rack as $da) {
        if($da->rack_detalle_estado_fact==0){
            ?>
        if($("#fact_<?= $da->id_rack_detalle; ?>").is(':checked')){
            cadenita += <?= $da->id_rack_detalle; ?>+"--/";
        }
        <?php
        }}
        ?>
        if(cadenita===""){
            respuesta("Seleccione al menos un item","error");
            variable="";
            armar_tabla();
            desactivarBoton();
        }else{
            $.ajax({
                type: "POST",
                url: urlweb + "api/Hotel/consultar_rack_detalle",
                data: "cadena="+cadenita,
                dataType: 'json',
                success:function (r) {
                    let datos_array = [];
                    datos_array = JSON.parse(JSON.stringify(r.result));
                    variable = datos_array;
                    //AQUI VA VENIR UN CODIGO PARA DISEÑAR UNA TABLA QUE SE MOSTRARA CON EL DETALLE DE LO SELECCIONADO
                    armar_tabla();
                }
            });
        }
    }
    function todos() {
        if($("#todos").is(':checked')){
            <?php
            foreach ($detalle_rack as $da) {
                if($da->rack_detalle_estado_fact==0){
                ?>
                $("#fact_<?= $da->id_rack_detalle; ?>").attr('checked','checked');
                <?php
                }
            }
            ?>
        }else{
            <?php
            foreach ($detalle_rack as $da) {
                if($da->rack_detalle_estado_fact==0){
                ?>
                $("#fact_<?= $da->id_rack_detalle; ?>").removeAttr('checked');
                <?php
                }
            }
            ?>
        }
    }
    function poner_id_eliminar(id) {
        $("#eliminar_id").val(id);
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

    function cambiar_codigo(){
        let cod1 = $("#partir_pago_tipo").val();
        let cod2 = $("#partir_pago_tipo2").val();
        if(cod1==1){
            $("#c1").show();
        }else{
            $("#c1").hide();
        }
        if(cod2==1){
            $("#c2").show();
        }else{
            $("#c2").hide();
        }
    }

    function eliminar_detalle_(){
        let valor = true;
        var boton = "btn_eliminar_detalle";
        var eliminar_clave = $("#eliminar_clave").val();
        var eliminar_id = $("#eliminar_id").val();
        var eliminar_motivo = $("#eliminar_motivo").val();
        var id_rack = <?= $id?>;

        valor = validar_campo_vacio('eliminar_clave',eliminar_clave, valor);
        valor = validar_campo_vacio('eliminar_motivo',eliminar_motivo, valor);

        if(valor){
            var cadena = "eliminar_clave=" + eliminar_clave+
                "&eliminar_id="+eliminar_id+
                "&id_rack="+id_rack+
                "&eliminar_motivo="+eliminar_motivo;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Ventas/eliminar_detalle",
                data: cadena,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, 'Eliminando...', true);
                },
                success:function (r) {
                    cambiar_estado_boton(boton, "<i class=\"fa fa-trash fa-sm text-white-50\"></i> Eliminar", false);
                    switch (r.result.code) {
                        case 1:
                            respuesta('Eliminado correctamente', 'success');
                            setTimeout(function () {
                                location.reload()
                            }, 800);
                            break;
                        case 2:
                            respuesta('Error al guardar', 'error');
                            break;
                        case 3:
                            respuesta('Clave incorrecta, debe generar otra clave de eliminación', 'error');
                            $('#eliminar_clave').css('border','solid red');
                            break;
                        default:
                            respuesta('¡Ocurrió un error inesperado. Contactar con TI', 'error');
                            break;
                    }
                }
            });
        }
    }

    //INICIO - FORMA DE PAGO
    function forma_pago_credito(){
        let tipo_pago = $('#tipo_pago').val();
        if(tipo_pago == 5){
            $('#div_cuotas').show();
        }else{
            $('#div_cuotas').hide();
        }

        let tipo_pago2 = $('#tipo_pago').val();
        if(tipo_pago2 == 1){
            $('#div_tarjeta').show();
        }else{
            $('#div_tarjeta').hide();
        }


    }
    function limpiar_cuotas(){
        $("#cuotas").html('');
        $("#contenido_cuota").val('');
        $("#importe_cuota").val('');
        $("#fecha_cuota").val('');
        $("#total_importe_cuotas").html('');
        contenido_cuota = '';
    }
    function agregar_cuota(){
        let valor = true;
        let importe = $('#importe_cuota').val();
        let fecha = $('#fecha_cuota').val();
        let fecha_hoy = $('#hoy').val();
        valor = validar_campo_vacio('importe_cuota', importe, valor);
        valor = validar_campo_vacio('fecha_cuota', fecha, valor);
        if(valor){
            if(importe != "" & fecha != ""){
                if(fecha_hoy < fecha){
                    contenido_cuota += importe + "-.-." + fecha + "/./.";
                    $('#contenido_cuota').val(contenido_cuota);
                    show();
                    clean_cuotas();
                }else{
                    respuesta('La fecha debe ser mayor a Hoy','error');
                }
            }else{
                respuesta('Debe llenar todos los campos','error');
            }
        }
    }
    function show(){
        let llenar = "";
        let llenar_total = "";
        let conteo = 1;
        let total = 0;
        if (contenido_cuota.length > 0){
            let filas = contenido_cuota.split('/./.');
            if (filas.length>0){
                for(let i=0; i<filas.length - 1; i++){
                    let celdas = filas[i].split('-.-.');
                    llenar += "<div class='col-lg-2'>" +
                        "<label>Cuota 0"+conteo+"</label>" +
                        "       </div>" +
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
                    conteo++;
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
    function clean_cuotas(){
        $('#importe_cuota').val('');
    }
    function quitar_cuota(ind) {
        let contenido_artificio ="";
        if (contenido_cuota.length>0){
            let filas=contenido_cuota.split('/./.');
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
        contenido_cuota = contenido_artificio;
        show();
    }
    //FIN - FORMA DE PAGO

    function ticket_detalle(id_rack,id_h,fecha){
        var boton = 'imprimir';
        $.ajax({
            type: "POST",
            url: urlweb + "api/Hotel/ticket_detalle",
            data: "id_rack=" + id_rack+ "&id_habitacion=" + id_h + "&fecha="+ fecha,
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
                        }, 800);
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
    //FUNCION PARA CALCULAR VALORE DE DOLARS O SOLES
    function calcular(valor) {
        if(valor == <?= $data_rack->id_moneda?>){
            //AQUI SE VA CALCULAR PARA PASARA A DOLARES
            $("#tcambio").hide();
        }else{
            $("#tcambio").show();
            //AQUI SE VA CALCULAR PARA PASAR A SOLES
        }
        convertir_d()
    }

    function convertir_d(){
        let tipo_cambio = $("#tipo_cambio").val();
        let moneda = $("#moneda").val();
        let calcular_precio = "";
        let calcular_sub = "";
        if(moneda==1){
            //AQUI SE PASA A SOLES
            for (var i=0; i<variable.length; i++){
                calcular_precio = redondear(parseFloat(tipo_cambio) * parseFloat(variable[i]['precio']),3);
                calcular_sub = parseFloat(calcular_precio) * parseFloat(variable[i]['cantidad']);
                variable[i]['precio_final'] = calcular_precio;
                variable[i]['subtotal'] = redondear(calcular_sub,2);
            }
            armar_tabla()
        }else{
            //AQUI SE PASA A DOLARES
            for (var i=0; i<variable.length; i++){
                calcular_precio = redondear(parseFloat(variable[i]['precio']) / parseFloat(tipo_cambio),3);
                calcular_sub =calcular_precio * variable[i]['cantidad'];
                variable[i]['precio_final'] = calcular_precio;
                variable[i]['subtotal'] = redondear(calcular_sub,2);
            }
            armar_tabla()
        }
        //$("#totalito").html(redondear(calcular,2,false));
    }
    function activarBoton() {
        var boton = document.getElementById("btn-agregar-venta");
        boton.style.display = "block"; // Cambia el estilo a "block" para mostrar el botón
    }

    function desactivarBoton() {
        var boton = document.getElementById("btn-agregar-venta");
        boton.style.display = "none"; // Cambia el estilo a "none" para ocultar el botón
    }

    function  armar_tabla(){
        var tablas_e = "";
        let suma = 0;
        for (var i=0; i<variable.length; i++){
            tablas_e += "<tr > <td>" +variable[i]['id']+"</td>" +
                "<td data-nombre='"+ variable[i]['producto']+"'><input class='form-control' id='editar_' onchange='cambiar_descripcion(event)' type='text' value='" +variable[i]['producto']+"'></td>" +
                "<td>" +variable[i]['cantidad']+"</td>" +
                "<td>" +variable[i]['precio_final']+"</td>" +
                "<td>" +variable[i]['subtotal']+"</td> </tr>"
            suma = redondear(parseFloat(suma) + parseFloat(variable[i]['subtotal']),2);
        }
        $("#generar_fact_tbody").html(tablas_e);
        $("#generar_fact_tbody_cargo").html(tablas_e);
        $("#generar_fact_tbody_comple").html(tablas_e);
        $("#aqui_va_suma").html("TOTAL : "+ parseFloat(suma,2));
        $("#aqui_va_suma_cargo").html("TOTAL : "+ parseFloat(suma,2));
        $("#aqui_va_suma_comple").html("TOTAL : "+ parseFloat(suma,2));
        $("#totalito").val(parseFloat(suma,2));
        activarBoton()
    }

    function cambiar_descripcion(event) {
        // Get the name of the product and the new description
        var productName = event.target.parentNode.getAttribute('data-nombre');
        var newDescription = event.target.value;

        // Create a new text input element
        var newElement = document.createElement("input");
        newElement.setAttribute("id", "editar_");
        newElement.setAttribute("class", "form-control");
        newElement.setAttribute("type", "text");
        newElement.setAttribute("onchange", "cambiar_descripcion(event)");
        newElement.setAttribute("value", newDescription);
        console.log(newDescription)
        // Replace the original element with the new element
        event.target.parentNode.replaceChild(newElement, event.target);
        // Update the product name in the variable array
        var product = variable.find(function(item) {
            return item.producto === productName;
        });
        if (product) {
            product.producto = newDescription;
            console.log(product)
        }
        armar_tabla()
    }
    // function cambiar_descripcion(event) {
    //     var nombre_producto = event.target.parentNode.getAttribute('data-nombre');
    //     var nuevo_nombre = event.target.value;
    //
    //     // Crear un nuevo elemento de entrada de texto
    //     var nuevo_elemento = document.createElement("input");
    //     nuevo_elemento.setAttribute("id", "editar_");
    //     nuevo_elemento.setAttribute("class", "form-control");
    //     nuevo_elemento.setAttribute("type", "text");
    //     nuevo_elemento.setAttribute("onchange", "cambiar_descripcion(event)");
    //     nuevo_elemento.setAttribute("value", nuevo_nombre);
    //     console.log(nuevo_nombre)
    //     // Reemplazar el elemento original con el nuevo elemento
    //     event.target.parentNode.replaceChild(nuevo_elemento, event.target);
    //
    //     // Actualizar el nombre del producto en el arreglo variable
    //     var producto = variable.find(function(item) {
    //         return item.producto === nombre_producto;
    //     });
    //     if (producto) {
    //         producto.producto = nuevo_nombre;
    //         console.log(producto)
    //     }
    // }

    /*function cambiar_descripcion(event) {
        var nombre_producto = event.target.parentNode.getAttribute('data-nombre');
        var nuevo_nombre = event.target.value;
        console.log(nuevo_nombre)
        // Actualizar el nombre del producto en la tabla
        event.target.parentNode.innerHTML = "<input id='editar_' class='form-control' onchange='cambiar_descripcion(event)' type='text' value='" +nuevo_nombre+"'>";

        // Actualizar el nombre del producto en el arreglo variable
        var producto = variable.find(function(item) {
            return item.producto === nombre_producto;
        });
        if (producto) {
            producto.producto = nuevo_nombre;
        }

    }*/

</script>
<style>
    body {
        font-family: "Questrial", sans-serif;
    }

    aside.context {
        text-align: center;
        color: #333;
        line-height: 1.7;
    }
    aside.context a {
        text-decoration: none;
        color: #333;
        padding: 3px 0;
        border-bottom: 1px dashed;
    }
    aside.context a:hover {
        border-bottom: 1px solid;
    }
    aside.context .explanation {
        max-width: 700px;
        margin: 4em auto 0;
    }

    .main-content {
        /*margin: 4em auto 0;*/
        width: 100%;
        text-transform: uppercase;
    }

    .ticket {
        display: grid;
        grid-template-columns: auto 5px;
        background: white;
        border-radius: 10px;
        border: 2px solid var(--color-logo);
        cursor: default;
    }
    .ticket__main {
        display: grid;
        grid-template-columns: repeat(6, 1fr) 120px;
        grid-template-rows: repeat(4, min-content) auto;
        padding: 10px;
    }

    .header {
        grid-area: title;
        grid-column: span 7;
        grid-row: 1;
        font: 900 25px "Montserrat", sans-serif;
        padding: 5px 0 5px 20px;
        letter-spacing: 6px;
        background: #6a0c0d;
        color: #f3f1c9;
    }

    .info {
        border: 3px solid;
        border-width: 0 3px 3px 0;
        padding: 8px;
    }
    .info__item {
        font: 400 13px "Questrial", sans-serif;
        letter-spacing: 0.5px;
        color: #6a0c0d;
    }
    .info__detail {
        font: 700 16px/1 "Jura";
        letter-spacing: 1px;
        margin: 4px 0;
        color: #6a0c0d;
    }

    .passenger {
        grid-column: 1/span 7;
    }

    .departure {
        grid-column-start: span 4;
    }
    .arrival {
        grid-column-start: span 3;
    }

    .passenger, .departure, .date {
        border-left: 3px solid;
    }

    .date {
        grid-column-start: span 3;
    }
     .time {
        grid-column-start: span 2;
    }

    .fineprint {
        grid-column-start: span 5;
        font-size: 14px;
        font-family: "Inconsolata";
        line-height: 1;
        margin-top: 10px;
        padding-right: 5px;
    }
    .fineprint p:nth-child(2) {
        margin: 4px 4px 0 0;
        padding-top: 4px;
        border-top: 1.5px dotted;
        font: 11px/1 "Inconsolata";
    }

    .snack {
        grid-column-start:span 1;
        width: 115px;
        margin: 10px 10px 0 0;
        position: relative;
        background: #fff;
        padding: 6px 0 2px;
        text-align: center;
        border-radius: 5px;
    }
    .snack svg {
        fill: #f3f1c9;
        width: 36px;
    }
    .snack__name {
        color: #f3f1c9;
        font-size: 12px;
    }

    .barcode {
        grid-column: 6/span 1;
        display: grid;
        margin: 10px 0 0;
        grid-template-rows: 1fr min-content;
    }
    .barcode__scan {
        background: linear-gradient(to right, #333 2%, #333 4%, transparent 4%, transparent 5%, #333 5%, #333 6%, transparent 6%, #333 6%, #333 8%, transparent 8%, transparent 9%, #333 9%, #333 10.5%, transparent 10.5%, transparent 11%, #333 11%, #333 12%, transparent 12%, transparent 13.5%, #333 13.5%, #333 15%, #333 17%, transparent 17%, transparent 19%, #333 19%, #333 20%, transparent 20%, transparent 21%, #333 21%, #333 22%, transparent 22%, transparent 23.5%, #333 23.5%, #333 25%, transparent 25%, transparent 26.5%, #333 26.5%, #333 27.5%, transparent 27.5%, transparent 28.5%, #333 28.5%, #333 30%, transparent 30%, transparent 32%, #333 32%, #333 34%, #333 36%, transparent 36%, transparent 37.5%, #333 37.5%, #333 40%, transparent 40%, transparent 41.5%, #333 41.5%, #333 43%, transparent 43%, transparent 46%, #333 46%, #333 48%, transparent 48%, transparent 49%, #333 49%, transparent 49%, transparent 50%, #333 50%, #333 51%, transparent 51%, transparent 53%, #333 53%, #333 54.5%, transparent 54.5%, transparent 56%, #333 56%, #333 58%, transparent 58%, transparent 59%, #333 59%, #333 60%, #333 62.5%, transparent 62.5%, transparent 64%, #333 64%, #333 64%, #333 67%, transparent 67%, transparent 69%, #333 69%, #333 70%, transparent 70%, transparent 71%, #333 71%, #333 72%, transparent 72%, transparent 73.5%, #333 73.5%, #333 76%, transparent 76%, transparent 79%, #333 79%, #333 80%, transparent 80%, transparent 82%, #333 82%, #333 82.5%, transparent 82.5%, transparent 84%, #333 84%, #333 87%, transparent 87%, transparent 89%, #333 89%, #333 91%, transparent 91%, transparent 92%, #333 92%, #333 95%, transparent 95%);
    }
    .barcode__id {
        letter-spacing: 1px;
        padding: 2px 0 0;
        color: #c02a28;
        font: 700 13px/1 "Jura";
    }

    .ticket__side {
        background: rgba(192, 42, 40, 0.2);
        box-sizing: border-box;
        border-left: 1.5px dashed #111;
        display: grid;
        grid-template-rows: repeat(2, 124px) 60px;
        grid-template-columns: 40px repeat(2, 45px);
        border-radius: 0 10px 10px 0;
    }
    .ticket__side .logo {
        text-align: center;
        background: #c02a28;
        padding: 10px 5px 10px 0px;
        margin: 10px 0 0 10px;
        font: 900 16px/1 "Montserrat";
        letter-spacing: 1.5px;
        grid-column: 1/span 1;
        grid-row: 1/span 2;
        position: relative;
        color: #fff;
        writing-mode: vertical-rl;
    }
    .ticket__side .logo p {
        transform: rotate(180deg);
    }
    .ticket__side .info {
        border: 3px solid #c02a28;
        border-width: 3px 3px 0;
        grid-column-start: 2;
        writing-mode: vertical-rl;
        transform: rotate(180deg);
    }
    .ticket__side .info.side-arrive {
        margin-top: 10px;
        border-width: 3px;
    }
    .ticket__side .info.side-date {
        grid-column-start: 3;
        border-right: none;
    }
    .ticket__side .info.side-time {
        grid-column: 3/span 1;
        grid-row: 1;
        margin-top: 10px;
        border-width: 3px 0 3px 3px;
    }
    .ticket__side .info__item {
        font-size: 11px;
        color: #c02a28;
    }
    .ticket__side .info__detail {
        font-size: 12px;
        margin: 0 2px 0 0;
        letter-spacing: 0px;
    }
    .ticket__side .barcode {
        grid-template-rows: 30px min-content;
        grid-row-start: 3;
        grid-column: 1/span 3;
        margin: 9px 0 0 10px;
        text-align: center;
    }
</style>
<!-- New Card -->
<style>
    *{
        margin: 0;
        padding: 0;
        font-family: "montserrat",sans-serif;
        box-sizing: border-box;
        list-style: none;
    }



    .business-card{
        width: 100%;
        height: 355px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;

    }

    .middle{
        position: relative;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
    }

    .front,.back{
        width: 100%;
        height: 100%;
        overflow: hidden;
        position: absolute;
        backface-visibility: hidden;
        transition: transform 0.5s linear;
    }

    .front{
        background: rgba(255,255,255,.7);
        padding: 20px;
        /*transform: perspective(600px) rotateX(180deg);*/
    }

    .front::before,.front::after{
        content: "";
        position: absolute;
        right: 0;
    }

    .front::before{
        background: #b02f4d;
        width: 80px;
        height: 120px;
        bottom: 0;
        clip-path:polygon(0 100%,46% 0,100% 100%);
    }

    .front::after{
        background: #f7d3e4;
        width: 100px;
        height: 100%;
        top: 0;
        clip-path:polygon(0 0,100% 0,100% 100%,80% 100%);
    }

    .front h2{
        text-transform: uppercase;
    }

    .front span{
        background: var(--color-logo);
        color: #fff;
        padding: 4px 10px;
        display: inline-block;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .front .contact-info li{
        margin: 5px 0px;
        display: flex;
        font-size: 12px;
        text-transform: uppercase;
        align-items: center;
    }

    .front .contact-info li i{
        width: 26px;
        height: 26px;
        background: var(--color-logo);
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 6px;
    }

    .back{
        background: #FF9671;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 8px;
        font-size: 24px;
        transform: perspective(600px) rotateX(0deg);
    }

    .business-card-active .front{
        transform: perspective(600px) rotateX(0deg);
    }
    .business-card-active .back{
        transform: perspective(600px) rotateX(-180deg);
    }
</style>