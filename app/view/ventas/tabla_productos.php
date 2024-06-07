<?php
/**
 * Created by PhpStorm
 * User: Franz
 * Date: 20/04/2019
 * Time: 18:25
 */
?>
<div class="row">

    <div class="col-lg-2" style="display: none">
        <button class="btn btn-secondary btn-xs" type="button" style="width: 100%" data-toggle="modal" data-target="#largeModal"><i class="fa fa-plus"></i> Agregar Producto</button>
    </div>
</div>
<br>
<div class="row">
    <!--<div class="col-lg-1"></div>-->
    <div class="col-lg-12">
        <table class="table table-bordered table-hover" style="border-color: black">
            <thead>
            <tr style="background-color: #ebebeb">
                <th style="width: 15px;">ITEM</th>
                <th>Descripción</th>
                <th style="width: 140px;">Precio Unitario</th>
                <th style="width: 95px;">Cantidad</th>
                <th style="width: 80px;">Total</th>
                <th style="width: 75px;">Accion</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $totales = count($_SESSION['productos']);
            $monto = 0;
            $igv = 0;
            $gravada = 0;
            $inafecta = 0;
            $exonerada = 0;
            $gratuita = 0;
            $ICBPER = 0;
            $des_global = 0;
            $des_total = 0;
            $des_producto = 0;
            $igv_porcentaje=0.18;
            $fecha_bolsa = date("Y");
            if ($fecha_bolsa == "2020"){
                $impuesto_icbper = 0.20;
            } else if ($fecha_bolsa == "2021"){
                $impuesto_icbper = 0.30;
            } else if ($fecha_bolsa == "2022") {
                $impuesto_icbper = 0.40;
            } else{
                $impuesto_icbper = 0.50;
            }

            if($totales != 0){
                $item = 1;
                foreach ($_SESSION['productos'] as $p){
                    $subtotal = round($p[4] * $p[3],2);
                    if($p[6] > 0){
                        $subtotal = $subtotal - $p[6];
                    }

                    ?>
                    <tr> <!--De esta tapla se jala los valores por la posicion de los arrays-->
                        <td><?php echo $item;?></td>
                        <td><?php echo $p[1];?></td>
                        <td>s/. <?php echo $p[3];?></td>
                        <td>
                            <input type="text" class="form-control" style="width: 100%" id="valor_nueva_cantidad" onblur="editar_cantidad_tabla(<?= $p[0];?>)" value="<?php echo $p[4];?>">
                        </td>
                        <td>s/. <?php echo $subtotal;?></td>
                        <td style="text-align: center;"><a type="button" class="btn btn-xs btn-danger btne" style="color: white;" onclick="quitarProducto(<?php echo $p[0];?>)" ><i class="fa fa-times"></i></a></td>
                    </tr>
                    <?php
                    $item++;
                    $afectacion = $p[5];
                    if($afectacion == "10"){
                        $gravada = $gravada + $subtotal;
                    }
                    if($afectacion == "20"){
                        $exonerada = $exonerada + $subtotal;
                    }
                    if($afectacion == "30"){
                        $inafecta = $inafecta + $subtotal;
                    }
                    if($afectacion == "21"){
                        $gratuita = $gratuita + $subtotal;
                    }
                    if($p[1] == "BOLSA"){
                        $ICBPER = $impuesto_icbper * $p[4];
                    }
                    $des_producto = $des_producto + $p[6];
                    $igv = $gravada * $igv_porcentaje;
                    $monto = $gravada + $exonerada + $inafecta + $igv + $ICBPER;

                    /*

                    if ($p[5] == 1 || $p[5] == 10){
                        $subtotal = round($p[4] * $p[3],2);

                        $inafecta = $inafecta;
                        $exonerada = $exonerada;
                        $gratuita = $gratuita;
                        $gravada = $gravada + round($subtotal / 1.18, 2);
                        $gravadaalmacenada = round($subtotal / 1.18, 2);
                        $igv = $igv +  round($subtotal - $gravadaalmacenada, 2);
                        if ($p[0] == "6" || $p[0] == "7" ){
                            $ICBPER = $ICBPER + round($p[4] * $impuesto_icbper , 2);
                        }
                        $monto = round($monto + $subtotal + $ICBPER, 2);
                        ?>
                        <tr> <!--De esta tapla se jala los valores por la posicion de los arrays-->
                            <td><?php echo $p[0];?></td>
                            <td><?php echo $p[1];?></td>
                            <td>s/. <?php echo $p[3];?></td>
                            <td><?php echo $p[4];?></td>
                            <td>s/. <?php echo $subtotal;?></td>
                            <td><a type="button" class="btn btn-xs btn-warning btne" onclick="quitarProducto(<?php echo $p[0];?>)" ><i class="fa fa-times"></i> Quitar</a></td>
                        </tr>
                        <?php
                    } else if($p[5] == 3){
                        //EXONERADO
                        $subtotal = round($p[4] * $p[3],2);


                        $inafecta = $inafecta;
                        $gratuita = $gratuita;
                        $exonerada = round($exonerada + $subtotal , 2);
                        $gravada = round($gravada, 2);
                        $igv =round($igv, 2);
                        if ($p[0] == "6" || $p[0] == "7" ){
                            $ICBPER = $ICBPER + round($p[4] * $impuesto_icbper , 2);
                        }
                        $monto = round($monto + $subtotal + $ICBPER, 2);
                        ?>
                        <tr> <!--De esta tapla se jala los valores por la posicion de los arrays-->
                            <td><?php echo $p[0];?></td>
                            <td><?php echo $p[1];?></td>
                            <td>s/. <?php echo $p[3];?></td>
                            <td><?php echo $p[4];?></td>
                            <td>s/. <?php echo $subtotal;?></td>
                            <td><a type="button" class="btn btn-xs btn-warning btne" onclick="quitarProducto(<?php echo $p[0];?>)" ><i class="fa fa-times"></i> Quitar</a></td>
                        </tr>
                        <?php
                    }//GRATUITA
                    else if($p[5] == 4 || $p[5] == 5 || $p[5] == 6 || $p[5] == 7 || $p[5] == 8 || $p[5] == 9 || $p[5] == 11 || $p[5] == 12 || $p[5] == 13 || $p[5] == 14 || $p[5] == 15 || $p[5] == 16 || $p[5] == 17 || $p[5] == 18 || $p[5] == 20){
                        $subtotal = round($p[4] * $p[3],2);

                        $inafecta = $inafecta;
                        $exonerada = $exonerada;
                        $gratuita = round($gratuita + $subtotal , 2);
                        $gravada = round($gravada, 2);
                        $igv =round($igv, 2);
                        if ($p[0] == "6" || $p[0] == "7" ){
                            $ICBPER = $ICBPER + round($p[4] * $impuesto_icbper , 2);
                        }
                        $monto = round($monto + $subtotal + $ICBPER, 2);
                        ?>
                        <tr> <!--De esta tapla se jala los valores por la posicion de los arrays-->
                            <td><?php echo $p[0];?></td>
                            <td><?php echo $p[1];?></td>
                            <td>s/. <?php echo $p[3];?></td>
                            <td><?php echo $p[4];?></td>
                            <td>s/. <?php echo $subtotal;?></td>
                            <td><a type="button" class="btn btn-xs btn-warning btne" onclick="quitarProducto(<?php echo $p[0];?>)" ><i class="fa fa-times"></i> Quitar</a></td>
                        </tr>
                        <?php
                    }else{
                        //INAFECTA
                        $subtotal = round($p[4] * $p[3],2);

                        $inafecta = round($inafecta + $subtotal , 2);
                        $exonerada = $exonerada;
                        $gratuita = $gratuita;
                        $gravada = round($gravada, 2);
                        $igv =round($igv, 2);
                        if ($p[0] == "6" || $p[0] == "7" ){
                            $ICBPER = $ICBPER + round($p[4] * $impuesto_icbper , 2);
                        }
                        $monto = round($monto + $subtotal + $ICBPER, 2);
                        ?>
                        <tr> <!--De esta tapla se jala los valores por la posicion de los arrays-->
                            <td><?php echo $p[0];?></td>
                            <td><?php echo $p[1];?></td>
                            <td>s/. <?php echo $p[3];?></td>
                            <td><?php echo $p[4];?></td>
                            <td>s/. <?php echo $subtotal;?></td>
                            <td><a type="button" class="btn btn-xs btn-warning btne" onclick="quitarProducto(<?php echo $p[0];?>)" ><i class="fa fa-times"></i> Quitar</a></td>
                        </tr>
                        <?php
                    }*/
                }
                $des_total = $des_total + $des_producto;
            }
            ?>
            </tbody>
        </table>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-9" style="text-align: right">
                        <label for="" style="font-size: 14px;">% DESCUENTO GLOBAL</label><br>
                        <label for="" style="font-size: 14px;">DESCUENTO GLOBAL (-)</label><br>
                        <label for="" style="font-size: 14px;">DESCUENTO X ITEM (-)</label><br>
                        <label for="" style="font-size: 14px;">DESCUENTO TOTAL (-)</label><br>
                        <?php
                        if($gravada > 0){ ?>
                            <label for="" style="font-size: 14px;">OP. GRAVADAS</label><br>
                            <label for="" style="font-size: 14px;">IGV(18%)</label><br>
                        <?php
                        }
                        ?>

                        <label for="" style="font-size: 14px;">OP. EXONERADAS</label><br>
                        <?php
                        if($inafecta > 0){ ?>
                            <label for="" style="font-size: 14px;">OP. INAFECTAS</label><br>
                            <?php
                        }
                        ?>
                        <label for="" style="font-size: 14px;">OP. GRATUITAS</label><br>
                        <label for="" style="font-size: 14px;">ICBPER</label><br>
                        <label for="" style="font-size: 17px;"><strong>TOTAL</strong></label><br>
                        <label for="" style="font-size: 14px;">PAGÓ CON</label><br>
                        <label for="" style="font-size: 14px;">VUELTO</label>
                    </div>
                    <div class="col-lg-2" style="text-align: right;" >
                        <input style="width: 80px; margin-left: 25px; font-size: 14px; text-align: right;" class="form-control" onkeyup="calcular_descuento(this.value); return validar_numeros_decimales_dos(this.id)" type="text" id="des_global_porcentaje_">
                        <label for="" style="font-size: 14px;"><span id="des_global_"><?php echo number_format($des_global, 2);?></span></label><br>
                        <label for="" style="font-size: 14px;"><span id="des_item_"><?php echo number_format($des_producto, 2);?></span></label><br>
                        <label for="" style="font-size: 14px;"><span id="des_total_"><?php echo number_format($des_total, 2);?></span></label><br>
                        <input type="hidden" value="<?php echo $des_global;?>" id="des_global">
                        <input type="hidden" value="<?php echo $des_producto;?>" id="des_item">
                        <input type="hidden" value="<?php echo $des_total;?>" id="des_total">

                        <?php
                        if($gravada > 0){ ?>
                            <label for="" style="font-size: 14px;"><span id="gravada_"><?php echo number_format($gravada, 2);?></span></label><br>
                            <label for="" style="font-size: 14px;"><span id="igv_"><?php echo number_format($igv, 2);?></span></label><br>
                            <?php
                        }
                        ?>
                        <input type="hidden" value="<?php echo $gravada;?>" id="gravada">
                        <input type="hidden" value="<?php echo $gravada;?>" id="gravada__">

                        <input type="hidden" value="<?php echo $igv;?>" id="igv">
                        <label for="" style="font-size: 14px;"><span id="exonerada_"><?php echo number_format($exonerada, 2);?></span></label><br>
                        <input type="hidden" value="<?php echo $exonerada;?>" id="exonerada">
                        <input type="hidden" value="<?php echo $exonerada;?>" id="exonerada__">
                        <?php
                        if($inafecta > 0){ ?>
                            <label for="" style="font-size: 14px;"><span id="inafecta_"><?php echo number_format($inafecta, 2);?></span></label><br>
                            <?php
                        }
                        ?>
                        <input type="hidden" value="<?php echo $inafecta;?>" id="inafecta">
                        <input type="hidden" value="<?php echo $inafecta;?>" id="inafecta__">
                        <label for="" style="font-size: 14px;"><span id="gratuita_"><?php echo number_format($gratuita, 2);?></span></label><br>
                        <input type="hidden" value="<?php echo $gratuita;?>" id="gratuita">
                        <label for="" style="font-size: 14px;"><span id="icbper_"><?php echo number_format($ICBPER, 2);?></span></label><br>
                        <input type="hidden" value="<?php echo $ICBPER;?>" id="icbper">
                        <label for="" style="font-size: 17px;"><span id="montototal_"><?php echo number_format($monto, 2);?></span></label><br>
                        <input type="hidden" value="<?php echo $monto;?>" id="montototal">
                        <input type="hidden" value="<?php echo $monto;?>" id="montototal__">
                        <input style="width: 80px; margin-left: 25px; font-size: 14px; text-align: right;" type="text" class="form-control" id="pago_cliente" onkeyup="calcular_vuelto(this.value); return validar_numeros_decimales_dos(this.id)">
                        <!--<label for="" style="font-size: 14px;"><span id="pago_con">0.00</span></label><br>-->
                        <input type="hidden" id="pago_con_">
                        <label for="" style="font-size: 14px;"><span id="vuelto">0.00</span></label>
                        <input type="hidden" id="vuelto_">
                        <input type="hidden" id="descuento_global">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    function calcular_descuento(valor){
        var desc_porcentaje = valor / 100;
        var des_item_ = $('#des_item').val() * 1;
        var montototal = $('#montototal__').val();
        var exonerada = $('#exonerada__').val();
        var inafecta = $('#inafecta__').val();
        var gravada = $('#gravada__').val();

        //var montototal = $('#montototal').val();
        var desc_total_ = montototal * desc_porcentaje;
        var desc_total = desc_total_.toFixed(2);
        $('#des_global_').html(desc_total);
        $('#des_global').val(desc_total);
        var total_descuento = desc_total_ + des_item_;
        $('#des_total_').html(total_descuento.toFixed(2));
        $('#des_total').val(total_descuento.toFixed(2));
        $('#descuento_global').val(desc_porcentaje);
        var total_exonerado = 0;
        var total_gravada = 0;
        var igv = 0;
        var total_ = 0;
        if(exonerada > 0){
            var desc_exonerado_ = exonerada * desc_porcentaje * 1;
            var desc_exonerado = exonerada - desc_exonerado_;
            total_exonerado = desc_exonerado.toFixed(2);
            $('#exonerada_').html(total_exonerado);
            $('#exonerada').val(total_exonerado);
        }
        if(gravada > 0){
            var desc_gravada_ = gravada * desc_porcentaje * 1;
            var desc_gravada = gravada - desc_gravada_;
            total_gravada = desc_gravada.toFixed(2);
            var igv_ = desc_gravada * 0.18 ;
            igv = igv_.toFixed(2) * 1;
            $('#gravada_').html(total_gravada);
            $('#gravada').val(total_gravada);
            $('#igv_').html(igv);
            $('#igv').val(igv);
        }
        var exo = $('#exonerada').val() * 1;
        var gra = $('#gravada').val() * 1;
        var ig = $('#igv').val() * 1;
        total_ = (exo + gra + ig) * 1;
        var total = total_.toFixed(2);
        var total = total_.toFixed(2);
        $('#montototal_').html(total);
        $('#montototal').val(total);

    }


</script>