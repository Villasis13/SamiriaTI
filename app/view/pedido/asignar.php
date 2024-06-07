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
</style>
<div class="modal fade" id="asignar_pedido" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 50% !important;color: #171718">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div id="persona">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <b><label class="col-form-label">Producto</label></b>
                                    <br>
                                    <span id="producto_nombre"></span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group">
                                    <b><label class="col-form-label">Precio</label></b>
                                    <input type="text" class="form-control" id="comanda_detalle_precio" name="comanda_detalle_precio">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group">
                                    <b><label class="col-form-label">Cantidad</label></b>
                                    <input type="number" value="1" class="form-control" id="comanda_detalle_cantidad" name="comanda_detalle_cantidad" onkeyup="validar_numeros(this.id);comparar_stock()">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <b><label class="col-form-label">Tipo Entrega</label></b>
                                    <select class="form-control" id= "comanda_detalle_despacho" name="comanda_detalle_despacho">
                                        <option value="SALON">SALON</option>
                                        <option value="PARA LLEVAR">PARA LLEVAR</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <b><label class="col-form-label">¿Impimir?</label></b>
                                    <select class="form-control" id= "comanda_detalle_imprimir" name="comanda_detalle_imprimir">
                                        <option value="SI">SI</option>
                                        <option value="NO">NO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div id="opciones"></div>
                                    <input type="hidden" id="servicio_check_" name="servicio_check_">
                                    <input type="hidden" id="id_escondido" name="id_escondido">
                                    <input type="hidden" id="stock_escondido" name="stock_escondido">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <b><label class="col-form-label">Observacion</label></b>
                                    <textarea rows="3" class="form-control" type="text" id="comanda_detalle_observacion" name="comanda_detalle_observacion" maxlength="200" placeholder="Ingrese Alguna Observacion...">-</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="agregar__()" class="btn btn-success" id="btn-agregar"><i class="fa fa-check fa-sm text-white-50"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="main-content" id="panel-asignar">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 " style=" color: #171718;"><?= $_SESSION['controlador'] . ' / ' . $_SESSION['accion'];?> / <?= $data_mesa->mesa_nombre; ?></h1>
                    </div>
                </div>
                <div class="col-lg-2" style="margin-top: 12px;padding-left: 70px">
                    <a class="btn btn-secondary" href="javascript:history.back()" role="button"><i class="fa fa-backward"></i> Regresar</a>
                </div>
            </div>
            <!-- Page Heading -->


            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-5" style="box-shadow: 0 6px 12px 0 rgba(3,2, 2,0.15)">
                            <br>
                            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" >
                                <div class="row">
                                    <div class="form-group col-lg-8">
                                        <input required autocomplete="off" name="parametro" type="text" value="<?= $parametro;?>" class="form-control" id="parametro" placeholder="Buscar Productos">
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <button type="submit" class="btn btn-success" onclick="productos()" style="width: 80%"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <table class='table' width='100%' class="table table-striped" style="background: white;color: #171718;box-shadow: 0 6px 12px 0 rgba(3,2, 2,0.15)">
                                        <thead class='text-capitalize'>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Stock</th>
                                                <th>Stock en Comandas</th>
                                                <th>Precio</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="producto">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                                <?php
                                foreach ($familia as $f){
                                    $productos_familia = $this->pedido->listar_productos_x_familia($f->id_producto_familia);
                                    ?>
                                    <h5 style="color: #171718" data-toggle="collapse" href="#tipo_<?= $f->id_producto_familia;?>"><?= $f->producto_familia_nombre?><i class="fa fa-arrow-down" style="float: right"></i></h5><br>

                                    <div id="tipo_<?= $f->id_producto_familia;?>" class="collapse">
                                        <table class='table' width='100%' style="color: #171718;box-shadow: 0 6px 12px 0 rgba(3,2, 2,0.15)">
                                            <tbody>
                                            <?php
                                            foreach ($productos_familia as $pf){
                                                $buscar_stock=$this->pedido->buscar_stock($pf->id_receta);
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
                                                /*if($pf->id_receta == "0"){
                                                    $total = $total + $icbper;
                                                }*/
                                                ?>
                                                <tr>
                                                    <td><?=$pf->producto_nombre?></td>
                                                    <td><?= ($buscar_stock->cantidad_nueva_distribuida)? $buscar_stock->cantidad_nueva_distribuida :'Sin Relación' ?></td>
                                                    <td><?=$total ?></td>
                                                    <td>
                                                        <?php
                                                        if (!empty($buscar_stock)) {
                                                            if($buscar_stock->cantidad_nueva_distribuida != 0){
                                                                ?>
                                                                <button class='btn btn-success' data-toggle='modal' onclick="guardar_pedido(<?=$pf->id_producto?>,'<?=$pf->producto_nombre?>','<?=$total?>','<?= $pf->producto_precio_codigoafectacion?>')" data-target='#asignar_pedido'><i class='fa fa-check'></i></button>
                                                                <?php
                                                            }else{
                                                                echo '--';
                                                            }
                                                        }else{
                                                            ?>
                                                            <button class='btn btn-success' data-toggle='modal' onclick="guardar_pedido(<?=$pf->id_producto?>,'<?=$pf->producto_nombre?>','<?=$total?>','<?= $pf->producto_precio_codigoafectacion?>')" data-target='#asignar_pedido'><i class='fa fa-check'></i></button>
                                                            <?php
                                                        }
                                                        ?>
                                                        <a style="display: none" class="btn btn-primary" href="<?= _SERVER_ . $pf->producto_foto?>" target="_blank"><i class="fa fa-eye"></i></a>
                                                    </td>
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
                        <div class="col-lg-7">
                            <div class="col-md-12 col-lg-12 col-xs-12">
                                <form class="" enctype="multipart/form-data" id="guardar_comanda" style="">
                                    <input type="hidden" id="contenido" name="contenido">
                                    <input type="hidden" class="form-control" id="id_producto" name="id_producto">
                                    <input type="hidden" class="form-control" id="comanda_total" name="comanda_total">
                                    <input type="hidden" id="id_mesa" name="id_mesa" value="<?= $id;?>">


                                    <table class="table table-striped" style="background: white;color: #171718;box-shadow: 0 6px 12px 0 rgba(3,2, 2,0.15)">
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
                                            <tr style=" color: #171718;background: white; text-align: center">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>Total</td>
                                                <td style=""><b><span id="comanda_total_">S/ 0.00</span></b></td>
                                            </tr>
                                    </table>
                                    <div class="row">
                                        <br>
                                        <div class="col-lg-5 col-sm-5 col-md-5 col-xs-5"></div>
                                        <div class="col-lg-3 col-xs-4 col-md-4 col-sm-4" style="color: #171718">
                                            <label for="">Cant. de Personas</label>
                                            <input type="number" value="1" class="form-control" id="comanda_cantidad_personas" name="comanda_cantidad_personas">
                                        </div>
                                        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-4" style="padding-left: 20px;padding-top: 17px;margin-top: 12px">
                                            <button type="submit" class="btn btn-primary submitBtn" style="width: 100%"><i class="fa fa-check"></i> Generar</button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>pedido.js"></script>

<script>
    $(document).ready(function(){
        $('#parametro').keypress(function(e){
            if(e.which === 13){
                productos();
            }
        });
    });
    var contenido = "";
    var conteo = 1;
    var total_total = 0;
    var opciones_check_ = "";

    function comparar_stock(){
      let stock = $('#stock_escondido').val() * 1;
      let comanda_detalle_cantidad = $('#comanda_detalle_cantidad').val() * 1;
      if(stock < comanda_detalle_cantidad){
         alert('La cantidad seleccionada')
      }
    }

    function productos(){
        var param_ = $("#parametro").val();
        var param = param_.replace("+",".--");
        $("#producto").html("");
        $.ajax({
            type: "POST",
            url: urlweb + "api/Pedido/ver_productos/",
            data:"&parametro=" + param,
            dataType: 'json',
            success:function (r) {
                $("#producto").html(r.result.producto);
            }
        });
    }

    function show_table() {
        var llenar="";
        conteo=1;
        var monto_total = 0;
        var total = 0.00;
        if (contenido.length>0){
            var filas=contenido.split('/./.');
            if(filas.length>0){
                for(var i=0;i<filas.length - 1;i++){
                    var celdas =filas[i].split('-.-.');
                    llenar +="<tr style='background: white;text-align: center'>" +
                        "<td>"+celdas[1]+"</td>"+
                        "<td>"+celdas[2]+"</td>"+
                        "<td>"+celdas[3]+"</td>"+
                        "<td>"+celdas[4]+"</td>"+
                        "<td>"+celdas[5]+"</td>"+
                        "<td>"+celdas[6]+"</td>"+
                        "<td class='text-center'><a data-toggle=\"tooltip\" onclick='delete_detalle("+i+")' title=\"Eliminar\" type=\"button\" class=\"text-danger\" ><i class=\"fa fa-trash ver_detalle ov-btn-slide-right\"></i></a></td>"+
                        "</tr>";
                    conteo++;
                    var monto_total = monto_total + celdas[6] * 1;
                    var total = monto_total.toFixed(2);
                }
            }
        }
        $("#contenido_detalle_compra").html(llenar);
        $("#conteo").html(conteo);
        $("#comanda_total").val(total);
        $("#comanda_total_").html("S/ " + total);
        $("#contenido").val(contenido);
    }

    function clean() {
        opciones_check_ = "";
        $('#asignar_pedido').modal('toggle');
        $("#comanda_detalle_cantidad").val("1");
        $("#comanda_detalle_precio").val("");
        $("#comanda_detalle_observacion").val("-");
        $("#servicio_check_").val("");
        $("#producto_nombre").val("");

        $("#comanda_detalle_despacho option[value='SALON']").attr('selected','selected');
        $("#comanda_detalle_imprimir option[value='SI']").attr('selected','selected');
        $("#comanda_detalle_despacho").val("SALON");
        $("#comanda_detalle_despacho").select().trigger('change');
        $("#parametro").val("");
        $("#producto").html("");
        $("#id_escondido").val('');
    }

    function delete_detalle(ind) {
        var contenido_artificio ="";
        if (contenido.length>0){
            var filas=contenido.split('/./.');
            if(filas.length>0){
                for(var i=0;i<filas.length - 1;i++){
                    if(i!=ind){
                        var celdas =filas[i].split('-.-.');
                        contenido_artificio += celdas[0] + "-.-."+celdas[1] + "-.-." + celdas[2] + "-.-." + celdas[3] + "-.-." +celdas[4] + "-.-."+ celdas[5] + "-.-."+ celdas[6] +  "-.-."+ celdas[7] + "/./.";
                    }else{
                        var celdas =filas[i].split('-.-.');
                    }
                }
            }
        }
        contenido = contenido_artificio;
        show_table();
    }

    function agregar__(){
        var comanda_detalle_despacho_val = $("#comanda_detalle_despacho").val();
        if(comanda_detalle_despacho_val!=""){
            var comanda_detalle_despacho = $("select[name='comanda_detalle_despacho'] option:selected").text();
        }else{
            var comanda_detalle_despacho = "";
        }
        var opciones_id ="";
        $("input:checkbox:checked").each(function() {
            opciones_check_ += $(this).val() + "//";
            opciones_id += $(this).attr('id') + "/-/";
        });
        opciones_check_ += $("#comanda_detalle_observacion").val();
        console.log(opciones_id);
        $('#servicio_check_').val(opciones_check_);
        var servicio_check = $('#servicio_check_').val();
        //INICIO DEL MILAGRO2
        $("#id_escondido").val(opciones_id);
        var id_escondido = $('#id_escondido').val();
        //FIN
        var producto_nombre = $("#producto_nombre").html();
        var id_producto = $("#id_producto").val();
        var comanda_detalle_cantidad = $("#comanda_detalle_cantidad").val() * 1;
        var comanda_detalle_precio = $("#comanda_detalle_precio").val() * 1;
        var comanda_detalle_imprimir = $("#comanda_detalle_imprimir").val();
        var subtotal = comanda_detalle_cantidad * comanda_detalle_precio;
        subtotal.toFixed(2);
        subtotal = subtotal.toFixed(2);
        /*total_total = total_total + subtotal;
        total_total.toFixed(2);
        total_total = parseFloat(total_total);*/
        let stock = $('#stock_escondido').val() * 1;
        if(stock >= comanda_detalle_cantidad){
            if(id_producto !="" && comanda_detalle_cantidad!="" && comanda_detalle_precio!="" && producto_nombre!="" && servicio_check !="" && subtotal!="" && comanda_detalle_despacho !="" ){
                contenido += id_producto + "-.-." + producto_nombre + "-.-."+ comanda_detalle_precio+"-.-." + comanda_detalle_cantidad +"-.-."+comanda_detalle_despacho+"-.-." + servicio_check+"-.-."+subtotal+"-.-."+comanda_detalle_imprimir+"-.-."+id_escondido+"/./.";
                $("#contenido").val(contenido);
                //$("#comanda_total_pedido").val(subtotal);
                respuesta('Agregado', 'success');
                show_table();
                clean();
            }else{
                respuesta('Ingrese todos los campos','error');
            }
        }else{
            respuesta('La cantidad seleccionada es mayor: '+ comanda_detalle_cantidad +' al stock existente: '+stock,'error');
        }

    }


    $("#guardar_comanda").on('submit', function(e){
        e.preventDefault();
        var valor = true;
        var id_mesa = $('#id_mesa').val();
        var contenido = $('#contenido').val();
        var comanda_cantidad_personas = $('#comanda_cantidad_personas').val();

        valor = validar_campo_vacio('contenido', contenido, valor);
        valor = validar_campo_vacio('id_mesa', id_mesa, valor);

        if (valor){
            $.ajax({
                type:"POST",
                url: urlweb + "api/Pedido/guardar_comanda",
                dataType: 'json',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function(){
                    $('.submitBtn').attr("disabled","disabled");
                    $('#guardar_comanda').css("opacity",".5");
                },
                success:function (r) {
                    switch (r.result.code) {
                        case 1:
                            respuesta('¡Guardado con Exito!','success');
                            setTimeout(function () {
                                location.href = urlweb +  'Pedido/gestionar';
                            }, 1000);
                            break;
                        case 2:
                            respuesta("Fallo el envio, intentelo de nuevo", 'error');
                            break;
                        case 6:
                            respuesta("Algún dato fue ingresado de manera erronéa. Recargue la página por favor.",'error');
                            break;
                        default:
                            respuesta("ERROR DESCONOCIDO", 'error');
                    }
                    $('#guardar_comanda').css("opacity","");
                    $(".submitBtn").removeAttr("disabled");
                }
            });
        }
    });


</script>