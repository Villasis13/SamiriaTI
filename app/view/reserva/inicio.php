<!-- Modal Editar-->
<div class="modal fade" id="editarReserva" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Reserva</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="hidden" id="id_reserva_edit" name="id_reserva_edit">
                                        <label for="cliente_nombre" class="col-form-label">Nombre del Cliente</label>
                                        <input class="form-control" type="text" id="cliente_nombre" name="cliente_nombre">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="cliente_dni" class="col-form-label">DNI</label>
                                        <input class="form-control" type="text" id="cliente_dni" name="cliente_dni">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="cliente_desde" class="col-form-label">Desde</label>
                                        <input class="form-control" type="date" id="cliente_desde" name="cliente_desde">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="cliente_hasta" class="col-form-label">Hasta</label>
                                        <input class="form-control" type="date" id="cliente_hasta" name="cliente_hasta">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-5">
                                        <label for="cliente_habitacion" class="col-form-label">Habitación</label>
                                        <select class="form-control" id="cliente_habitacion">
											<?php foreach($tipos_hab as $hb){
												?>
                                                <option value="<?= $hb->id_habitacion_tipo ?>"><?= $hb->habitacion_tipo_nombre ?></option>
												<?php
											}
											?>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="cliente_cantidad" class="col-form-label">Cantidad</label>
                                        <input class="form-control" type="text" id="cliente_cantidad" name="cliente_cantidad">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="cliente_tarifa" class="col-form-label">Tarifa</label>
                                        <input class="form-control" type="text" id="cliente_tarifa" name="cliente_tarifa">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-5">
                                        <label for="cliente_habitacion_2" class="col-form-label">Habitación</label>
                                        <select class="form-control" id="cliente_habitacion_2">
											<?php foreach($tipos_hab as $hb2){
												?>
                                                <option value="<?= $hb2->id_habitacion_tipo ?>"><?= $hb2->habitacion_tipo_nombre ?></option>
												<?php
											}
											?>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="cliente_cantidad_2" class="col-form-label">Cantidad</label>
                                        <input class="form-control" type="text" id="cliente_cantidad_2" name="cliente_cantidad_2" value="">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="cliente_tarifa_2" class="col-form-label">Tarifa</label>
                                        <input class="form-control" type="text" id="cliente_tarifa_2" name="cliente_tarifa_2">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-5">
                                        <label for="cliente_habitacion_3" class="col-form-label">Habitación</label>
                                        <select class="form-control" id="cliente_habitacion_3">
											<?php foreach($tipos_hab as $hb3){
												?>
                                                <option value="<?= $hb3->id_habitacion_tipo ?>"><?= $hb3->habitacion_tipo_nombre ?></option>
												<?php
											}
											?>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="cliente_cantidad_3" class="col-form-label">Cantidad</label>
                                        <input class="form-control" type="text" id="cliente_cantidad_3" name="cliente_cantidad_3">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="cliente_tarifa_3" class="col-form-label">Tarifa</label>
                                        <input class="form-control" type="text" id="cliente_tarifa_3" name="cliente_tarifa_3">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="cliente_numero" class="col-form-label">Número</label>
                                        <input class="form-control" type="text" id="cliente_numero" name="cliente_numero">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="cliente_contacto" class="col-form-label">Contacto</label>
                                        <input class="form-control" type="text" id="cliente_contacto" name="cliente_contacto">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="cliente_origen" class="col-form-label">Origen</label>
                                        <select class="form-control" id="cliente_origen">
                                            <option>DIRECTO</option>
                                            <option>WEB</option>
                                            <option>OTA</option>
                                            <option>BOOKING</option>
                                            <option>EXPEDIA</option>
                                            <option>OTROS</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="cliente_observaciones" class="col-form-label">Observaciones</label>
                                        <input class="form-control" type="text" id="cliente_observaciones" name="cliente_observaciones">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btn-editar-reserva" onclick="guardar_reserva_edit()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>
