

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
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Tipo de Cambio $</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <input class="form-control" onkeyup="validar_numeros_decimales_dos(this.id)" type="text" value="0" id="tipo_cambio" name="tipo_cambio">
                            </div>
                        </div>
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

<div class="modal fade" id="cerrar_caja" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">CERRAR CAJA <b><span id="caja_a_cerrar"></span></b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <input type="hidden" id="id_caja_a_cerrar">
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
                                    <input class="form-control" type="text" value="0" id="cierre_monto<?= $moned->id_moneda?>" name="apertura_monto<?= $moned->id_moneda?>">
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
                <button type="button" class="btn btn-success" id="btn-cerrar-caja" onclick="cerrar_caja()"><i class="fa fa-save fa-sm text-white-50"></i> Cerrar Caja</button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!--<div class="row m-t-25">
        <div class="col-sm-6 col-lg-3">
            <div class="overview-item overview-item--c1">
                <div class="overview__inner">
                    <div class="overview-box clearfix">
                        <div class="icon">
                            <i class="zmdi zmdi-shopping-cart"></i>
                        </div>
                        <div class="text">
                            <h2>0</h2>
                            <span>Ventas del Día</span>
                        </div>
                    </div>
                    <div class="overview-chart">
                        <canvas id="widgetChart1"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="overview-item overview-item--c2">
                <div class="overview__inner">
                    <div class="overview-box clearfix">
                        <div class="icon">
                            <i class="zmdi zmdi-shopping-cart"></i>
                        </div>
                        <div class="text">
                            <h2>0</h2>
                            <span>Ingresos del Día</span>
                        </div>
                    </div>
                    <div class="overview-chart">
                        <canvas id="widgetChart2"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="overview-item overview-item--c3">
                <div class="overview__inner">
                    <div class="overview-box clearfix">
                        <div class="icon">
                            <i class="zmdi zmdi-calendar-note"></i>
                        </div>
                        <div class="text">
                            <h2>0</h2>
                            <span>Ventas del Mes</span>
                        </div>
                    </div>
                    <div class="overview-chart">
                        <canvas id="widgetChart3"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
        <div class="col-sm-6 col-lg-3">
            <div class="overview-item overview-item--c4">
                <div class="overview__inner">
                    <div class="overview-box clearfix">
                        <div class="icon">
                            <i style="font-size: 25px;">S/.</i>
                        </div>
                        <div class="text">
                            <h2>0</h2>
                            <span>Ingreso del Mes</span>
                        </div>
                    </div>
                    <div class="overview-chart">
                        <canvas id="widgetChart4"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>-->

    <?php
    if($id_rol == 2 || $id_rol == 3 || $id_rol==4){
        ?>

        <div class="row">
            <?php
            if(count($data_caja)>0){
                foreach ($data_caja as $dc){
                    ?>
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-md font-weight-bold text-primary text-uppercase mb-1"><?= $dc->caja_nombre; ?></div>
                                        <div class="row">
                                            <?php
                                            foreach ($tipo_pago as $tp){
                                                echo "<div class='col-md-4'><div class=\"text-xs font-weight-bold text-primary text-uppercase mb-1\">$tp->tipo_pago_nombre</div></div>";
                                                foreach ($monedas as $m){
                                                    $data_a = $this->nav->listar_caja_monto_por_caja_cierre_moneda_tipo_pago($dc->id_caja_cierre,$m->id_moneda,$tp->id_tipo_pago);
                                                    echo "<div class='col-md-4'><div class=\"text-xs font-weight-bold text-uppercase mb-1\">$m->moneda</div>";
                                                    echo "<div class=\"h5 mb-0 font-weight-bold text-gray-800\">$data_a->caja_monto_monto</div></div>";
                                                }
                                            }
                                            ?>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <?php
                                            echo "<div class='col-md-4'><div class=\"text-xs font-weight-bold text-primary text-uppercase mb-1\">Tipo de Cambio $</div></div>";
                                            echo "<div class='col-md-4'><div class=\"text-xs font-weight-bold text-uppercase mb-1\">Valor</div>";
                                            echo "<div class=\"h5 mb-0 font-weight-bold text-gray-800\">$dc->tipo_cambio</div></div>";
                                            ?>
                                        </div>
                                        <a style="cursor: pointer" data-toggle="modal" onclick="caja_a_cerrar('<?= $dc->id_caja_cierre; ?>','<?= $dc->caja_nombre; ?>');" data-target="#cerrar_caja" class="btn btn-success text-white"><i class="fa fa-unlock-alt"></i> Cerrar Caja</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                if(count($cajas)>0){
                    ?>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-danger shadow h-100 py-2" style="cursor: pointer">
                            <a data-toggle="modal" data-target="#abrir_caja">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">ABRIR CAJA</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-warning fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php
                }
            }else{
                ?>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2" style="cursor: pointer">
                        <a data-toggle="modal" data-target="#abrir_caja">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">ABRIR CAJA</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa fa-warning fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <a href="<?= _SERVER_ ?>Hotel/inicio" target="_blank" style="text-decoration: none">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Ver habitaciones</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Libres: <?= $libres; ?></div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Ocupadas: <?= count($ocupadas); ?></div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Deshabilitadas: <?= count($deshabilitados); ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <a href="<?= _SERVER_ ?>Hotel/movimientos_caja" target="_blank" style="text-decoration: none">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Ver Movimientos de Caja</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($data_caja) ?> caja(s) abierta(s)</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-sticky-note fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <a href="<?= _SERVER_ ?>Ventas/historial_ventas" target="_blank" style="text-decoration: none">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Facturación Electrónica</div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= count($ventas_cant); ?> comprobantes sin enviar</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-clipboard fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100 py-2" style="border-left: 0.25rem solid #6a0c0d !important;">
                    <div class="card-body">

                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-lg font-weight-bold text-uppercase mb-1" style="color: #6a0c0d">Rooming List</div>
                                <a href="<?= _SERVER_ ?>Hotel/rooming" class="btn btn-warning" target="_blank" style="text-decoration: none;"><i class="fa fa-eye"></i> Ver</a>
                                <a href="<?= _SERVER_ ?>Hotel/rooming_excel" class="btn btn-success" target="_blank" style="text-decoration: none"><i class="fa fa-file-excel-o"></i> Excel</a>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-list-ul fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <a href="<?= _SERVER_ ?>Configuracion" target="_blank" style="text-decoration: none">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Configuracion</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-cogs fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <a href="<?= _SERVER_ ?>Receta/inicio" target="_blank" style="text-decoration: none">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">TOTAL DE RECETAS</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Con Insumos : <?= $receta_si->total; ?></div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Sin Insumos : <?= $receta_faltante->cantidad_faltante; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa fa-calendar fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
        </div>
        <?php
    }
    if($id_rol == 5 || $id_rol == 2 ){
        ?>
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <a href="<?= _SERVER_ ?>Pedido/gestionar" target="_blank" style="text-decoration: none">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Gestionar Pedidos</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Libres: <?= $verde; ?></div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Ocupadas: <?= $amarillo; ?></div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Deshabilitadas: <?= $rojo; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    <?php
    }
    ?>
</div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _STYLES_ADMIN_;?>vendor/chart.js/Chart.js"></script>
<script>
    function actualizar_cambios(){
        var valor=true;
        var cadena=true;
        if (valor){
            $('#btn_actualizar').hide();
            $('#btn_actualizar_none').show();
            $.ajax({
                type: "POST",
                url: urlweb + "api/Menu/actualizar_cambio",
                data: cadena,
                dataType: 'json',
                success:function (r) {
                    console.log(r);
                    console.log(r.result.code)
                    switch (r.result.code) {
                        case 1:
                            respuesta('¡Actualizado  Exitosamente!', 'success');
                            $('#btn_actualizar_none').hide();
                            $('#btn_actualizar').show();
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                            break;
                        case 2:
                            respuesta('Error al Actualizar - No ejecuto linea 2 ', 'error');
                            $('#btn_actualizar_none').hide();
                            $('#btn_actualizar').show();
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!- no ejecuto linea 1', 'error');
                            $('#btn_actualizar_none').hide();
                            $('#btn_actualizar').show();
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                            break;
                    }

                }

            });
        }
    }

    function abrir_caja() {
        var valor = true;
        var boton = "btn-abrir-caja";
        var caja = $('#caja').val();
        var tipo_cambio = $('#tipo_cambio').val() * 1;
        var apertura_monto = "";
        <?php
        foreach ($monedas as $moned){
        ?>
        var montito = $("#apertura_monto<?= $moned->id_moneda?>").val();
        apertura_monto+="<?= $moned->id_moneda ?>--..--"+montito+".-.-";
        <?php
        }
        ?>
        var turno = $('#turno').val();
        if(tipo_cambio==0){
            respuesta('Ingrese un valor mayor que 0 en el tipo de cambio', 'error')
            $('#tipo_cambio').css('border','solid red');
            valor = false;
        }
        valor = validar_campo_vacio('caja', caja, valor);
        valor = validar_campo_vacio('turno', turno, valor);
        if(valor){
            var cadena = "caja=" + caja +
                    "&montos=" + apertura_monto+
                    "&tipo_cambio=" + tipo_cambio+
                    "&turno=" + turno;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Hotel/abrir_caja",
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
    function cerrar_caja() {
        var valor = true;
        var boton = "btn-cerrar-caja";
        var caja = $('#id_caja_a_cerrar').val();
        var apertura_monto = "";
        <?php
        foreach ($monedas as $moned){
        ?>
        var montito = $("#cierre_monto<?= $moned->id_moneda?>").val();
        apertura_monto+="<?= $moned->id_moneda ?>--..--"+montito+".-.-";
        <?php
        }
        ?>
        if(valor){
            var cadena = "caja=" + caja + "&montos=" + apertura_monto;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Hotel/cerrar_caja",
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
    function caja_a_cerrar(id,nombre) {
        $("#id_caja_a_cerrar").val(id);
        $("#caja_a_cerrar").html(nombre);
    }
</script>
<style>
    .overview-wrap {
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -webkit-justify-content: space-between;
        -moz-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        -webkit-box-align: center;
        -webkit-align-items: center;
        -moz-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }

    @media (max-width: 767px) {
        .overview-wrap {
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -webkit-flex-direction: column;
            -moz-box-orient: vertical;
            -moz-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        .overview-wrap .button {
            -webkit-box-ordinal-group: 2;
            -webkit-order: 1;
            -moz-box-ordinal-group: 2;
            -ms-flex-order: 1;
            order: 1;
        }

        .overview-wrap h2 {
            -webkit-box-ordinal-group: 3;
            -webkit-order: 2;
            -moz-box-ordinal-group: 3;
            -ms-flex-order: 2;
            order: 2;
        }
    }

    .overview-item {
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        padding: 30px;
        padding-bottom: 0;
        -webkit-box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.1);
        -moz-box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.1);
        box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.1);
        margin-bottom: 40px;
    }

    @media (min-width: 992px) and (max-width: 1519px) {
        .overview-item {
            padding-left: 15px;
            padding-right: 15px;
        }
    }

    .overview-item--c1 {
        background-image: -moz-linear-gradient(90deg, #3f5efb 0%, #fc466b 100%);
        background-image: -webkit-linear-gradient(90deg, #3f5efb 0%, #fc466b 100%);
        background-image: -ms-linear-gradient(90deg, #3f5efb 0%, #fc466b 100%);
    }

    .overview-item--c2 {
        background-image: -moz-linear-gradient(90deg, #11998e 0%, #38ef7d 100%);
        background-image: -webkit-linear-gradient(90deg, #11998e 0%, #38ef7d 100%);
        background-image: -ms-linear-gradient(90deg, #11998e 0%, #38ef7d 100%);
    }

    .overview-item--c3 {
        background-image: -moz-linear-gradient(90deg, #ee0979 0%, #ff6a00 100%);
        background-image: -webkit-linear-gradient(90deg, #ee0979 0%, #ff6a00 100%);
        background-image: -ms-linear-gradient(90deg, #ee0979 0%, #ff6a00 100%);
    }

    .overview-item--c4 {
        background-image: -moz-linear-gradient(90deg, #45b649 0%, #dce35b 100%);
        background-image: -webkit-linear-gradient(90deg, #45b649 0%, #dce35b 100%);
        background-image: -ms-linear-gradient(90deg, #45b649 0%, #dce35b 100%);
    }

    .overview-box .icon {
        display: inline-block;
        vertical-align: top;
        margin-right: 15px;
    }

    .overview-box .icon i {
        font-size: 60px;
        color: #fff;
    }

    @media (min-width: 992px) and (max-width: 1199px) {
        .overview-box .icon {
            margin-right: 3px;
        }

        .overview-box .icon i {
            font-size: 30px;
        }
    }

    @media (max-width: 991px) {
        .overview-box .icon {
            font-size: 46px;
        }
    }

    .overview-box .text {
        font-weight: 300;
        display: inline-block;
    }

    .overview-box .text h2 {
        font-weight: 300;
        color: #fff;
        font-size: 36px;
        line-height: 1;
        margin-bottom: 5px;
    }

    .overview-box .text span {
        font-size: 18px;
        color: rgba(255, 255, 255, 0.6);
    }

    @media (min-width: 992px) and (max-width: 1199px) {
        .overview-box .text {
            display: inline-block;
        }

        .overview-box .text h2 {
            font-size: 20px;
            margin-bottom: 0;
        }

        .overview-box .text span {
            font-size: 14px;
        }
    }

    @media (max-width: 991px) {
        .overview-box .text h2 {
            font-size: 26px;
        }

        .overview-box .text span {
            font-size: 15px;
        }
    }

    .overview-chart {
        height: 115px;
        position: relative;
    }

    .overview-chart canvas {
        width: 100%;
    }
</style>
<script>
    (function ($) {
        // USE STRICT
        "use strict";

        try {
            //WidgetChart 1
            var ctx = document.getElementById("widgetChart1");
            if (ctx) {
                ctx.height = 130;
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                        type: 'line',
                        datasets: [{
                            data: [78, 81, 80, 45, 34, 12, 40],
                            label: 'Dataset',
                            backgroundColor: 'rgba(255,255,255,.1)',
                            borderColor: 'rgba(255,255,255,.55)',
                        },]
                    },
                    options: {
                        maintainAspectRatio: true,
                        legend: {
                            display: false
                        },
                        layout: {
                            padding: {
                                left: 0,
                                right: 0,
                                top: 0,
                                bottom: 0
                            }
                        },
                        responsive: true,
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    color: 'transparent',
                                    zeroLineColor: 'transparent'
                                },
                                ticks: {
                                    fontSize: 2,
                                    fontColor: 'transparent'
                                }
                            }],
                            yAxes: [{
                                display: false,
                                ticks: {
                                    display: false,
                                }
                            }]
                        },
                        title: {
                            display: false,
                        },
                        elements: {
                            line: {
                                borderWidth: 0
                            },
                            point: {
                                radius: 0,
                                hitRadius: 10,
                                hoverRadius: 4
                            }
                        }
                    }
                });
            }


            //WidgetChart 2
            var ctx = document.getElementById("widgetChart2");
            if (ctx) {
                ctx.height = 130;
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                        type: 'line',
                        datasets: [{
                            data: [1, 18, 9, 17, 34, 22],
                            label: 'Dataset',
                            backgroundColor: 'transparent',
                            borderColor: 'rgba(255,255,255,.55)',
                        },]
                    },
                    options: {

                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        responsive: true,
                        tooltips: {
                            mode: 'index',
                            titleFontSize: 12,
                            titleFontColor: '#000',
                            bodyFontColor: '#000',
                            backgroundColor: '#fff',
                            titleFontFamily: 'Montserrat',
                            bodyFontFamily: 'Montserrat',
                            cornerRadius: 3,
                            intersect: false,
                        },
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    color: 'transparent',
                                    zeroLineColor: 'transparent'
                                },
                                ticks: {
                                    fontSize: 2,
                                    fontColor: 'transparent'
                                }
                            }],
                            yAxes: [{
                                display: false,
                                ticks: {
                                    display: false,
                                }
                            }]
                        },
                        title: {
                            display: false,
                        },
                        elements: {
                            line: {
                                tension: 0.00001,
                                borderWidth: 1
                            },
                            point: {
                                radius: 4,
                                hitRadius: 10,
                                hoverRadius: 4
                            }
                        }
                    }
                });
            }


            //WidgetChart 3
            var ctx = document.getElementById("widgetChart3");
            if (ctx) {
                ctx.height = 130;
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                        type: 'line',
                        datasets: [{
                            data: [65, 59, 84, 84, 51, 55],
                            label: 'Dataset',
                            backgroundColor: 'transparent',
                            borderColor: 'rgba(255,255,255,.55)',
                        },]
                    },
                    options: {

                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        responsive: true,
                        tooltips: {
                            mode: 'index',
                            titleFontSize: 12,
                            titleFontColor: '#000',
                            bodyFontColor: '#000',
                            backgroundColor: '#fff',
                            titleFontFamily: 'Montserrat',
                            bodyFontFamily: 'Montserrat',
                            cornerRadius: 3,
                            intersect: false,
                        },
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    color: 'transparent',
                                    zeroLineColor: 'transparent'
                                },
                                ticks: {
                                    fontSize: 2,
                                    fontColor: 'transparent'
                                }
                            }],
                            yAxes: [{
                                display: false,
                                ticks: {
                                    display: false,
                                }
                            }]
                        },
                        title: {
                            display: false,
                        },
                        elements: {
                            line: {
                                borderWidth: 1
                            },
                            point: {
                                radius: 4,
                                hitRadius: 10,
                                hoverRadius: 4
                            }
                        }
                    }
                });
            }


            //WidgetChart 4
            var ctx = document.getElementById("widgetChart4");
            if (ctx) {
                ctx.height = 115;
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                        datasets: [
                            {
                                label: "My First dataset",
                                data: [78, 81, 80, 65, 58, 75, 60, 75, 65, 60, 60, 75],
                                borderColor: "transparent",
                                borderWidth: "0",
                                backgroundColor: "rgba(255,255,255,.3)"
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: true,
                        legend: {
                            display: false
                        },
                        scales: {
                            xAxes: [{
                                display: false,
                                categoryPercentage: 1,
                                barPercentage: 0.65
                            }],
                            yAxes: [{
                                display: false
                            }]
                        }
                    }
                });
            }

            // Recent Report
            const brandProduct = 'rgba(0,181,233,0.8)'
            const brandService = 'rgba(0,173,95,0.8)'

            var elements = 10
            var data1 = [52, 60, 55, 50, 65, 80, 57, 70, 105, 115]
            var data2 = [102, 70, 80, 100, 56, 53, 80, 75, 65, 90]

            var ctx = document.getElementById("recent-rep-chart");
            if (ctx) {
                ctx.height = 250;
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', ''],
                        datasets: [
                            {
                                label: 'My First dataset',
                                backgroundColor: brandService,
                                borderColor: 'transparent',
                                pointHoverBackgroundColor: '#fff',
                                borderWidth: 0,
                                data: data1

                            },
                            {
                                label: 'My Second dataset',
                                backgroundColor: brandProduct,
                                borderColor: 'transparent',
                                pointHoverBackgroundColor: '#fff',
                                borderWidth: 0,
                                data: data2

                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: true,
                        legend: {
                            display: false
                        },
                        responsive: true,
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    drawOnChartArea: true,
                                    color: '#f2f2f2'
                                },
                                ticks: {
                                    fontFamily: "Poppins",
                                    fontSize: 12
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    maxTicksLimit: 5,
                                    stepSize: 50,
                                    max: 150,
                                    fontFamily: "Poppins",
                                    fontSize: 12
                                },
                                gridLines: {
                                    display: true,
                                    color: '#f2f2f2'

                                }
                            }]
                        },
                        elements: {
                            point: {
                                radius: 0,
                                hitRadius: 10,
                                hoverRadius: 4,
                                hoverBorderWidth: 3
                            }
                        }


                    }
                });
            }

            // Percent Chart
            var ctx = document.getElementById("percent-chart");
            if (ctx) {
                ctx.height = 280;
                var myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        datasets: [
                            {
                                label: "My First dataset",
                                data: [60, 40],
                                backgroundColor: [
                                    '#00b5e9',
                                    '#fa4251'
                                ],
                                hoverBackgroundColor: [
                                    '#00b5e9',
                                    '#fa4251'
                                ],
                                borderWidth: [
                                    0, 0
                                ],
                                hoverBorderColor: [
                                    'transparent',
                                    'transparent'
                                ]
                            }
                        ],
                        labels: [
                            'Products',
                            'Services'
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        cutoutPercentage: 55,
                        animation: {
                            animateScale: true,
                            animateRotate: true
                        },
                        legend: {
                            display: false
                        },
                        tooltips: {
                            titleFontFamily: "Poppins",
                            xPadding: 15,
                            yPadding: 10,
                            caretPadding: 0,
                            bodyFontSize: 16
                        }
                    }
                });
            }

        } catch (error) {
            console.log(error);
        }



        try {

            // Recent Report 2
            const bd_brandProduct2 = 'rgba(0,181,233,0.9)'
            const bd_brandService2 = 'rgba(0,173,95,0.9)'
            const brandProduct2 = 'rgba(0,181,233,0.2)'
            const brandService2 = 'rgba(0,173,95,0.2)'

            var data3 = [52, 60, 55, 50, 65, 80, 57, 70, 105, 115]
            var data4 = [102, 70, 80, 100, 56, 53, 80, 75, 65, 90]

            var ctx = document.getElementById("recent-rep2-chart");
            if (ctx) {
                ctx.height = 230;
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', ''],
                        datasets: [
                            {
                                label: 'My First dataset',
                                backgroundColor: brandService2,
                                borderColor: bd_brandService2,
                                pointHoverBackgroundColor: '#fff',
                                borderWidth: 0,
                                data: data3

                            },
                            {
                                label: 'My Second dataset',
                                backgroundColor: brandProduct2,
                                borderColor: bd_brandProduct2,
                                pointHoverBackgroundColor: '#fff',
                                borderWidth: 0,
                                data: data4

                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: true,
                        legend: {
                            display: false
                        },
                        responsive: true,
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    drawOnChartArea: true,
                                    color: '#f2f2f2'
                                },
                                ticks: {
                                    fontFamily: "Poppins",
                                    fontSize: 12
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    maxTicksLimit: 5,
                                    stepSize: 50,
                                    max: 150,
                                    fontFamily: "Poppins",
                                    fontSize: 12
                                },
                                gridLines: {
                                    display: true,
                                    color: '#f2f2f2'

                                }
                            }]
                        },
                        elements: {
                            point: {
                                radius: 0,
                                hitRadius: 10,
                                hoverRadius: 4,
                                hoverBorderWidth: 3
                            },
                            line: {
                                tension: 0
                            }
                        }


                    }
                });
            }

        } catch (error) {
            console.log(error);
        }


        try {

            // Recent Report 3
            const bd_brandProduct3 = 'rgba(0,181,233,0.9)';
            const bd_brandService3 = 'rgba(0,173,95,0.9)';
            const brandProduct3 = 'transparent';
            const brandService3 = 'transparent';

            var data5 = [52, 60, 55, 50, 65, 80, 57, 115];
            var data6 = [102, 70, 80, 100, 56, 53, 80, 90];

            var ctx = document.getElementById("recent-rep3-chart");
            if (ctx) {
                ctx.height = 230;
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', ''],
                        datasets: [
                            {
                                label: 'My First dataset',
                                backgroundColor: brandService3,
                                borderColor: bd_brandService3,
                                pointHoverBackgroundColor: '#fff',
                                borderWidth: 0,
                                data: data5,
                                pointBackgroundColor: bd_brandService3
                            },
                            {
                                label: 'My Second dataset',
                                backgroundColor: brandProduct3,
                                borderColor: bd_brandProduct3,
                                pointHoverBackgroundColor: '#fff',
                                borderWidth: 0,
                                data: data6,
                                pointBackgroundColor: bd_brandProduct3

                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        responsive: true,
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    drawOnChartArea: true,
                                    color: '#f2f2f2'
                                },
                                ticks: {
                                    fontFamily: "Poppins",
                                    fontSize: 12
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    maxTicksLimit: 5,
                                    stepSize: 50,
                                    max: 150,
                                    fontFamily: "Poppins",
                                    fontSize: 12
                                },
                                gridLines: {
                                    display: false,
                                    color: '#f2f2f2'
                                }
                            }]
                        },
                        elements: {
                            point: {
                                radius: 3,
                                hoverRadius: 4,
                                hoverBorderWidth: 3,
                                backgroundColor: '#333'
                            }
                        }


                    }
                });
            }

        } catch (error) {
            console.log(error);
        }

        try {
            //WidgetChart 5
            var ctx = document.getElementById("widgetChart5");
            if (ctx) {
                ctx.height = 220;
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                        datasets: [
                            {
                                label: "My First dataset",
                                data: [78, 81, 80, 64, 65, 80, 70, 75, 67, 85, 66, 68],
                                borderColor: "transparent",
                                borderWidth: "0",
                                backgroundColor: "#ccc",
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: true,
                        legend: {
                            display: false
                        },
                        scales: {
                            xAxes: [{
                                display: false,
                                categoryPercentage: 1,
                                barPercentage: 0.65
                            }],
                            yAxes: [{
                                display: false
                            }]
                        }
                    }
                });
            }

        } catch (error) {
            console.log(error);
        }

        try {

            // Percent Chart 2
            var ctx = document.getElementById("percent-chart2");
            if (ctx) {
                ctx.height = 209;
                var myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        datasets: [
                            {
                                label: "My First dataset",
                                data: [60, 40],
                                backgroundColor: [
                                    '#00b5e9',
                                    '#fa4251'
                                ],
                                hoverBackgroundColor: [
                                    '#00b5e9',
                                    '#fa4251'
                                ],
                                borderWidth: [
                                    0, 0
                                ],
                                hoverBorderColor: [
                                    'transparent',
                                    'transparent'
                                ]
                            }
                        ],
                        labels: [
                            'Products',
                            'Services'
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        cutoutPercentage: 87,
                        animation: {
                            animateScale: true,
                            animateRotate: true
                        },
                        legend: {
                            display: false,
                            position: 'bottom',
                            labels: {
                                fontSize: 14,
                                fontFamily: "Poppins,sans-serif"
                            }

                        },
                        tooltips: {
                            titleFontFamily: "Poppins",
                            xPadding: 15,
                            yPadding: 10,
                            caretPadding: 0,
                            bodyFontSize: 16,
                        }
                    }
                });
            }

        } catch (error) {
            console.log(error);
        }

        try {
            //Sales chart
            var ctx = document.getElementById("sales-chart");
            if (ctx) {
                ctx.height = 150;
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ["2010", "2011", "2012", "2013", "2014", "2015", "2016"],
                        type: 'line',
                        defaultFontFamily: 'Poppins',
                        datasets: [{
                            label: "Foods",
                            data: [0, 30, 10, 120, 50, 63, 10],
                            backgroundColor: 'transparent',
                            borderColor: 'rgba(220,53,69,0.75)',
                            borderWidth: 3,
                            pointStyle: 'circle',
                            pointRadius: 5,
                            pointBorderColor: 'transparent',
                            pointBackgroundColor: 'rgba(220,53,69,0.75)',
                        }, {
                            label: "Electronics",
                            data: [0, 50, 40, 80, 40, 79, 120],
                            backgroundColor: 'transparent',
                            borderColor: 'rgba(40,167,69,0.75)',
                            borderWidth: 3,
                            pointStyle: 'circle',
                            pointRadius: 5,
                            pointBorderColor: 'transparent',
                            pointBackgroundColor: 'rgba(40,167,69,0.75)',
                        }]
                    },
                    options: {
                        responsive: true,
                        tooltips: {
                            mode: 'index',
                            titleFontSize: 12,
                            titleFontColor: '#000',
                            bodyFontColor: '#000',
                            backgroundColor: '#fff',
                            titleFontFamily: 'Poppins',
                            bodyFontFamily: 'Poppins',
                            cornerRadius: 3,
                            intersect: false,
                        },
                        legend: {
                            display: false,
                            labels: {
                                usePointStyle: true,
                                fontFamily: 'Poppins',
                            },
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                gridLines: {
                                    display: false,
                                    drawBorder: false
                                },
                                scaleLabel: {
                                    display: false,
                                    labelString: 'Month'
                                },
                                ticks: {
                                    fontFamily: "Poppins"
                                }
                            }],
                            yAxes: [{
                                display: true,
                                gridLines: {
                                    display: false,
                                    drawBorder: false
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Value',
                                    fontFamily: "Poppins"

                                },
                                ticks: {
                                    fontFamily: "Poppins"
                                }
                            }]
                        },
                        title: {
                            display: false,
                            text: 'Normal Legend'
                        }
                    }
                });
            }


        } catch (error) {
            console.log(error);
        }

        try {

            //Team chart
            var ctx = document.getElementById("team-chart");
            if (ctx) {
                ctx.height = 150;
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ["2010", "2011", "2012", "2013", "2014", "2015", "2016"],
                        type: 'line',
                        defaultFontFamily: 'Poppins',
                        datasets: [{
                            data: [0, 7, 3, 5, 2, 10, 7],
                            label: "Expense",
                            backgroundColor: 'rgba(0,103,255,.15)',
                            borderColor: 'rgba(0,103,255,0.5)',
                            borderWidth: 3.5,
                            pointStyle: 'circle',
                            pointRadius: 5,
                            pointBorderColor: 'transparent',
                            pointBackgroundColor: 'rgba(0,103,255,0.5)',
                        },]
                    },
                    options: {
                        responsive: true,
                        tooltips: {
                            mode: 'index',
                            titleFontSize: 12,
                            titleFontColor: '#000',
                            bodyFontColor: '#000',
                            backgroundColor: '#fff',
                            titleFontFamily: 'Poppins',
                            bodyFontFamily: 'Poppins',
                            cornerRadius: 3,
                            intersect: false,
                        },
                        legend: {
                            display: false,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                fontFamily: 'Poppins',
                            },


                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                gridLines: {
                                    display: false,
                                    drawBorder: false
                                },
                                scaleLabel: {
                                    display: false,
                                    labelString: 'Month'
                                },
                                ticks: {
                                    fontFamily: "Poppins"
                                }
                            }],
                            yAxes: [{
                                display: true,
                                gridLines: {
                                    display: false,
                                    drawBorder: false
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Value',
                                    fontFamily: "Poppins"
                                },
                                ticks: {
                                    fontFamily: "Poppins"
                                }
                            }]
                        },
                        title: {
                            display: false,
                        }
                    }
                });
            }


        } catch (error) {
            console.log(error);
        }

        try {
            //bar chart
            var ctx = document.getElementById("barChart");
            if (ctx) {
                ctx.height = 200;
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    defaultFontFamily: 'Poppins',
                    data: {
                        labels: ["January", "February", "March", "April", "May", "June", "July"],
                        datasets: [
                            {
                                label: "My First dataset",
                                data: [65, 59, 80, 81, 56, 55, 40],
                                borderColor: "rgba(0, 123, 255, 0.9)",
                                borderWidth: "0",
                                backgroundColor: "rgba(0, 123, 255, 0.5)",
                                fontFamily: "Poppins"
                            },
                            {
                                label: "My Second dataset",
                                data: [28, 48, 40, 19, 86, 27, 90],
                                borderColor: "rgba(0,0,0,0.09)",
                                borderWidth: "0",
                                backgroundColor: "rgba(0,0,0,0.07)",
                                fontFamily: "Poppins"
                            }
                        ]
                    },
                    options: {
                        legend: {
                            position: 'top',
                            labels: {
                                fontFamily: 'Poppins'
                            }

                        },
                        scales: {
                            xAxes: [{
                                ticks: {
                                    fontFamily: "Poppins"

                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontFamily: "Poppins"
                                }
                            }]
                        }
                    }
                });
            }


        } catch (error) {
            console.log(error);
        }

        try {

            //radar chart
            var ctx = document.getElementById("radarChart");
            if (ctx) {
                ctx.height = 200;
                var myChart = new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: [["Eating", "Dinner"], ["Drinking", "Water"], "Sleeping", ["Designing", "Graphics"], "Coding", "Cycling", "Running"],
                        defaultFontFamily: 'Poppins',
                        datasets: [
                            {
                                label: "My First dataset",
                                data: [65, 59, 66, 45, 56, 55, 40],
                                borderColor: "rgba(0, 123, 255, 0.6)",
                                borderWidth: "1",
                                backgroundColor: "rgba(0, 123, 255, 0.4)"
                            },
                            {
                                label: "My Second dataset",
                                data: [28, 12, 40, 19, 63, 27, 87],
                                borderColor: "rgba(0, 123, 255, 0.7",
                                borderWidth: "1",
                                backgroundColor: "rgba(0, 123, 255, 0.5)"
                            }
                        ]
                    },
                    options: {
                        legend: {
                            position: 'top',
                            labels: {
                                fontFamily: 'Poppins'
                            }

                        },
                        scale: {
                            ticks: {
                                beginAtZero: true,
                                fontFamily: "Poppins"
                            }
                        }
                    }
                });
            }

        } catch (error) {
            console.log(error)
        }

        try {

            //line chart
            var ctx = document.getElementById("lineChart");
            if (ctx) {
                ctx.height = 150;
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ["January", "February", "March", "April", "May", "June", "July"],
                        defaultFontFamily: "Poppins",
                        datasets: [
                            {
                                label: "My First dataset",
                                borderColor: "rgba(0,0,0,.09)",
                                borderWidth: "1",
                                backgroundColor: "rgba(0,0,0,.07)",
                                data: [22, 44, 67, 43, 76, 45, 12]
                            },
                            {
                                label: "My Second dataset",
                                borderColor: "rgba(0, 123, 255, 0.9)",
                                borderWidth: "1",
                                backgroundColor: "rgba(0, 123, 255, 0.5)",
                                pointHighlightStroke: "rgba(26,179,148,1)",
                                data: [16, 32, 18, 26, 42, 33, 44]
                            }
                        ]
                    },
                    options: {
                        legend: {
                            position: 'top',
                            labels: {
                                fontFamily: 'Poppins'
                            }

                        },
                        responsive: true,
                        tooltips: {
                            mode: 'index',
                            intersect: false
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        },
                        scales: {
                            xAxes: [{
                                ticks: {
                                    fontFamily: "Poppins"

                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontFamily: "Poppins"
                                }
                            }]
                        }

                    }
                });
            }


        } catch (error) {
            console.log(error);
        }


        try {

            //doughut chart
            var ctx = document.getElementById("doughutChart");
            if (ctx) {
                ctx.height = 150;
                var myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [45, 25, 20, 10],
                            backgroundColor: [
                                "rgba(0, 123, 255,0.9)",
                                "rgba(0, 123, 255,0.7)",
                                "rgba(0, 123, 255,0.5)",
                                "rgba(0,0,0,0.07)"
                            ],
                            hoverBackgroundColor: [
                                "rgba(0, 123, 255,0.9)",
                                "rgba(0, 123, 255,0.7)",
                                "rgba(0, 123, 255,0.5)",
                                "rgba(0,0,0,0.07)"
                            ]

                        }],
                        labels: [
                            "Green",
                            "Green",
                            "Green",
                            "Green"
                        ]
                    },
                    options: {
                        legend: {
                            position: 'top',
                            labels: {
                                fontFamily: 'Poppins'
                            }

                        },
                        responsive: true
                    }
                });
            }


        } catch (error) {
            console.log(error);
        }


        try {

            //pie chart
            var ctx = document.getElementById("pieChart");
            if (ctx) {
                ctx.height = 200;
                var myChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        datasets: [{
                            data: [45, 25, 20, 10],
                            backgroundColor: [
                                "rgba(0, 123, 255,0.9)",
                                "rgba(0, 123, 255,0.7)",
                                "rgba(0, 123, 255,0.5)",
                                "rgba(0,0,0,0.07)"
                            ],
                            hoverBackgroundColor: [
                                "rgba(0, 123, 255,0.9)",
                                "rgba(0, 123, 255,0.7)",
                                "rgba(0, 123, 255,0.5)",
                                "rgba(0,0,0,0.07)"
                            ]

                        }],
                        labels: [
                            "Green",
                            "Green",
                            "Green"
                        ]
                    },
                    options: {
                        legend: {
                            position: 'top',
                            labels: {
                                fontFamily: 'Poppins'
                            }

                        },
                        responsive: true
                    }
                });
            }


        } catch (error) {
            console.log(error);
        }

        try {

            // polar chart
            var ctx = document.getElementById("polarChart");
            if (ctx) {
                ctx.height = 200;
                var myChart = new Chart(ctx, {
                    type: 'polarArea',
                    data: {
                        datasets: [{
                            data: [15, 18, 9, 6, 19],
                            backgroundColor: [
                                "rgba(0, 123, 255,0.9)",
                                "rgba(0, 123, 255,0.8)",
                                "rgba(0, 123, 255,0.7)",
                                "rgba(0,0,0,0.2)",
                                "rgba(0, 123, 255,0.5)"
                            ]

                        }],
                        labels: [
                            "Green",
                            "Green",
                            "Green",
                            "Green"
                        ]
                    },
                    options: {
                        legend: {
                            position: 'top',
                            labels: {
                                fontFamily: 'Poppins'
                            }

                        },
                        responsive: true
                    }
                });
            }

        } catch (error) {
            console.log(error);
        }

        try {

            // single bar chart
            var ctx = document.getElementById("singelBarChart");
            if (ctx) {
                ctx.height = 150;
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ["Sun", "Mon", "Tu", "Wed", "Th", "Fri", "Sat"],
                        datasets: [
                            {
                                label: "My First dataset",
                                data: [40, 55, 75, 81, 56, 55, 40],
                                borderColor: "rgba(0, 123, 255, 0.9)",
                                borderWidth: "0",
                                backgroundColor: "rgba(0, 123, 255, 0.5)"
                            }
                        ]
                    },
                    options: {
                        legend: {
                            position: 'top',
                            labels: {
                                fontFamily: 'Poppins'
                            }

                        },
                        scales: {
                            xAxes: [{
                                ticks: {
                                    fontFamily: "Poppins"

                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontFamily: "Poppins"
                                }
                            }]
                        }
                    }
                });
            }

        } catch (error) {
            console.log(error);
        }

    })(jQuery);
</script>