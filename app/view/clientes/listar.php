<div class="modal fade" id="add_cliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">AGREGAR CLIENTE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="id_tipo_documento">TIPO DE DOCUMENTO</label>
                        <select id="id_tipo_documento" class="form-control">
                            <option value="0">Seleccione el tipo de documento...</option>
                            <?php
                            foreach($tipo_documento as $l){
                                ?>
                                <option value="<?php echo $l->id_tipodocumento;?>"><?php echo $l->tipodocumento_identidad;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="cliente_numero">Documento de Identidad</label>
                        <input type="tel" class="form-control" id="cliente_numero" onchange="consultar_documento(this.value)" onkeypress="return valida(event)">
                    </div>
                    <div class="col-md-12">
                        <label for="cliente_nombre">Nombre o Razón Social</label>
                        <input class="form-control" id="cliente_nombre">
                    </div>
                    <div class="col-md-12">
                        <label for="cliente_direccion">Domicilio Fiscal</label>
                        <textarea class="form-control" cols="30" rows="2" id="cliente_direccion"></textarea>
                    </div>
                    <div class="col-md-5">
                        <label for="cliente_telefono">Teléfono o Celular</label>
                        <input type="text" class="form-control" id="cliente_telefono" onkeypress="return valida(event)">
                    </div>
                    <div class="col-md-7">
                        <label for="cliente_correo">Correo</label>
                        <input type="email" class="form-control" id="cliente_correo">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button id="btn-agregar" class="btn btn-success" onclick="guardar()"><i class="fa fa-plus"></i> Agregar Cliente</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editar_cliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">EDITAR CLIENTE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="id_tipo_documento_e">TIPO DE DOCUMENTO</label>
                        <select id="id_tipo_documento_e" class="form-control">
                            <option value="0">Seleccione el tipo de documento...</option>
                            <?php
                            foreach($tipo_documento as $l){
                                ?>
                                <option value="<?php echo $l->id_tipodocumento;?>"><?php echo $l->tipodocumento_identidad;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="cliente_numero_e">Documento de Identidad</label>
                        <input type="tel" class="form-control" id="cliente_numero_e" onchange="consultar_documento(this.value)" onkeypress="return valida(event)">
                    </div>
                    <div class="col-md-12">
                        <label for="cliente_nombre_e">Nombre o Razón Social</label>
                        <input class="form-control" id="cliente_nombre_e">
                        <input type="hidden" id="id_cliente_e">
                    </div>
                    <div class="col-md-12">
                        <label for="cliente_direccion_e">Domicilio Fiscal</label>
                        <textarea class="form-control" cols="30" rows="2" id="cliente_direccion_e"></textarea>
                    </div>
                    <div class="col-md-5">
                        <label for="cliente_telefono_e">Teléfono o Celular</label>
                        <input type="text" class="form-control" id="cliente_telefono_e" onkeypress="return valida(event)">
                    </div>
                    <div class="col-md-7">
                        <label for="cliente_correo_e">Correo</label>
                        <input type="email" class="form-control" id="cliente_correo_e">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button id="btn-agregar" class="btn btn-success" onclick="guardar_editar()"><i class="fa fa-pencil"></i> Editar Cliente</button>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-12">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10">
                            <h5 class="font-weight-bold text-center text-primary">LISTA DE CLIENTES REGISTRADOS</h5>
                        </div>
                        <div class="col-lg-2">
                            <button data-toggle="modal" data-target="#add_cliente" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm font-weight-bold" style="width: 100%"><i class="fa fa-plus fa-sm text-white-50"></i> CLIENTE NUEVO</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="dataTable">
                            <thead class="text-capitalize">
                            <tr>
                                <th>ID</th>
                                <th>NOMBRE</th>
                                <th>NÚMERO DE DOCUMENTO</th>
                                <th>CORREO</th>
                                <th>DIRECCIÓN</th>
                                <th>TELÉFONO</th>
                                <th>OPCIONES</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $a = 1;
                            foreach ($clientes as $c){
                                if($c->id_tipodocumento != "4"){
                                    $nombre = $c->cliente_nombre;
                                }else{
                                    $nombre = $c->cliente_razonsocial;
                                }
                                ($c->cliente_estado==0)?$back_s="background:yellow;":$back_s="";
                                ?>
                                <tr id="cliente<?= $c->id_cliente;?>" style="<?= $back_s; ?>">
                                    <td><?= $a;?></td>
                                    <td><?= $nombre;?></td>
                                    <td><?= $c->cliente_numero;?></td>
                                    <td><?= $c->cliente_correo;?></td>
                                    <td><?= $c->cliente_direccion;?></td>
                                    <td><?= $c->cliente_telefono;?></td>
                                    <td>
                                        <?php
                                        if($c->id_cliente != 1){
                                        ?>
                                        <button data-toggle="modal" data-target="#editar_cliente" onclick="set_id_editar_cliente(<?= $c->id_cliente ?>)" class="btn btn-sm btn-primary btne" title="EDITAR"><i class="fa fa-pencil"></i></button>
                                        <?php
                                        $validar = $this->clientes->validar($c->id_cliente);
                                        (empty($validar))?$resultado=true:$resultado=false;
                                        if($resultado){
                                        ?>
                                            <button id="btn-eliminar_cliente<?= $c->id_cliente;?>" class="btn btn-sm btn-danger btne" onclick="preguntar('¿Está seguro que desea Eliminar este Cliente?','eliminarcliente','Si','No',<?= $c->id_cliente;?>)" title="ELIMINAR"><i class="fa fa-times"></i></button>
                                            <?php
                                        }else{
                                            ?>
                                            <button id="btn-eliminar_cliente<?= $c->id_cliente;?>" class="btn btn-sm btn-warning btne" onclick="preguntar('¿Está seguro que desea Deshabilitar este Cliente?','cambiar_estado','Si','No',<?= $c->id_cliente;?>)" title="DESHABILITAR"><i class="fa fa-exclamation"></i></button>
                                            <?php
                                            }
                                        }
                                        ?>
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
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>clientes.js"></script>
<script>
    function consultar_documento(valor){
        var tipo_doc = $('#id_tipo_documento').val();

        if(tipo_doc == "2"){
            if(valor.length==8){
                ObtenerDatosDni(valor);
            }else{
                alert('El numero debe tener 8 digitos');
            }
        }else if(tipo_doc == "4"){
            if (valor.length==11){
                ObtenerDatosRuc(valor);
            }else{
                alert('El numero debe tener 11 digitos');
            }

        }
    }
    function ObtenerDatosDni(valor){
        var numero_dni =  valor;
        var cliente_nombre = 'cliente_nombre';

        $.ajax({
            type: "POST",
            url: urlweb + "api/Clientes/obtener_datos_x_dni",
            data: "numero_dni="+numero_dni,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(cliente_nombre, 'Buscando...', true);
            },
            success:function (r) {
                cambiar_estado_boton(cliente_nombre, "", false);
                $("#cliente_nombre").val(r.result.name+ ' ' + r.result.first_name+ ' ' + r.result.last_name);
            }
        });
    }
    function ObtenerDatosRuc(valor){
        var numero_ruc =  valor;
        var cliente_razonsocial = 'cliente_razonsocial';

        $.ajax({
            type: "POST",
            url: urlweb + "api/Clientes/obtener_datos_x_ruc",
            data: "numero_ruc="+numero_ruc,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(cliente_razonsocial, 'Buscando...', true);
            },
            success:function (r) {
                cambiar_estado_boton(cliente_razonsocial, "", false);
                $("#cliente_razonsocial").val(r.result.razon_social);
            }
        });
    }
    function set_id_editar_cliente(id){
        $.ajax({
            type: "POST",
            url: urlweb + "api/Clientes/cliente_por_id",
            data: "id="+id,
            dataType: 'json',
            success:function (r) {
                $("#id_tipo_documento_e").val(r.result.id_tipodocumento);
                if(r.result.id_tipodocumento==2){$("#cliente_nombre_e").val(r.result.cliente_nombre);}else{$("#cliente_nombre_e").val(r.result.cliente_razonsocial);}
                $("#cliente_numero_e").val(r.result.cliente_numero);
                $("#cliente_direccion_e").val(r.result.cliente_direccion);
                $("#cliente_telefono_e").val(r.result.cliente_telefono);
                $("#cliente_correo_e").val(r.result.cliente_correo);
                $("#id_cliente_e").val(id);
            }
        });
    }
</script>