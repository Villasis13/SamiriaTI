<?php
/**
 * Created by PhpStorm
 * User: Franz
 * Date: 22/04/2019
 * Time: 22:33
 */
?>


<div class="modal fade" id="ver_general" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 60% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" style="text-align: center"><div id="nombre_"></div></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow mb-4">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active"><a data-toggle="tab" href="#agregados"><i class="fa fa-table"></i> Agregados</a></li>
                            <li><a data-toggle="tab" href="#ventas"><i class="fa fa-bar-chart"></i> Ventas</a></li>
                            <li><a data-toggle="tab" href="#stock"><i class="fa fa-pie-chart"></i> Salidas de Stock</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div id="agregados" class="tab-pane fade in active show">
                    <div class="row">
                        <div class="col-sm-12 p-5">
                            <div id="detalle_agregados" class="table-responsive"></div>
                        </div>
                    </div>
                </div>
                <div id="ventas" class="tab-pane fade">
                    <div class="row">
                        <div class="col-sm-12 p-5">
                            <div id="detalle_ventas_"></div>
                            <div id="detalle_ventas" class="table-responsive"></div>
                        </div>
                    </div>
                </div>
                <div id="stock" class="tab-pane fade">
                    <div class="row">
                        <div class="col-sm-12 p-5">
                            <div id="detalle_stock" class="table-responsive">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
