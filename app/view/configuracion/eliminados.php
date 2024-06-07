<div class="modal fade" id="claves" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear clave de eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <?php
                                $data_clave_eliminacion = $this->hotel->existe_clave_eliminacion();
                                if(isset($data_clave_eliminacion->id_clave_eliminacion)){
                                    ?>
                                    <label style="color: red">Ya existe una clave de eliminación pendiente:</label><br>
                                    <label style="color: blue"><?= $data_clave_eliminacion->clave_eliminacion_clave ?></label>
                                    <?php
                                }else{
                                    ?>
                                    <label for="num_azar">Clave generada</label><br>
                                    <label id="num_azar" style="color: blue;font-weight: bold"></label><br>
                                    <input id="num_azar_val" type="hidden">
                                    <button onclick="random(1000,9999)" class="btn btn-sm btn-primary"><i class="fa fa-repeat"></i></button><br>
                                    <label style="color: red">* Esta clave solo podrá ser usada una vez</label>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-confirmar-clave" onclick="confirmar_clave()"><i class="fa fa-check-square fa-sm text-white-50"></i> Confirmar</button>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-2 col-md-2 mb-4"></div>
        <div class="col-xl-8 col-md-8 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <button data-toggle="modal" onclick="random(1000,9999)" data-target="#claves" class="btn btn-sm btn-primary">GENERAR CLAVE DE ELIMINACIÓN</button><br><br>
                            <div class="row">
                                <br><br><div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Registro de eliminaciones</div>
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Generado por</th>
                                        <th>Clave generada</th>
                                        <th>Fecha de generación</th>
                                        <th>Usado por</th>
                                        <th>Fecha de eliminación</th>
                                        <th>Motivo</th>
                                        <th>Detalle</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $a=1;
                                    if(count($eliminados)>0){
                                        foreach ($eliminados as $m){
											$habitacion_eliminada = $this->hotel->habitacion_eliminada($m->id_relacionado)->habitacion_tipo_nombre;
											$habitacion_eliminada_numero = $this->hotel->habitacion_eliminada($m->id_relacionado)->habitacion_nro;
                                            $data_usuario = $this->usuario->listar_usuario($m->id_usuario);
                                            $nombre=$data_usuario->persona_nombre." ".$data_usuario->persona_apellido_paterno;
                                            $nombre2=" - ";
                                            $fechita2=" - ";
                                            $obs=" - ";
                                            if($m->id_usuario_uso!=0){
                                                $data_usuario2 = $this->usuario->listar_usuario($m->id_usuario_uso);
                                                $data_obs = $this->hotel->listar_detalle_rack_id($m->id_relacionado);
                                                $obs=$data_obs->rack_detalle_anulado_obs;
                                                $nombre2=$data_usuario2->persona_nombre." ".$data_usuario2->persona_apellido_paterno;
                                                $fechita2=$m->clave_eliminacion_uso_datetime;
                                            }
                                            echo "
                                                <tr>
                                                    <td>$a</td>
                                                    <td>$nombre</td>
                                                    <td>$m->clave_eliminacion_clave</td>
                                                    <td>$m->clave_eliminacion_datetime</td>
                                                    <td>$nombre2</td>
                                                    <td>$fechita2</td>
                                                    <td>$obs</td>
                                                    <td>
                                                    HABITACIÓN <br>
                                                    $habitacion_eliminada / <br>
                                                    N° $habitacion_eliminada_numero
                                                    </td>
                                                </tr>";
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
    function random(min,max) {
        var al = Math.floor((Math.random() * (max - min + 1)) + min);
        $("#num_azar").html(al);
        $("#num_azar_val").val(al);
    }
    function confirmar_clave() {
        var boton = "btn-confirmar-clave";
        var val = $('#num_azar_val').val();
        $.ajax({
            type: "POST",
            url: urlweb + "api/Hotel/confirmar_clave",
            data: "val=" + val,
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
</script>