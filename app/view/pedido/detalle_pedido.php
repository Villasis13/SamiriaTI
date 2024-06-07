<style>
    /*** ESTILOS BOTÓN SLIDE RIGHT ***/
    .ov-btn-slide-right {
        background: #fff; /* color de fondo */
        color: grey; /* color de fuente */
        padding: 5px ;
        position: relative;
        z-index: 1;
        overflow: hidden;
        display: inline-block;
    }
    .ov-btn-slide-right:hover {
        color: #fff; /* color de fuente hover */
    }
    .ov-btn-slide-right::after {
        content: "";
        background: red; /* color de fondo hover */
        position: absolute;
        z-index: -1;
        padding: 16px 20px;
        display: block;
        top: 0;
        bottom: 0;
        left: 100%;
        right: -100%;
        -webkit-transition: all 0.35s;
        transition: all 0.35s;
    }
    .ov-btn-slide-right:hover::after {
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        -webkit-transition: all 0.35s;
        transition: all 0.35s;
    }

    .boton3 {
        color: #0012db;
        background-color: #c7d2f3;
        border-color: #adbadf;
        font-weight: bold;

        padding: 0.5em 1.2em;
        transition: all 1s ease;
        position: relative;
    }
    .boton3:hover {
        background: #318aac;
        color: #fff !important;
    }
    .boton-eliminar {
        color: white;
        background-color: #ff8787;
        border-color: #df7878;
        font-weight: bold;
        padding: 0.5em 1.2em;

        transition: all 1s ease;
        position: relative;
    }
    .andreBotonAccionTransferir{
        background-color: white;
        border: 1px solid white;
    }
    .boton-eliminar:hover {
        background: red;
        color: #fff !important;
    }
    .boton-guardar {
        color: white;
        background-color: #80e880;
        border-color: #59c741;
        font-weight: bold;
        padding: 0.5em 1.2em;
        transition: all 1s ease;
        position: relative;
    }
    .boton-guardar:hover {
        background: green;
        color: #fff !important;
    }
    .boton-comanda {
        color: white;
        background-color: #f6d88d;
        border-color: #ddb54e;
        font-weight: bold;
        padding: 0.5em 1.2em;
        transition: all 1s ease;
        position: relative;
    }
    .boton-comanda:hover {
        background: #f6c23e;
        color: #fff !important;
    }
    .boton-habitacion {
        color: white;
        background-color: #824c4c;
        border-color: #6a0c0d;
        font-weight: bold;
        padding: 0.5em 1.2em;
        transition: all 1s ease;
        position: relative;
    }
    .boton-habitacion:hover {
        background: #6a0c0d;
        color: #fff !important;
    }
    .andreBotonAccionEliminar{
        background-color: white;
        border: 1px solid white;
    }
    .andreBotonAccionTransferir:hover{
        background-color: #007BFD;
        color: white !important;
    }
    .AndreTabla2:hover .andreBotonAccionTransferir{
        background-color: #007BFD;
        color: white !important;
    }
    /* botones */
    .andreBotonAccionEliminar:hover{
        background-color: #bd2130;
        color: white !important;
    }
    .AndreTabla2:hover .andreBotonAccionEliminar{
        background-color: #bd2130;
        color: white !important;
    }
    .andreBotonAccionDividirPedido:hover{
        background-color: #28a745;
        color: white !important;
    }
    .AndreTabla2:hover .andreBotonAccionDividirPedido{
        background-color: #28a745;
        color: white !important;
    }
</style>

