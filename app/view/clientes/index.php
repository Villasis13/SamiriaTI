<hr><h2 class="concss">
    <a href="<?=_SERVER_; ?>"><i class="fa fa-fire"></i> INICIO</a> >
    <i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['accion'];?>
</h2><hr>
<div class="row">
    <div class="col-lg-3">
        <a href="<?=_SERVER_; ?>Clientes/agregar">
            <button class="boton_blanco">
                <img src="<?=_SERVER_ . _STYLES_LOGIN_;?>images/cliente_agregar.png" height="60%">
                <br><br>
                <h5 class="font-weight-bold">AGREGAR CLIENTE</h5>
            </button>
        </a>
    </div>
    <div class="col-lg-3">
        <a href="<?=_SERVER_; ?>Clientes/listar">
            <button class="boton_blanco">
                <img src="<?=_SERVER_ . _STYLES_LOGIN_;?>images/cliente_listar.png" height="60%">
                <br><br>
                <h5 class="font-weight-bold">LISTAR CLIENTES</h5>
            </button>
        </a>
    </div>
    <div class="col-lg-3">
        <a href="<?=_SERVER_; ?>Proveedor/agregar">
            <button class="boton_blanco">
                <img src="<?=_SERVER_ . _STYLES_LOGIN_;?>images/proveedor_agregar.png" height="60%">
                <br><br>
                <h5 class="font-weight-bold">AGREGAR PROVEEDOR</h5>
            </button>
        </a>
    </div>
    <div class="col-lg-3">
        <a href="<?=_SERVER_; ?>Proveedor/listar">
            <button class="boton_blanco">
                <img src="<?=_SERVER_ . _STYLES_LOGIN_;?>images/proveedor_listar.png" height="60%">
                <br><br>
                <h5 class="font-weight-bold">LISTAR PROVEEDOR</h5>
            </button>
        </a>
    </div>
</div>
