<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 text-center font-weight-bold text-primary" style="color: #6a0c0d !important;">Notas de Crédito/Débito</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-3">
                    <label for="tipo_venta">Tipo de Comprobante</label>
                    <select id="tipo_venta" class="form-control" onchange = "selecttipoventa_(this.value)">
                        <option value= "">Seleccionar...</option>
                        <option value= "07">NOTA DE CREDITO</option>
                        <option value= "08">NOTA DE DEBITO</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="serie">Serie</label>
                    <select name="serie" id="serie" class="form-control" onchange="ConsultarCorrelativo()">
                        <option value="">Seleccionar</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="numero">Numero</label>
                    <input class="form-control" type="text" id="numero" readonly>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="tipo_moneda">Moneda</label><br>
                        <select class="form-control" id="tipo_moneda" name="tipo_moneda">
                            <option value="1">SOLES</option>
                            <option value="2">DOLARES</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" id="credito_debito">
                <div class="col-lg-1"></div>
                <div class="col-lg-2">
                    <label>Documento a modificar</label>
                    <select name="" class="form-control" id="Tipo_documento_modificar" disabled>
                        <option <?= (($venta->venta_tipo == '03')?$selec='selected':$selec=''); ?> value="03">BOLETA</option>
                        <option <?= (($venta->venta_tipo == '01')?$selec='selected':$selec=''); ?> value="01">FACTURA</option>
                    </select>
                </div>
                <div class="col-lg-2" id="serie_nota">
                    <label>Serie</label>
                    <input type="hidden" id="id_venta" value="<?= $venta->id_venta;?>">
                    <input type="hidden" id="total_factura" value="<?= $venta->venta_total;?>">
                    <input class="form-control" type="text" id="serie_modificar" value="<?= $venta->venta_serie;?>" >
                </div>
                <div class="col-lg-2" id="numero_nota">
                    <label>Numero</label>
                    <input class="form-control" type="text" id="numero_modificar" value="<?= $venta->venta_correlativo;?>" >
                </div>
                <div class="col-lg-3" id="nota_descripcion">
                </div>
                <div class="col-lg-1"></div>
            </div>
            <div class="row">
                <div class="col-lg-12" style="text-align: center">
                    <h5><strong>Datos del Cliente</strong></h5>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-3">
                    <label>Tipo de Pago</label>
                    <select class="form-control" id="id_tipo_pago" name="id_tipo_pago">
                        <?php
                        foreach ($tipo_pago as $tp){
                            ?>
                            <option <?php echo ($tp->id_tipo_pago == 3) ? 'selected' : '';?> value="<?php echo $tp->id_tipo_pago;?>"><?php echo $tp->tipo_pago_nombre;?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-lg-4">
                    <label>Tipo Documento</label>
                    <select  class="form-control" name="select_tipodocumento" id="select_tipodocumento">
                        <option value="">Seleccionar...</option>
                        <?php
                        foreach ($tipos_documento as $td){
                            ($td->id_tipodocumento == $venta->id_tipodocumento)?$sele='selected':$sele='';
                            echo "<option value='".$td->id_tipodocumento."' ".$sele.">".$td->tipodocumento_identidad."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-lg-2" style="margin-top: 8px">
                    <br>
                    <button class="btn btn-success" type="button" data-toggle="modal" data-target="#basicModal" style="width: 100%"><i class="fa fa-search"></i> Buscar Cliente</button>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-2">
                    <label for="client_number">DNI ó RUC:</label>
                    <input class="form-control" type="text" id="client_number" value="<?= $venta->cliente_numero?>" onchange="consultar_documento(this.value)">
                </div>
                <div class="col-lg-7">
                    <label for="client_name">Nombre:</label>
                    <input class="form-control" type="text" id="client_name" value="<?= (($venta->id_tipodocumento == 2)? $venta->cliente_nombre : $venta->cliente_razonsocial); ?>" placeholder="Ingrese Nombre...">
                </div>

            </div>
            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-7">
                    <label for="client_address">Direccion:</label>
                    <textarea class="form-control" name="client_address" id="client_address"  rows="2" placeholder="Ingrese Dirección..."><?= $venta->cliente_direccion?></textarea>
                    <!--<input class="form-control" type="text" id="client_address">-->
                </div>
                <div class="col-lg-2">
                    <input type="hidden" id="contenido" name="contenido">
                    <label for="client_address">Telefono:</label>
                    <input class="form-control" type="text" id="client_telefono" placeholder="Ingrese telefono..." value="<?= $venta->cliente_telefono?>">
                </div>
            </div><hr>
            <center><div class="row" style="width: 85%; ">
                <!--<div class="col-lg-1"></div>
                <div class="col-lg-7">
                    <textarea id="notatipo_concepto" class="form-control"><?= $detalle_venta[0]->venta_detalle_nombre_producto; ?></textarea>
                    <input type="hidden" id="notatipo_concepto" name="notatipo_concepto" value="<?= $detalle_venta[0]->venta_detalle_nombre_producto; ?>">
                </div>
                <div class="col-lg-2">
                    <input type="text" class="form-control" id="" value="<?= $venta->venta_total; ?>">
                </div>-->
                <div class="form-group col-md-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <td>#</td>
                                <td>Descripcion</td>
                                <td>Cantidad</td>
                                <td style='width: 150px'>Valor U.</td>
                                <td>IGV</td>
                                <td>Total</td>
                                <td>Accion</td>
                            </tr>
                            </thead>
                            <tbody id="contenido_detalle"></tbody>
                            <tr id="linea_item_new">
                                <td><span id="conteo"></span></td>
                                <td><textarea class='form-control' name='descripcion_nuevo' id='descripcion_nuevo' cols='30' rows='1'></textarea></td>
                                <td><span id="cantidad_nuevo">1</span></td>
                                <td><input onchange="calcular_subtotal(this.value)" type="text" id="precio_nuevo" name="precio_nuevo" class="form-control" onkeyup='validar_numeros_decimales_dos(this.id)' value="0.00"></td>
                                <td><span id="igv_nuevo">0.00</span></td>
                                <td><span id="subtotal_nuevo">0.00</span></td>
                                <td><a style="color:#fff;font-weight: bold;font-size: large" onclick="add()" class="btn btn-success"><i class="fa fa-check"></i></a></td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div></center>
            <div class="row">
                <div class="form-group col-md-10" style="text-align: right">
                    <h5>OP. EXONERADA: <span id="simbolo_moneda"></span> <span id="total_exonerada"></span></h5>
                    <input type="hidden" id="total_exonerada_" name="total_exonerada_" value="">
                    <h5>OP. GRAVADA: <span id="simbolo_moneda_"></span> <span id="total_gravada">0.00</span></h5>
                    <input type="hidden" id="total_gravada_" name="total_gravada_" value="0.00">
                    <h5>IGV: <span id="simbolo_moneda__"></span> <span id="total_igv">0.00</span></h5>
                    <input type="hidden" id="total_igv_" name="total_igv_" value="0.00">
                    <h4><strong>MONTO TOTAL: <span id="simbolo_moneda___"></span> <span id="total"></span></strong></h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4"></div>
                <div class="col-lg-4">
                    <br><button type="button" id="btn_generarventa" class="btn btn-primary" style="width: 100%; padding: 1.2rem; font-size: 1.5rem; width: 100%" onclick="preguntar('¿Está seguro que desea realizar esta Nota?','realizar_venta_nota','Si','No')">
                        <i class="fa fa-money"></i> GENERAR NOTA</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>ventas.js"></script>
<script type="text/javascript">

    let contenido = "";
    let conteo = 1;
    <?php
    foreach ($detalle_venta as $ds){
    ?>
    contenido+="<?= $ds->id_producto_precio; ?>-.-.<?= $ds->venta_detalle_valor_unitario; ?>-.-.<?= $ds->venta_detalle_precio_unitario; ?>-.-.<?= $ds->venta_detalle_nombre_producto; ?>-.-.<?= $ds->venta_detalle_cantidad; ?>-.-.<?= $ds->venta_detalle_valor_total; ?>-.-.<?= $ds->venta_detalle_importe_total; ?>/./.";
    <?php
    }
    ?>

    $(document).ready(function(){
        $("#credito_debito").hide();
        $("#mostrar").hide();
        $("#detalle").hide();
        $("#detalle_").hide();
        $("#busqueda").hide();
        $("#general").hide();
        show_table()
    });
    var productfull = "";
    var unid = "";

    function add(){
        let id_producto_precio = '211'
        let descripcion = document.querySelector('#descripcion_nuevo').value
        //let descripcion = $("#descripcion_nuevo").text()
        let valor_uni = $("#precio_nuevo").val();
        let cantidad = $("#cantidad_nuevo").html();
        let subtotal = $("#subtotal_nuevo").html();


        if(id_producto_precio !="" && valor_uni!= 0 && descripcion!="" && cantidad!="" && subtotal != 0) {
            contenido += id_producto_precio + "-.-."+ valor_uni + "-.-."+ valor_uni + "-.-." + descripcion+ "-.-."+ cantidad + "-.-." + subtotal+ "-.-." + subtotal+ "/./.";
            $("#contenido").val(contenido);
            show_table();
            clean();
        }else{
            respuesta('Todo tiene que estar lleno!','error');
        }
    }
    function clean(){
        let descripcion = document.querySelector('#descripcion_nuevo').value=''
        let valor_uni = $("#precio_nuevo").val(0.00);
        let cantidad = $("#cantidad_nuevo").html(1);
        let subtotal = $("#subtotal_nuevo").html(0.00);
    }

    function show_table() {
        let llenar="";
        let total = 0;
        conteo = 1;
        if (contenido.length>0){
            let filas=contenido.split('/./.');
            if(filas.length>0){
                for(let i=0;i<filas.length - 1;i++){
                    let celdas =filas[i].split('-.-.');
                    llenar += "<tr><td>"+conteo+"</td>"+
                        "<td><textarea onchange='editar_descripcion("+i+",this.value)' class='form-control' name='descripcion_"+i+"' id='descripcion_"+i+"' cols='30' rows='1'>"+celdas[3]+"</textarea></td>"+
                        "<td>"+celdas[4]+"</td>"+
                        "<td><input onchange='editar_valor("+i+",this.value)' onkeyup='validar_numeros_decimales_dos(this.id)' class='form-control' type='text' name='valor_uni_"+i+"' id='valor_uni_"+i+"' value='"+celdas[1]+"'></td>"+
                        "<td>0.00</td>"+
                        "<td>"+celdas[6]+"</td>"+
                        "<td><a data-toggle=\"tooltip\" onclick='delete_detallesi("+i+")' title=\"Eliminar\" type=\"button\" class=\"text-danger\" ><i class=\"fa fa-times ver_detalle\"></i></a></td>"+
                        "</tr>";
                    conteo++;
                    total = total + celdas[6] * 1
                }
            }
        }
        $("#contenido_detalle").html(llenar);
        $("#conteo").html(conteo);
        $("#contenido").val(contenido);
        $("#total_exonerada").html(total.toFixed(2));
        $("#total").html(total.toFixed(2));
    }
    function delete_detallesi(ind) {
        let contenido_artificio ="";
        if (contenido.length>0){
            let filas=contenido.split('/./.');
            if(filas.length>0){
                for(let i=0;i<filas.length - 1;i++){
                    if(i!=ind){
                        let celdas =filas[i].split('-.-.');
                        contenido_artificio += celdas[0] + "-.-."+ celdas[1] + "-.-." + celdas[2] + "-.-." + celdas[3] + "-.-." + celdas[4] + "-.-." + celdas[5] + "-.-." + celdas[6] + "/./.";
                    }else{
                        let celdas =filas[i].split('-.-.');
                    }
                }
            }
        }
        contenido = contenido_artificio;
        show_table();
    }

    function editar_valor(ind, valor){
        let contenido_artificio ="";
        if (contenido.length>0){
            let filas=contenido.split('/./.');
            if(filas.length>0){
                for(let i=0;i<filas.length - 1;i++){
                    if(i==ind){
                        let celdas =filas[i].split('-.-.');
                        let cantidad = celdas[4]
                        let subtotal = valor * cantidad
                        console.log(valor)
                        contenido_artificio += celdas[0] + "-.-."+ valor + "-.-." + valor + "-.-." + celdas[3] + "-.-." + celdas[4] + "-.-." + subtotal.toFixed(2) + "-.-." + subtotal.toFixed(2) + "/./.";
                    }else{
                        let celdas =filas[i].split('-.-.');
                        contenido_artificio += celdas[0] + "-.-."+ celdas[1] + "-.-." + celdas[2] + "-.-." + celdas[3] + "-.-." + celdas[4] + "-.-." + celdas[5] + "-.-." + celdas[6] + "/./.";
                    }
                }
            }
        }
        contenido = contenido_artificio;
        show_table();
    }
    function editar_descripcion(ind, valor){
        let contenido_artificio ="";
        if (contenido.length>0){
            let filas=contenido.split('/./.');
            if(filas.length>0){
                for(let i=0;i<filas.length - 1;i++){
                    if(i==ind){
                        let celdas =filas[i].split('-.-.');
                        console.log(valor)
                        contenido_artificio += celdas[0] + "-.-."+ celdas[1] + "-.-." + celdas[2] + "-.-." + valor + "-.-." + celdas[4] + "-.-." + celdas[5] + "-.-." + celdas[6] + "/./.";
                    }else{
                        let celdas =filas[i].split('-.-.');
                        contenido_artificio += celdas[0] + "-.-."+ celdas[1] + "-.-." + celdas[2] + "-.-." + celdas[3] + "-.-." + celdas[4] + "-.-." + celdas[5] + "-.-." + celdas[6] + "/./.";
                    }
                }
            }
        }
        contenido = contenido_artificio;
        show_table();
    }

    function selecttipoventa_(valor){
        selecttipoventa(valor);
        if (valor == "07" || valor == "08"){
            $('#credito_debito').show();

            if(valor == "07"){
                $('#notaCredito').show();
                $('#notaDebito').hide();
            }else{
                $('#notaCredito').hide();
                $('#notaDebito').show();
            }
            var tipo_comprobante =  valor;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Ventas/tipo_nota_descripcion",
                data: "tipo_comprobante="+tipo_comprobante,
                dataType: 'json',
                success:function (r) {
                    $("#nota_descripcion").html(r);
                }
            });
        } else{
            $('#credito_debito').hide();
        }
    }

    function calcular_subtotal(valor){
        let cantidad = $('#cantidad_nuevo').html() * 1
        let subtotal = cantidad * valor;
        $('#subtotal_nuevo').html(subtotal.toFixed(2));
    }
    function calcular_vuelto(valor){
        var monto_cliente = valor;
        var monto_total = $('#montototal').val();
        var vuelto_sin_ = monto_cliente - monto_total;
        var vuelto_sin = vuelto_sin_.toFixed(2);
        $('#pago_con').html(monto_cliente);
        $('#pago_con_').val(monto_cliente);
        $('#vuelto').html(vuelto_sin);
        $('#vuelto_').val(vuelto_sin);
    }


    function agregarPersona(nombre, numero, direccion, telefono, id_tipodocumento) {
        $("#client_number").val(numero);
        $("#client_name").val(nombre);
        $("#client_address").val(direccion);
        $("#client_telefono").val(telefono);
        $("#select_tipodocumento").val(id_tipodocumento);
        respuesta('El cliente se agregó correctamente!','success');

    }

    function selecttipoventa(valor){
        Consultar_serie();
        var tipo_comprobante =  valor;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/tipo_nota_descripcion",
            data: "tipo_comprobante="+tipo_comprobante,
            dataType: 'json',
            success:function (r) {
                $("#nota_descripcion").html(r);
            }
        });
    }
    function Consultar_serie(){
        var tipo_documento_modificar = $('#Tipo_documento_modificar').val();
        var tipo_venta =  $("#tipo_venta").val();
        var concepto = "LISTAR_SERIE";
        var cadena = "tipo_venta=" + tipo_venta +
            "&concepto=" + concepto +
        "&tipo_documento_modificar=" + tipo_documento_modificar;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/consultar_serie_nota",
            data: cadena,
            dataType: 'json',
            success:function (r) {
                var series = "";
                //var series = "<option value='' selected>Seleccione</option>";
                for (var i=0; i<r.serie.length; i++){
                    series += "<option value='"+r.serie[i].id_serie+"'>"+r.serie[i].serie+"</option>"
                }
                $("#serie").html(series);
                ConsultarCorrelativo();
            }

        });
    }
    function ConsultarCorrelativo(){
        var id_serie =  $("#serie").val();
        var concepto = "LISTAR_NUMERO";
        var cadena = "id_serie=" + id_serie +
            "&concepto=" + concepto;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/consultar_serie",
            data: cadena,
            dataType: 'json',
            success:function (r) {
                $("#numero").val(r.correlativo);
            }

        });
    }

    function consultar_documento(valor){
        var tipo_doc = $('#select_tipodocumento').val();
        if(tipo_doc == "2"){
            ObtenerDatosDni(valor);
        }else if(tipo_doc == "4"){
            ObtenerDatosRuc(valor);
        }
    }
    function ObtenerDatosDni(valor){
        var numero_dni =  valor;

        $.ajax({
            type: "POST",
            url: urlweb + "api/Clientes/obtener_datos_x_dni",
            data: "numero_dni="+numero_dni,
            dataType: 'json',
            success:function (r) {
                $("#client_name").val(r.result.name+ ' ' + r.result.first_name+ ' ' + r.result.last_name);
            }
        });
    }
    function ObtenerDatosRuc(valor){
        var numero_ruc =  valor;

        $.ajax({
            type: "POST",
            url: urlweb + "api/Clientes/obtener_datos_x_ruc",
            data: "numero_ruc="+numero_ruc,
            dataType: 'json',
            success:function (r) {
                $("#client_name").val(r.result.razon_social);
            }
        });
    }

</script>