</div>

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <hr><h2 class="concss">
            <a href="http://localhost/fire"><i class="fa fa-fire"></i> INICIO</a> >
            <a href="http://localhost/fire/Inventario/productos"><i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['controlador'];?></a> >
            <i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['accion'];?>
        </h2><hr>
    </section>

    <!-- Main content -->
    <section class="container-fluid">
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4" style="text-align: center;">
                <h5 class="font-weight-bold text-primary">PRODUCTOS REGISTRADOS</h5>
            </div>
            <div class="col-lg-1"></div>
            <div class="col-lg-3">
                <a class="btn btn-block btn-success btn-sm font-weight-bold" href="<?php echo _SERVER_;?>Inventario/agregar_producto" ><i class="fa fa-plus"></i> AGREGAR NUEVO PRODUCTO</a>
            </div>
        </div>
        <br>

        <!-- /.row (main row) -->
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                    <thead class="text-capitalize">
                    <tr>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Código de Barras</th>
                        <th>Proveedor</th>
                        <th>Tipo de Unidad</th>
                        <th>Precio Unitario Compra</th>
                        <th>Precio Unitario Venta</th>
                        <th>Stock</th>
                        <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($productos as $p){
                        ?>
                        <tr id="producto<?= $p->id_producto;?>">

                            <td>
                                <a style="color: blue; cursor: pointer;" class="button" data-toggle="modal" data-target="#ver_general" onclick="llenar_modal(<?= $p->id_producto;?>)">
                                <?php echo $p->producto_nombre;?>
                                </a>
                            </td>
                            <td><?php echo $p->categoria_nombre;?></td>
                            <td><?php echo $p->producto_codigo_barra;?></td>
                            <td><?php echo $p->proveedor_nombre;?></td>
                            <td><?php echo $p->medida_nombre;?></td>
                            <td>S/. <?php echo $p->producto_precio_compra;?></td>
                            <td>S/. <?php echo $p->producto_precio_valor;?></td>
                            <td><?php echo $p->producto_stock;?></td>
                            <td><a class="btn btn-chico btn-warning" type="button" href="<?php echo _SERVER_;?>Inventario/editar_producto/<?php echo $p->id_producto;?>"><i class="fa fa-pencil"></i> Editar</a>

                                <?php
                                $validar = $this->inventario->validar($p->id_producto);

                                (!empty($validar))?$resultado=true:$resultado=false;
                                if($resultado){
                                ?>
                                <button id="btn-eliminar_producto<?= $p->id_producto;?>" class="btn btn-chico btn-danger btn-xs" onclick="preguntar('¿Está seguro que desea deshabilitar este producto?','eliminarproducto','Si','No',<?= $p->id_producto;?>)"><i class="fa fa-times"></i> Deshabilitar</button>
                                <?php
                                }else{
                                    ?>
                                <button id="" class="btn btn-chico btn-danger btn-xs" onclick="preguntar('¿Está seguro que desea eliminar este producto?','quitarproducto','Si','No',<?= $p->id_producto;?>,'<?= $p->id_producto_precio;?>')"><i class="fa fa-times"></i> Eliminar</button>
                                <?php
                                }
                                ?>
                                <a class="btn btn-info btn-xs" href="<?php echo _SERVER_;?>Inventario/agregar_stock/<?php echo $p->id_producto;?>"><i class="fa fa-sort-numeric-asc"></i> Agregar Stock</a>
                                <a class="btn btn-primary btn-xs" href="<?php echo _SERVER_;?>Inventario/salida_stock/<?php echo $p->id_producto;?>"><i class="fa fa-eraser"></i> Salida Stock</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>inventario.js"></script>
<script>
    function llenar_modal(id_producto){
        //$("#id_producto").val(id_producto);
        $.ajax({
            type: "POST",
            url: urlweb + "api/Inventario/consultar_datos",
            data: "id_producto="+id_producto,
            dataType: 'json',
            success:function (r) {
                console.log(r.detalle_agregados_);
                $("#detalle_agregados").html(r.detalle_agregados);
                $("#detalle_ventas").html(r.detalle_ventas);
                $("#detalle_ventas_").html(r.detalle_ventas_);
                $("#detalle_stock").html(r.detalle_salida_stock);
                $("#nombre_").html(r.detalle_agregados_);
            }
        });
    }
</script>

<style>
    .nav-tabs.nav-justified {width: 100%;border-bottom: 0;}
    .panel .tabs {margin: 0;padding: 0; }
    .nav-tabs {background: #f2f3f2;border: 0; }
    .nav-tabs li a:hover {background: #fff; }
    .nav-tabs li a, .nav-tabs li a:hover, .nav-tabs li.active a, .nav-tabs li.active a:hover {border: 0;padding: 15px 20px; }
    .nav-tabs li.active a {color: #FFA233; }
    .nav-tabs li a {color: #999; }
    .nav-pills li a, .nav-pills li a:hover, .nav-pills li.active a, .nav-pills li.active a:hover {border: 0;padding: 7px 15px; }
    .nav-pills li.active a, .nav-pills li.active a:hover {background: #FFA233; }
    .tab-content {padding: 15px; }
    .nav>li>a {position: relative;display: block;padding: 10px 15px;}
    .nav-tabs.nav-justified>li {display: table-cell;width: 1%;}
    .nav-tabs.nav-justified>li {float: none;text-align: center;}
    .nav-tabs.nav-justified>li.active {background: white;}
    .nav>li {position: relative;display: block;}
    .nav {padding-left: 0;margin-bottom: 0;list-style: none;display: block !important;}
</style>
<script>
    (jQuery),+function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("bs.tab");e||d.data("bs.tab",e=new c(this)),"string"==typeof b&&e[b]()})}var c=function(b){this.element=a(b)};c.VERSION="3.3.7",c.TRANSITION_DURATION=150,c.prototype.show=function(){var b=this.element,c=b.closest("ul:not(.dropdown-menu)"),d=b.data("target");if(d||(d=b.attr("href"),d=d&&d.replace(/.*(?=#[^\s]*$)/,"")),!b.parent("li").hasClass("active")){var e=c.find(".active:last a"),f=a.Event("hide.bs.tab",{relatedTarget:b[0]}),g=a.Event("show.bs.tab",{relatedTarget:e[0]});if(e.trigger(f),b.trigger(g),!g.isDefaultPrevented()&&!f.isDefaultPrevented()){var h=a(d);this.activate(b.closest("li"),c),this.activate(h,h.parent(),function(){e.trigger({type:"hidden.bs.tab",relatedTarget:b[0]}),b.trigger({type:"shown.bs.tab",relatedTarget:e[0]})})}}},c.prototype.activate=function(b,d,e){function f(){g.removeClass("active").find("> .dropdown-menu > .active").removeClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded",!1),b.addClass("active").find('[data-toggle="tab"]').attr("aria-expanded",!0),h?(b[0].offsetWidth,b.addClass("in")):b.removeClass("fade"),b.parent(".dropdown-menu").length&&b.closest("li.dropdown").addClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded",!0),e&&e()}var g=d.find("> .active"),h=e&&a.support.transition&&(g.length&&g.hasClass("fade")||!!d.find("> .fade").length);g.length&&h?g.one("bsTransitionEnd",f).emulateTransitionEnd(c.TRANSITION_DURATION):f(),g.removeClass("in")};var d=a.fn.tab;a.fn.tab=b,a.fn.tab.Constructor=c,a.fn.tab.noConflict=function(){return a.fn.tab=d,this};var e=function(c){c.preventDefault(),b.call(a(this),"show")};a(document).on("click.bs.tab.data-api",'[data-toggle="tab"]',e).on("click.bs.tab.data-api",'[data-toggle="pill"]',e)}
    (jQuery);
</script>