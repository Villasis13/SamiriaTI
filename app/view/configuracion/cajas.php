<div class="container-fluid">
    <div class="row">
        <div class="col-xl-2 col-md-2 mb-4"></div>
        <div class="col-xl-8 col-md-8 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">AGREGAR CAJA</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="caja_nombre">Nombre</label>
                                    <input type="text" class="form-control" id="caja_nombre">
                                    <input type="hidden" id="id_caja_e"><br>
                                    <div id="div_edit" style="display: none">
                                        <select class="form-control" id="caja_estado">
                                            <option value="1" style="color: green;font-weight: bold">HABILITADO</option>
                                            <option value="0" style="color: red;font-weight: bold">DESHABILITADO</option>
                                        </select>
                                        <br><button onclick="unset_id_editar()" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Cancelar edición</button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <br><button id="btn-guardar-caja" onclick="guardar_caja()" class="btn btn-success" style="width: 100%"><i class="fa fa-plus"></i> Guardar</button><br><br>
                                </div>
                            </div><hr>
                            <div class="row">
                                <br><br><div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Listado de cajas</div>
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Acción</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $a=1;
                                    if(count($cajas)>0){
                                        foreach ($cajas as $m){
                                            echo "<tr><td>$a</td><td>$m->caja_nombre</td><td><button style='margin-right: 10px;' onclick='set_id_editar($m->id_caja,\"$m->caja_nombre\")' class='btn btn-sm btn-warning'><i class='fa fa-pencil'></i></button><button onclick='eliminar_caja($m->id_caja)' class='btn btn-sm btn-danger'><i class='fa fa-times'></i></button></td></tr>";
                                            $a++;
                                        }
                                    }else{
                                        echo "<tr><td colspan='5'>No se encontraron resultados</td></tr>";
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
    function guardar_caja() {
        var valor = true;
        var boton = "btn-guardar-caja";
        var caja_nombre = $("#caja_nombre").val();
        var id_caja_e = $("#id_caja_e").val();
        var caja_estado = $("#caja_estado").val();
        valor = validar_campo_vacio('caja_nombre', caja_nombre, valor);
        var cadena = "caja_nombre=" + caja_nombre+"&caja_estado="+caja_estado;
        if(id_caja_e!=""){
            cadena+="&id_caja="+id_caja_e;
        }
        if(valor){
            $.ajax({
                type: "POST",
                url: urlweb + "api/Configuracion/guardar_caja",
                data: cadena,
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
    function eliminar_caja(id) {
        $.ajax({
            type: "POST",
            url: urlweb + "api/Configuracion/eliminar_caja",
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
    function set_id_editar(id,nombre) {
        $("#id_caja_e").val(id);
        $("#caja_nombre").val(nombre);
        $("#div_edit").show();
    }
    function unset_id_editar() {
        $("#id_caja_e").val("");
        $("#caja_nombre").val("");
        $("#div_edit").hide();
    }
</script>