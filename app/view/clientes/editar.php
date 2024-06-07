<?php
/**
 * Created by PhpStorm.
 * User: CesarJose39
 * Date: 02/11/2018
 * Time: 0:36
 */
?>

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="h3 mb-0 text-gray-800"><?= $_SESSION['controlador'] . ' / ' . $_SESSION['accion'];?></h1>
    </section>

    <br>
    <section class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">

                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div>
                        <div class="box-body">
                            <div class="form-group">
                                <label class="col-form-label">Tipo de Documento</label>
                                <input type="hidden" id="id_cliente" name="id_cliente" value="<?= $id;?>">
                                <select id="id_tipo_documento" class="form-control" onchange="escoger_tipodocumento_editar(this.value)">
                                    <option value="0">Seleccione el tipo de documento...</option>
                                    <?php
                                    foreach($tipo_documento as $l){
                                        ?>
                                        <option <?php echo ($l->id_tipodocumento == $clientes->id_tipodocumento) ? 'selected' : '';?> value="<?php echo $l->id_tipodocumento;?>"><?php echo $l->tipodocumento_identidad;?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div id="div_documento_dni">
                                <div class="form-group">
                                    <label class="col-form-label">Documento de Identidad</label>
                                    <input type="tel" class="form-control" id="cliente_numero" placeholder="Ingresar Número..." value="<?= $clientes->cliente_numero; ?>" pattern="[0-9]{8}" onkeypress="return valida(event)">
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Nombre del Cliente</label>
                                    <input type="text" class="form-control" id="cliente_nombre" value="<?= $clientes->cliente_nombre;?>" placeholder="Ingresar Nombre del Cliente...">
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Razón social</label>
                                    <input type="text" class="form-control" id="cliente_razonsocial" value="<?= $clientes->cliente_razonsocial;?>" placeholder="Ingresar Nombre del Cliente...">
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Domicilio Fiscal</label>
                                    <textarea type="text" class="form-control" id="cliente_direccion" placeholder="Ingresar Dirección..."><?= $clientes->cliente_direccion;?></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Teléfono o Celular</label>
                                    <input type="text" class="form-control" id="cliente_telefono" placeholder="Ingresar Teléfono o Celular..." value="<?= $clientes->cliente_telefono;?>" onkeypress="return valida(event)">
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Correo</label>
                                    <input type="email" class="form-control" id="cliente_correo" value="<?= $clientes->cliente_correo;?>" placeholder="Ingresar correo válido..." >
                                </div>
                                <div class="form-group" style="text-align: center">
                                    <button id="btn-agregar" class="btn btn-success" onclick="guardar_editar()"><i class="fa fa-check"></i> Guardar Cambios</button>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
        <!-- /.row -->
    </section>
    <script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
    <script src="<?php echo _SERVER_ . _JS_;?>clientes.js"></script>

    <script type="text/javascript">

        $(document).ready(function(){
            var valor = $('#id_tipo_documento').val();
            escoger_tipodocumento_editar(valor);
        });

        function escoger_tipodocumento_editar(valor){
            if(valor === '0'){
                $('#div_documento_dni').hide();
                $('#div_documento_ruc').hide();
            }else if(valor === '2'){
                $('#div_documento_dni').show();
                $('#div_documento_ruc').hide();
            }
            else if (valor === '4') {
                $('#div_documento_dni').hide();
                $('#div_documento_ruc').show();
            } else{
                $('#div_documento_dni').hide();
                $('#div_documento_ruc').hide();
            }
        }
    </script>