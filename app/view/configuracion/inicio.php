<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Inicio</h1>
    </div>
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <a href="<?= _SERVER_ ?>Configuracion/asistencia" target="_blank" style="text-decoration: none">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">ASISTENCIA</div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-clock-o fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <a href="<?= _SERVER_ ?>Configuracion/habitaciones" target="_blank" style="text-decoration: none">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">HABITACIONES</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-bed fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <a href="<?= _SERVER_ ?>Configuracion/productos" target="_blank" style="text-decoration: none">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">PRODUCTOS</div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-tags fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <a href="<?= _SERVER_ ?>Configuracion/cajas" target="_blank" style="text-decoration: none">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">CAJAS</div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-laptop fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <a href="<?= _SERVER_ ?>Configuracion/comandas" target="_blank" style="text-decoration: none">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">ENTREGA DE COMANDAS</div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-file fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <?php
        if($id_rol == 2 || $id_rol == 3){
        ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <a href="<?= _SERVER_ ?>Configuracion/eliminados" target="_blank" style="text-decoration: none">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">ELIMINACIONES</div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-times fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>