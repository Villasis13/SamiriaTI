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
        <h1 class="h3 mb-0 text-gray-800"><?= $_SESSION['controlador'] . ' / ' . $_SESSION['accion'];?></h1>
    </section>


    <section class="container-fluid">
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <div class="col-lg-10">
            </div>
            <div class="col-lg-2">
                <a class="btn btn-block btn-success btn-sm" href="<?php echo _SERVER_;?>Egresos/agregar"><i class="fa fa-plus"></i> Agregar Nuevo</a>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Lista de Unidades de Medida</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead class="text-capitalize">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Código</th>
                                    <th>Acción</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $a = 1;
                                foreach ($unimedida as $um){
                                    ?>
                                    <tr>
                                        <td><?php echo $a;?></td>
                                        <td><?php echo $um->medida_nombre;?></td>
                                        <td><?php echo $um->medida_codigo_unidad;?></td>
                                        <?php
                                        if ($um->medida_activo == 0){?>
                                            <td><a type="button" class="btn btn-xs btn-success btne" onclick="cambiarestado(<?php echo $um->id_medida ?>, 1)" href="" >ACTIVAR</a></td>
                                            <?php
                                        } else{ ?>
                                            <td><a type="button" class="btn btn-xs btn-danger btne" onclick="cambiarestado(<?php echo $um->id_medida ?>, 0)" href="" >DESACTIVAR</a></td>
                                            <?php
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                    $a++;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>unidadmedida.js"></script>