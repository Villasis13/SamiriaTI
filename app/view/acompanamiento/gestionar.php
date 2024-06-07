
<div class="modal fade" id="agregar_acompa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 40% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Registrar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">
                            <label class="col-form-label">Producto</label><br>
                            <select class="form-control" name="id_producto" id="id_producto" style="width: 300px">
                                <option value="">Seleccione...</option>
                                <?php
                                foreach($product as $g){
                                    ?>
                                    <option value="<?php echo $g->id_producto;?>"><?php echo $g->producto_nombre;?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="col-form-label">Preferencia</label>
                            <input class="form-control" type="text" id="acompanamiento_texto" name="acompanamiento_texto">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-lg-3">
                    <button class="btn btn-success" id="btn_stock" onclick="agregar_acompanamiento()"><i class="fa fa-plus"></i> Agregar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="agregar_detalle_acompa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 50% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Opciones</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <input type="hidden" id="id_acompanamiento" name="id_acompanamiento">
                        <div class="col-lg-12">
                            <h5><span id="nombre_producto_"></span></h5><br>
                        </div>
                        <div class="col-lg-6">
                            <label>Texto</label>
                            <input class="form-control" type="text" id="detalle_texto" name="detalle_texto" placeholder="Ingrese texto...">
                        </div>
                        <div class="col-lg-6">
                            <label>Recurso</label>
                            <select class="form-control" name="recurso" id="recurso" onchange="habilitar_input()" style="width: 100%">
                                <option value="">Seleccione</option>
                                <?php
                                foreach ($recursos as $r){?>
                                    <option value="<?php echo $r->id_recurso;?>"><?php echo $r->recurso_nombre;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-lg-4" id="uu">
                            <label>Unida Medida</label>
                            <select class="form-control" name="unidad_medida" id="unidad_medida">
                                <option value="">Seleccione</option>
                                <?php
                                foreach($unidad_medida as $um){
                                    ?>
                                    <option value="<?php echo $um->id_medida;?>"><?php echo $um->medida_nombre;?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-4" id="ca">
                            <label>Cantidad</label>
                            <input type="text" class="form-control" id="cantidad" name="cantidad">
                        </div>
                        <div class="col-lg-2">
                            <button style="margin-top: 30px" class="btn btn-success" id="btn_stock" onclick="guardar_detalle_acompa()"><i class="fa fa-save"></i> Guardar</button>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12" style="text-align: center; padding-bottom:5px; "><h4>Listado de Opciones</h4></div>
                        <div class="col-lg-12">
                            <!-- DataTales Example -->
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div id="tabla_detalles" class="table-responsive">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
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
            <div class="row">
                <div class="col-lg-9"></div>
                <div class="col-lg-3">
                    <button style="width: 80%" data-toggle="modal" data-target="#agregar_acompa" class="btn btn-primary"><i class="fa fa-plus"></i> Agregar</button>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12" style="text-align: center; padding-bottom:5px; "><h2>Listado de Preferencias</h2></div>
                <div class="col-lg-12">
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless table-striped table-earning" id="dataTable2" width="100%" cellspacing="0">
                                    <thead class="text-capitalize">
                                    <tr>
                                        <th>ID</th>
                                        <th>Producto</th>
                                        <th>Titulo</th>
                                        <th>Acción</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $a = 1;
                                    foreach ($acompas as $ar){
                                        ?>
                                        <tr>
                                            <td><?= $a;?></td>
                                            <td><?= $ar->producto_nombre?></td>
                                            <td><?= $ar->acompanamiento_texto?></td>
                                            <td>
                                                <a class="btn btn-primary" data-toggle="modal" data-target="#agregar_detalle_acompa" onclick="llenar_id_acompa(<?= $ar->id_acompanamiento;?>)">
                                                <i class='fa fa-plus text-white editar margen'></i></a>
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
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>

<script>
    $(document).ready(function(){
        $("#id_producto").select2({
            dropdownParent: $("#agregar_acompa")
        });
        $("#recurso").select2();
        $("#ca").hide();
        $("#uu").hide();
    })

    function habilitar_input(){
        var recurso = $("#recurso").val();
        if(recurso){
            $("#ca").show();
            $("#uu").show();
        }else{
            $("#ca").hide();
            $("#uu").hide();
        }
    }

    function agregar_acompanamiento(){
        var valor = true;
        var id_producto = $('#id_producto').val();
        var acompanamiento_texto = $('#acompanamiento_texto').val();
        if(valor) {
            var cadena = "id_producto=" + id_producto +
                "&acompanamiento_texto=" + acompanamiento_texto;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Acompanamiento/guardar_acompa",
                data: cadena,
                dataType: 'json',
                success: function (r) {
                    switch (r.result.code) {
                        case 1:
                            respuesta('¡Guardado correctamente!', 'success');
                            setTimeout(function () {
                                location.href = urlweb +  'Acompanamiento/gestionar';
                            }, 200);
                            break;
                        case 2:
                            respuesta('Error al transferir pedido. Llame a BufeoTec Company', 'error');
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        }
    }

    function llenar_id_acompa(id_acompanamiento){
        $("#id_acompanamiento").val(id_acompanamiento);
        $.ajax({
            type: "POST",
        url: urlweb + "api/Acompanamiento/consultar_datos_acompa",
            data: "id_acompanamiento="+id_acompanamiento,
            dataType: 'json',
            success:function (r) {
                $("#tabla_detalles").html(r.tabla_detalles);
            }
        });
    }

    function guardar_detalle_acompa(){
        var valor = true;
        var id_acompanamiento = $('#id_acompanamiento').val();
        var detalle_texto = $('#detalle_texto').val();
        var recurso = $('#recurso').val();
        var cantidad = $('#cantidad').val();
        var unidad_medida = $('#unidad_medida').val();
        if(valor) {
            var cadena = "id_acompanamiento=" + id_acompanamiento +
                "&detalle_texto=" + detalle_texto+
                "&recurso=" + recurso+
                "&unidad_medida=" + unidad_medida+
                "&cantidad=" + cantidad;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Acompanamiento/guardar_detalle_acompa",
                data: cadena,
                dataType: 'json',
                success: function (r) {
                    switch (r.result.code) {
                        case 1:
                            respuesta('¡Guardado correctamente!', 'success');
                            setTimeout(function () {
                                location.href = urlweb +  'Acompanamiento/gestionar';
                            }, 200);
                            break;
                        case 2:
                            respuesta('Error al guardar. Llame a BufeoTec Company', 'error');
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        }
    }

    function eliminar_detalle_acompa(id_acompa_detalle){
        var valor = true;
        //Validamos si los campos a usar no se encuentran vacios
        valor = validar_parametro_vacio(id_acompa_detalle, valor);
        //Si var valor no ha cambiado de valor, procedemos a hacer la llamada de ajax
        if(valor){
            var cadena = "id_acompa_detalle=" + id_acompa_detalle;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Acompanamiento/eliminar_detalle_acompa_",
                data: cadena,
                dataType: 'json',
                beforeSend: function () {
                },
                success:function (r) {
                    switch (r.result.code) {
                        case 1:
                            respuesta('¡Registro Eliminado correctamente!', 'success');
                            setTimeout(function () {
                                location.href = urlweb +  'Acompanamiento/gestionar';
                            }, 500);
                            break;
                        case 2:
                            respuesta('Error al eliminar registro, comuniquese con BufeoTec', 'error');
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        }
    }
</script>