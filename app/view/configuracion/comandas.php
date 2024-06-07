<div class="container-fluid">
    <div class="row">
        <div class="col-xl-2 col-md-2 mb-4"></div>
        <div class="col-xl-8 col-md-8 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">AGREGAR ENTREGA DE COMANDA</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="comandas_nombre">Entregado a</label>
                                    <input type="text" class="form-control" id="comandas_nombre">
                                </div>
                                <div class="col-md-3">
                                    <label for="comandas_fecha">Fecha de entrega</label>
                                    <input type="date" class="form-control" id="comandas_fecha" value="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="comandas_numero">Número</label>
                                    <input type="text" class="form-control" id="comandas_numero">
                                </div>
                                <div class="col-md-12">
                                    <br><button id="btn-guardar-config-comanda" onclick="guardar_config_comanda()" class="btn btn-success" style="width: 100%"><i class="fa fa-plus"></i> Guardar</button><br><br>
                                </div>
                            </div>
                            <div class="row">
                                <br><br><div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Listado de comandas entregadas</div>
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Fecha</th>
                                        <th>Número</th>
                                        <th>Acción</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $a=1;
                                    if(count($comandas)>0){
                                        foreach ($comandas as $m){
                                            echo "<tr><td>$a</td><td>$m->config_comanda_nombre</td><td>$m->config_comanda_fecha</td><td>$m->config_comanda_numero</td><td><button onclick='eliminar_config_comanda($m->id_config_comanda)' class='btn btn-sm btn-danger'><i class='fa fa-times'></i></button></td></tr>";
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
    function guardar_config_comanda() {
        var valor = true;
        var boton = "btn-guardar-config-comanda";
        var comandas_nombre = $("#comandas_nombre").val();
        var comandas_fecha = $("#comandas_fecha").val();
        var comandas_numero = $("#comandas_numero").val();
        valor = validar_campo_vacio('comandas_nombre', comandas_nombre, valor);
        valor = validar_campo_vacio('comandas_fecha', comandas_fecha, valor);
        valor = validar_campo_vacio('comandas_numero', comandas_numero, valor);
        if(valor){
            $.ajax({
                type: "POST",
                url: urlweb + "api/Configuracion/guardar_config_comanda",
                data: "comandas_nombre=" + comandas_nombre+"&comandas_fecha="+comandas_fecha+"&comandas_numero="+comandas_numero,
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
    function eliminar_config_comanda(id) {
        $.ajax({
            type: "POST",
            url: urlweb + "api/Configuracion/eliminar_config_comanda",
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
</script>