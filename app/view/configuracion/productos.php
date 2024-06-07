<div class="modal fade" id="modal_agregar_producto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">AGREGAR PRODUCTO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="id_categoria">Categoría</label>
                            <select class="form-control" id="id_categoria">
                                <option value="">Seleccione</option>
                                <?php
                                foreach ($categorias as $cat){
                                    echo "<option value='$cat->id_categoria'>$cat->categoria_nombre</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="codigo">Código</label>
                            <input type="text" class="form-control" id="codigo">
                        </div>
                        <div class="col-md-12">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre">
                        </div>
                        <div class="col-md-12">
                            <label for="descripcion">Descripcion</label>
                            <input type="text" class="form-control" id="descripcion">
                        </div>
                        <div class="col-md-4">
                            <label for="stock">Stock</label>
                            <input type="text" class="form-control" id="stock">
                        </div>
                        <div class="col-md-4">
                            <label for="stock_minimo">Stock mínimo</label>
                            <input type="text" class="form-control" id="stock_minimo">
                        </div>
                        <div class="col-md-4">
                            <label for="id_proveedor">Proveedor</label>
                            <select class="form-control" id="id_proveedor">
                                <option value="">Seleccione</option>
                                <?php
                                foreach ($proveedores as $pro){
                                    echo "<option value='$pro->id_proveedor'>$pro->proveedor_nombre</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="id_medida">Unidad de Medida</label>
                            <select class="form-control" id="id_medida">
                                <option value="">Seleccione</option>
                                <?php
                                foreach ($medidas as $u){
                                    ($u->id_medida==58)?$sele="selected":$sele="";
                                    echo "<option $sele value='$u->id_medida'>$u->medida_nombre</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="precio_compra">Precio de compra</label>
                            <input type="text" class="form-control" id="precio_compra">
                        </div>
                        <div class="col-md-6">
                            <label for="precio_venta">Precio de venta</label>
                            <input type="text" class="form-control" id="precio_venta">
                        </div>
                        <div class="col-md-6">
                            <label for="precio_venta_mayor">Precio al por mayor</label>
                            <input type="text" class="form-control" id="precio_venta_mayor">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-guardar-producto" onclick="guardar_producto()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_editar_producto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">EDITAR PRODUCTO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="id_categoria_e">Categoría</label>
                            <select class="form-control" id="id_categoria_e">
                                <option value="">Seleccione</option>
                                <?php
                                foreach ($categorias as $cat){
                                    echo "<option value='$cat->id_categoria'>$cat->categoria_nombre</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="codigo_e">Código</label>
                            <input type="text" class="form-control" id="codigo_e">
                            <input type="hidden" id="id_producto_e">
                        </div>
                        <div class="col-md-12">
                            <label for="nombre_e">Nombre</label>
                            <input type="text" class="form-control" id="nombre_e">
                        </div>
                        <div class="col-md-12">
                            <label for="descripcion_e">Descripcion</label>
                            <input type="text" class="form-control" id="descripcion_e">
                        </div>
                        <div class="col-md-4">
                            <label for="stock_e">Stock</label>
                            <input type="text" class="form-control" id="stock_e">
                        </div>
                        <div class="col-md-4">
                            <label for="stock_minimo_e">Stock mínimo</label>
                            <input type="text" class="form-control" id="stock_minimo_e">
                        </div>
                        <div class="col-md-4">
                            <label for="id_proveedor_e">Proveedor</label>
                            <select class="form-control" id="id_proveedor_e">
                                <option value="">Seleccione</option>
                                <?php
                                foreach ($proveedores as $pro){
                                    echo "<option value='$pro->id_proveedor'>$pro->proveedor_nombre</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="id_medida_e">Unidad de Medida</label>
                            <select class="form-control" id="id_medida_e">
                                <option value="">Seleccione</option>
                                <?php
                                foreach ($medidas as $u){
                                    ($u->id_medida==58)?$sele="selected":$sele="";
                                    echo "<option $sele value='$u->id_medida'>$u->medida_nombre</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="precio_compra_e">Precio de compra</label>
                            <input type="text" class="form-control" id="precio_compra_e">
                        </div>
                        <div class="col-md-6">
                            <label for="precio_venta_e">Precio de venta</label>
                            <input type="text" class="form-control" id="precio_venta_e">
                        </div>
                        <div class="col-md-6">
                            <label for="precio_venta_mayor_e">Precio al por mayor</label>
                            <input type="text" class="form-control" id="precio_venta_mayor_e">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-editar-producto" onclick="editar_producto()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <h6 class="font-weight-bold text-primary text-uppercase mb-1">GESTIÓN DE PRODUCTOS</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="buscar_nombre">Ingrese nombre</label>
                                    <input type="text" class="form-control" id="buscar_nombre">
                                </div>
                                <div class="col-md-3">
                                    <br><button onclick="buscar_nombre()" class="btn btn-primary" style="width: 100%"><i class="fa fa-search"></i> Buscar</button><br><br>
                                </div>
                                <div class="col-md-3">
                                    <br><button data-target="#modal_agregar_producto" data-toggle="modal" class="btn btn-success" style="width: 100%"><i class="fa fa-plus"></i> Agregar producto</button><br><br>
                                </div>
                            </div>
                            <?php
                            if($datos){
                            ?>
                            <div class="row">
                                <br><br><div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Listado de productos</div>
                                <table id="dataTable" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Categoría</th>
                                        <th>Cod</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Stock</th>
                                        <th>Proveedor</th>
                                        <th>Precio Venta</th>
                                        <th>Precio Compra</th>
                                        <th>Acción</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $aa=1;
                                    foreach ($productos as $h){
                                        $existe = $this->configuracion->existe_producto_eliminar($h->id_producto);
                                        $btn_eliminar="";
                                        if(!$existe){
                                            $btn_eliminar="<button onclick='eliminar($h->id_producto)' class='btn btn-sm btn-danger'><i class='fa fa-times'></i></button>";
                                        }
                                        echo "<tr><td>$aa</td><td>$h->categoria_nombre</td><td>$h->producto_codigo_barra</td><td>$h->producto_nombre</td><td>$h->producto_descripcion</td><td>$h->producto_stock (stock mínimo: $h->producto_stock_minimo)</td><td>$h->proveedor_nombre</td><td>$h->producto_precio_valor ($h->producto_precio_unidad $h->medida_nombre)<br>Al por mayor: $h->producto_precio_valor_xmayor</td><td>$h->producto_precio_compra</td><td><button data-toggle='modal' data-target='#modal_editar_producto' onclick='set_id_editar($h->id_producto)' class='btn btn-sm btn-warning'><i class='fa fa-pencil'></i></button> $btn_eliminar</td></tr>";
                                        $aa++;
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                            }
                            ?>
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
    function guardar_producto() {
        var valor = true;
        var boton = "btn-guardar-producto";
        var id_categoria = $("#id_categoria").val();
        var id_medida = $("#id_medida").val();
        var codigo = $("#codigo").val();
        var nombre = $("#nombre").val();
        var descripcion = $("#descripcion").val();
        var stock = $("#stock").val();
        var stock_minimo = $("#stock_minimo").val();
        var id_proveedor = $("#id_proveedor").val();
        var precio_venta = $("#precio_venta").val();
        var precio_venta_mayor = $("#precio_venta_mayor").val();
        var precio_compra = $("#precio_compra").val();
        valor = validar_campo_vacio('id_categoria', id_categoria, valor);
        valor = validar_campo_vacio('id_medida', id_medida, valor);
        valor = validar_campo_vacio('codigo', codigo, valor);
        valor = validar_campo_vacio('nombre', nombre, valor);
        valor = validar_campo_vacio('descripcion', descripcion, valor);
        valor = validar_campo_vacio('stock', stock, valor);
        valor = validar_campo_vacio('stock_minimo', stock_minimo, valor);
        valor = validar_campo_vacio('id_proveedor', id_proveedor, valor);
        valor = validar_campo_vacio('precio_venta', precio_venta, valor);
        valor = validar_campo_vacio('precio_venta_mayor', precio_venta_mayor, valor);
        valor = validar_campo_vacio('precio_compra', precio_compra, valor);
        if(valor){
            $.ajax({
                type: "POST",
                url: urlweb + "api/Configuracion/guardar_producto",
                data: "id_categoria=" + id_categoria+"&codigo="+codigo+"&nombre="+nombre+"&descripcion="+descripcion+"&stock="+stock+"&id_medida="+id_medida+"&stock_minimo="+stock_minimo+"&id_proveedor="+id_proveedor+"&precio_venta="+precio_venta+"&precio_venta_mayor="+precio_venta_mayor+"&precio_compra="+precio_compra,
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
    function editar_producto() {
        var valor = true;
        var boton = "btn-editar-producto";
        var id_producto = $("#id_producto_e").val();
        var id_categoria = $("#id_categoria_e").val();
        var id_medida = $("#id_medida_e").val();
        var codigo = $("#codigo_e").val();
        var nombre = $("#nombre_e").val();
        var descripcion = $("#descripcion_e").val();
        var stock = $("#stock_e").val();
        var stock_minimo = $("#stock_minimo_e").val();
        var id_proveedor = $("#id_proveedor_e").val();
        var precio_venta = $("#precio_venta_e").val();
        var precio_venta_mayor = $("#precio_venta_mayor_e").val();
        var precio_compra = $("#precio_compra_e").val();
        valor = validar_campo_vacio('id_categoria_e', id_categoria, valor);
        valor = validar_campo_vacio('id_medida_e', id_medida, valor);
        valor = validar_campo_vacio('codigo_e', codigo, valor);
        valor = validar_campo_vacio('nombre_e', nombre, valor);
        valor = validar_campo_vacio('descripcion_e', descripcion, valor);
        valor = validar_campo_vacio('stock_e', stock, valor);
        valor = validar_campo_vacio('stock_minimo_e', stock_minimo, valor);
        valor = validar_campo_vacio('id_proveedor_e', id_proveedor, valor);
        valor = validar_campo_vacio('precio_venta_e', precio_venta, valor);
        valor = validar_campo_vacio('precio_venta_mayor_e', precio_venta_mayor, valor);
        valor = validar_campo_vacio('precio_compra_e', precio_compra, valor);
        if(valor){
            $.ajax({
                type: "POST",
                url: urlweb + "api/Configuracion/guardar_producto",
                data: "id_categoria=" + id_categoria+"&id_producto="+id_producto+"&codigo="+codigo+"&nombre="+nombre+"&descripcion="+descripcion+"&stock="+stock+"&id_medida="+id_medida+"&stock_minimo="+stock_minimo+"&id_proveedor="+id_proveedor+"&precio_venta="+precio_venta+"&precio_venta_mayor="+precio_venta_mayor+"&precio_compra="+precio_compra,
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
    function eliminar(id) {
        $.ajax({
            type: "POST",
            url: urlweb + "api/Configuracion/eliminar_producto",
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
    function set_id_editar(id) {
        $.ajax({
            type: "POST",
            url: urlweb + "api/Configuracion/producto_por_id",
            data: "id=" + id,
            dataType: 'json',
            success:function (r) {
                if(r.result.code==1) {
                    $("#id_producto_e").val(id);
                    $("#id_categoria_e").val(r.result.data.id_categoria);
                    $("#nombre_e").val(r.result.data.producto_nombre);
                    $("#codigo_e").val(r.result.data.producto_codigo_barra);
                    $("#descripcion_e").val(r.result.data.producto_descripcion);
                    $("#stock_e").val(r.result.data.producto_stock);
                    $("#stock_minimo_e").val(r.result.data.producto_stock_minimo);
                    $("#id_proveedor_e").val(r.result.data.id_proveedor);
                    $("#id_medida_e").val(r.result.data.id_medida);
                    $("#precio_compra_e").val(r.result.data.producto_precio_compra);
                    $("#precio_venta_e").val(r.result.data.producto_precio_valor);
                    $("#precio_venta_mayor_e").val(r.result.data.producto_precio_valor_xmayor);
                }
            }
        });
    }
    function buscar_nombre() {
        var buscar = $("#buscar_nombre").val();
        if(buscar==""){
            buscar=1;
        }
        location.href=urlweb+"Configuracion/productos/"+buscar;
    }
</script>