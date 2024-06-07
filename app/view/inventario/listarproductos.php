<?php
/**
 * Created by PhpStorm
 * User: LuisSalazar
 * Date: 10/12/2020
 * Time: 12:12 a. m.
 */
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Inventario <small>Listar Productos</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="container-fluid">
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <div class="col-xs-10">
                <h2 style="text-align: center;">Gestion de Productos</h2>
            </div>
            <div class="col-xs-2">
                <center><a class="btn btn-block btn-success btn-sm" href="<?php echo _SERVER_;?>Inventory/addProduct" >Agregar Nuevo</a></center>
            </div>
        </div>
        <br>
        <!-- /.row (main row) -->
        <div class="row">
            <div class="col-lg-12">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>ID Interno</th>
                        <th>Nombre</th>
                        <!--<th>Descripción Producto</th>-->
                        <th>Stock</th>
                        <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($products as $product){
                        ?>
                        <tr>
                            <td><?php echo $product->id_product;?></td>
                            <td><?php echo $product->product_name;?></td>
                            <!--<td><?php echo $product->product_description;?></td>-->
                            <td><?php echo $product->product_stock;?></td>
                            <td><a class="btn btn-chico btn-warning btn-xs" type="button" href="<?php echo _SERVER_;?>Inventory/editProduct/<?php echo $product->id_product;?>">Editar</a><a class="btn btn-chico btn-danger btn-xs" onclick="preguntarSiNo(<?php echo $product->id_product;?>)">Eliminar</a><a class="btn btn-dropbox btn-xs" href="<?php echo _SERVER_;?>Inventory/productForsale/<?php echo $product->id_product;?>">Ver Costo Venta</a><a class="btn btn-info btn-xs" href="<?php echo _SERVER_;?>Inventory/addProductstock/<?php echo $product->id_product;?>">Agregar Stock</a></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>ID Interno</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Stock</th>
                        <th>Acción</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>