<!--#014826 -->
<div class="modal fade bd-example-modal-lg" id="gestionMenu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 60% !important;">
        <div class="modal-content">
            <div class="modal-header" 
                style=" border-bottom:2px solid #6A0C0D;
                        padding-bottom: 0px;
                        padding-left: 0px;
                        padding-right: 0px;
                        margin-left: 40px;
                        margin-right: 40px;">
                <h5 class="modal-title" id="label_titulo" 
                style=" padding-left: 0px; 
                        padding-right: 0px; 
                        color:#6A0C0D; 
                        margin-left: -7px;
                        margin-bottom: -1px;
                        font-weight:700"></h5> 
                <!-- ESTE ES EL TITULO -->
                <button type="button" class="close" onclick="limpiar()" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-9"></div>
                        <div class="col-lg-3 text-right">
                            <button class="btn btn-primary" onclick="buscar_pinches_reservas()"><i class="fa fa-search"></i> BUSCAR RESERVAS</button>
                        </div>
                        <br><br>
                        <!--Aqui ira la tabla de los datos de las reservas-->
                        <div id="tabla_nueva" class="table-responsive"></div>
                    </div>
                    <div class="row">

                        <div class="col-lg-3 container_grupo">
                            <div class="form-group">
                                <label  style="color:#4D4D4D" for="rack_moneda">Moneda</label>
                                <select style="color:#4D4D4D; font-weight:700" class="form-control" id="rack_moneda">
                                    <?php
                                    foreach ($monedas as $m){
                                        echo "<option value='$m->id_moneda'>$m->moneda</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 container_grupo">
                            <div class="form-group">
                                <label style="color:#4D4D4D" for="rack_cant">Huéspedes</label>
                                <input style="color:#4D4D4D; font-weight:700" onchange="cant_personas()" class="form-control" type="number" id="rack_cant" value="1" min="1" max="99">
                            </div>
                        </div>
                        <div class="col-lg-7 container_grupo">
                            <div class="form-group">
                                <label  style="color:#4D4D4D" for="tipo_doc">Tipo Doc</label>
                                <select style="color:#4D4D4D; font-weight:700" class="form-control" id="tipo_doc" name="tipo_doc">
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach ($tipo_doc as $td) {
                                        ($td->id_tipodocumento==2)?$selected="selected":$selected="";
                                        echo "<option $selected value='$td->id_tipodocumento'>$td->tipodocumento_identidad</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 container_grupo">
                            <div class="form-group">
                                <label style="color:#4D4D4D" for="rack_dni">Nro Doc</label>
                                <input  class="form-control" type="hidden" id="id_habitacion">
                                <input  class="form-control" type="hidden" id="id_reserva">
                                <input style="color:#4D4D4D; font-weight:700" class="form-control" type="text" id="rack_dni" maxlength="12">
                            </div>
                        </div>

                        <div class="col-lg-9 container_grupo">
                            <div class="form-group">
                                <label style="color:#4D4D4D" for="rack_nombre">Nombre</label>
                                <input style="color:#4D4D4D; font-weight:700" class="form-control" type="text" id="rack_nombre" maxlength="300">
                            </div>
                        </div>

                        <div class="col-lg-12 container_grupo">
                            <div id="extras" class="form-group">

                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div  class="form-group">
                                <label style="color:#4D4D4D" for="rack_in">Entrada</label>
                                <input style="color:#4D4D4D; font-weight:700" class="form-control" onchange="validar_fecha_i()" value="<?= date('Y-m-d'); ?>" type="date" id="rack_in">
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label style="color:#4D4D4D" for="rack_out">Salida</label>
                                <input style="color:#4D4D4D; font-weight:700" class="form-control" onchange="validar_fecha()" value="<?= date('Y-m-d'); ?>" min="<?= date('Y-m-d'); ?>" type="date" id="rack_out">
                            </div>
                        </div>

                        <div class="col-lg-3 container_grupo">
                            <div class="form-group">
                                <label style="color:#4D4D4D" for="rack_noches"># Noches</label>
                                <input style="color:#4D4D4D; font-weight:700" class="form-control" type="number" id="rack_noches" value="1" min="1" max="99">
                            </div>
                        </div>

                        <div class="col-lg-3 container_grupo">
                            <div class="form-group">
                                <label style="color:#4D4D4D" for="rack_desayuno"># Desayunos</label>
                                <input style="color:#4D4D4D; font-weight:700" class="form-control" type="number" id="rack_desayuno" value="0" min="0" max="99">
                                <input class="form-control" type="hidden" id="rack_desayuno_costo" value="15">
                            </div>
                        </div>

                        <div class="col-lg-3 container_grupo">
                            <div class="form-group">
                                <label style="color:#4D4D4D" for="rack_tarifa">Tarifa x noche</label>
                                <input style="color:#4D4D4D; font-weight:700" class="form-control" type="text" id="rack_tarifa">
                            </div>
                        </div>

                        <div class="col-lg-5 container_grupo">
                            <div class="form-group">
                                <label style="color:#4D4D4D" for="rack_agencia">Agencia</label>
                                <input style="color:#4D4D4D; font-weight:700" class="form-control" type="text" id="rack_agencia" value="Directo">
                            </div>
                        </div>
                       
                        <div class="col-lg-4 container_grupo">
                            <div class="form-group">
                                <label style="color:#4D4D4D" >Estado</label>
                                <select id="menu_estado" class="form-control" onchange="cambiar_color_estado('menu_estado');desha()">
                                    <option value="1" style="background-color: #014826; color: white;" selected>HABILITADO</option>
                                    <option value="0" style="background-color: #e74a3b; color: white;">DESHABILITADO</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-lg-12">
                            <div class="form-group">
                                <label style="color:#4D4D4D" for="rack_observaciones">Observaciones</label>
                                <textarea style="color:#4D4D4D; font-weight:700" class="form-control" id="rack_observaciones"></textarea>
                            </div>
                        </div>


                        <div class="col-lg-12">
                            <br><button type="button" style="width: 100%; background: #6A0C0D; border-color:#6A0C0D" class="btn btn-primary" id="btn-agregar-rack" onclick="gestionar_rack()"><i class="fa fa-check fa-sm "></i> Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_deshabilitado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="label_titulo2"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row" id="container_grupo">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Observaciones</label>
                                <p style="color: red;" id="deshabilitado_obs"></p>
                                <input type="hidden" id="id_habitacion_habilitar">
                            </div>
                            <button onclick="habilitar()" class="btn btn-success">Volver a habilitar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->

    <div class="card shadow mb-2 text-white" id="vista_block">
        <div class="card-body text-white">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="text-color-base font-weight-bold"> <i class="fa fa-tv"  ></i> Panel de Habitaciones </h4>
                </div>
               <div class="col-lg-12">
                   <div class="d-sm-flex align-items-center mb-4">
                       <div class="col-md-3">
                           <label for="fecha_a_buscar">Fecha</label>
                           <input type="date" class="form-control" value="<?= $fecha_busqueda; ?>" id="fecha_a_buscar">
                       </div>
                       <div class="col-md-2">
                           <label for="tipo_hab">Tipo de Habitación</label>
                           <select class="form-control" id="tipo_hab">
                               <option <?= ($tip == 0)?"selected":""; ?> value="0">TODOS</option>
                               <?php
                               foreach ($tipos_habitacion as $th){
                                   ($tip == $th->id_habitacion_tipo) ? $sle="selected" : $sle="";
                                   echo "<option $sle value='$th->id_habitacion_tipo'>$th->habitacion_tipo_nombre</option>";
                               }
                               ?>
                           </select>
                       </div>
                       <div class="col-md-2">
                           <label for="estado_hab">Estado</label>
                           <select class="form-control" id="estado_hab">
                               <option <?= ($esta == 0)?"selected":""; ?> value="0">TODOS</option>
                               <option <?= ($esta == 1)?"selected":""; ?> value="1">LIBRES</option>
                               <option <?= ($esta == 2)?"selected":""; ?> value="2">OCUPADAS</option>
                               <option <?= ($esta == 3)?"selected":""; ?> value="3">INHABILITADAS</option>
                           </select>
                       </div>
                       <div class="col-md-3">
                           <br><button style="width:100%" class="btn btn-primary" onclick="cambiar_fecha()"><i class="fa fa-search"></i>Buscar</button>
                       </div>
                       <!--<div class="col-md-2">
                           <br><button class="btn btn-secondary" data-toggle="modal" data-target="#multiple" style="background: #6a0c0d"><i class="fa fa-pencil"></i> Registro múltiple</button>
                       </div>
                       <div class="col-md-2">
                           <br><button class="btn btn-secondary" data-toggle="modal" data-target="#multiple" style="background: #6a0c0d"><i class="fa fa-pencil"></i> Registro múltiple</button>
                       </div>-->
                   </div>
               </div>
            </div>
            <div class="row">
                <div class="col-md-11"></div>
                <div class="col-md-1">
                    <button class="btn btn-primary" onclick="cambiar_vista(1)" style="background: #6a0c0d; border: none"><i class="fa fa-list-ol"></i></button><br><br>
                </div>
            </div>
            <div class="row">
                <!--<div class="col-lg-3">
                    <div class="card shadow border-left-success1 text-color-base mt-2">
                        <div class="card-body" style="height: 150px">
                            <div class="row">
                                <div class="col-lg-6 text-left">
                                    <h4  class="font-weight-bold text-success">101</h4>
                                </div>
                                <div class="col-lg-6 text-right"  >
                                    <div class="rounded-circle border-0" style="width: 25px; height: 25px; background: #E5E8E8; float: right; text-align: center">
                                        <i class=" fa fa-bell text-success"></i>
                                    </div>

                                </div>

                                <div class="col-lg-12 text-success">
                                    <label for="">Matrimonial</label>
                                </div>
                                <div class="col-lg-12 text-success">
                                    12345678909876543212
                                </div>
                                <div class="col-lg-12 text-success">
                                    Mensaje
                                </div>
                            </div>
                        </div>
                    </div>

                </div>-->
                <?php
                foreach ($habitaciones as $h){
                    $desha_des="";
                    $text=$h->habitacion_tipo_nombre;
                    $tipo="";
                    $ocupado = $this->hotel->buscar_estado_habitacion($fecha_busqueda,$h->id_habitacion);
                    $ocupado2 = $this->hotel->buscar_estado_rack($fecha_busqueda,$h->id_habitacion);
                    (isset($ocupado->id_habitacion))?$estado =$ocupado->habitacion_estado_estado:$estado=1;
                    $todo_a = "";$si=true;$check_out="";
                    if($estado == 1 ){
                        $colorcito="border-left-success1";
                        $colorcito_="text-success";
                        $target="gestionMenu";
                    }elseif ($estado == 2){
                        if($ocupado2->rack_checkout != NULL && date('Y-m-d',strtotime($ocupado2->rack_checkout)) != $fecha_busqueda && date('Y-m-d',strtotime($ocupado2->rack_checkout)) < $fecha_busqueda ){

                            $colorcito="border-left-success1";
                            $colorcito_="text-success";
                            $target="gestionMenu";
                        }else{
                            $data_rack = $this->hotel->listar_rack_por_id_habitacion($h->id_habitacion,$fecha_busqueda);
                            if($data_rack->rack_out==$fecha_busqueda){
                                $check_out="<p style=\"font-size: 8pt;\">Checkout el $fecha_busqueda</p>";
                            }
                            $colorcito="border-left-danger1";
                            $colorcito_="text-danger1";
                            $target="modal_deshabilitado";
                            $text=$data_rack->cliente_nombre;
                            $tipo=$h->habitacion_tipo_nombre;
                            $si=false;
                        }

                    }else{
                        $colorcito="bg-gradient-warning";
                        $colorcito_="orange";
                        $target="modal_deshabilitado";
                        $desha=$this->hotel->listar_deshabilitado($h->id_habitacion,date("Y-m-d"));
                        (isset($desha->deshabilitado_descripcion))?$desha_des=$desha->deshabilitado_descripcion:$desha_des="";
                    }
                    ?>
                    <div class="col-lg-3">
                        <?php
                        if($si){
                            ?>
                        <a data-toggle="modal" data-target="#<?=$target?>" style="cursor: pointer" onclick="getid(<?= $h->id_habitacion; ?>,'<?= $h->habitacion_nro ?>','<?= $h->habitacion_tipo_nombre; ?>','<?= $h->habitacion_tipo_soles; ?>');desha2('<?= $desha_des; ?>',<?= $h->id_habitacion; ?>);cerralmodal()">
                            <?php
                        }else{
                            ?>
                                <a  style="text-decoration: none;" href="<?= _SERVER_ ?>Hotel/detalle_habitacion/<?= $h->id_habitacion ?>/<?= $fecha_busqueda; ?>">
                                <?php
                            }
                        ?>
                        <!--Nuevo Diseño-->
                                        <div class="card shadow <?= $colorcito; ?> text-color-base mt-2">
                                            <div class="card-body" style="height: 150px">
                                                <div class="row">
                                                    <div class="col-lg-6 text-left">
                                                        <h4  class="font-weight-bold <?= $colorcito_ ?>"><?= $h->habitacion_nro; ?></h4>
                                                    </div>
                                                    <div class="col-lg-6 text-right"  >
                                                        <button onclick="ver_historial(<?= $h->id_habitacion; ?>)" class="rounded-circle border-0" style="width: 25px; height: 25px; background: #E5E8E8; float: right; text-align: center">
                                                            <i class=" fa fa-bell <?= $colorcito_ ?>"></i>
                                                        </button>

                                                    </div>

                                                    <div class="col-lg-12 <?= $colorcito_ ?>">
                                                        <label for=""><?= $tipo ?></label>
                                                    </div>
                                                    <div class="col-lg-12 <?= $colorcito_ ?>">
                                                        <?= substr($text, 0, 25).'...' ; ?>
                                                    </div>
                                                    <div class="col-lg-12 <?= $colorcito_ ?>">
                                                        <?= $check_out; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                        <!-- Approach -->
                        <!--<div class="card shadow mb-2 <?/*= $colorcito; */?> text-white" style="border: 1px solid <?/*= $colorcito_; */?>">
                            <div class="card-body text-white" style="text-align: center; border: 4px solid white;">
                                <span style="font-size: 8pt;"><?/*= $tipo */?></span>
                                <h2 style="padding-top: 5px;color: white;font-family: FontAwesome;font-size: 12pt;"><span style="border: 1px solid white;padding: 2px;font-weight: bold"><?/*= $h->habitacion_nro; */?></span></h2>
                                <h6 class="m-0 text-center text-primary" style="color: white !important;font-size: 10pt;"><?/*= $text; */?></h6>
                                <button style="margin-top:5px;float: center;border: 1px solid black;" class="btn btn-sm btn-secondary <?/*= $colorcito*/?>" onclick="ver_historial(<?/*= $h->id_habitacion; */?>)"><i class="fa fa-bell"></i></button>
                                <?/*= $check_out; */?>
                            </div>
                        </div>-->
                        </a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="card shadow mb-2" id="vista_table" style="display: none">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11"></div>
                <div class="col-md-1">
                    <button class="btn btn-primary" onclick="cambiar_vista(0)" style="background: #6a0c0d; border: none"><i class="fa fa-th"></i></button><br><br>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                <table class="table table-bordered" id="dataTableh" style="width: 100%;color: black">
                    <thead style="background: lightgrey;font-weight: bold">
                    <tr>
                        <td>Hab</td>
                        <td>Tipo</td>
                        <td>Nombre</td>
                        <td>In/Out</td>
                        <td>Obs</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($habitaciones as $h){
                        $desha_des="";
                        $ocupado = $this->hotel->buscar_estado_habitacion($fecha_busqueda,$h->id_habitacion);
                        (isset($ocupado->id_habitacion))?$estado =$ocupado->habitacion_estado_estado:$estado=1;
                        $sii=0;
                        if($estado == 1){
                            $colorcito="";
                            $nombre="";
                            $rate="";
                            $in="";
                            $out="";
                            $out_f="";
                            $agencia="";
                            $obs="";
                        }elseif ($estado == 2){
                            $data_rack = $this->hotel->listar_rack_por_id_habitacion($h->id_habitacion,$fecha_busqueda);
                            $colorcito="background:#e38279;color:white;cursor:pointer;";
                            $nombre=$data_rack->cliente_nombre;
                            $rate=$data_rack->rack_tarifa;
                            $in=$this->validar->obtener_nombre_fecha($data_rack->rack_in,"Date","Date","es");
                            $out=$this->validar->obtener_nombre_fecha($data_rack->rack_out,"Date","Date","es");
                            $out_f= date('Y-m-d', strtotime($data_rack->rack_out));
                            $agencia=$data_rack->rack_agencia;
                            $obs="";
                            $sii=1;
                        }else{
                            $colorcito="background: yellow;";
                            $desha=$this->hotel->listar_deshabilitado($h->id_habitacion,date("Y-m-d"));
                            (isset($desha->deshabilitado_descripcion))?$desha_des=$desha->deshabilitado_descripcion:$desha_des="";
                            $nombre=$desha_des;
                            $rate="";
                            $in="";
                            $out="";
                            $out_f="";
                            $agencia="";
                            $obs="";
                        }
                        $fechaaaa=date('Y-m-d');
                        ($fechaaaa==$fechaaaa)?$styy="style='background: yellow;'":$styy ="";?>
                        <tr style="<?= $colorcito; ?>;" onclick="redir(<?= $h->id_habitacion ?>,<?= $sii; ?>)">
                            <td style="text-align: center"><?= $h->habitacion_nro ?></td>
                            <td><?= $h->habitacion_tipo_nombre ?></td>
                            <td><?= $nombre ?></td>
                            <td <?= $styy; ?>><?= ($in!=="")?$in." / ".$out:""; ?></td>
                            <td><?= $obs ?></td>
                        </tr>
                        <?php
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
<style>
    .span_hab{
        background:#6A0C0D;
        color: white;
        
        border-radius: 6px 0px 0px 6px;
        padding: 3px 20px 3px 20px;
    }
    .border-left-success1{
        border-left: 0.9rem solid #1cc88a!important;
    }
    .border-left-danger1{
        border-left: 0.9rem solid #CB4335 !important;
    }
    .text-danger1{
        color: #CB4335 !important;
    }

</style>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script>

    function cerralmodal(){
        $('#gestionMenu').modal({
            backdrop: 'static'
        });
    }

    function cambiar_vista(ind) {
        if(ind==0){
            $("#vista_table").hide();
            $("#vista_block").show();
        }else{
            $("#vista_table").show();
            $("#vista_block").hide();
        }
    }
    function getid(id,hab,tipo,tarifa) {
        $("#id_habitacion").val(id);
        $("#label_titulo").html("<span class='span_hab'>"+hab + "</span>  "+tipo);
        $("#label_titulo2").html(hab + "  "+tipo);
        $("#rack_tarifa").val(tarifa);
        $("#gestionMenu").on('shown.bs.modal', function() {
            $("#rack_dni").focus();
        });
    }
    function desha() {
        if($("#menu_estado").val()==0){
            $(".container_grupo").hide();
        }else{
            $(".container_grupo").show();
        }
    }
    function gestionar_rack(){
        var valor = true;
        var boton = "btn-agregar-rack";
        var id_habitacion = $('#id_habitacion').val();
        var id_reserva = $('#id_reserva').val();
        var rack_nombre = $('#rack_nombre').val();
        var tipo_doc = $('#tipo_doc').val();
        var rack_dni = $('#rack_dni').val();
        var rack_in = $('#rack_in').val();
        var rack_out = $('#rack_out').val();
        var rack_noches = $('#rack_noches').val();
        var rack_tarifa = $('#rack_tarifa').val();
        var rack_moneda = $('#rack_moneda').val();
        var rack_agencia = $('#rack_agencia').val();
        var rack_desayuno = $('#rack_desayuno').val();
        var rack_desayuno_costo = $('#rack_desayuno_costo').val();
        var rack_observaciones = $('#rack_observaciones').val();
        var menu_estado = $('#menu_estado').val();
        if(menu_estado==0){
            valor = validar_campo_vacio('rack_in', rack_in, valor);
            valor = validar_campo_vacio('rack_out', rack_out, valor);
            valor = validar_campo_vacio('rack_observaciones', rack_observaciones, valor);
        }else{
            valor = validar_campo_vacio('tipo_doc', tipo_doc, valor);
            valor = validar_campo_vacio('rack_in', rack_in, valor);
            valor = validar_campo_vacio('rack_out', rack_out, valor);
            valor = validar_campo_vacio('rack_noches', rack_noches, valor);
            valor = validar_campo_vacio('rack_tarifa', rack_tarifa, valor);
            valor = validar_campo_vacio('rack_agencia', rack_agencia, valor);
            valor = validar_campo_vacio('rack_desayuno', rack_desayuno, valor);
            if(rack_nombre=="" || rack_dni==""){
                valor = validar_campo_vacio('rack_nombre', rack_nombre, valor);
                valor = validar_campo_vacio('rack_dni', rack_dni, valor);
            }
            if(rack_nombre.includes("&")){
                valor=false;
                respuesta("No usar el caracter & en el nombre del cliente.","error");
            }
            if(rack_in>=rack_out){
                valor=false;
                respuesta("Ingrese una fecha valida","error");
            }
        }
        var cant = $("#rack_cant").val();
        var resp = "";
        if(cant>1){
            var j = 1;
            for (var i = 1;i<cant;i++){
                var cont = j+i;
                var person = $("#extra_"+cont).val();
                resp+=person+"//--";
            }
        }
        if(valor){
            //Cadena donde enviaremos los parametros por POST
            var cadena = "id_habitacion=" + id_habitacion +
                "&id_reserva=" + id_reserva +
                "&rack_nombre=" + rack_nombre +
                "&tipo_doc=" + tipo_doc +
                "&rack_dni=" + rack_dni +
                "&rack_in=" + rack_in +
                "&rack_out=" + rack_out +
                "&extras=" + resp +
                "&rack_noches=" + rack_noches +
                "&rack_tarifa=" + rack_tarifa +
                "&rack_moneda=" + rack_moneda +
                "&rack_agencia=" + rack_agencia +
                "&rack_desayuno=" + rack_desayuno +
                "&rack_desayuno_costo=" + rack_desayuno_costo +
                "&rack_observaciones=" + rack_observaciones +
                "&menu_estado=" + menu_estado;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Hotel/guardar_rack",
                data: cadena,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, 'Guardando...', true);
                },
                success:function (r) {
                    //cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                    switch (r.result.code) {
                        case 1:
                            location.reload();
                            break;
                        case 2:
                            respuesta('Error al guardar', 'error');
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        }
    }
    function limpiar() {
        $('#id_habitacion').val("");
        $('#rack_nombre').val("");
        $('#rack_in').val("<?= date("Y-m-d"); ?>");
        $('#rack_out').val("<?= date("Y-m-d"); ?>");
        $('#rack_noches').val("1");
        $('#rack_tarifa').val("");
        $('#rack_dni').val("");
        $('#rack_agencia').val("DIRECTO");
        $('#rack_desayuno').val("0");
        $('#rack_observaciones').val("");
        $('#menu_estado').val(1);
        $("#menu_estado option[value='1']").attr("selected");
        cambiar_color_estado("menu_estado");
        $(".container_grupo").show();
    }
    function desha2(des,id) {
        $("#deshabilitado_obs").html(des);
        $("#id_habitacion_habilitar").val(id);
    }
    function cambiar_fecha() {
        var fecha = $("#fecha_a_buscar").val();
        var tipo = $("#tipo_hab").val();
        var est = $("#estado_hab").val();
        var criterio = fecha+"99932"+tipo+"99932"+est;
        location.href=urlweb+"Hotel/inicio/"+criterio;
    }
    function ver_historial(id) {
        window.open(urlweb+"Hotel/historial_habitacion/"+ id + "/<?= date('Y-m-d'); ?>al<?= date('Y-m-d') ?>","_blank");
    }
    function cant_personas() {
        var cant = $("#rack_cant").val();
        var resp = "";
        if(cant>1){
            var j = 1;
            for (var i = 1;i<cant;i++){
                var cont = j+i;
                resp+="<label>Persona "+cont+" </label><input type='text' id='extra_"+cont+"' class='form-control'>";
            }
        }
        $("#extras").html(resp);
    }
    function mostrar(id) {
        $("#habs_"+id).show();
    }
    function habilitar(){
        var id_habitacion = $('#id_habitacion_habilitar').val();
        var fecha = "<?= $fecha_busqueda ?>";
        var cadena = "id_habitacion=" + id_habitacion +
            "&fecha=" + fecha;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Hotel/habilitar_habitacion",
            data: cadena,
            dataType: 'json',
            success:function (r) {
                switch (r.result.code) {
                    case 1:
                        respuesta('Guardado', 'success');
                        location.reload();
                        break;
                    case 2:
                        respuesta('Error al guardar', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
    function validar_fecha_i() {
        var f_i = $("#rack_in").val();
        $("#rack_out").val(f_i);
        $("#rack_out").attr("min",f_i);
    }
    function validar_fecha() {
        var f_i = new Date($("#rack_in").val());
        var f_f = new Date($("#rack_out").val());
        if(f_f<f_i){
            alert("La fecha de salida debe ser mayor a la fecha de entrada");
            $("#rack_out").val($("#rack_in").val());
        }else{
            var difference= Math.abs(f_f-f_i);
            var resta = difference/(1000 * 3600 * 24)
            $("#rack_noches").val(resta);
        }
    }

    $(function(){
        $('#rack_dni').on('blur', function(){
            var dni = $('#rack_dni').val();
            var tipo = $('#tipo_doc').val();
            if(tipo==2){
                if(dni.length==8){
                    respuesta("Buscando en base de datos","warning");
                    var entrar_api=true;
                    $.ajax({
                        type: "POST",
                        url: urlweb + "api/Hotel/buscar_cliente",
                        data: "ruc="+dni,
                        dataType: 'json',
                        success:function (r) {
                            if (r.result.code == 1) {
                                entrar_api=false;
                                $('#rack_nombre').val(r.result.razon_social);
                                respuesta("Datos encontrados","success");
                            }else{
                                if(entrar_api){
                                    var formData = new FormData();
                                    formData.append("token", "uTZu2aTvMPpqWFuzKATPRWNujUUe7Re1scFlRsTy9Q15k1sjdJVAc9WGy57m");
                                    formData.append("dni", dni);
                                    var request = new XMLHttpRequest();
                                    request.open("POST", "https://api.migo.pe/api/v1/dni");
                                    request.setRequestHeader("Accept", "application/json");
                                    request.send(formData);
                                    respuesta("Buscando en SUNAT","warning");
                                    request.onload = function() {
                                        var data = JSON.parse(this.response);
                                        if(data.success){
                                            respuesta("Datos encontrados","success");
                                            $('#rack_nombre').val(data.nombre);
                                        }else{
                                            respuesta(data.message,"error");
                                        }
                                    };
                                }
                            }
                        }
                    });
                }else{
                    if(dni.length>1){
                        respuesta("El dni debe contener 8 dígitos.","error");
                    }
                }
            }
        });
    });
    $(document).ready(function() {
    $('#dataTableh').DataTable({
        searching: true,
        "lengthMenu": [[80,10,25,50], ["80","10","25","50"]],
        "language": {
            sEmptyTable: "No existen datos en esta tabla",
            sInfo: "Mostrando _START_ de _END_ de _TOTAL_ Entradas",
            sInfoEmpty: "0 de 0 de 0 Entradas",
            sInfoFiltered: "(Filtrado de un total de _MAX_ resultados)",
            sInfoPostFix: "",
            sInfoThousands: ".",
            sLengthMenu: "Mostrar _MENU_ Resultados",
            sLoadingRecords: "Cargando resultados...",
            sProcessing: "Espere por favor..",
            sSearch: "Buscar:",
            sZeroRecords: "No se han encontrado resultados.",
            oPaginate: {
                sFirst: "Primero",
                sPrevious: "Anterior",
                sNext: "Siguiente",
                sLast: "Último"
            },
            oAria: {
                sSortAscending: ":Habilitar para ordenar de forma ascendente",
                sSortDescending: ":Habilitar para ordenar de forma descendente"
            }
        }
    });
    $('#dataTableh').DataTable();
    });
    function redir(id,ind) {
        if(ind==1){
            window.open("<?= _SERVER_ ?>Hotel/detalle_habitacion/"+id+"/<?= $fecha_busqueda ?>","_blank");
        }
    }
    //FUNCION NUEVA PARA BUSCAR LAS RESERVAS
    function buscar_pinches_reservas(){
        hab_tabla()
        let valor = 1;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Reserva/ver_reservas",
            data: "valor=" + valor,
            dataType: 'json',
            success:function (r) {
                console.log(r)
                $("#tabla_nueva").html(r.tabla_nuevo);
            }
        });
    }

    function ocultar_tabla(){
        var tabla = document.getElementById("tabla_nueva");
        tabla.style.display = "none"; // Oculta la tabla estableciendo el estilo "display" en "none"
    }

    function hab_tabla(){
        var tabla = document.getElementById("tabla_nueva");
        tabla.style.display = "block"; // Oculta la tabla estableciendo el estilo "display" en "none"
    }
    function bucarrrrr(){
        var dni = $('#rack_dni').val();
        var tipo = $('#tipo_doc').val();
        if(tipo==2){
            if(dni.length==8){
                respuesta("Buscando en base de datos","warning");
                var entrar_api=true;
                $.ajax({
                    type: "POST",
                    url: urlweb + "api/Hotel/buscar_cliente",
                    data: "ruc="+dni,
                    dataType: 'json',
                    success:function (r) {
                        if (r.result.code == 1) {
                            entrar_api=false;
                            $('#rack_nombre').val(r.result.razon_social);
                            respuesta("Datos encontrados","success");
                        }else{
                            if(entrar_api){
                                var formData = new FormData();
                                formData.append("token", "uTZu2aTvMPpqWFuzKATPRWNujUUe7Re1scFlRsTy9Q15k1sjdJVAc9WGy57m");
                                formData.append("dni", dni);
                                var request = new XMLHttpRequest();
                                request.open("POST", "https://api.migo.pe/api/v1/dni");
                                request.setRequestHeader("Accept", "application/json");
                                request.send(formData);
                                respuesta("Buscando en SUNAT","warning");
                                request.onload = function() {
                                    var data = JSON.parse(this.response);
                                    if(data.success){
                                        respuesta("Datos encontrados","success");
                                        $('#rack_nombre').val(data.nombre);
                                    }else{
                                        respuesta(data.message,"error");
                                    }
                                };
                            }
                        }
                    }
                });
            }else{
                if(dni.length>1){
                    respuesta("El dni debe contener 8 dígitos.","error");
                }
            }
        }
    }

    function llenar_reserva(id,dni,tarifa,reserva_in,reserva_out,agencia){
        $('#id_reserva').val(id);
        $('#rack_dni').val(dni);
        $('#rack_tarifa').val(tarifa);
        $('#rack_in').html(reserva_in);
        $('#rack_out').val(reserva_out);
        $('#rack_agencia').val(agencia);
        bucarrrrr()
        validar_fecha()
        ocultar_tabla()
    }

    // var modal = document.getElementById('gestionMenu');
    // //var closeButton = document.getElementsByClassName('close')[0];
    //
    // // Función para limpiar los campos de entrada
    // function limpiarCampos() {
    //     var campos = modal.querySelectorAll('input[type="text"]');
    //     campos.forEach(function(campo) {
    //         campo.value = '';
    //     });
    // }
    // // Evento de clic fuera del modal para cerrarlo
    // window.onclick = function(event) {
    //     if (event.target == modal) {
    //         modal.style.display = 'none'; // Oculta el modal
    //         limpiarCampos(); // Limpia los campos de entrada
    //         setTimeout(function() {
    //             modal.style.display = 'block'; // Vuelve a habilitar el modal
    //         }, 100); // Pequeña espera para asegurar que se haya ocultado antes de mostrarlo
    //     }
    // }


</script>