<div class="modal fade" id="agregar_pedido_nuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 70% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Pedido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="form-group col-lg-8 col-md-7 col-xs-7 col-sm-7">
                        <input autocomplete="off" name="parametro" type="text" value="<?= $parametro;?>" class="form-control" id="parametro" placeholder="Buscar Productos">
                    </div>
                    <div class="form-group col-lg-4 col-md-5 col-xs-5 col-sm-5">
                        <a type="submit" onclick="productos_nuevo()" class="btn btn-success" style="width: 80%; color:white"><i class="fa fa-search"></i> Buscar</a>
                    </div>
                </div>
                <div class="row">
                    <div id="producto_nuevo" class="table-responsive">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-10 col-sm-6 col-md-6 col-xs-6">
                        <div id="ver_seleccion" class="col-lg-6 col-sm-10 col-md-10 col-xs-10">
                            <label>Producto <span id ="producto_nombre_" style="color: black; font-size: 18pt;"></span> <!--: <span id ="comanda_detalle_precio_"></span>--></label>
                        </div>

                    </div>
                </div>
            </div>
            <form class="" enctype="multipart/form-data" id="guardar_pedido_nuevo">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" style="display:none;">
                            <?php
                            foreach ($familia as $f){
                                $productos_familia = $this->pedido->listar_productos_x_familia($f->id_producto_familia);
                                ?>
                                <h3 data-toggle="collapse" href="#tipo_<?= $f->id_producto_familia;?>"><?= $f->producto_familia_nombre?><i class="fa fa-arrow-down" style="float: right"></i></h3><br>

                                <div id="tipo_<?= $f->id_producto_familia;?>" class="collapse">
                                    <table class='table table-bordered' width='100%'>
                                        <thead class='text-capitalize'>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($productos_familia as $pf){
                                            $anho = date('Y');
                                            if($anho == "2021"){
                                                $icbper = 0.30;
                                            }elseif($anho == "2022"){
                                                $icbper = 0.40;
                                            }else{
                                                $icbper = 0.50;
                                            }
                                            $op_gravadas=0.00;
                                            $op_exoneradas=0.00;
                                            $op_inafectas=0.00;
                                            $op_gratuitas=0.00;
                                            $igv=0.0;
                                            $igv_porcentaje=0.18;
                                            if($pf->producto_precio_codigoafectacion == 10){
                                                $op_gravadas = $pf->producto_precio_venta;
                                                $igv = $op_gravadas * $igv_porcentaje;
                                                $total = $op_gravadas + $igv;
                                            }else{
                                                $total = $pf->producto_precio_venta;
                                            }
                                            if($pf->id_receta == "0"){
                                                $total = $total + $icbper;
                                            }
                                            ?>
                                            <tr>
                                                <td><?=$pf->producto_nombre?></td>
                                                <td><?=$total ?></td>
                                                <td>
                                                    <button class='btn btn-success' onclick="guardar_pedido_nuevo(<?=$pf->id_producto?>,'<?=$pf->producto_nombre?>','<?=$total?>')"><i class='fa fa-check'></i></button>
                                                    <a class="btn btn-primary" href="<?= _SERVER_ . $pf->producto_foto?>" target="_blank"><i class="fa fa-eye"></i></a>
                                                <td>
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
                        <div id="mostrar">
                            <div class="row">
                                <div class="col-lg-2  col-sm-6 col-md-6 col-xs-6">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" id="id_producto" name="id_producto">
                                        <input type="hidden" class="form-control" id="id_comanda" name="id_comanda" value="<?= $ultimo_valor_;?>">
                                        <input type="hidden" class="form-control" id="producto_nombre" name="producto_nombre">
                                        <!--<input type="hidden" class="form-control" id="comanda_detalle_precio" name="comanda_detalle_precio">-->
                                        <input type="hidden" class="form-control" id="comanda_detalle_total" name="comanda_detalle_total">
                                        <input type="hidden" class="form-control" id="id_mesa" name="id_mesa" value="<?= $id;?>">
                                        <input type="hidden" id="contenido_pedido" name="contenido_pedido">
                                        <input type="hidden" id="stock_escondido" name="stock_escondido">
                                        <label class="col-form-label">Cantidad</label>
                                        <input type="number" value="1" class="form-control" id="comanda_detalle_cantidad" name="comanda_detalle_cantidad">
                                    </div>
                                </div>
                                <div class="col-lg-2  col-sm-6 col-md-6 col-xs-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Tipo Entrega</label>
                                        <select class="form-control" id= "comanda_detalle_despacho" name="comanda_detalle_despacho">
                                            <option value="SALON">Salon</option>
                                            <option value="PARA LLEVAR">Para llevar</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-md-6 col-xs-6">
                                    <label for="">Precio</label>
                                    <input type="text" class="form-control" id="comanda_detalle_precio" name="comanda_detalle_precio">
                                </div>
                                <div class="col-lg-4 col-sm-8 col-md-8 col-xs-8">
                                    <div class="form-group">
                                        <label class="col-form-label">Observacion</label>
                                        <textarea rows="3" class="form-control" type="text" id="comanda_detalle_observacion" name="comanda_detalle_observacion" maxlength="200" placeholder="Ingrese Alguna Observacion...">-</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-3 col-md-3 col-xs-3">
                                    <a style="margin-top: 50px; color: white" class='btn btn-primary' data-toggle='modal' onclick='add_pedido_nuevo()' data-target='#asignar_pedido'><i class='fa fa-check'></i> Agregar</a>
                                </div>
                            </div>
                            <div id="opciones_dentro"></div>
                            <input type="hidden" id="servicio_check_detalle" name="servicio_check_detalle">
                            <input type="hidden" id="id_escondido_" name="id_escondido_">
                            <div class="row">
                                <div class="container-fluid">
                                    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
                                        <div class="table-responsive">
                                            <table id="" class="table table-bordered" style="background: antiquewhite;">
                                                <thead>
                                                <tr style="font-weight: bold;text-align: center">
                                                    <td>PRODUCTO</td>
                                                    <td>PU</td>
                                                    <td>CANT</td>
                                                    <td>ENTR</td>
                                                    <td>OBS</td>
                                                    <td>TOTAL</td>
                                                    <td>ACCIÓN</td>
                                                </tr>
                                                </thead>
                                                <tbody id="contenido_detalle_compra">
                                                </tbody>
                                                <!--<tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>-->
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>Total</td>
                                                    <td><span id="comanda_total_">S/ 0.00</span></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="contenido_impresion" name="contenido_impresion">
                    <button type="button" class="btn btn-secondary" onclick="" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                    <button type="submit" class="btn btn-success" id="btn-guardar_nuevo"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="cambiar_mesa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 40% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cambiar Mesa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="col-form-label">Elegir Mesa</label>
                                <select class="form-control" id="id_mesa_nuevo" name="id_mesa_nuevo">
                                    <option value="">Seleccionar Mesa</option>
                                    <?php
                                    foreach ($mesas as $m){
                                        ?>
                                        <option value="<?php echo $m->id_mesa;?>"><?php echo $m->mesa_nombre;?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="preguntar('¿Está seguro que desea cambiar de mesa?','cambiar_mesa','Si','No')" id="btn-cambiar_mesa"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="transferir_pedido" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 40% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Transferir Pedido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="col-form-label">Elegir Mesa</label>
                                <select class="form-control" id="id_mesa_pn" name="id_mesa_pn">
                                    <option value="">Seleccionar Mesa</option>
                                    <?php
                                    foreach ($mesas_pn as $m){
                                        ?>
                                        <option value="<?php echo $m->id_mesa;?>"><?php echo $m->mesa_nombre;?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="preguntar('¿Está seguro que desea cambiar de mesa?','transferir_pedido','Si','No')" id="btn-cambiar_mesa"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cambiar_pedido" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 35% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Transferir Pedido por Pedido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <input type="hidden" id="id_comanda_detalle_transferir" name="id_comanda_detalle_transferir">
                                <label class="col-form-label">Elegir Mesa</label>
                                <select class="form-control" id="id_mesa_transp" name="id_mesa_transp">
                                    <option value="">Seleccionar Mesa</option>
                                    <?php
                                    foreach ($mesas_pn as $m){
                                        ?>
                                        <option value="<?php echo $m->id_mesa;?>"><?php echo $m->mesa_nombre;?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Contraseña</label>
                                <input class="form-control" type="password" id="password_xp" name="password_xp" placeholder="Contraseña...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="preguntar('¿Está seguro que desea mover este pedido?','transferir_mesa_x_pedido','Si','No')" id="btn-cambiar_mesa_pedido"><i class="fa fa-save fa-sm text-white-50"></i> Transferir</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="eliminar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 60% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Pedido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <input type="hidden" id="id_comanda_detalle_eliminar" name="id_comanda_detalle_eliminar">
                                <input type="hidden" id="id_comanda_eliminar" name="id_comanda_eliminar">
                                <input type="hidden" id="id_mesa_eliminar" name="id_mesa_eliminar">
                                <label class="col-form-label">Motivo</label>
                                <textarea class="form-control" name="comanda_detalle_eliminacion" id="comanda_detalle_eliminacion" cols="30" rows="2" placeholder="Ingrese Motivo..."></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label for="">Contraseña</label>
                            <input class="form-control" type="password" id="password" name="password" placeholder="Contraseña...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="preguntar('¿Está seguro que desea eliminar este pedido?','eliminar_comanda_detalle','Si','No')" id="btn-cambiar_mesa"><i class="fa fa-save fa-sm text-white-50"></i> Eliminar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="eliminar_todo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 60% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Borrar todo el Pedido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <input type="hidden" id="id_comanda_todo" name="id_comanda_todo">
                                <input type="hidden" id="id_mesa_todo" name="id_mesa_todo">
                                <label class="col-form-label">Motivo</label>
                                <textarea class="form-control" name="motivo_eliminar_todo" id="motivo_eliminar_todo" cols="30" rows="2" placeholder="Ingrese Motivo..."></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label for="">Contraseña</label>
                            <input class="form-control" type="password" id="password_todo" name="password_todo" placeholder="Contraseña...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="preguntar('¿Está seguro que desea eliminar todo el pedido?','eliminar_todo_pedido','Si','No')" id="btn-eliminar_todo"><i class="fa fa-save fa-sm text-white-50"></i> Eliminar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ventas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 80% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Generar Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div id="persona">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                                <div class="form-group">
                                    <label>Productos : </label><br>
                                    <!--<input type="checkbox" name="checkbox_todo" id="checkbox_todo" value="1" onchange="checkbox_todo()">
                                    <label for="checkbox_todo"> SELECCIONAR TODO</label>-->
                                    <hr>
                                    <div class="row" id="div_checkbox">
                                        <?php
                                        $cal_posi = 0;
                                        $cal_nega = 0;
                                        foreach($pedidos as $ls){

                                            $consultar_estado = $this->pedido->consultar_($ls->id_comanda_detalle);
                                            if($ls->comanda_detalle_estado_venta == 0 || empty($consultar_estado)){
                                                //$tipo_afectacion = $this->pedido->tipo_afectacion_x_producto
                                                ?>
                                                <div class="col-md-12" style="font-weight: bold;">
                                                    <input  type="checkbox" onchange="calcular_total(<?= $ls->id_comanda_detalle;?>)" id="id_comanda_detalle_<?= $ls->id_comanda_detalle;?>" name="id_comanda_detalle_<?= $ls->id_comanda_detalle;?>" value="<?= $ls->id_comanda_detalle;?>" class="chk-box cobrar_venta_check">
                                                    <label for="id_comanda_detalle_<?= $ls->id_comanda_detalle;?>"> <?php echo $ls->producto_nombre;?> // S/.<?php echo $ls->comanda_detalle_precio;?> // Cant. <?php echo $ls->comanda_detalle_cantidad?> // Total: <?php echo $ls->comanda_detalle_total;?> // Para: <?php echo $ls->comanda_detalle_despacho;?></label>
                                                    <input type="hidden" id="precio_total_detalle<?= $ls->id_comanda_detalle;?>" name="precio_total_detalle<?= $ls->id_comanda_detalle;?>" value="<?= $ls->comanda_detalle_total;?>">
                                                    <input type="hidden" id="tipo_afectacion_producto<?= $ls->id_comanda_detalle;?>" name="tipo_afectacion_producto<?= $ls->id_comanda_detalle;?>" value="<?= $ls->producto_precio_codigoafectacion;?>">
                                                    <input type="hidden" id="producto_precio_venta<?= $ls->id_comanda_detalle;?>" name="producto_precio_venta<?= $ls->id_comanda_detalle;?>" value="<?= $ls->comanda_detalle_precio;?>">
                                                    <input type="hidden" id="comanda_detalle_cantidad<?= $ls->id_comanda_detalle;?>" name="comanda_detalle_cantidad<?= $ls->id_comanda_detalle;?>" value="<?= $ls->comanda_detalle_cantidad;?>">
                                                    <input type="hidden" id="id_receta<?= $ls->id_comanda_detalle;?>" name="id_receta<?= $ls->id_comanda_detalle;?>" value="<?= $ls->id_receta;?>">
                                                </div>
                                                <?php
                                            }else{
                                                ?>
                                                <div class="col-md-12" style="color: lightgray">
                                                    <input type="checkbox" disabled  id="id_comanda_detalle_<?= $ls->id_comanda_detalle;?>">
                                                    <label for="id_comanda_detalle_<?= $ls->id_comanda_detalle;?>"> <?php echo $ls->producto_nombre;?> // S/.<?php echo $ls->comanda_detalle_precio;?> // Cant. <?php echo $ls->comanda_detalle_cantidad?> // Total: <?php echo $ls->comanda_detalle_total;?> // Para: <?php echo $ls->comanda_detalle_despacho;?></label>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <hr>
                                    <!--<div class="row" >
                                        <div class="col-md-12" style="font-weight: bold;">
                                            <input  type="checkbox" id="por_consumo" name="por_consumo" onchange="obtener_total()" value="-1" class="chk-box_1">
                                            <label for="por_consumo">POR CONSUMO // S/. <span id="por_consumo_precio">0.00</span> // Cant. 1 // Total: <span id="por_consumo_total">0.00</span></label>
                                            <input type="hidden" id="por_consumo_precio_valor">
                                            <input type="hidden" id="por_consumo_total_valor">
                                            <input type="hidden" id="por_consumo_cantidad_valor" value="1">
                                        </div>
                                    </div>
                                    <hr>-->
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <!--SECCION DE LAS ETIQUETAS O NOMBRES-->
                                                <div class="col-lg-8 col-sm-8 col-md-8 col-xs-8" style="text-align: right">
                                                    <label for="">DESCUENTO S/.</label><br>
                                                    <label for="" style="font-size: 14px;">OP. GRAVADAS</label><br>
                                                    <label for="" style="font-size: 14px;">IGV(18%)</label><br>
                                                    <label for="" style="font-size: 14px;">OP. EXONERADAS</label><br>
                                                    <label for="" style="font-size: 14px;">OP. INAFECTAS</label><br>
                                                    <label for="" style="font-size: 14px;">OP. GRATUITAS</label><br>
                                                    <label for="" style="font-size: 14px;">ICBPER</label><br>
                                                    <label for="" style="font-size: 17px;"><strong>TOTAL</strong></label><br>
                                                    <label for="" style="font-size: 14px;">VUELTO</label><br>
                                                    <label for="" style="font-size: 14px;">DESCUENTO TOTAL (S/.)</label>
                                                </div>
                                                <!--SECCION DE LOS VALORES-->
                                                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-2" style="text-align: right">
                                                    <label for=""><input style="border: 1px solid black;width: 70%" id="descuento_samiria" type="text" onkeyup="validar_numeros_decimales_dos(this.id);calcular_nuevo_descuento(this.value)"></label>
                                                    <input type="hidden" id="descuento_f" name="descuento_f">
                                                    <label for="" style="font-size: 14px;"><span id="op_gravadas">0.00</span></label><br>
                                                    <input type="hidden" id="op_gravadas_" name="op_gravadas_">
                                                    <label for="" style="font-size: 14px;"><span id="igv">0.00</span></label><br>
                                                    <input type="hidden" id="igv_" name="igv_">
                                                    <label for="" style="font-size: 14px;"><span id="op_exoneradas">0.00</span></label><br>
                                                    <input type="hidden" id="op_exoneradas_" name="op_exoneradas_">
                                                    <label for="" style="font-size: 14px;"><span id="op_inafectas">0.00</span></label><br>
                                                    <input type="hidden" id="op_inafectas_" name="op_inafectas_">
                                                    <label for="" style="font-size: 14px;"><span id="op_gratuitas">0.00</span></label><br>
                                                    <input type="hidden" id="op_gratuitas_" name="op_gratuitas_">
                                                    <label for="" style="font-size: 14px;"><span id="icbper">0.00</span></label><br>
                                                    <input type="hidden" id="icbper_" name="icbper_">
                                                    <label for="" style="font-size: 17px;"><span id="venta_total">0.00</span></label><br>
                                                    <input type="hidden" id="venta_total_" name="venta_total_">
                                                    <input type="hidden" id="venta_total_antiguo" name="venta_total_antiguo">
                                                    <label for="" style="font-size: 14px;"><span id="vuelto">0.00</span></label><br>
                                                    <input type="hidden" id="vuelto_" name="vuelto_">
                                                    <input type="hidden" id="tipo_desct_v" name="tipo_desct_v">

                                                    <label for="" style="font-size: 14px;"><span id="des_global">0.00</span></label>
                                                    <input type="hidden" value="0" id="des_global_" name="des_global_">
                                                    <input type="hidden" value="0" id="des_global_permanente" name="des_global_permanente">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <span style="color: red" id="mensaje_oculto"></span>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <label>Cambiar Concepto</label>
                                                    <select class="form-control" name="cambiar_concepto" id="cambiar_concepto" onchange="habilitar_concepto()">
                                                        <option value="1">NO</option>
                                                        <option value="2">SI</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-9" id="concep">
                                                    <label>Concepto</label>
                                                    <textarea class="form-control" name="concepto_nuevo" id="concepto_nuevo" cols="30" rows="2"></textarea>
                                                </div>
                                            </div>
                                            <!--<div class="form-group">
                                                <label for="">Monto Total</label>
                                                <input class="form-control" type="text" id="venta_total" name="venta_total" readonly>
                                                <label for=""><span id="igv_total">0.00</span></label>

                                            </div>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label class="col-form-label">Partir Pago</label>
                                        <select class="form-control" id="partir_pago" name="partir_pago" onchange="partir_pago()">
                                            <option value="1">SI</option>
                                            <option value="2" selected>NO</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="col-form-label">Tipo de Pago</label>
                                        <select class="form-control" id="id_tipo_pago" name="id_tipo_pago" onchange="forma_pago_aparecer()">
                                            <?php
                                            foreach ($tipo_pago as $tp){
                                                ?>
                                                <option <?php echo ($tp->id_tipo_pago == 3) ? 'selected' : '';?> value="<?php echo $tp->id_tipo_pago;?>"><?php echo $tp->tipo_pago_nombre;?></option>
                                                <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-4" id="desaparecer"></div>
                                    <div class="col-lg-3 no-show" id="div_tarjeta_3">
                                        <label for="codigo_venta">Código</label>
                                        <input type="text" id="codigo_venta" name="codigo_venta" class="form-control">
                                    </div>
                                    <div class="col-lg-4" id="div_monto_1">
                                        <label class="col-form-label">Monto 1</label>
                                        <input type="text" class="form-control" id="monto_1" onblur="monto_dividido(this.value)">
                                    </div>
<!--                                    <div class="col-lg-4">-->
<!--                                    </div>-->
                                    <div class="col-lg-4" id="div_tipo_pago_2">
                                        <label class="col-form-label">Tipo de Pago 2</label>
                                        <select class="form-control" id="id_tipo_pago_2" name="id_tipo_pago_2">
                                            <?php
                                            foreach ($tipo_pago as $tp){
                                                ?>
                                                <option <?php echo ($tp->id_tipo_pago == 3) ? 'selected' : '';?> value="<?php echo $tp->id_tipo_pago;?>"><?php echo $tp->tipo_pago_nombre;?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-lg-4" id="div_monto_2">
                                        <label class="col-form-label">Monto 2</label>
                                        <input type="text" class="form-control" id="monto_2" readonly>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="tipo_igv">Tipo Venta</label><br>
                                            <select class="form-control" id="tipo_venta" name="tipo_venta" onchange="Consultar_serie()">
                                                <!--<option value="">Seleccionar...</option>-->
                                                <?php
                                                $consultar_existe_nota_venta = $this->pedido->consultar_existe_en_nota_venta_detalle($dato_pedido->id_comanda);
                                                (count($consultar_existe_nota_venta) != count($pedidos))?$en=true:$en=false;
                                                if($en){
                                                    ?>
                                                    <option value="20">Nota de Venta</option>
                                                    <?php
                                                }
                                                ?>
                                                <option value="03" selected>BOLETA</option>
                                                <option value="01">FACTURA</option>
                                                <!--<option value= "07">NOTA DE CREDITO</option>
                                                <option value= "08">NOTA DE DEBITO</option>-->
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-6 col-ms-6 col-xs-6">
                                        <div class="form-group">
                                            <label for="serie">Serie</label><br>
                                            <select class="form-control" id="serie" name="serie" onchange="ConsultarCorrelativo()" disabled>
                                                <option value="">Seleccionar...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-ms-6 col-xs-6">
                                        <div class="form-group">
                                            <label for="correlativo">Correlativo</label><br>
                                            <input type="text" class="form-control" id="correlativo" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="">Pagó con:</label><br>
                                        <input type="text" class="form-control" name="pago_cliente" id="pago_cliente" onkeypress="return validar_numeros_decimales_dos(this.id)" onkeyup="calcular_vuelto()" >
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="tipo_moneda">Moneda</label><br>
                                            <select class="form-control" id="tipo_moneda" name="tipo_moneda">
                                                <option value="1">SOLES</option>
                                                <option value="2">DOLARES</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="imprimir">¿Imprimir?</label><br>
                                            <select class="form-control" id="imprimir" name="imprimir">
                                                <option value="1">SI</option>
                                                <option value="2">NO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="gratis">Cortesía</label><br>
                                            <select class="form-control" onchange="select_cortesia()" id="gratis" name="gratis">
                                                <option value="1">SI</option>
                                                <option value="2" selected>NO</option>
                                            </select>
                                            <input type="hidden" name="id_comanda" id="id_comanda" value="<?= $dato_pedido->id_comanda?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <label>Tipo Documento</label><br>
                                        <select  class="form-control" name="select_tipodocumento" id="select_tipodocumento" onchange="seleccionar_tipodocumento()">
                                            <option value="">Seleccionar...</option>
                                            <?php
                                            foreach ($tipos_documento as $td){
                                                ($td->id_tipodocumento==2)?$sele='selected':$sele='';
                                                echo "<option value='".$td->id_tipodocumento."' $sele >".$td->tipodocumento_identidad."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-5" id="div_observacion_cortesia">
                                        <label>Observación de Cortesía</label><br>
                                        <textarea class="form-control" name="observacion_cortesia" id="observacion_cortesia" cols="30" rows="2"></textarea>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="">DNI / RUC </label><br>
                                                    <input type="text" class="form-control" id="cliente_numero" value="11111111" onchange="consultar_documento(this.value)" onkeyup="return validar_numeros(this.id)">
                                                    <!--<label for="" id="cliente_numero"></label>-->
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="form-group">
                                                    <label for="">Cliente</label><br>
                                                    <textarea rows="2" class="form-control" type="text" id="cliente_nombre" name="cliente_nombre" maxlength="500" placeholder="Ingrese Razón Social...">ANONIMO</textarea>

                                                    <!--<label for="" id="cliente_nombre"></label>-->
                                                    <input type="hidden" id="id_cliente">
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="">Dirección</label><br>
                                                    <textarea rows="2" class="form-control" type="text" id="cliente_direccion" name="cliente_direccion" maxlength="500" placeholder="Ingrese Dirección..."></textarea>
                                                    <!--<label for="" id="cliente_direccion"></label>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="form-group col-lg-9">
                                        <input required autocomplete="off" name="parametro_c" onkeyup="buscar_cliente()" type="text" class="form-control" id="parametro_c" placeholder="Buscar Cliente">
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <button class="btn btn-success" style="width: 60%"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div id="cliente" class="table-responsive">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="form-group col-lg-5">
                                        <input required autocomplete="off" name="parametro_piscina" onchange="buscar_descuento()" type="text" class="form-control" id="parametro_piscina" placeholder="BUSCAR DESCUENTO...">
                                    </div>
                                    <div class="col-lg-3"></div>
                                    <div class="form-group col-lg-4" style="text-align: right;">
                                        <input type="hidden" id="datos_detalle_pedido" name="datos_detalle_pedido">
                                        <input type="hidden" id="id_mesa" name="id_mesa" value="<?= $id;?>">
                                        <input type="hidden" id="id_boleta_pis" name="id_boleta_pis" value="">
                                        <input type="hidden" id="correlativo_pis" name="correlativo_pis" value="">
                                        <input type="hidden" id="tipo_desct" name="tipo_desct" value="">
                                        <button type="button" onclick="agregar()" class="btn btn-danger" id="btn-agregar"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
                                        <button type="button" onclick="convertir()" class="btn btn-success" id="btn-convertir"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div id="descuento_piscina" class="table-responsive">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!--<button type="button" onclick="agregar()" class="btn btn-success" id="btn-agregar"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>-->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cantidad" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 40% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Generar Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div id="persona">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="">Cantidad de Personas</label>
                                <input type="number" class="form-control" id="comanda_cantidad_personas" name="comanda_cantidad_personas" value="<?= $dato->comanda_cantidad_personas;?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cambiar_cantidad()" class="btn btn-success" id="btn-agregar"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pre_cuenta_check" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 60% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Generar Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="" enctype="multipart/form-data" id="imprimir_pre_cuenta">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div id="persona">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card shadow mb-4">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" width="100%" cellspacing="0">
                                                    <thead class="text-capitalize">
                                                    <tr>
                                                        <th><i class="fa fa-print"></i></th>
                                                        <th>Producto</th>
                                                        <th>Estado</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $det = 0;
                                                    $det_cero = 0;
                                                    $total_x_cancelar = 0;
                                                    $a = 1;
                                                    foreach ($pedidos as $p){
                                                        $estilo = "";
                                                        if($p->comanda_detalle_estado_venta == "0"){
                                                            $estilo = "style=\"background-color: #ea817c\"";
                                                            $total_x_cancelar = $total_x_cancelar + $p->comanda_detalle_total;
                                                        }
                                                        ?>
                                                        <tr id="detalle<?= $p->id_comanda_detalle;?>" <?= $estilo;?>>
                                                            <td style="text-align: center"><input checked name='imprimir_detalle[]' type='checkbox' id='imprimir_detalle[]' class='chk-box' value='<?= $p->id_comanda_detalle;?>'></td>
                                                            <td style="font-size: 13px;">
                                                                <p><?php echo $p->producto_nombre;?> // S/.<?php echo $p->comanda_detalle_precio;?> // Cant. <?php echo $p->comanda_detalle_cantidad?> // Total: <?php echo $p->comanda_detalle_total;?> // Para: <?php echo $p->comanda_detalle_despacho;?> // Oservación: <?= $p->comanda_detalle_observacion;?>
                                                                </p>
                                                            </td>

                                                            <?php
                                                            $consultar_estado = $this->pedido->consultar($p->id_comanda_detalle);
                                                            (!empty($consultar_estado))?$det++:$det_cero++;
                                                            ($p->comanda_detalle_estado_venta == 1)?$resultado=true:$resultado=false;
                                                            if($resultado){
                                                                ?>
                                                                <td style="font-size: 13px;">PAGADO</td>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                <td style="font-size: 13px;">Pendiente de Pago</td>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tr>
                                                        <?php
                                                        $a++;
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
                </div>
                <div class="modal-footer">
                    <div class="col-lg-12" style="text-align: center">
                        <input type="hidden" id="comanda_ultimo" name="comanda_ultimo" value="<?= $ultimo_valor_;?>">
                        <button type="submit" class="btn btn-success" id="btn-print-precuenta"><i class="fa fa-print"></i> Imprimir</button>
                        <button type="button" class="btn btn-secondary" onclick="" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="vincular_habitacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 40% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Vincular Habitación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-3"></div>
                       <div class="col-lg-5">
                           <label for="">Habitación</label>
                           <select name="id_habitacion" id="id_habitacion" class="form-control" onchange="llenar_dat_cliente()">
                               <option value="" disabled selected>--Seleccione--</option>
                                <?php
                                    foreach ($habitaciones as $h){?>
                                        <option value="<?=$h->id_habitacion?>"><?=$h->habitacion_nro?></option>
                                    <?php } ?>
                           </select>
                       </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-3">
                            <label for="">Documento</label>
                            <input type="hidden" id="id_rack" name="id_rack">
                            <input type="text" id="dni_cliente" name="dni_cliente" class="form-control" disabled>
                        </div>
                        <div class="col-lg-9">
                            <label for="">Nombre y Apellido</label>
                            <input type="text" id="nombre_cliente" name="nombre_cliente" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="row" style="display: none">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-5">
                            <h5 class="text-center">Precio total s/ <?=$dato_pedido->comanda_total?></h5>
                        </div>
                    </div>
                    <div class="row" style="display: none">
                        <div class="col-lg-3">
                            <label for="">Moneda</label>
                            <input type="text" class="form-control" id="moneda_cliente" name="moneda_cliente">
                        </div>
                        <div class="col-lg-3">
                            <label for="">Precio</label>
                            <input type="text" class="form-control" id="precio_cliente" name="precio_cliente" onkeyup="return validar_numeros_decimales_dos(this.id)">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btn-vincular_habitacion" onclick="guardar_vinculacion()"><i class="fa fa-save fa-sm text-white-50"></i> Vincular</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cargar_pedido_cuenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 40% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cargar a Cuenta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                       <div class="col-lg-12">
                           <label for="">CLIENTE</label>
                           <select name="id_cliente_cuenta" id="id_cliente_cuenta" class="form-control" onchange="llenar_datos_clientillo()" style="width: 100%;">
                               <option value="" selected>--Seleccione--</option>
                                <?php
                                    foreach ($clientes_cuenta as $h){?>
                                        <option value="<?=$h->id_cliente?>"><?=$h->cliente_razonsocial?> <?=$h->cliente_nombre?> || <?= $h->cliente_numero?></option>
                                    <?php } ?>
                           </select>
                       </div>
                    </div>
                    <br>
                    <div class="row" style="display:none;">
                        <div class="col-lg-3">
                            <label for="">Documento</label>
                            <input type="hidden" id="id_rack" name="id_rack">
                            <input type="text" id="dni_cliente_cuentas" name="dni_cliente_cuentas" class="form-control" disabled>
                        </div>
                        <div class="col-lg-9">
                            <label for="">Nombre y Apellido</label>
                            <input type="text" id="nombre_cliente_cuentas" name="nombre_cliente_cuentas" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="row" style="display: none">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-5">
                            <h5 class="text-center">Precio total s/ <?=$dato_pedido->comanda_total?></h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btn-vincular_cargo" onclick="guardar_cargo_cuenta()"><i class="fa fa-save fa-sm text-white-50"></i> GUARDAR</button>
            </div>
        </div>
    </div>
</div>

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
            <!-- Page Heading -->
            <br>
            <div class="row" style="padding-bottom: 30px">
                <div class="col-lg-2" style="">
                    <div class="col-lg-12" style="background: white;color: #171718;box-shadow: 0 6px 12px 0 rgba(3,2, 2,0.15);">
                        <div class="col-lg-12" style="padding-left: 5px;padding-right: 5px;padding-top: 10px;padding-bottom: 30px">
                            <button class="btn boton3" data-toggle="modal" data-target="#cambiar_mesa" style="width: 100%;"><i class="fa fa-retweet"></i><span style="font-size: 11pt"> Cambiar Mesa</span></button>
                            <br>
                            <br>
                            <button class="btn btn-primary boton3" data-toggle="modal" data-target="#transferir_pedido" style="width: 100%"><i class="fa fa-refresh"></i> <span style="font-size: 11pt">Transferir</span></button>
                            <br>
                            <br>
                            <button style="display: none" class="btn btn-primary" data-toggle="modal" data-target="#cantidad" ><i class="fa fa-save"></i> Cant. Personas</button>
                            <button class="btn boton3" id="nuevo_pedido" style="width: 100%;"><i class="fa fa-pencil" ></i> <span style="font-size: 11pt">Nuevo Pedido</span></button>
                            <br>
                            <br>
                            <button class="btn boton-eliminar" data-toggle="modal" data-target="#eliminar_todo" style="width: 100%" onclick="llenar_borrar_todo(<?= $ultimo_valor_?>,'<?= $id?>')" title='Eliminar Todo'><i class="fa fa-trash"></i><span style="font-size: 11pt"> Eliminar Todo</span></button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8" style="box-shadow: 0 6px 12px 0 rgba(3,2, 2,0.15);color: #554c4c">
                    <div class="col-lg-12 text-center" style="padding-top: 13px">
                        <h3><b>Pedido N° <?= $dato_pedido->comanda_correlativo;?></b></h3>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-6" style="padding-left: 30px">
                            <table class="table table-bordered" style="color: #554c4c">
                                <tbody>
                                    <tr style="background: #f1ebeb">
                                        <td><i class="fa fa-table"></i> <span style="padding-left: 6px"></span><?= $dato_pedido->mesa_nombre;?></div></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="fa fa-user"></i> <span style="padding-left: 6px"></span>Personas:  <?= $dato_pedido->comanda_cantidad_personas;?>

                                        </td>
                                    </tr>
                                    <tr style="background: #f1ebeb">
                                        <td>
                                            <i class="fa fa-calendar"></i><span style="padding-left: 6px"></span> Fecha: <?= date('d-m-Y',strtotime($dato_pedido->comanda_fecha_registro))?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="fa fa-calendar"></i> <span style="padding-left: 6px"></span>Hora: <?= date('H:i:s',strtotime($dato_pedido->comanda_fecha_registro))?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <h5 style="padding-left: 50px;color: #554c4c">TOTAL DEL PEDIDO <button class="btn btn-success">s/.<?= $dato_pedido->comanda_total; ?></button></h5>
                        </div>
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                                    <button class="btn btn-danger" style="background: #dd1036;height: 100px;width: 340px;border-radius: 113px"><h5>Falta cancelar <br> <span>s/</span> <span id="total_por_cancelar" style=" font-size: 30pt;padding-top: 10px"></span></h5></button>
                            </div>
                            <br>
                            <?php
                            //$fecha = date('Y-m-d');
                            //$id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
                            //$caja_apertura_fecha = $this->pedido->listar_ultima_fecha($fecha);
                            ?>
                            <div class="col-lg-12">
                                <button type="button" style="background: #0c5744;height: 100px;border-radius: 113px;font-size: 17pt" id="btn_generarventa" class="btn btn-block boton-guardar" data-toggle="modal" data-target="#ventas">
                                    <i class="fa fa-money"></i> Cobrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="padding-top: 10px; padding-bottom: 30px">
                <div class="col-lg-12">
                    <!-- DataTales Example -->
                    <div class=" mb-4">
                            <div class="row">
                                <div class="col-lg-2" style="padding-bottom: 30px;background: white;color: #171718;box-shadow: 0 6px 12px 0 rgba(3,2, 2,0.15);">
                                    <br>
                                        <a id="imprimir_ticket_comanda" style="color: white;width: 100%" class="btn boton-comanda" ><i class="fa fa-print"></i> <span style="font-size: 11pt">Imprimir Comanda</span></a>
                                    <br>
                                    <br>
                                    <?php
                                    $entre=true;

                                    if($id_rol == 2 || $id_rol == 3 || $id_rol ==5 || $id_rol ==7 || $id_rol ==4){
                                        ?>
                                        <a id="imprimir_ticket" class="btn boton3" style="color: blue;width: 100%" onclick="ticket_pedido(<?= $ultimo_valor_; ?>)"><i class="fa fa-print"></i> <span style="font-size: 11pt">Imprimir Pre Cuenta</span></a>
                                        <br>
                                        <br>
                                        <a style="color: white; width: 100%" data-toggle="modal" data-target="#vincular_habitacion" onclick="llenar_precio(<?=$dato_pedido->comanda_total?>)" class="btn boton-habitacion" ><i class="fa fa-building"></i><span style="font-size: 11pt"> Asignar a Habitación</span></a>
                                        <br>
                                        <br>
                                        <button style="width: 100%" class="btn boton3" data-toggle="modal" data-target="#cargar_pedido_cuenta" ><i class="fa fa-folder"></i> Cargar a Cuenta</button>
                                    <?php }
                                    ?>
                                </div>
                                <div class="col-lg-10" style="background: white;color: #171718;box-shadow: 0 6px 12px 0 rgba(3,2, 2,0.15)">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="" width="100%" cellspacing="0" style="background: white;color: #171718;box-shadow: 0 6px 12px 0 rgba(3,2, 2,0.15)">
                                            <div class="container-fluid">

                                                <br>
                                            </div>
                                            <div class="row" style="display: none">
                                                <div class="col-lg-3">
                                                    <input class="form-control" type="password" id="contrase" name="contrase" placeholder="Ingrese Contraseña...">
                                                </div>
                                            </div>
                                            <br>
                                            <thead class="text-capitalize">
                                            <tr>
                                                <th>Mesero</th>
                                                <th>Producto</th>
                                                <th>Observación</th>
                                                <!--<th>N° Pedido</th>-->
                                                <th>Prec Unit</th>
                                                <th style="width: 65px;">Cantidad</th>
                                                <th>Total</th>
                                                <th>Fecha / Hora</th>
                                                <th>Acción</th>
                                                <th>Estado</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $det = 0;
                                            $det_cero = 0;
                                            $total_x_cancelar = 0;
                                            $a = 1;
                                            foreach ($pedidos as $p){
                                                $buscar_id = $this->pedido->buscar_user($p->id_usuario_a);
                                                $estilo = "";
                                                if($p->comanda_detalle_estado_venta == "0"){
                                                    $estilo = "style=\"background-color: #white\"";
                                                    $total_x_cancelar = $total_x_cancelar + $p->comanda_detalle_total;
                                                }
                                                ?>
                                                <tr id="detalle<?= $p->id_comanda_detalle;?>" <?= $estilo;?>>
                                                    <td><?= $buscar_id->persona_nombre;?> <?= $buscar_id->persona_apellido_paterno;?></td>
                                                    <td><?= $p->producto_nombre;?></td>
                                                    <td><?= $p->comanda_detalle_observacion?></td>
                                                    <!--<td><?= $p->comanda_correlativo;?></td>-->
                                                    <input type="hidden" id="comanda_detalle_precio<?= $p->id_comanda_detalle;?>" value="<?= $p->comanda_detalle_precio;?>">
                                                    <td><?= $p->comanda_detalle_precio;?></td>

                                                    <?php
                                                    if($p->comanda_detalle_estado_venta == "0"){ ?>
                                                        <td><input class="form-control" type="number" id="cantidad_detalle_cantidad<?= $p->id_comanda_detalle;?>" value="<?= $p->comanda_detalle_cantidad;?>" onchange="cambiar_comanda_detalle_cantidad(<?= $p->id_comanda_detalle;?>, <?= $p->id_comanda;?>)"></td>
                                                        <?php
                                                    }else{ ?>
                                                        <td><?= $p->comanda_detalle_cantidad;?></td>
                                                        <?php
                                                    }
                                                    ?>

                                                    <td><span id="span_comanda_detalle_total<?= $p->id_comanda_detalle;?>"><?= $p->comanda_detalle_total;?></span></td>
                                                    <td><?= date('d-m-Y H:i:s', strtotime($p->comanda_detalle_fecha_registro));?>
                                                    <td>
                                                        <?php
                                                        if($p->comanda_detalle_estado_venta == 0){
                                                            ?>
                                                            <a class="andreBotonAccionEliminar btn" type="button" id="btn-eliminar_pedido" onclick="cargar_valores(<?= $p->id_comanda_detalle;?>,'<?= $p->id_comanda?>','<?= $p->id_mesa?>')" data-toggle="modal" data-target="#eliminar" title='Eliminar'><i class='fa fa-times eliminar margen'></i></a>
                                                            <a class="andreBotonAccionTransferir btn" type="button" id="btn-cambiar_pedido" onclick="cargar_transferencia(<?= $p->id_comanda_detalle;?>)" data-toggle="modal" data-target="#cambiar_pedido" title='TRANSFERIR'><i class='fa fa-reply eliminar margen'></i></a>
                                                            <?php
                                                            if($p->comanda_detalle_cantidad > 1){
                                                                ?>
                                                                <button style="display: none" class="btn btn-success" id="btn-modificar_pedido<?=$p->id_comanda_detalle?>" onclick="dividir_pedido(<?=$p->id_comanda_detalle?>)" title='DIVIDIR PEDIDO'><i class="fa fa-refresh text-white"></i></button>
                                                                <?php
                                                            }
                                                        }
                                                        else{
                                                            ?>
                                                            <!-- <a class="btn btn-danger" type="button" id="btn-eliminar_servicio" onclick="preguntar('¿El pedido no se puede eliminar porque ya esta siendo preparado?','eliminar_comanda_detalle','Si','No',<?= $p->id_comanda_detalle;?>)" data-toggle="tooltip" title='No se Puede Eliminar'><i class='fa fa-eyes text-white eliminar margen'></i></a> -->
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>

                                                    <?php
                                                    $consultar_estado = $this->pedido->consultar($p->id_comanda_detalle);
                                                    (!empty($consultar_estado))?$det++:$det_cero++;
                                                    ($p->comanda_detalle_estado_venta == 1)?$resultado=true:$resultado=false;
                                                    if($resultado){
                                                        ?>
                                                        <td>PAGADO</td>
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <td>Pendiente de Pago</td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <?php
                                                $a++;
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row" style="padding-bottom: 30px">

                                    </div>
                                </div>
                            </div>
                            <br>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>pedido.js"></script>

<script>

    function forma_pago_aparecer(){
        let tipo_pago = $('#id_tipo_pago').val();
        if(tipo_pago == 1){
            $('#div_tarjeta_3').show();
            $('#desaparecer').hide();
        }else{
            $('#div_tarjeta_3').hide();
            $('#desaparecer').show();
        }
    }

    $(document).ready(function(){
      $("#btn-agregar").show();
      $("#btn-convertir").hide();
      $("#concep").hide();
      $("#id_cliente_cuenta").select2();
    })

    function habilitar_concepto(){
        let concepto = $("#cambiar_concepto").val();
        if(concepto==1){
            $("#concep").hide();
        }else{
            $("#concep").show();
        }
    }

    const llenar_precio = (precio)=>{
        $('#precio_cliente').val(precio);
    }

    function buscar_descuento(){
        var parametro_piscina =  $("#parametro_piscina").val();
        var comanda_ultimo = $("#comanda_ultimo").val();
        var id_mesa = $("#id_mesa").val();

        $.ajax({
            type: "POST",
            url: urlweb + "api/Pedido/buscar_descuento",
            data: "parametro_piscina="+parametro_piscina + "&comanda_ultimo="+comanda_ultimo + "&id_mesa=" + id_mesa,
            dataType: 'json',
            success:function (r) {
                $("#descuento_piscina").html(r);
            }
        });
    }

    function realizar_resta_piscina(total,id,correlativo,tipo_desct,total_cd){
        $("#id_boleta_pis").val(id);
        $("#correlativo_pis").val(correlativo);
        $("#des_global_permanente").val(total);
        $("#tipo_desct").val(tipo_desct);

        calculo_maldito(0, total,tipo_desct,total_cd)
        /*if(des_final !== 0){
            if(total_consumido > des_final){
                let nuevo_total = total_consumido - des_final;
                $("#btn-agregar").show();
                $("#btn-convertir").hide();

                $("#venta_total_").val(nuevo_total.toFixed(2));
                $("#op_exoneradas_").val(nuevo_total.toFixed(2));
                $("#venta_total").html(nuevo_total.toFixed(2));
                $("#op_exoneradas").html(nuevo_total.toFixed(2));

                $("#des_global").html(des_final.toFixed(2));
                $("#des_global_").val(des_final.toFixed(2));
                $("#des_global_permanente").val(des_final.toFixed(2));
            }else{
                let nuevo_total = 0;
                $("#btn-agregar").hide();
                $("#btn-convertir").show();
                $("#venta_total_").val(nuevo_total.toFixed(2));
                $("#op_exoneradas_").val(nuevo_total.toFixed(2));
                $("#venta_total").html(nuevo_total.toFixed(2));
                $("#op_exoneradas").html(nuevo_total.toFixed(2));
                $("#des_global").html(des_final.toFixed(2));
                $("#des_global_").val(des_final.toFixed(2));
                $("#des_global_permanente").val(des_final.toFixed(2));
            }
        }*/
        $('#descuento_piscina').html('');
    }

    function calcular_nuevo_descuento(valor){
        //let var_total = $("#venta_total_").val() * 1;
        calculo_maldito(valor,0,0)
        /*let var_total_antiguo = $("#venta_total_antiguo").val() * 1;
        let var_desc_inicial = $("#des_global_permanente").val() * 1;
        if(valor >= var_total_antiguo){
            respuesta('¡El valor del descuento no debe ser mayor o igual al valor del total!', 'error');
            $("#descuento_samiria").val("");
            $("#venta_total_").val(var_total_antiguo.toFixed(2));
            $("#op_exoneradas_").val(var_total_antiguo.toFixed(2));
            $("#venta_total").html(var_total_antiguo.toFixed(2));
            $("#op_exoneradas").html(var_total_antiguo.toFixed(2));
            $("#des_global").html(0.00);
            $("#des_global_").val(0.00);
        }else{
            let var_descuento = valor * 1;
            let descuento_restado = var_total_antiguo - valor * 1;
            let valore_final = descuento_restado.toFixed(2);
            let descuen_final = var_desc_inicial + var_descuento;
            //OPERACIONES PARA MOSTRAR
            $("#venta_total_").val(valore_final);
            $("#op_exoneradas_").val(valore_final);
            $("#venta_total").html(valore_final);
            $("#op_exoneradas").html(valore_final);
            $("#des_global").html(descuen_final.toFixed(2));
            $("#des_global_").val(descuen_final.toFixed(2));
            //$("#des_global_permanente").val(descuen_final.toFixed(2));
        }*/
    }

    function calculo_maldito(valor1, valor2,tipo_desct,total_cd) {
        let monto1 = 0
        let monto2 = 0
        let tipo_desct_ = tipo_desct * 1;
        $("#tipo_desct_v").val(tipo_desct_);
        let total_cd_ = total_cd * 1;
        if(valor1 === 0){
            monto1 = $('#descuento_samiria').val() * 1
        }else{
            monto1 = valor1 * 1
        }
        if(valor2 === 0){
            monto2 = $("#des_global_permanente").val() * 1
        }else{
            monto2 = valor2 * 1
        }
        let des_final = monto1 + monto2;
        let total_consumido = $("#venta_total_antiguo").val() * 1;
        let nuevo_total = total_consumido - des_final;

        //AQUI SERA EL DESCUENTO DENTRO DEL HORA
        /*let fechaActual = new Date();
        let diaActual = fechaActual.toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase(); // Obtener el día actual en formato texto
        let horaActual = fechaActual.getHours() + ":" + fechaActual.getMinutes(); // Obtener la hora actual en formato "HH:mm:ss"

        let dias_permitidos = ["thursday", "friday", "saturday"];
        let dias_permitidos_2 = ["saturday","sunday"];
        let hora_inicio = "19:00"; // Hora de inicio permitida
        let hora_fin = "23:30";   // Hora de fin permitida
        let hora_inicio_f = "12:30";
        let hora_fin_f = "17:00";*/
        if(des_final !== 0) {
            if(total_consumido > des_final && tipo_desct_ !==4){
                $("#btn-agregar").show();
                $("#btn-convertir").hide();
                $("#venta_total_").val(nuevo_total.toFixed(2));
                $("#op_exoneradas_").val(nuevo_total.toFixed(2));
                $("#venta_total").html(nuevo_total.toFixed(2));
                $("#op_exoneradas").html(nuevo_total.toFixed(2));
                $("#des_global").html(des_final.toFixed(2));
                $("#des_global_").val(des_final.toFixed(2));
                //$("#des_global_permanente").val(des_final.toFixed(2));
            }else{
                let nuevo_total = 0;
                if(tipo_desct_ ===4){
                    if(total_cd_ !==0){
                        $("#btn-agregar").show();
                        $("#btn-convertir").hide();
                        $("#venta_total_").val(total_cd_.toFixed(2));
                        $("#op_exoneradas_").val(total_cd_.toFixed(2));
                        $("#venta_total").html(total_cd_.toFixed(2));
                        $("#op_exoneradas").html(total_cd_.toFixed(2));
                        $("#des_global").html(nuevo_total.toFixed(2));
                        $("#des_global_").val(nuevo_total.toFixed(2));
                        //AQUI IRA EL MENSAJE PARA AVISAR QUE HAY PRODUCTOS PASADOS DE LA FECHA
                        $("#mensaje_oculto").html('* Existe uno o más ítems que no están en el rango de hora y no entrarán en el descuento');
                    }else{
                        $("#btn-agregar").hide();
                        $("#btn-convertir").show();
                        $("#venta_total_").val(nuevo_total.toFixed(2));
                        $("#op_exoneradas_").val(nuevo_total.toFixed(2));
                        $("#venta_total").html(nuevo_total.toFixed(2));
                        $("#op_exoneradas").html(nuevo_total.toFixed(2));
                        $("#des_global").html(total_consumido.toFixed(2));
                        $("#des_global_").val(total_consumido.toFixed(2));
                        $("#mensaje_oculto").html('');
                    }

                }else{
                    $("#btn-agregar").hide();
                    $("#btn-convertir").show();
                    $("#venta_total_").val(nuevo_total.toFixed(2));
                    $("#op_exoneradas_").val(nuevo_total.toFixed(2));
                    $("#venta_total").html(nuevo_total.toFixed(2));
                    $("#op_exoneradas").html(nuevo_total.toFixed(2));
                    $("#des_global").html(des_final.toFixed(2));
                    $("#des_global_").val(des_final.toFixed(2));
                }
            }
        } else{
            $("#venta_total_").val(nuevo_total.toFixed(2));
            $("#op_exoneradas_").val(nuevo_total.toFixed(2));
            $("#venta_total").html(nuevo_total.toFixed(2));
            $("#op_exoneradas").html(nuevo_total.toFixed(2));
            $("#des_global").html(des_final.toFixed(2));
            $("#des_global_").val(des_final.toFixed(2));
        }
    }

    const llenar_dat_cliente =()=>{
        let id = $('#id_habitacion').val();
        if(id != ""){
            $.ajax({
                type: "POST",
                url: urlweb + "api/Pedido/llenar_dat_cliente",
                data: { 'id': id },
                dataType: 'json',
                success: (r)=>{
                    if(r.result.code != "" && r.result.code != 2 && r.result.code != false){
                        let dato = JSON.parse(JSON.stringify(r.result.code));
                        let {
                           cliente_nombre,cliente_numero,id_rack,moneda
                        } = dato;
                        $('#nombre_cliente').val(cliente_nombre);
                        $('#dni_cliente').val(cliente_numero);
                        $('#moneda_cliente').val(moneda);
                        $('#id_rack').val(id_rack)
                    }
                }
            });
        }
    }
    $(document).ready(function (){
        $("#mostrar").hide();
        Consultar_serie();
        var por_cancelar_ = <?= $total_x_cancelar;?>;
        var por_cancelar = por_cancelar_.toFixed(2);
        $("#total_por_cancelar").html(por_cancelar);
        partir_pago();
        select_cortesia();

        $('#parametro').keypress(function(e){
            if(e.which === 13){
                productos_nuevo();
            }
        });
        checkbox_todo();
    });

    var contenido_pedido = "";
    var details = "";
    function ir_caja(){
        location.href = urlweb +  'Admin/inicio';
    }

    function cargar_valores(id_comanda_detalle,id_comanda,id_mesa){
        $("#id_comanda_detalle_eliminar").val(id_comanda_detalle);
        $("#id_comanda_eliminar").val(id_comanda);
        $("#id_mesa_eliminar").val(id_mesa);
    }

    function llenar_borrar_todo(id_comanda,id_mesa){
        $("#id_comanda_todo").val(id_comanda);
        $("#id_mesa_todo").val(id_mesa);
    }

    function cargar_transferencia(id_comanda_detalle){
        $("#id_comanda_detalle_transferir").val(id_comanda_detalle);
    }

    function ticket_pedido(id){
        var boton = 'imprimir_ticket';
        $.ajax({
            type: "POST",
            url: urlweb + "api/Pedido/ticket_pedido",
            data: "id=" + id,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'imprimiendo...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-print\"></i> Pre Cuenta", false);
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
    $('#imprimir_ticket_comanda').on('click',function(){
        $('#pre_cuenta_check').modal({backdrop: 'static', keyboard: false})
    })
    function ticket_comanda_pedido(id_comanda){
        var boton = 'imprimir_ticket_comanda';
        $.ajax({
            type: "POST",
            url: urlweb + "api/Pedido/ticket_comanda",
            data: "id=" + id_comanda,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'imprimiendo...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-print\"></i> Comanda", false);
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

    function consultar_documento(valor){
        var tipo_doc = $('#select_tipodocumento').val();

        $.ajax({
            type: "POST",
            url: urlweb + "api/Clientes/obtener_datos_cliente",
            data: "numero="+valor,
            dataType: 'json',
            success:function (r) {
                if(r.result.resultado == 1){
                    $("#cliente_nombre").val(r.result.name+ ' ' + r.result.first_name+ ' ' + r.result.last_name);
                    $("#cliente_direccion").val(r.result.direccion);
                }else{
                    if(tipo_doc == "2"){
                        ObtenerDatosDni(valor);
                    }else if(tipo_doc == "4"){
                        if(valor.length == 11){
                            ObtenerDatosRuc(valor)
                        }else{
                            respuesta('¡El RUC tiene que teer 11 dígitos!', 'error');
                        }
                    }
                }
            }
        });

    }
    function ObtenerDatosDni(valor){
        var numero_dni =  valor;

        var formData = new FormData();
        formData.append("token", "uTZu2aTvMPpqWFuzKATPRWNujUUe7Re1scFlRsTy9Q15k1sjdJVAc9WGy57m");
        formData.append("dni", numero_dni);
        var request = new XMLHttpRequest();
        request.open("POST", "https://api.migo.pe/api/v1/dni");
        request.setRequestHeader("Accept", "application/json");
        request.send(formData);
        //$('.loader').show();
        request.onload = function() {
            var data = JSON.parse(this.response);
            if(data.success){
                //$('.loader').hide();
                console.log("Datos Encontrados");

                //$('#cotizacion_beneficiario').val(data.nombre);
                $("#cliente_nombre").val(data.nombre);
                //$('#cliente_direccion').val('');
                //$('#cliente_condicion').val("HABIDO");
            }else{
                //$('.loader').hide();
                console.log(data.message);
            }
        };

    }

    function ObtenerDatosRuc(valor){
        var numero_ruc =  valor;

        var formData = new FormData();
        formData.append("token", "uTZu2aTvMPpqWFuzKATPRWNujUUe7Re1scFlRsTy9Q15k1sjdJVAc9WGy57m");
        formData.append("ruc", numero_ruc);
        var request = new XMLHttpRequest();
        request.open("POST", "https://api.migo.pe/api/v1/ruc");
        request.setRequestHeader("Accept", "application/json");
        request.send(formData);
        $('.loader').show();
        request.onload = function() {
            var data = JSON.parse(this.response);
            if(data.success){
                //$('.loader').hide();
                console.log("Datos Encontrados");
                //$('#cotizacion_beneficiario').val(data.nombre_o_razon_social);
                $("#cliente_nombre").val(data.nombre_o_razon_social);
                $("#cliente_direccion").val(data.direccion);
            }else{
                //$('.loader').hide();
                console.log(data.message);
            }
        };
        /*$.ajax({
            type: "POST",
            url: urlweb + "api/Cliente/obtener_datos_x_ruc",
            data: "numero_ruc="+numero_ruc,
            dataType: 'json',
            success:function (r) {
                $("#client_name").val(r.result.razon_social);
            }
        });*/
    }
    //INICIO - AGREGAR NUEVOS PEDIDOS
    var opciones_id_="";
    function add_pedido_nuevo(){
        $("input.sol_check:checkbox:checked").each(function() {
            details += $(this).val() + " // ";
            opciones_id_ += $(this).attr('id') + "/-/";
        });
        details += $("#comanda_detalle_observacion").val();
        $('#servicio_check_detalle').val(details);
        var servicio_check_detalle_ = $('#servicio_check_detalle').val();
        //INICIO DEL MILAGRO2
        $("#id_escondido_").val(opciones_id_);
        var id_escondido_ = $('#id_escondido_').val();

        //var comanda_detalle_observacion = $("#comanda_detalle_observacion").val();
        var producto_nombre = $("#producto_nombre").val();
        var id_producto = $("#id_producto").val();
        var comanda_detalle_cantidad = $("#comanda_detalle_cantidad").val() * 1;
        var comanda_detalle_precio = $("#comanda_detalle_precio").val() * 1;
        var comanda_detalle_despacho = $("#comanda_detalle_despacho").val();

        let stock = $('#stock_escondido').val() * 1;
        var subtotal = comanda_detalle_cantidad * comanda_detalle_precio;
        subtotal.toFixed(2);
        subtotal = subtotal.toFixed(2);

        /*total_total = total_total + subtotal;
        total_total.toFixed(2);
        total_total = parseFloat(total_total);*/
        if(stock >= comanda_detalle_cantidad){
            if(id_producto !="" && comanda_detalle_cantidad!="" && comanda_detalle_precio!="" && producto_nombre!="" && subtotal!="" && comanda_detalle_despacho !="" ){
                contenido_pedido += id_producto + "-.-." + producto_nombre + "-.-."+ comanda_detalle_precio+"-.-." + comanda_detalle_cantidad +"-.-."+comanda_detalle_despacho+"-.-." + servicio_check_detalle_+"-.-."+subtotal+"-.-."+id_escondido_+"/./.";
                $("#contenido_pedido").val(contenido_pedido);
                //$("#comanda_total_pedido").val(subtotal);
                show_table();
                clean();
            }else{
                respuesta('Ingrese todos los campos');
            }
        }else{
            respuesta('La cantidad seleccionada es mayor: '+ comanda_detalle_cantidad +' al stock existente: '+stock,'error');
        }
    }

    function show_table() {
        var llenar="";
        conteo=1;
        var monto_total = 0;
        var total = 0.00;
        if (contenido_pedido.length>0){
            var filas=contenido_pedido.split('/./.');
            if(filas.length>0){
                for(var i=0;i<filas.length - 1;i++){
                    var celdas =filas[i].split('-.-.');
                    llenar +="<tr>" +
                        "<td>"+celdas[1]+"</td>"+
                        "<td>"+celdas[2]+"</td>"+
                        "<td>"+celdas[3]+"</td>"+
                        "<td>"+celdas[4]+"</td>"+
                        "<td>"+celdas[5]+"</td>"+
                        "<td>"+celdas[6]+"</td>"+
                        "<td><a data-toggle=\"tooltip\" onclick='delete_detalle("+i+")' title=\"Eliminar\" type=\"button\" class=\"text-danger\" ><i class=\"fa fa-times ver_detalle\"></i></a></td>"+
                        "</tr>";
                    conteo++;
                    monto_total = monto_total + celdas[6] * 1;
                    total = monto_total.toFixed(2);
                }
            }
        }
        $("#contenido_detalle_compra").html(llenar);
        $("#conteo").html(conteo);
        $("#comanda_total").val(total);
        $("#comanda_total_").html("S/ " + total);
        //$("#contenido_pedido").val(contenido_pedido);
    }
    function delete_detalle(ind) {
        var contenido_artificio ="";
        if (contenido_pedido.length>0){
            var filas=contenido_pedido.split('/./.');
            if(filas.length>0){
                for(var i=0;i<filas.length - 1;i++){
                    if(i!=ind){
                        var celdas =filas[i].split('-.-.');
                        contenido_artificio += celdas[0] + "-.-."+celdas[1] + "-.-." + celdas[2] + "-.-." + celdas[3] + "-.-." +celdas[4] + "-.-."+ celdas[5] + "-.-."+ celdas[6] + "/./.";
                    }else{
                        var celdas =filas[i].split('-.-.');
                    }
                }
            }
        }
        contenido_pedido = contenido_artificio;
        show_table();
    }
    function clean() {
        $("#comanda_detalle_observacion").val("-");
        $("#producto_nombre").val("");
        $("#id_producto").val("");
        $("#comanda_detalle_cantidad").val("1");

        $("#comanda_detalle_despacho option[value='salon']").attr('selected','selected');
        $("#comanda_detalle_precio").val("");
        $("#producto_nombre_").html("");
        $("#comanda_detalle_precio_").html("");
        $("#parametro").val("");
    }
    //FIN - AGREGAR NUEVOS PEDIDOS
    function partir_pago(){
        var partir = $('#partir_pago').val();
        if(partir == 1){
            $('#div_monto_1').show();
            $('#div_tipo_pago_2').show();
            $('#div_monto_2').show();
        }else {
            $('#div_monto_1').hide();
            $('#monto_1').val('');
            $('#monto_2').val('');
            $('#div_tipo_pago_2').hide();
            $('#div_monto_2').hide();
        }

    }
    function monto_dividido(valor){
        var total = $('#venta_total_').val() * 1;
        if(valor <= total){
            var resta = total - valor * 1;
            $('#monto_2').val(resta.toFixed(2));
        }else{
            respuesta('Monto ' + valor + ' tiene que ser menor que ' + total);
            $('#monto_2').val('');

        }
    }
    function select_cortesia(){
        var  cortesia = $('#gratis').val();
        if(cortesia == 1){
            $('#div_observacion_cortesia').show();
        }else{
            $('#div_observacion_cortesia').hide();
        }
    }
    function checkbox_todo(){
        $('.chk-box').attr('checked', "checked");
        /*var che_todo = $('#checkbox_todo');
        if (che_todo.is(':checked')){
            $('.chk-box').attr('checked', "checked");
            //$('#div_checkbox > input[type=checkbox]').prop('checked', $(this).is(':checked'));
        }else{
            $(".chk-box:checkbox:checked").removeAttr("checked");
        }*/

        <?php
        foreach($pedidos as $ls){
        ?>
        var id = "<?= $ls->id_comanda_detalle;?>";
        calcular_total(id)
        <?php
        }
        ?>
    }
    $('#nuevo_pedido').on('click', function(){
        $('#agregar_pedido_nuevo').modal({backdrop: 'static', keyboard: false})
    })
    $("#imprimir_pre_cuenta").on('submit', function(e){
        e.preventDefault();
        var valor = true;
        var boton = 'btn-print-precuenta';
        //var id_mesa = $('#id_mesa').val();

        if (valor){
            $.ajax({
                type:"POST",
                url: urlweb + "api/Pedido/ticket_comanda",
                dataType: 'json',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
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
                            }, 200);
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        }
    });

    function dividir_pedido(id){
        var boton = 'btn-modificar_pedido' + id;

        // / $(`#${boton}`).prop('disabled',true)

        $.ajax({
            type: "POST",
            url: urlweb + "api/Pedido/dividir_pedido",
            data: "id=" + id,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'dividiendo...', true);
            },
            success:function (r) {
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

    function llenar_datos_clientillo(){
        $("#dni_cliente_cuentas").val();
        $("#nombre_cliente_cuentas").val();
    }

    const guardar_vinculacion=()=>{
        let boton = 'btn-vincular_habitacion';
        let id_rack = $('#id_rack').val()
        let id_habitacion = $('#id_habitacion').val()
        let nombre_cliente = $('#nombre_cliente').val()
        let dni_cliente = $('#dni_cliente').val()
        let id_comanda = "<?=$comanda->id_comanda?>";
        let id_mesa = "<?=$id?>";
        let dato = "id_rack="+id_rack+
            "&id_comanda="+ id_comanda+
            "&nombre_cliente="+ nombre_cliente+
            "&dni_cliente="+ dni_cliente+
            "&id_habitacion="+ id_habitacion+
            "&id_mesa="+id_mesa;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Pedido/guardar_vinculacion",
            data: dato,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'Vinculando...', true);
            },
            success:function (r) {
                switch (r.result.code) {
                    case 1:
                        respuesta('Vinculado exitosamente...!', 'success');
                        setTimeout(function () {
                            location.href = urlweb +  'Pedido/gestionar';
                            //location.reload();
                        }, 800);
                        break;
                    case 3:
                        respuesta('Existe un error con la habitación', 'error');
                        setTimeout(function () {
                            //location.href = urlweb +  'Pedido/gestionar';
                            location.reload();
                        }, 1000);
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }

    const guardar_cargo_cuenta=()=>{
        let boton = 'btn-vincular_cargo';
        let id_rack = $('#id_rack').val()
        let id_cliente = $('#id_cliente_cuenta').val()
        let id_habitacion = $('#id_habitacion').val()
        let id_comanda = "<?=$comanda->id_comanda?>";
        let id_mesa = "<?=$id?>";
        let dato = "id_rack="+id_rack+
            "&id_comanda="+ id_comanda+
            "&id_cliente="+ id_cliente+
            "&id_habitacion="+ id_habitacion+
            "&id_mesa="+id_mesa;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Pedido/guardar_cargo_cuenta",
            data: dato,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'Guardando...', true);
            },
            success:function (r) {
                switch (r.result.code) {
                    case 1:
                        respuesta('Asignado exitosamente...!', 'success');
                        setTimeout(function () {
                            location.href = urlweb +  'Pedido/gestionar';
                        }, 800);
                        break;
                    case 2:
                        respuesta('Error al guardar, comuniquese con BufeoTec e informe lo sucedido', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }

</script>