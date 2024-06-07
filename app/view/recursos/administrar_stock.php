

<div class="modal fade" id="subir_stock" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Actualizar Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="col-form-label">Agregar Cantidad:</label>
                                <input type="hidden" id="id_detalle_receta" name="id_detalle_receta" value="">
                                <input type="hidden" id="id_receta_" name="id_receta_">
                                <input class="form-control" type="text" onkeyup="validar_numeros(this.id);calcular_platos_update_()" id="detalle_receta_cantidad_total_" name="detalle_receta_cantidad_total_">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="col-form-label">Cantidad a usar:</label>
                                <input class="form-control" type="text" id="detalle_cantidad_x_plato_" name="detalle_cantidad_x_plato_" value="1" onkeyup="validar_numeros_decimales_dos(this.id);calcular_platos_update_inverso()">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="col-form-label">Stock para Venta:</label>
                                <input class="form-control" type="text" id="total_platos_" onkeyup="validar_numeros(this.id)" name="total_platos_" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="btn-add_sub" onclick="actualizar_cantidad_nuevo_()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="stock_gestionar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 50% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Convertir Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-3">
                            <input type="hidden" id="id_recurso_sede" name="id_recurso_sede" value="<?= $id?>">
                            <label>Cantidad a Distribuir</label>
                            <input class="form-control" type="text" id="cantidad_distribuida" name="cantidad_distribuida" onkeyup="validar_numeros_decimales_dos(this.id)">
                        </div>
                        <div class="col-lg-3">
                            <label>Realizar Conversión</label>
                            <select class="form-control" name="conversion_estado" id="conversion_estado" onchange="mostrar_para_conversion();">
                                <option value="">Seleccione...</option>
                                <option value="1">SI</option>
                                <option value="0">NO</option>
                            </select>
                        </div>
                        <div class="col-lg-3" id="b">
                            <label>Medida de Cambio</label>
                            <select class="form-control" name="id_medida_nueva" id="id_medida_nueva" onchange="convertir_valor()">
                                <option value="">Seleccione...</option>
                                <?php
                                foreach($unidad_medida as $n){
                                    ?>
                                    <option value="<?php echo $n->id_medida;?>"><?php echo $n->medida_nombre;?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-3" id="a">
                            <label>Capacidad (ml.)</label>
                            <input class="form-control" type="text" id="cantidad_convertir" name="cantidad_convertir" onkeyup="validar_numeros_decimales_dos(this.id);multiplicar_capacidad()">
                        </div>
                        <div class="col-lg-3" id="c">
                            <label>Stock Convertido</label>
                            <input type="text" class="form-control" id="cantidad_nueva_distribuida" name="cantidad_nueva_distribuida">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="btn-add_mmm" onclick="cambiar_stock_conversion()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800"><?= $_SESSION['controlador'] . ' / ' . $_SESSION['accion'];?></h1>
            </div>
            <!--AQUI PONDRE UN BOTON PARA DISTRIBUIR EL STOCK-->
            <div class="row">
                <div class="col-lg-9"></div>
                <div class="col-lg-3 text-right">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#stock_gestionar" title="Gestionar Stock" ><i class="fa fa-save"></i> Gestionar Stock</button>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12" style="text-align: center; padding-bottom:5px; "><h2> Listado de Recetas Vinculadas al Recurso <?= $real_nombre->recurso_nombre?></h2></div>

                <div class="col-lg-3">
<!--                      <h3>Stock General</h3>-->
                      <h3>Stock Sin Distribuir</h3>
                    <?php
                    if($ver_conversion->conversion_estado==1){
                        ?>
<!--                        <h3>Stock Distribuido</h3>-->
                        <h3>Stock Convertido</h3>
                        <?php
                    }else{
                        ?>
                        <h3>Stock Distribuido</h3>
                    <?php
                    }
                    ?>
                </div>
                <div class="col-lg-3">
<!--                    <h3 --><?//=$color?><?//= $sumar?><!--</h3>-->
                    <h3 >:<?= $nombre_recurso_->recurso_sede_stock ?? 0?></h3>
                    <?php
                    if($ver_conversion->conversion_estado==1){
                        ?>
                        <!--<h3 >:<?= $ver_conversion->cantidad_distribuida ?? 0?></h3>-->
                        <h3 style="color:blue" >:<?= $ver_conversion->cantidad_nueva_distribuida ?? 0?> - <?= $ver_conversion->medida_nombre?></h3>
                    <?php
                    }else{
                        ?>
                        <h3>:<?= $ver_conversion->cantidad_nueva_distribuida ?? 0?></h3>
                    <?php
                    }
                    ?>
                </div>
                <div class="col-lg-12">
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless table-striped table-earning" id="dataTable2" width="100%" cellspacing="0">
                                    <thead class="text-capitalize">
                                    <tr>
                                        <th>ID</th>
                                        <th>Receta / Preferencia</th>
                                        <!--<th>Cantidad Actual</th>-->
                                        <th>Unidad Medida</th>
                                        <th>Cantidad a Usar</th>
                                        <!--<th>Cantidad Para Venta</th>-->
                                        <!--<th>Acción</th>-->
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $a = 1;
                                    foreach ($recurso_recetas as $ar){
                                        $estilo = "";
                                        $estilo_ = "";
                                        if($ar->recurso_estado == "0"){
                                            $estilo = "style=\"background-color: #FF6B70\"";
                                        }
                                        ?>
                                        <tr <?= $estilo;?>>
                                            <td><?= $a;?></td>
                                            <td><?= $ar->receta_nombre?></td>
                                            <td><?= $ar->medida_nombre?></td>
                                            <td><?= $ar->detalle_receta_cantidad?></td>
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
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script>
    $(document).ready(function(){
        $("#a").hide();
        $("#b").hide();
        $("#c").hide();
    });

    function convertir_valor(){
        var id_media_nueva = $("#id_medida_nueva").val();
        //var cantidad_convertir = $("#cantidad_convertir").val();
        if(id_media_nueva==41){
            $("#a").show();
            $("#c").show();
        }else if(id_media_nueva==58){
            $("#c").show();
            $("#a").hide();
        }else if(id_media_nueva==18){
            $("#c").show();
        }else if(id_media_nueva==35){
            $("#c").show();
        }
    }

    function multiplicar_capacidad(){
        var id_media_nueva = $("#id_medida_nueva").val();
        var cantidad_convertir = $("#cantidad_convertir").val();
        if(id_media_nueva==41){
            var cambio = cantidad_convertir / 30 * 1;
            $("#cantidad_nueva_distribuida").val(cambio.toFixed(2));
        }

    }

    function mostrar_para_conversion(){
        var validar = $("#conversion_estado").val();
        if(validar==1){
            $("#cantidad_nueva_distribuida").val("");
            $("#b").show();
        }else{
            var cantidad_distribuida = $("#cantidad_distribuida").val();
            $("#a").hide();
            $("#b").hide();
            $("#c").show();
            $("#cantidad_nueva_distribuida").val(cantidad_distribuida);
        }
    }

    function cambiar_stock_conversion(){
        var valor = true;
        //Definimos el botón que activa la función
        var boton = "btn-add_mmm";
        var id_recurso_sede = $('#id_recurso_sede').val();
        var cantidad_distribuida = $("#cantidad_distribuida").val();
        var conversion_estado = $("#conversion_estado").val();
        var cantidad_convertir = $("#cantidad_convertir").val();
        var id_medida_nueva = $("#id_medida_nueva").val();
        var cantidad_nueva_distribuida = $("#cantidad_nueva_distribuida").val();

        if(valor){
            var dato = "id_recurso_sede=" + id_recurso_sede +
                "&cantidad_distribuida=" + cantidad_distribuida+
                "&conversion_estado=" + conversion_estado+
                "&cantidad_convertir=" + cantidad_convertir+
                "&id_medida_nueva=" + id_medida_nueva+
                "&cantidad_nueva_distribuida=" + cantidad_nueva_distribuida;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Almacen/llenar_tabla_nueva_stock",
                data:dato,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, 'Guardando...', true);
                },
                success:function (r) {
                    cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                    switch (r.result.code) {
                        case 1:
                            respuesta('Guardado con exito...', 'success');
                            setTimeout(function () {
                                location.href = urlweb+'Almacen/administrar_stock/'+id_recurso_sede;
                            }, 500);
                            break;
                        case 2:
                            respuesta('Error al guardar, Comuniquese con Bufeo Tec','error');
                            break;
                        case 6:
                            respuesta('No se realizo de forma correcta el registro...Avisar a BufeoTec','error');
                            break;
                        case 9:
                            respuesta('El stock a repartir es mayor del que tienes en almacen!!','error');
                            break;
                        default:
                            respuesta('Algo salió mal','error');
                            break;
                    }

                }
            });
        }
    }



    function llenar_cantidad_usar_nuevo(id_detalle_receta,cantidad_x_plato,id_receta){
        $("#id_detalle_receta").val(id_detalle_receta);
        if(cantidad_x_plato == ""){
            $("#detalle_cantidad_x_plato_").val(1);
        }else{
            $("#detalle_cantidad_x_plato_").val(cantidad_x_plato);
        }
        $("#id_receta_").val(id_receta);
        console.log(id_receta);
    }

    function calcular_platos_update_(){
        var cantidad_usada_ = $("#detalle_cantidad_x_plato_").val() * 1;
        var cantidad_agregada = $("#detalle_receta_cantidad_total_").val() * 1;

        var calculito = cantidad_agregada / cantidad_usada_;

        $("#total_platos_").val(calculito.toFixed(2));
    }

    function calcular_platos_update_inverso(){
        var cantidad_add = $("#detalle_receta_cantidad_total_").val() * 1;
        var cantidad_usar = $("#detalle_cantidad_x_plato_").val() * 1;
        var calculin = cantidad_add / cantidad_usar;
        $("#total_platos_").val(calculin.toFixed(2));
    }

    function actualizar_cantidad_nuevo_(){
        var valor = true;
        //Definimos el botón que activa la función
        var boton = "btn-add_sub";
        //Extraemos las variable según los valores del campo consultado
        var detalle_receta_cantidad_total = $('#detalle_receta_cantidad_total_').val();
        var detalle_cantidad_x_plato = $('#detalle_cantidad_x_plato_').val();
        var total_platos = $('#total_platos_').val();
        var id_detalle_receta = $('#id_detalle_receta').val();
        var id_recurso_sede = $('#id_recurso_sede').val();
        var id_receta = $("#id_receta_").val();

        if(valor){
            var dato = "id_detalle_receta=" + id_detalle_receta +
                "&id_recurso_sede=" + id_recurso_sede +
                "&detalle_cantidad_x_plato=" + detalle_cantidad_x_plato +
                "&total_platos=" + total_platos +
                "&detalle_receta_cantidad_total=" + detalle_receta_cantidad_total +
                "&id_receta=" + id_receta;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Almacen/actualizar_cantidad_",
                data:dato,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, 'Guardando...', true);
                },
                success:function (r) {
                    cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                    switch (r.result.code) {
                        case 1:
                            respuesta('Guardado con exito...', 'success');
                            setTimeout(function () {
                                location.href = urlweb+'Almacen/administrar_stock/'+id_recurso;
                            }, 500);
                            break;
                        case 2:
                            respuesta('Error al guardar, Comuniquese con Bufeo Tec','error');
                            break;
                        case 7:
                            respuesta('No se realizo la disminución del stock sin aignar','error');
                            break;
                        case 10:
                            respuesta('El stock a distribuir es mayor a lo permitido','error');
                            break;
                        default:
                            respuesta('Algo salió mal','error');
                            break;
                    }

                }
            });
        }
    }
</script>