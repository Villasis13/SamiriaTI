<?php
/**
 * Created by PhpStorm
 * User: Franz
 * Date: 22/04/2019
 * Time: 22:33
 */
?>
<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $_SESSION['controlador'];?> /
            <small> <?php echo $_SESSION['accion'];?></small>
        </h1>
    </section>

    <section class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Salida del Producto</h3>
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
                                <input type="text" class="form-control" id="product_stockactual"  value="<?php echo $producto->producto_stock;?>" readonly>
                            </div>
                            <div class="form-group">
                                <label >Stock de Salida</label>
                                <input type="text" class="form-control" id="stockout_out"  onkeyup="return validar_numeros(this.id)" placeholder="Ingresar Stock Producto..." value="0" >
                            </div>
                            <div class="form-group">
                                <label >Guia de Remisión</label>
                                <input type="text" class="form-control" readonly id="stockout_guide" value="<?php echo 'GS-'.$fechahoy.'-'.$correlativo->correlativo_out; ?>" placeholder="Ingresar Guia de Remisión..." >
                            </div>
                            <div class="form-group">
                                <label >Origen</label>
                                <input type="text" class="form-control" id="stockout_origin" placeholder="Ingresar Origen..." >
                            </div>
                            <div class="form-group">
                                <label >Descripción</label>
                                <textarea class="form-control" name="stockout_description" id="stockout_description" cols="30" rows="2" placeholder="Ingresar Descripción..."></textarea>
                            </div>
                            <div class="form-group">
                                <label >Destino</label>
                                <input type="text" class="form-control" id="stockout_destiny" placeholder="Ingresar Destino..." >
                            </div>
                            <div class="form-group" style="display: none;">
                                <label >RUC</label>
                                <input type="text" class="form-control" id="stockout_ruc" placeholder="Ingresar RUC..." >
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer" style="text-align: center">
                            <button id="btn-salida_stock" class="btn btn-primary" onclick="salida_stock()"><i class="fa fa-save"></i> Registrar Salida Stock</button>
                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
        <!-- /.row -->
    </section>

</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>inventario.js"></script>