<!--Fin de modal editar-->
<div class="modal fade" id="abrir_caja" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ABRIR CAJA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="caja">Caja</label>
                                <select class="form-control" name="caja" id="caja">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($cajas as $c){
                                        echo "<option value='$c->id_caja'>$c->caja_nombre</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="turno">Turno</label>
                                <select class="form-control" name="turno" id="turno">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($turnos as $c){
                                        echo "<option value='$c->id_turno'>$c->turno_nombre</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
                        foreach ($monedas as $moned){
                          ?>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="apertura_monto<?= $moned->id_moneda?>">Monto en <?= $moned->moneda ?></label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" value="0" id="apertura_monto<?= $moned->id_moneda?>" name="apertura_monto<?= $moned->id_moneda?>">
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-abrir-caja" onclick="abrir_caja()"><i class="fa fa-save fa-sm text-white-50"></i> Abrir Caja</button>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reservas</h1>
        <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fa fa-download fa-sm text-white-50"></i> Generate Report</a>-->
    </div>
    <div class="row">
        <div class="col-sm-5">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dni">Dni</label>
                        <input type="text" class="form-control" id="dni">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input class="form-control" type="text" id="nombre">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="desde">Desde</label>
                        <input class="form-control" type="date" id="desde">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="hasta">Hasta</label>
                        <input class="form-control" type="date" id="hasta">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="id_tipo_hab">Tipo Habitacion</label>
                        <select class="form-control" name="id_tipo_hab" id="id_tipo_hab">
                            <?php foreach ($tipos_hab as $tp){
                                echo "<option value='$tp->id_habitacion_tipo'>$tp->habitacion_tipo_nombre</option>";
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="cant_tipo_habitacion">Cant</label>
                        <input class="form-control" type="number" id="cant_tipo_habitacion">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="tarifa_tipo_habitacion">Tarifa</label>
                        <input class="form-control" type="text" id="tarifa_tipo_habitacion">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="id_tipo_hab_2">Tipo Habitacion</label>
                        <select class="form-control" name="id_tipo_hab_2" id="id_tipo_hab_2">
                            <?php foreach ($tipos_hab as $tp){
                                echo "<option value='$tp->id_habitacion_tipo'>$tp->habitacion_tipo_nombre</option>";
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="cant_tipo_habitacion_2">Cant</label>
                        <input class="form-control" type="number" id="cant_tipo_habitacion_2">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="tarifa_tipo_habitacion_2">Tarifa</label>
                        <input class="form-control" type="text" id="tarifa_tipo_habitacion_2">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="id_tipo_hab_3">Tipo Habitacion</label>
                        <select class="form-control" name="id_tipo_hab_3" id="id_tipo_hab_3">
                            <?php foreach ($tipos_hab as $tp){
                                echo "<option value='$tp->id_habitacion_tipo'>$tp->habitacion_tipo_nombre</option>";
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="cant_tipo_habitacion_3">Cant</label>
                        <input class="form-control" type="number" id="cant_tipo_habitacion_3">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="tarifa_tipo_habitacion_3">Tarifa</label>
                        <input class="form-control" type="text" id="tarifa_tipo_habitacion_3">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="reserva_contacto">Contacto</label>
                        <input class="form-control" type="text" id="reserva_contacto">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="reserva_numero">Número</label>
                        <input class="form-control" type="text" id="reserva_numero">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="reserva_origen">Origen</label>
                        <select class="form-control" id="reserva_origen">
                            <option>DIRECTO</option>
                            <option>WEB</option>
                            <option>OTA</option>
                            <option>BOOKING</option>
                            <option>EXPEDIA</option>
                            <option>OTROS</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="reserva_obs">Observaciones</label>
                        <textarea class="form-control" id="reserva_obs"></textarea>
                    </div>
                </div>
                        <div class="col-lg-12">
                            <button style="width: 100%;" class="btn btn-success" onclick="guardar_reserva()">Guardar</button>
                        </div>
            </div>
                </div>
            </div>
        </div>
        <div class="col-sm-7" style="font-size: 10pt !important">
            <div class="row">
                <div class="col-sm-3">
                    <label for="fecha_a_buscar">Fecha</label>
                    <input type="date" value="<?= $fecha_busqueda ?>" id="fecha_a_buscar" class="form-control">
                </div>
                <div class="col-sm-1" style="margin-top: 8px">
                    <br><button style="width: 100%" onclick="buscar_fecha()" class="btn btn-primary"><i class="fa fa-search-plus"></i></button>
                </div>
                <div class="col-sm-1" style="margin-top: 8px">
                    <br><a target="_blank" href="<?= _SERVER_ ?>Reserva/reporte_excel/<?= $fecha_busqueda ?>" style="width: 100%" class="btn btn-success"><i class="fa fa-file-excel-o"></i></a>
                </div>
            </div><br><br>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <td style="text-align: center">Cliente</td>
                            <td style="text-align: center">Fechas</td>
                            <td style="text-align: center">Habitaciones</td>
                            <td style="text-align: center">Observaciones</td>
                            <td style="text-align: center">Número</td>
                            <td style="text-align: center">Contacto</td>
                            <td style="text-align: center">Origen</td>
                            <td style="text-align: center">Opciones</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(count($reservas)>0){
                            foreach ($reservas as $dc){
                                $detalle="";
                                if($dc->cant_tipo_habitacion!=0){
                                    $data_tipo=$this->hotel->listar_tipo_habitacion_por_id($dc->id_tipo_habitacion);
                                    $detalle.=$dc->cant_tipo_habitacion." ".$data_tipo->habitacion_tipo_nombre." S/. ".$dc->tarifa_tipo_habitacion."<br>";
                                }if($dc->cant_tipo_habitacion_2!=0){
                                    $data_tipo=$this->hotel->listar_tipo_habitacion_por_id($dc->id_tipo_habitacion_2);
                                    $detalle.=$dc->cant_tipo_habitacion_2." ".$data_tipo->habitacion_tipo_nombre." S/. ".$dc->tarifa_tipo_habitacion_2."<br>";
                                }if($dc->cant_tipo_habitacion_3!=0){
                                    $data_tipo=$this->hotel->listar_tipo_habitacion_por_id($dc->id_tipo_habitacion_3);
                                    $detalle.=$dc->cant_tipo_habitacion_3." ".$data_tipo->habitacion_tipo_nombre." S/. ".$dc->tarifa_tipo_habitacion_3."<br>";
                                }
                                ?>
                                <tr>
                                    <td style="text-align: center"><?= $dc->reserva_dni." ".$dc->reserva_nombre ?></td>
                                    <td style="text-align: center"><?= $dc->reserva_in. " al ". $dc->reserva_out ?></td>
                                    <td style="text-align: center"><?= $detalle ?></td>
                                    <td style="text-align: center"><?= $dc->reserva_obs ?></td>
                                    <td style="text-align: center"><?= $dc->reserva_numero ?></td>
                                    <td style="text-align: center"><?= $dc->reserva_contacto ?></td>
                                    <td style="text-align: center"><?= $dc->reserva_origen ?></td>
                                    <td style="text-align: center">
                                        <button class="btn btn-warning" data-toggle="modal" data-target="#editarReserva" onclick="editar_reserva(<?= $dc->id_reserva?>)"><i class="fa fa-pencil"></i></button><br><br>
                                        <button class="btn btn-danger" onclick="eliminar_reserva(<?= $dc->id_reserva ?>)"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                                <?php
                            }
                        }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script>
    function guardar_reserva() {
        var valor = true;
        var boton = "btn-guardar-reserva";
        var dni = $('#dni').val();
        var nombre = $('#nombre').val();
        var desde = $('#desde').val();
        var hasta = $('#hasta').val();
        var cant_tipo_habitacion = $('#cant_tipo_habitacion').val();
        var cant_tipo_habitacion_2 = $('#cant_tipo_habitacion_2').val();
        var cant_tipo_habitacion_3 = $('#cant_tipo_habitacion_3').val();
        var id_tipo_hab = $('#id_tipo_hab').val();
        var id_tipo_hab_2 = $('#id_tipo_hab_2').val();
        var id_tipo_hab_3 = $('#id_tipo_hab_3').val();
        var reserva_obs = $('#reserva_obs').val();
        var tarifa_tipo_habitacion = $('#tarifa_tipo_habitacion').val();
        var tarifa_tipo_habitacion_2 = $('#tarifa_tipo_habitacion_2').val();
        var tarifa_tipo_habitacion_3 = $('#tarifa_tipo_habitacion_3').val();
        var reserva_contacto = $('#reserva_contacto').val();
        var reserva_numero = $('#reserva_numero').val();
        var reserva_origen = $('#reserva_origen').val();
        valor = validar_campo_vacio('dni', dni, valor);
        valor = validar_campo_vacio('nombre', nombre, valor);
        valor = validar_campo_vacio('desde', desde, valor);
        valor = validar_campo_vacio('hasta', hasta, valor);
        valor = validar_campo_vacio('reserva_obs', reserva_obs, valor);
        if(valor){
            var cadena = "dni=" + dni +
                    "&nombre=" + nombre+
                    "&desde=" + desde+
                    "&hasta=" + hasta+
                    "&cant_tipo_habitacion=" + cant_tipo_habitacion+
                    "&cant_tipo_habitacion_2=" + cant_tipo_habitacion_2+
                    "&cant_tipo_habitacion_3=" + cant_tipo_habitacion_3+
                    "&id_tipo_hab=" + id_tipo_hab+
                    "&id_tipo_hab_2=" + id_tipo_hab_2+
                    "&id_tipo_hab_3=" + id_tipo_hab_3+
                    "&tarifa_tipo_habitacion=" + tarifa_tipo_habitacion+
                    "&tarifa_tipo_habitacion_2=" + tarifa_tipo_habitacion_2+
                    "&tarifa_tipo_habitacion_3=" + tarifa_tipo_habitacion_3+
                    "&reserva_contacto=" + reserva_contacto+
                    "&reserva_numero=" + reserva_numero+
                    "&reserva_origen=" + reserva_origen+
                    "&reserva_obs=" + reserva_obs;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Reserva/guardar_reserva",
                data: cadena,
                dataType: 'json',
                beforeSend: function () {cambiar_estado_boton(boton, 'Guardando...', true);},
                success:function (r) {
                    switch (r.result.code) {
                        case 1:
                            respuesta('Guardado correctamente', 'success')
                            setTimeout(function () {
                                location.reload();
                            }, 600);
                            break;
                        case 2: respuesta('Error al guardar', 'error');break;
                        default:respuesta('¡Algo catastrofico ha ocurrido!', 'error');break;
                    }
                }
            });
        }
    }
    function guardar_reserva_edit() {
        var valor = true;
        var boton = "btn-editar-reserva";
        var id_reserva_edit = $('#id_reserva_edit').val();
        var cliente_nombre = $('#cliente_nombre').val();
        var cliente_dni = $('#cliente_dni').val();
        var cliente_desde = $('#cliente_desde').val();
        var cliente_hasta = $('#cliente_hasta').val();
        var cliente_habitacion = $('#cliente_habitacion').val();
        var cliente_cantidad = $('#cliente_cantidad').val();
        var cliente_tarifa = $('#cliente_tarifa').val();
        var cliente_habitacion_2 = $('#cliente_habitacion_2').val();
        var cliente_cantidad_2 = $('#cliente_cantidad_2').val();
        var cliente_tarifa_2 = $('#cliente_tarifa_2').val();
        var cliente_habitacion_3 = $('#cliente_habitacion_3').val();
        var cliente_cantidad_3 = $('#cliente_cantidad_3').val();
        var cliente_tarifa_3 = $('#cliente_tarifa_3').val();
        var cliente_numero = $('#cliente_numero').val();
        var cliente_contacto = $('#cliente_contacto').val();
        var cliente_origen = $('#cliente_origen').val();
        var cliente_observaciones = $('#cliente_observaciones').val();

        valor = validar_campo_vacio('cliente_nombre', cliente_nombre, valor);
        valor = validar_campo_vacio('cliente_dni', cliente_dni, valor);
        valor = validar_campo_vacio('cliente_desde', cliente_desde, valor);
        valor = validar_campo_vacio('cliente_hasta', cliente_hasta, valor);
        valor = validar_campo_vacio('cliente_habitacion', cliente_habitacion, valor);
        valor = validar_campo_vacio('cliente_cantidad', cliente_cantidad, valor);
        valor = validar_campo_vacio('cliente_tarifa', cliente_tarifa, valor);
        valor = validar_campo_vacio('cliente_observaciones', cliente_observaciones, valor);
        if(valor){
            var cadena = "id_reserva_edit=" + id_reserva_edit +
                    "&cliente_nombre=" + cliente_nombre+
                    "&cliente_dni=" + cliente_dni+
                    "&cliente_desde=" + cliente_desde+
                    "&cliente_hasta=" + cliente_hasta+
                    "&cliente_habitacion=" + cliente_habitacion+
                    "&cliente_cantidad=" + cliente_cantidad+
                    "&cliente_tarifa=" + cliente_tarifa+
                    "&cliente_habitacion_2=" + cliente_habitacion_2+
                    "&cliente_cantidad_2=" + cliente_cantidad_2+
                    "&cliente_tarifa_2=" + cliente_tarifa_2+
                    "&cliente_habitacion_3=" + cliente_habitacion_3+
                    "&cliente_cantidad_3=" + cliente_cantidad_3+
                    "&cliente_tarifa_3=" + cliente_tarifa_3+
                    "&cliente_numero=" + cliente_numero+
                    "&cliente_contacto=" + cliente_contacto+
                    "&cliente_origen=" + cliente_origen+
                    "&cliente_observaciones=" + cliente_observaciones;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Reserva/editar_reserva",
                data: cadena,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, 'Editando...', true);
                },
                success:function (r) {
                    switch (r.result.code) {
                        case 1:
                            respuesta('Guardando', 'success');
                            setTimeout(function () {
                                location.reload();
                            }, 500);
                        break;
                        case 2:respuesta('Error al editar', 'error');
                            setTimeout(function () {
                                location.reload();
                            }, 500);
                        break;
                        default:respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            setTimeout(function () {
                                location.reload();
                            }, 500);
                        break;
                    }
                }
            });
        }
    }
    function eliminar_reserva(id) {
        $.ajax({
            type: "POST",
            url: urlweb + "api/Reserva/eliminar_reserva",
            data: "id="+id,
            dataType: 'json',
            success:function (r) {
                switch (r.result.code) {
                    case 1:location.reload();break;
                    case 2:respuesta('Error al guardar', 'error');break;
                    default:respuesta('¡Algo catastrofico ha ocurrido!', 'error');break;
                }
            }
        });

    }
    function editar_reserva(id){
        let guardarid = id;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Reserva/edicion_reserva",
            data: {
                guardarid :guardarid
            },
            dataType: 'json'
        }).done(function(datos_editar){
            console.log(datos_editar);
            let almacenar = datos_editar.result.code;
            $("#id_reserva_edit").val(guardarid);
            $('#cliente_nombre').val(almacenar.reserva_nombre);
            $('#cliente_dni').val(almacenar.reserva_dni);
            $('#cliente_desde').val(almacenar.reserva_in);
            $('#cliente_hasta').val(almacenar.reserva_out);
            $('#cliente_habitacion').val(almacenar.id_tipo_habitacion);
            //$("#cliente_habitacion option[value='"+id_tipo_habitacion+"']").attr("selected",true);
            $('#cliente_cantidad').val(almacenar.cant_tipo_habitacion);
            $('#cliente_tarifa').val(almacenar.tarifa_tipo_habitacion);
            $('#cliente_habitacion_2').val(almacenar.id_tipo_habitacion_2);
            $('#cliente_cantidad_2').val(almacenar.cant_tipo_habitacion_2);
            $('#cliente_tarifa_2').val(almacenar.tarifa_tipo_habitacion_2);
            $('#cliente_habitacion_3').val(almacenar.id_tipo_habitacion_3);
            $('#cliente_cantidad_3').val(almacenar.cant_tipo_habitacion_3);
            $('#cliente_tarifa_3').val(almacenar.tarifa_tipo_habitacion_3);
            $('#cliente_numero').val(almacenar.reserva_numero);
            $('#cliente_contacto').val(almacenar.reserva_contacto);
            $('#cliente_origen').val(almacenar.reserva_origen);
            $('#cliente_observaciones').val(almacenar.reserva_obs);
        });
        function edicion(reserva_nombre,
                         reserva_dni,
                         reserva_in,
                         reserva_out,
                         id_tipo_habitacion,
                         cant_tipo_habitacion,
                         tarifa_tipo_habitacion,
                         id_tipo_habitacion_2,
                         cant_tipo_habitacion_2,
                         tarifa_tipo_habitacion_2,
                         id_tipo_habitacion_3,
                         cant_tipo_habitacion_3,
                         tarifa_tipo_habitacion_3,
                         reserva_numero,
                         reserva_contacto,
                         reserva_origen,
                         reserva_obs){
        }

    }
    function buscar_fecha() {
        var fecha = $("#fecha_a_buscar").val();
        location.href=urlweb+"Reserva/inicio/"+fecha;
    }
</script>