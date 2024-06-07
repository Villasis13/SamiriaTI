<hr><h2 class="concss">
    <a href="http://localhost/fire"><i class="fa fa-fire"></i> INICIO</a> >
    <i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['accion'];?>
</h2><hr>

<div class="row">
    <div class="col-lg-3"></div>
    <div class="col-lg-3">
        <a href="<?=_SERVER_; ?>Inventario/agregar_producto">
            <button class="boton_blanco">
                <img src="<?=_SERVER_ . _STYLES_LOGIN_;?>images/producto_agregar.png" height="60%">
                <br><br>
                <h5 class="font-weight-bold">AGREGAR PRODUCTOS</h5>
            </button>
        </a>
    </div>
    <div class="col-lg-3">
        <a href="<?=_SERVER_; ?>Inventario/listarproductos">
            <button class="boton_blanco">
                <img src="<?=_SERVER_ . _STYLES_LOGIN_;?>images/producto_listar.png" height="60%">
                <br><br>
                <h5 class="font-weight-bold">LISTAR PRODUCTOS</h5>
            </button>
        </a>
    </div>
</div>