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
        <hr><h2 class="concss">
            <a href="<?=_SERVER_; ?>"><i class="fa fa-fire"></i> INICIO</a> >
            <a href="<?=_SERVER_; ?>Clientes"><i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['controlador'];?></a> >
            <i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['accion'];?>
        </h2><hr>
    </section>

    <section class="container-fluid">
        <div class="row">
            <div class="form-group col-md-2"></div>
            <div class="form-group col-md-5" style="text-align: center">
                <label class="col-form-label" for="">TIPO DE DOCUMENTO</label>
                <select id="id_tipo_documento" class="form-control" onchange="escoger_tipodocumento(this.value)">
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
            <div class="form-group col-md-3" style="text-align: center">
                <label class="col-form-label">Documento de Identidad</label>
                <input type="tel" class="form-control" id="cliente_numero" onchange="consultar_documento(this.value)" placeholder="Ingresar DNI ó RUC..." onkeypress="return valida(event)">
            </div>
        </div>
        <br>
        <div class="row" id="div_documento_dni">
            <div class="form-group col-md-6" id="div_nombre">
                <label class="col-form-label">Nombre del Cliente</label>
                <textarea class="form-control" cols="30" rows="2" id="cliente_nombre" placeholder="Ingresar Nombre del Cliente..."></textarea>
            </div>
            <div class="form-group  col-md-6" id="div_razon_social">
                <label class="col-form-label">Razón social</label>
                <textarea class="form-control" cols="30" rows="2" id="cliente_razonsocial" placeholder="Ingresar Nombre del Cliente..."></textarea>
            </div>
            <div class="form-group  col-md-6">
                <label class="col-form-label">Domicilio Fiscal</label>
                <textarea class="form-control" cols="30" rows="2" id="cliente_direccion" placeholder="Ingresar Domicilio Fiscal..."></textarea>
            </div>
            <div class="form-group  col-md-6">
                <label class="col-form-label">Correo</label>
                <input type="email" class="form-control" id="cliente_correo" placeholder="Ingresar correo válido..." >
            </div>
            <div class="form-group  col-md-3">
                <label class="col-form-label">Teléfono o Celular</label>
                <input type="text" class="form-control" id="cliente_telefono" placeholder="Ingresar Teléfono o Celular..." onkeypress="return valida(event)">
            </div>
            <div class="form-group col-md-3">
                <button id="btn-agregar" class="btn btn-success" style="width: 100%; margin-top: 37px" onclick="guardar()"><i class="fa fa-plus"></i> Agregar Cliente</button>
            </div>
        </div>
        <!-- /.row -->
    </section>

    <script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
    <script src="<?php echo _SERVER_ . _JS_;?>clientes.js"></script>

    <script type="text/javascript">

        $(document).ready(function(){
            $("#div_documento_dni").hide();
            $("#div_documento_ruc").hide();
            $("#div_documento").hide();
        });

        function escoger_tipodocumento(valor){
            if(valor === '0'){
                $('#div_documento_dni').hide();
            }else if(valor === '2'){
                $('#div_documento_dni').show();
                $('#div_nombre').show();
                $('#div_razon_social').hide();
            }
            else if (valor === '4') {
                $('#div_documento_dni').show();
                $('#div_nombre').hide();
                $('#div_razon_social').show();
            } else{
                $('#div_documento_dni').hide();
                $('#div_nombre').hide();
                $('#div_razon_social').hide();
            }
        }

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

    </script>