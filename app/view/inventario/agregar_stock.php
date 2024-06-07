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
        <h1>
            Inventario
            <small>Agregar Producto Stock</small>
        </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo _SERVER_;?>Inventario/listarproductos"><i class="fa fa-dashboard"></i> Volver</a></li>
            </ol>
    </section>

    <!-- Main content -->
    <section class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Producto / Agregar Stock</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div>
                        <div class="box-body">
                            <div class="form-group">
                                <label >Nombre Producto</label>
                                <input type="hidden" id="id_producto" name="id_producto" value="<?= $id;?>">
                                <input type="text" class="form-control" id="producto_nombre" placeholder="Ingresar Nombre Producto..." value="<?php echo $producto->producto_nombre;?>" readonly>
                            </div>
                            <div class="form-group">
                                <label >Stock Actual</label>
                                <input type="text" class="form-control" id="producto_stockactual"  value="<?php echo $producto->producto_stock;?>" readonly>
                            </div>
                            <div class="form-group">
                                <label >Stock A Agregar</label>
                                <input type="text" class="form-control" id="producto_stock"  onkeypress="validar_numeros(this.id)" placeholder="Ingresar Stock Producto..." value="0" >
                            </div>
                            <div class="form-group">
                                <label >Proveedor</label>
                                <select class="form-control" id= "id_proveedor" disabled>
                                    <option value="">Seleccionar Proveedor</option>
                                    <?php
                                    foreach($proveedor as $p){
                                        ?>
                                        <option <?php echo ($p->id_proveedor == $producto->id_proveedor) ? 'selected' : '';?> value="<?php echo $p->id_proveedor;?>"><?php echo $p->proveedor_nombre ;?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label >Precio de Compra</label>
                                <input type="text" class="form-control" id="producto_precio_compra" value="<?= $producto->producto_precio_compra;?>" placeholder="Ingresar Precio Unitario..." >
                            </div>
                            <div class="form-group">
                                <label >Precio de Venta</label>
                                <input type="text" class="form-control" id="producto_precio_valor" value="<?= $producto->producto_precio_valor;?>" placeholder="Ingresar Precio Unitario..." >
                            </div>
                            <div class="form-group">
                                <label >Guia de Ingreso</label>
                                <input type="text" class="form-control" id="stocklog_guide" readonly value="<?php echo 'GE-'.$fechahoy.'-'.$correlativo->correlativo_in; ?>" placeholder="Ingresar Guia de Ingreso de Producto..." >
                            </div>
                            <div class="form-group">
                                <label >Descripción</label>
                                <input type="text" class="form-control" value="--" id="stocklog_description" placeholder="Ingresar Descripción..."  >
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button class="btn btn-primary" id="btn-editar-stock" name="btn-editar-stock" onclick="agregarstock()">Agregar Stock</button>
                        </div>
                    </div>
                </div>
                <!-- /.box -->



            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>inventario.js"></script>

