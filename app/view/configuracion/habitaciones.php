<!--navbar
login
admin/inicio-->
<div class="modal fade" id="modal_editar_habitacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">EDITAR HABITACION</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="id_habitacion_tipo">Tipo de habitación</label>
                            <select class="form-control" id="id_habitacion_tipo_e">
                                <option value="">Seleccione</option>
                                <?php
                                foreach ($tipos_hab as $th){
                                    echo "<option value='$th->id_habitacion_tipo'>$th->habitacion_tipo_nombre</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="nro_habitacion">Nro de habitación</label>
                            <input type="text" class="form-control" id="nro_habitacion_e">
                            <input type="hidden" id="id_habitacion_e">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-editar-habitacion" onclick="editar_habitacion()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_editar_tipo_habitacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">EDITAR TIPO HABITACION</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="habitacion_tipo_nombre_e">Nombre del Tipo de hab</label>
                            <input type="text" class="form-control" id="habitacion_tipo_nombre_e">
                            <input type="hidden" id="id_habitacion_tipo_e">
                        </div>
                        <div class="col-md-6">
                            <label for="habitacion_tipo_detalle_e">Detalle</label>
                            <input type="text" class="form-control" id="habitacion_tipo_detalle_e">
                        </div>
                        <div class="col-md-6">
                            <label for="habitacion_soles_e">Precio Soles</label>
                            <input type="text" class="form-control" id="habitacion_soles_e">
                        </div>
                        <div class="col-md-6">
                            <label for="habitacion_dolares_e">Precio Dólares</label>
                            <input type="text" class="form-control" id="habitacion_dolares_e">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-editar-tipo-habitacion" onclick="editar_tipo_habitacion()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Habitaciones</h1>
    </div>
    <div class="row">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">AGREGAR HABITACION</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="id_habitacion_tipo">Tipo de habitación</label>
                                    <select class="form-control" id="id_habitacion_tipo">
                                        <option value="">Seleccione</option>
                                        <?php
                                        foreach ($tipos_hab as $th){
                                            echo "<option value='$th->id_habitacion_tipo'>$th->habitacion_tipo_nombre</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="nro_habitacion">Nro de habitación</label>
                                    <input type="text" class="form-control" id="nro_habitacion">
                                </div>
                                <div class="col-md-12">
                                    <br><button id="btn-guardar-habitacion" onclick="guardar_habitacion()" class="btn btn-success" style="width: 100%"><i class="fa fa-plus"></i> Guardar Habitación</button><br><br>
                                </div>
                            </div>
                            <div class="row">
                                <br><br><div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Listado de habitaciones</div>
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tipo</th>
                                        <th>Nro</th>
                                        <th>Acción</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($habitaciones as $h){
                                        $data_exists = $this->configuracion->existe_habitacion_estado($h->id_habitacion);
                                        if(isset($data_exists->id_habitacion)){
                                            $btn_eliminar = "";
                                        }else{
                                            $data_exists2 = $this->configuracion->existe_habitacion_rack($h->id_habitacion);
                                            if(isset($data_exists2->id_habitacion)){
                                                $btn_eliminar = "";
                                            }else{
                                                $btn_eliminar="<button onclick='eliminar_habitacion($h->id_habitacion)' class='btn btn-danger btn-sm'> X </button>";
                                            }
                                        }
                                        echo "<tr><td>$h->id_habitacion</td><td>$h->habitacion_tipo_nombre</td><td>$h->habitacion_nro</td><td><button data-toggle='modal' data-target='#modal_editar_habitacion' onclick='set_id_editar($h->id_habitacion,\"$h->habitacion_nro\",$h->id_habitacion_tipo)' class='btn btn-sm btn-warning'><i class='fa fa-pencil'></i></button> $btn_eliminar</td></tr>";
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
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">TIPOS DE HABITACION</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="habitacion_tipo_nombre">Nombre del Tipo de hab</label>
                                    <input type="text" class="form-control" id="habitacion_tipo_nombre">
                                </div>
                                <div class="col-md-6">
                                    <label for="habitacion_tipo_detalle">Detalle</label>
                                    <input type="text" class="form-control" id="habitacion_tipo_detalle">
                                </div>
                                <div class="col-md-6">
                                    <label for="habitacion_soles">Precio Soles</label>
                                    <input type="text" class="form-control" id="habitacion_soles">
                                </div>
                                <div class="col-md-6">
                                    <label for="habitacion_dolares">Precio Dólares</label>
                                    <input type="text" class="form-control" id="habitacion_dolares">
                                </div>
                                <div class="col-md-12">
                                    <br><button class="btn btn-success" id="btn-guardar-tipo-habitacion" onclick="guardar_tipo_habitacion()" style="width: 100%"><i class="fa fa-plus"></i> Guardar Tipo de Habitación</button><br><br>
                                </div>
                            </div>
                            <div class="row">
                                <br><br><div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Listado de tipos de habitación</div>
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre Tipo</th>
                                        <th>Detalle</th>
                                        <th>Soles</th>
                                        <th>Dólares</th>
                                        <th>Acción</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($tipos_hab as $h){
                                        $data_exists3 = $this->configuracion->existe_habitacion_tipo($h->id_habitacion_tipo);
                                        if(isset($data_exists3->id_habitacion_tipo)){
                                            $btn_eliminar2 = "";
                                        }else{
                                            $btn_eliminar2="<button onclick='eliminar_habitacion_tipo($h->id_habitacion_tipo)' class='btn btn-danger btn-sm'> X </button>";
                                        }
                                        echo "<tr><td>$h->id_habitacion_tipo</td><td>$h->habitacion_tipo_nombre</td><td>$h->habitacion_tipo_detalle</td><td>$h->habitacion_tipo_soles</td><td>$h->habitacion_tipo_dolares</td><td><button data-toggle='modal' data-target='#modal_editar_tipo_habitacion' onclick='set_id_tipo_editar($h->id_habitacion_tipo,\"$h->habitacion_tipo_nombre\",\"$h->habitacion_tipo_detalle\",\"$h->habitacion_tipo_soles\",\"$h->habitacion_tipo_dolares\")' class='btn btn-sm btn-warning'><i class='fa fa-pencil'></i></button> $btn_eliminar2</td></tr>";
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
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script>
    function guardar_habitacion() {
        var valor = true;
        var boton = "btn-guardar-habitacion";
        var id_habitacion_tipo = $("#id_habitacion_tipo").val();
        var nro_habitacion = $("#nro_habitacion").val();
        valor = validar_campo_vacio('id_habitacion_tipo', id_habitacion_tipo, valor);
        valor = validar_campo_vacio('nro_habitacion', nro_habitacion, valor);
        if(valor){
            $.ajax({
                type: "POST",
                url: urlweb + "api/Configuracion/guardar_habitacion",
                data: "id=" + id_habitacion_tipo+"&nro="+nro_habitacion,
                dataType: 'json',
                beforeSend: function () {cambiar_estado_boton(boton, 'Guardando...', true);},
                success:function (r) {
                    switch (r.result.code) {
                        case 1:location.reload();break;
                        case 2:respuesta('Error al guardar', 'error');location.reload();break;
                        default:respuesta('¡Algo catastrofico ha ocurrido!', 'error');location.reload();break;
                    }
                }
            });
        }
    }
    function guardar_tipo_habitacion() {
        var valor = true;
        var boton = "btn-guardar-tipo-habitacion";
        var habitacion_tipo_nombre = $("#habitacion_tipo_nombre").val();
        var habitacion_tipo_detalle = $("#habitacion_tipo_detalle").val();
        var habitacion_soles = $("#habitacion_soles").val();
        var habitacion_dolares = $("#habitacion_dolares").val();
        valor = validar_campo_vacio('habitacion_tipo_nombre', habitacion_tipo_nombre, valor);
        valor = validar_campo_vacio('habitacion_soles', habitacion_soles, valor);
        if(valor){
            $.ajax({
                type: "POST",
                url: urlweb + "api/Configuracion/guardar_tipo_habitacion",
                data: "nombre=" + habitacion_tipo_nombre+"&detalle="+habitacion_tipo_detalle+"&soles="+habitacion_soles+"&dolares="+habitacion_dolares,
                dataType: 'json',
                beforeSend: function () {cambiar_estado_boton(boton, 'Guardando...', true);},
                success:function (r) {
                    switch (r.result.code) {
                        case 1:location.reload();break;
                        case 2:respuesta('Error al guardar', 'error');location.reload();break;
                        default:respuesta('¡Algo catastrofico ha ocurrido!', 'error');location.reload();break;
                    }
                }
            });
        }
    }
    function editar_tipo_habitacion() {
        var valor = true;
        var boton = "btn-editar-tipo-habitacion";
        var id = $("#id_habitacion_tipo_e").val();
        var habitacion_tipo_nombre_e = $("#habitacion_tipo_nombre_e").val();
        var habitacion_tipo_detalle_e = $("#habitacion_tipo_detalle_e").val();
        var habitacion_soles_e = $("#habitacion_soles_e").val();
        var habitacion_dolares_e = $("#habitacion_dolares_e").val();
        valor = validar_campo_vacio('habitacion_tipo_nombre_e', habitacion_tipo_nombre_e, valor);
        valor = validar_campo_vacio('habitacion_soles_e', habitacion_soles_e, valor);
        if(valor){
            $.ajax({
                type: "POST",
                url: urlweb + "api/Configuracion/editar_tipo_habitacion",
                data: "nombre_e=" + habitacion_tipo_nombre_e+"&id="+id+"&detalle_e="+habitacion_tipo_detalle_e+"&soles_e="+habitacion_soles_e+"&dolares_e="+habitacion_dolares_e,
                dataType: 'json',
                beforeSend: function () {cambiar_estado_boton(boton, 'Guardando...', true);},
                success:function (r) {
                    switch (r.result.code) {
                        case 1:location.reload();break;
                        case 2:respuesta('Error al guardar', 'error');location.reload();break;
                        default:respuesta('¡Algo catastrofico ha ocurrido!', 'error');location.reload();break;
                    }
                }
            });
        }
    }
    function editar_habitacion() {
        var valor = true;
        var boton = "btn-editar-habitacion";
        var id_habitacion_e = $("#id_habitacion_e").val();
        var nro_habitacion_e= $("#nro_habitacion_e").val();
        var id_habitacion_tipo_e = $("#id_habitacion_tipo_e").val();
        valor = validar_campo_vacio('nro_habitacion_e', nro_habitacion_e, valor);
        valor = validar_campo_vacio('id_habitacion_tipo_e', id_habitacion_tipo_e, valor);
        if(valor){
            $.ajax({
                type: "POST",
                url: urlweb + "api/Configuracion/editar_habitacion",
                data: "id_habitacion_e=" + id_habitacion_e+"&nro_habitacion_e="+nro_habitacion_e+"&id_habitacion_tipo_e="+id_habitacion_tipo_e,
                dataType: 'json',
                beforeSend: function () {cambiar_estado_boton(boton, 'Guardando...', true);},
                success:function (r) {
                    switch (r.result.code) {
                        case 1:location.reload();break;
                        case 2:respuesta('Error al guardar', 'error');location.reload();break;
                        default:respuesta('¡Algo catastrofico ha ocurrido!', 'error');location.reload();break;
                    }
                }
            });
        }
    }
    function eliminar_habitacion(id) {
        $.ajax({
            type: "POST",
            url: urlweb + "api/Configuracion/eliminar_habitacion",
            data: "id=" + id,
            dataType: 'json',
            success:function (r) {
                switch (r.result.code) {
                    case 1:location.reload();break;
                    case 2:respuesta('Error al guardar', 'error');location.reload();break;
                    default:respuesta('¡Algo catastrofico ha ocurrido!', 'error');location.reload();break;
                }
            }
        });
    }
    function eliminar_habitacion_tipo(id) {
        $.ajax({
            type: "POST",
            url: urlweb + "api/Configuracion/eliminar_habitacion_tipo",
            data: "id=" + id,
            dataType: 'json',
            success:function (r) {
                switch (r.result.code) {
                    case 1:location.reload();break;
                    case 2:respuesta('Error al guardar', 'error');location.reload();break;
                    default:respuesta('¡Algo catastrofico ha ocurrido!', 'error');location.reload();break;
                }
            }
        });
    }
    function set_id_editar(id,nro,id_tipo) {
        $("#id_habitacion_e").val(id);
        $("#nro_habitacion_e").val(nro);
        $("#id_habitacion_tipo_e").val(id_tipo);
        $("#id_habitacion_tipo_e option[value="+ id_tipo +"]").attr("selected",true);
    }
    function set_id_tipo_editar(id,nombre,detalle,soles,dolares) {
        $("#id_habitacion_tipo_e").val(id);
        $("#habitacion_tipo_nombre_e").val(nombre);
        $("#habitacion_tipo_detalle_e").val(detalle);
        $("#habitacion_soles_e").val(soles);
        $("#habitacion_dolares_e").val(dolares);
    }
</script>