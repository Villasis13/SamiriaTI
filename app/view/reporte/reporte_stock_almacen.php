

<div class="modal fade" id="ver_general" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 80% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow mb-4">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active"><a data-toggle="tab" href="#ventas"><i class="fa fa-bar-chart"></i> PLATOS RELACIONADOS</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div id="ventas" class="tab-pane fade in active show">
                    <div class="row">
                        <div class="col-sm-12 p-5">
                            <div id="detalle_ventas_"></div>
                            <div id="detalle_ventas" class="table-responsive"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800"><?= $_SESSION['controlador'] . ' / ' . $_SESSION['accion'];?></h1>
            </div>

            <div class="row" style="display: none">
                <div class="col-lg-12">
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h4 class="m-0 font-weight-bold text-primary">Lista de Productos Registrados</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless table-striped table-earning" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="text-capitalize">
                                    <tr>
                                        <th>ID</th>
                                        <th>Recurso</th>
                                        <th>Stock Sin Distribuir</th>
                                        <th>Stock Distribuido</th>
                                        <th>Stock Total</th>
                                        <!--<th>Accion</th>-->
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $a = 1;
                                    foreach ($recuacua as $m){
                                        $stock_r = "";

                                        ?>
                                        <tr>
                                            <td><?= $a;?></td>
                                            <td><?= $m->recurso_nombre;?></td>
                                            <td><?= $m->recurso_stock_entrante?></td>
                                            <td>
                                                <a style="color: blue; cursor: pointer;" class="button" data-toggle="modal" data-target="#ver_general" onclick="llenar_modal_reporte(<?= $m->id_recurso;?>)">Ver
                                            </td>
                                            <td><?= $m->recurso_stock_global;?></td>
                                            <!--<td>
                                                <a class="btn btn-warning" onclick="sacar_stock_almacen(<?= $m->id_recurso_sede?>,'<?= $m->id_negocio?>','<?= $m->recurso_nombre?>')" data-target="#sacar_stock" data-toggle="modal" title='Sacar Stock'><i class='fa fa-minus-square text-white editar margen'></i></a>
                                                <a class="btn btn-danger" onclick="preguntar('¿Esta seguro que quiere Deshabilitar este producto?','deshabilitar','Si','No',<?= $m->id_recurso_sede?>,0)" title='Deshabilitar producto'><i class='fa fa-trash text-white editar margen'></i></a>
                                            </td>-->
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
            <form method="post" action="<?= _SERVER_ ?>Reporte/reporte_stock_almacen">
                <input type="hidden" id="enviar_fecha" name="enviar_fecha" value="1">
                <div class="row">
                    <div class="col-lg-1"></div>
                    <div class="col-lg-2 col-sm-4 col-xs-4 col-md-4">
                        <label for="turno">Desde:</label>
                        <input type="date" class="form-control" id="fecha_filtro" name="fecha_filtro" step="1" value="<?php echo $fecha_i;?>">
                    </div>
                    <div class="col-lg-2 col-sm-4 col-xs-4 col-md-4">
                        <label for="turno">Hasta:</label>
                        <input type="date" class="form-control" id="fecha_filtro_fin" name="fecha_filtro_fin" value="<?php echo $fecha_f;?>">
                    </div>
                    <div class="col-lg-2 col-sm-4 col-xs-4 col-md-4">
                        <button style="margin-top: 35px;" class="btn btn-success" ><i class="fa fa-search"></i> Buscar Registro</button>
                    </div>
                </div>
            </form>
            <br>
            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-10 col-sm-12 col-xs-12 col-md-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h4 class="m-0 font-weight-bold text-primary">Listado de Salidas de Stock</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-borderless table-striped table-earning" id="dataTable" style="border-color: black">
                                <thead>
                                <tr style="background-color: #ebebeb">
                                    <th>FECHAS</th>
                                    <th>HORA</th>
                                    <th>PRODUCTO</th>
                                    <th>CANTIDAD TRANSFERIDA</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if($datos){
                                    for($i=$fecha_filtro;$i<=$fecha_filtro_fin;$i+=86400) {
                                        //$cantidad_sacada = $this->almacen->cantidad_sacada(date("Y-m-d", $i));
                                        $buscar_salidas_stock = $this->reporte->buscar_salidad_stock(date("Y-m-d", $i));
                                        $fecha = date("d-m-Y", $i);
                                        ?>

                                        <?php
                                        foreach ($buscar_salidas_stock as $cs){
                                            ?>
                                            <tr>
                                                <td><?= $fecha;?></td>
                                                <td><?= date('H:i:s',strtotime($cs->recurso_log_fecha))?></td>
                                                <td><?= $cs->nombre?></td>
                                                <td><?= $cs->recurso_log_cantidad_salida ?? 0?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-1"></div>
            </div>

        </div>
    </div>
</div>

<!-- End of Main Content -->
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>almacen.js"></script>
<script>
    function sacar_stock_almacen(id,id_negocio,nombre){
        $("#id_recurso_modal").val(id);
        $("#id_negocio_modal").val(id_negocio);
        $("#nombre_producto_").html(nombre);
    }

    function llenar_modal_reporte(id_recurso){

        $.ajax({
            type: "POST",
            url: urlweb + "api/Reporte/consultar_datos",
            data: "id_recurso="+id_recurso,
            dataType: 'json',
            success:function (r) {
                console.log(r.detalle_ventas);
                console.log(r.detalle_ventas_);
                $("#detalle_ventas").html(r.detalle_ventas);
                $("#detalle_ventas_").html(r.detalle_ventas_);
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