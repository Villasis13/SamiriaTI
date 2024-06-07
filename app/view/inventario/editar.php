<?php
/**
 * Created by PhpStorm
 * User: LuisSalazar
 * Date: 10/12/2020
 * Time: 12:12 a. m.
 */
?>

<div class="container-fluid">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Inventario / <small>Editar Productos</small>
        </h1>
    </section>
    <div class="row">
        <div class="col-lg-10"></div>
        <div class="col-lg-2">
            <a type="button" href="<?php echo _SERVER_;?>Inventario/listarproductos"><i class="fa fa-retweet"></i>Volver</a>
        </div>
    </div>

    <section class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->

                <div class="box-body">
                    <div class="form-group">
                        <input type="hidden" id="id_producto" name="id_producto" value="<?= $id;?>">
                        <label >Nombre Producto</label>
                        <input type="text" class="form-control" id="producto_nombre" placeholder="Ingresar Nombre Producto..." value="<?php echo $producto->producto_nombre;?>">
                    </div>
                    <div class="form-group">
                        <label >Proveedor del Producto</label>
                        <select class="form-control" id= "id_proveedor">
                            <option value="">Seleccionar Proveedor</option>
                            <?php
                            foreach($proveedor as $p){
                                ?>
                                <option <?php echo ($p->id_proveedor == $producto->id_proveedor) ? 'selected' : '';?> value="<?php echo $p->id_proveedor;?>"><?php echo $p->proveedor_nombre;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label >Código de Barra</label>
                        <input type="text" class="form-control" id="producto_codigo_barra" placeholder="Ingresar Código de Barra..." value="<?php echo $producto->producto_codigo_barra;?>">
                    </div>
                    <div class="form-group">
                        <label >Categoría Producto</label>
                        <select class="form-control" id="id_categoria">
                            <option value="">Seleccione Una Categoría...</option>
                            <?php
                            foreach ($categoria as $ca){
                                ?>
                                <option <?php echo ($ca->id_categoria == $producto->id_categoria) ? 'selected' : '';?> value="<?php echo $ca->id_categoria;?>"><?php echo $ca->categoria_nombre;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group" style="display: none;">
                        <label >Descripcion Producto</label>
                        <input type="text" class="form-control" id="producto_descripcion" placeholder="Ingresar Descripción Producto..." value="<?php echo $producto->producto_descripcion;?>">
                    </div>
                    <div class="form-group">
                        <label >Unidad de Medida</label>
                        <select class="form-control" id="id_medida">
                            <option value="">Seleccione Una unidad de medida...</option>
                            <?php
                            foreach ($unimedida as $um){
                                ?>
                                <option <?php echo ($um->id_medida == $producto->id_medida) ? 'selected' : '';?> value="<?php echo $um->id_medida;?>"><?php echo $um->medida_nombre;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label >Stock Producto</label>
                        <input type="text" class="form-control" id="producto_stock" placeholder="Ingresar Stock Producto..." onkeypress="return valida(event)" value="<?php echo $producto->producto_stock;?>" readonly>
                    </div>
                    <div class="form-group">
                        <label >Precio Por Unidad</label>
                        <input type="text" class="form-control" id="producto_precio_valor" onkeypress="return valida(event)" placeholder="Ingresar Precio Producto..." value="<?php echo $producto->producto_precio_valor;?>">
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer" style="text-align: center">
                    <button id="btn-editar-producto" type="submit" class="btn btn-primary" onclick="editar()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>



    <script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
    <script src="<?php echo _SERVER_ . _JS_;?>inventario.js"></script>