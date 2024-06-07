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
        <hr><h2 class="concss">
            <a href="http://localhost/fire"><i class="fa fa-fire"></i> INICIO</a> >
            <a href="http://localhost/fire/Inventario/productos"><i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['controlador'];?></a> >
            <i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['accion'];?>
        </h2><hr>
    </section>

    <div class="row">
        <div class="col-md-12">
            <h5 class="font-weight-bold" style="text-align: center">PRODUCTO A AGREGAR</h5>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-4">
            <label >Nombre Producto</label>
            <input type="text" class="form-control" id="producto_nombre" placeholder="Ingresar Nombre Producto...">
        </div>
        <div class="col-md-4">
            <label >Proveedor del Producto</label>
            <select class="form-control" id= "id_proveedor">
                <option value="">Seleccionar Proveedor</option>
                <?php
                foreach($proveedor as $p){
                    ($p->id_proveedor == "2")?$selec='selected':$selec='';
                    ?>
                    <option value="<?php echo $p->id_proveedor;?>" <?= $selec;?>><?php echo $p->proveedor_nombre;?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="col-md-4">
            <label >Código de Barra</label>
            <input type="text" class="form-control" id="producto_codigo_barra" placeholder="Ingresar Código de Barra...">
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-4">
            <label >Categoría Producto</label>
            <select class="form-control" id="id_categoria">
                <option value="">Seleccione Una Categoría...</option>
                <?php
                foreach ($categoria as $ca){
                    ?>
                    <option value="<?php echo $ca->id_categoria;?>"><?php echo $ca->categoria_nombre;?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="col-md-4" style="display: none;">
            <label >Descripcion Producto</label>
            <input type="text" class="form-control" id="producto_descripcion" placeholder="Ingresar Descripción Producto..." value="--">
        </div>
        <div class="col-md-4">
            <label >Unidad de Medida</label>
            <select class="form-control" id="id_medida">
                <option value="">Seleccione Una unidad de medida...</option>
                <?php
                foreach ($unimedida as $um){
                    ($um->id_medida == "58")?$selec='selected':$selec='';
                    ?>
                    <option value="<?php echo $um->id_medida;?>" <?= $selec;?>><?php echo $um->medida_nombre;?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="col-md-4">
            <label >Tipo Afectación</label>
            <select class="form-control" id="id_tipoafectacion">
                <option value="">Seleccione...</option>
                <?php
                foreach ($codigoafectacion as $um){
                    ($um->codigo == "20")?$selec='selected':$selec='';
                    ?>
                    <option value="<?php echo $um->codigo;?>" <?= $selec;?>><?php echo $um->descripcion;?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-3">
            <label >Stock Producto</label>
            <input type="text" class="form-control" id="producto_stock" placeholder="Ingresar Stock Producto..." onkeypress="return validar_numeros(this.id)">
        </div>
        <div class="col-md-3">
            <label >Precio Por Unidad Compra</label>
            <input type="text" class="form-control" id="producto_precio_compra" placeholder="Ingresar Precio Compra de Producto..." onkeypress="return validar_numeros_decimales_dos(this.id)">
        </div>
        <div class="col-md-3">
            <label >Precio Por Unidad Venta</label>
            <input type="text" class="form-control" id="producto_precio_valor" placeholder="Ingresar Precio Venta Producto..." onkeypress="return validar_numeros_decimales_dos(this.id)">
        </div>
        <div class="col-md-3">
            <label >Precio Por Unidad Venta x Mayor</label>
            <input type="text" class="form-control" id="producto_precio_valor_xmayor" placeholder="Ingresar Precio Venta Producto..." onkeypress="return validar_numeros_decimales_dos(this.id)">
        </div>
    </div>

    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <button id="btn-agregar_producto" type="submit" class="btn btn-primary" style="margin-top:33px; width:100%" onclick="add()"><i class="fa fa-save fa-sm text-white-50"></i> GUARDAR PRODUCTO</button>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>
<!-- /.box-body -->


<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>inventario.js"></script>