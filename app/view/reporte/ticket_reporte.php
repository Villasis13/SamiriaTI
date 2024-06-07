<?php
require 'app/models/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;



$nombre_impresora = "CAJA_R";


$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector);


/*
	Vamos a imprimir un logotipo
	opcional. Recuerda que esto
	no funcionará en todas las
	impresoras

	Pequeña nota: Es recomendable que la imagen no sea
	transparente (aunque sea png hay que quitar el canal alfa)
	y que tenga una resolución baja. En mi caso
	la imagen que uso es de 250 x 250
*/
/* Initialize */
$printer -> initialize();
# Vamos a alinear al centro lo próximo que imprimamos
$printer->setJustification(Printer::JUSTIFY_CENTER);

/*
	Intentaremos cargar e imprimir
	el logo
*/
/*try{
    $logo = EscposImage::load("media/logo/logo_ruta_ticket.png", false);
    $printer->bitImage($logo);
}catch(Exception $e){*//*No hacemos nada si hay error}*/
/*
	Ahora vamos a imprimir un encabezado
*/
$printer->setFont(Printer::FONT_B);
$printer->setTextSize(2,2);
$printer->text("REPORTE GENERAL" . "\n");
$printer->setFont(Printer::FONT_A);
$printer->setTextSize(1,1);
//$printer->text("$dato_pago->empresa_nombre" . "\n");
$printer->text("DEL DIA : " . "$nueva_fecha_i AL $nueva_fecha_f\n");//AQUI IRIA LA FECHA
$printer->text("CAJERO : " . $usuario_->persona_nombre. " ". $usuario_->persona_apellido. "\n");//AQUI IRIA LA FECHA
//$printer->text("$empresa->empresa_domiciliofiscal" . "\n");
//$printer->text("CAL. YAVARI NRO. 1360" . "\n");
//$printer->text("LORETO - MAYNAS - PUNCHANA" . "\n");


//$printer->text("PADRES:       $padre1" . "\n" . "           $padre2" . "\n");
# Vamos a alinear al centro lo próximo que imprimamos
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("------------------------------------------------" . "\n");
/*
	Ahora vamos a imprimir los
	productos
*/

# Para mostrar el total
//$caja_total = 0;
//$ingresos_total = 0;
//$egresos_totales = 0;
//$ingresos_tarjeta_total = 0;
//$ingresos_transferencias_total = 0;
//$ingresos_totales_salon = 0;
//$ingresos_totales_delivery = 0;
//$ingresos_generales = 0;
//$movimientos_caja_chica = 0;
//$salida_caja_chica = 0;
//$orden_pedido_total = 0;
//$ingresos_total_efectivo_delivery = 0;
//$ingresos_total_tarjeta_delivery = 0;
//$ingresos_total_transferencia_delivery = 0;
//$ingresos_total_de_ventas = 0;
//$diferencia = 0;
//    for($i=$fecha_filtro;$i<=$fecha_filtro_fin;$i+=86400){
//
//        $caja = $this->reporte->sumar_caja(date("Y-m-d",$i),$id_caja_numero);
//        $reporte_ingresos_movi = $this->reporte->listar_datos_ingresos_caja(date("Y-m-d",$i),$id_caja_numero);
//        $reporte_ingresos = $this->reporte->listar_datos_ingresos(date("Y-m-d",$i),$id_caja_numero);
//        $reporte_ingresos_tarjeta = $this->reporte->listar_datos_ingresos_tarjeta(date("Y-m-d",$i),$id_caja_numero);
//        $reporte_ingresos_transferencia = $this->reporte->listar_datos_ingresos_transferencia(date("Y-m-d",$i),$id_caja_numero);
//        $reporte_orden_pedido = $this->reporte->listar_monto_op(date("Y-m-d",$i),$id_caja_numero);
//        $reporte_egresos_movi = $this->reporte->listar_datos_egresos(date("Y-m-d",$i),$id_caja_numero);
//        //FUNCIONES PARA LOS INGRESOS DEL DELIVERY
//        $reporte_ingresos_delivery = $this->reporte->listar_datos_ingresos_delivery(date("Y-m-d",$i),$id_caja_numero);
//        $reporte_ingresos_tarjeta_delivery = $this->reporte->listar_datos_ingresos_tarjeta_delivery(date("Y-m-d",$i),$id_caja_numero);
//        $reporte_ingresos_transferencia_delivery = $this->reporte->listar_datos_ingresos_transferencia_delivery(date("Y-m-d",$i),$id_caja_numero);
//        //RESULTADO DE LAS FUNCIONES PARA SALON
//        $caja = $caja->total;
//        $reporte_ingresos_movi = $reporte_ingresos_movi->total;
//        $ingresos = $reporte_ingresos->total;
//        $reporte_ingresos_tarjeta = $reporte_ingresos_tarjeta->total;
//        $reporte_ingresos_transferencia = $reporte_ingresos_transferencia->total;
//        //$reporte_orden_pedido = $reporte_orden_pedido->total;
//        $reporte_egresos_movi = $reporte_egresos_movi->total;
//        //RESULTADO DE LAS FUNCIONES PARA DELIVERYS
//        $reporte_ingresos_delivery = $reporte_ingresos_delivery->total;
//        $reporte_ingresos_tarjeta_delivery = $reporte_ingresos_tarjeta_delivery->total;
//        $reporte_ingresos_transferencia_delivery = $reporte_ingresos_transferencia_delivery->total;
//
//        //SUMA DE TOTALES EN EL SALON
//        $caja_total = $caja_total + $caja;
//        $movimientos_caja_chica = $movimientos_caja_chica + $reporte_ingresos_movi;
//        $ingresos_total = $ingresos_total + $ingresos;
//        $ingresos_tarjeta_total = $ingresos_tarjeta_total + $reporte_ingresos_tarjeta;
//        $ingresos_transferencias_total = $ingresos_transferencias_total + $reporte_ingresos_transferencia;
//        //$orden_pedido_total = $orden_pedido_total + $reporte_orden_pedido;
//        $salida_caja_chica = $salida_caja_chica + $reporte_egresos_movi;
//        //SUMA DE TOTALES DEL DELIVERY
//        $ingresos_total_efectivo_delivery = $ingresos_total_efectivo_delivery + $reporte_ingresos_delivery;
//        $ingresos_total_tarjeta_delivery = $ingresos_total_tarjeta_delivery + $reporte_ingresos_tarjeta_delivery;
//        $ingresos_total_transferencia_delivery = $ingresos_total_transferencia_delivery + $reporte_ingresos_transferencia_delivery;
//    }
//    $ingresos_total_de_ventas = $ingresos_total_de_ventas + $ingresos_total + $ingresos_tarjeta_total + $ingresos_transferencias_total + $ingresos_total_efectivo_delivery + $ingresos_total_tarjeta_delivery + $ingresos_total_transferencia_delivery;
//    $ingresos_generales = $ingresos_generales + $ingresos_total + $ingresos_tarjeta_total + $ingresos_transferencias_total + $ingresos_total_efectivo_delivery +
//    $ingresos_total_tarjeta_delivery + $ingresos_total_transferencia_delivery + $caja_total + $movimientos_caja_chica - $salida_caja_chica;
//
//    $ingresos_totales_salon = $ingresos_totales_salon + $ingresos_tarjeta_total + $ingresos_total + $ingresos_transferencias_total;
//    $ingresos_totales_delivery = $ingresos_totales_delivery + $ingresos_total_efectivo_delivery + $ingresos_total_tarjeta_delivery + $ingresos_total_transferencia_delivery;
//    $egresos_totales = $egresos_totales + $salida_caja_chica;
//
//    $diferencia = $diferencia + $caja_total + $movimientos_caja_chica + $ingresos_total + $ingresos_total_efectivo_delivery - $salida_caja_chica;


    $fecha_ini_caja = $datitos->caja_fecha_apertura;
    if ($datitos->caja_fecha_cierre == NULL) {
        $fecha_fin_caja = date('Y-m-d H:i:s');
    } else {
        $fecha_fin_caja = $datitos->caja_fecha_cierre;
    }

    $total = 0;
    $apertura = 1;
    //N° DE VENTAS POR TIPO
    $n_ventas_salon = $this->reporte->n_ventas_salon($fecha_i, $fecha_f, $id_usuario);

    $monto_caja_apertura = $this->reporte->reporte_caja_x_caja($fecha_i, $fecha_f, $id_usuario);
    //REPORTE DE VENTAS POR SALON
    $ventas_efectivo_salon_soles = $this->reporte->ventas_efectivo($fecha_i, $fecha_f, $id_usuario);
    $ventas_efectivo_salon_dolares = $this->reporte->ventas_efectivo_d($fecha_i, $fecha_f, $id_usuario);

    $ventas_tarjeta_salon_soles = $this->reporte->ventas_tarjeta($fecha_i, $fecha_f, $id_usuario);
    $ventas_tarjeta_salon_dolares = $this->reporte->ventas_tarjeta_d($fecha_i, $fecha_f, $id_usuario);

    $ventas_trans_soles = $this->reporte->ventas_trans_plin($fecha_i, $fecha_f, $id_usuario);
    $ventas_trans_dolares = $this->reporte->ventas_trans_plin_d($fecha_i, $fecha_f, $id_usuario);

//    $ventas_trans_yape_soles = $this->reporte->ventas_trans_yape($fecha_i, $fecha_f, $id_usuario);
//    $ventas_trans_yape_dolares = $this->reporte->ventas_trans_yape($fecha_i, $fecha_f, $id_usuario);
//
//    $ventas_trans_otros_soles = $this->reporte->ventas_trans_otros($fecha_i, $fecha_f, $id_usuario);
//    $ventas_trans_otros_dolares = $this->reporte->ventas_trans_otros($fecha_i, $fecha_f, $id_usuario);

    $monto_caja_apertura_soles = $monto_caja_apertura->caja_cierre_apertura_mon_s;
    $monto_caja_apertura_dolares = $monto_caja_apertura->caja_cierre_apertura_mon_d;
    //FUNCIONES PARA VENTAS SALON
    $ventas_efectivo_soles  = $ventas_efectivo_salon_soles->total  ?? 0;
    $ventas_efectivo_dolares  = $ventas_efectivo_salon_dolares->total  ?? 0;

    $ventas_tarjeta_s  = $ventas_tarjeta_salon_soles->total  ?? 0;
    $ventas_tarjeta_d  = $ventas_tarjeta_salon_dolares->total  ?? 0;

    $ventas_trans_s  = $ventas_trans_soles->total  ?? 0 ;
    $ventas_trans_d  = $ventas_trans_dolares->total ?? 0 ;

//    $ventas_trans_yape_s  = $ventas_trans_yape_soles->total ?? 0 ;
//    $ventas_trans_yape_d  = $ventas_trans_yape_dolares->total ?? 0 ;
//
//    $ventas_trans_otros_s = $ventas_trans_otros_soles->total ?? 0 ;
//    $ventas_trans_otros_d = $ventas_trans_otros_dolares->total ?? 0 ;

    //FUNCIONES PARA DESGLOSAR SALIDA DE CAJA

    $ingresos_total_de_ventas_soles = $ventas_efectivo_soles + $ventas_tarjeta_s + $ventas_trans_s ;
    $ingresos_total_de_ventas_dolares = $ventas_efectivo_dolares + $ventas_tarjeta_d + $ventas_trans_d ;
    //INGRESOS TOTAL DE VENTAS
    $ingresos_totales_salon_soles = $ventas_efectivo_soles + $ventas_trans_s + $ventas_tarjeta_s;
    $ingresos_totales_salon_dolares = $ventas_efectivo_dolares + $ventas_trans_d + $ventas_tarjeta_d;
    //INGRESOS - EGRESOS
    $ingresos_generales_soles = $ventas_efectivo_soles + $ventas_trans_s + $ventas_tarjeta_s + $monto_caja_apertura_soles   ;
    $ingresos_generales_dolares = $ventas_efectivo_dolares + $ventas_trans_d  + $ventas_tarjeta_d + $monto_caja_apertura_dolares   ;

    $diferencia_soles = $monto_caja_apertura_soles  + $ventas_efectivo_soles   ;
    $diferencia_dolares = $monto_caja_apertura_dolares  + $ventas_efectivo_dolares   ;

    //INICIO - DATOS HABITACIONES

    $hab_x_cobrar = $this->reporte->habitacion_comandas_x_cobrar($fecha_i, $fecha_f);
    $hab_cobrado = $this->reporte->habitacion_comandas_cobradas($fecha_i, $fecha_f);

    $cobrar = $hab_x_cobrar->total ?? 0;
    $cobrado = $hab_cobrado->total ?? 0;

    //FIN - DATOS HABITACIONES

    /*Alinear a la izquierda para la cantidad y el nombre*/
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text('TOTAL DE VENTAS DEL DIA S/.' . '            S/ ' . $ingresos_total_de_ventas_soles . "\n");
    $printer->text('TOTAL DE VENTAS DEL DIA $' . '              $ ' . $ingresos_total_de_ventas_dolares . "\n");
    $printer->text('INGRESOS - EGRESOS' . '                     S/ ' . $ingresos_generales_soles . "\n");
    $printer->text("------------------------------------------------\n");
    $printer->text('VENTAS EN SALON' . '                        S/ ' . $ingresos_totales_salon_soles . "\n");
    $printer->text('PAGOS EFECTIVO' . '                         S/ ' . $ventas_efectivo_soles . "\n");
    $printer->text('PAGOS TARJETA' . '                          S/ ' . $ventas_tarjeta_s . "\n");
    $printer->text('PAGOS TRANSFERENCIA' . '               S/ ' . $ventas_trans_s . "\n");
//    $printer->text('PAGOS TRANSFERENCIA YAPE' . '               S/ ' . $ventas_trans_yape_s . "\n");
//    $printer->text('PAGOS TRANSFERENCIA OTROS' . '              S/ ' . $ventas_trans_otros_s . "\n");
    $printer->text("------------------------------------------------\n");
    $printer->text('HABITACIONES POR COBRAR' . '               S/ ' . $cobrar . "\n");
    $printer->text('HABITACION COBRADOS' . '                   S/ ' . $cobrado . "\n");
    $printer->text("------------------------------------------------\n");
    $printer->text('N° VENTAS SALON' . '                     ' . $n_ventas_salon->total . "\n");
    $printer->text("------------------------------------------------\n");
    $printer->text('TOTAL EFECTIVO' . '                      S/ ' . $diferencia_soles . "\n");

    /*Y a la derecha para el importe*/
    //$printer->setJustification(Printer::JUSTIFY_CENTER);
    //$printer->text($dp->venta_detalle_cantidad . "   x   " .$dp->venta_detalle_valor_unitario.'  S/ ' . $dp->venta_detalle_valor_total . "\n");

    /*
        Terminamos de imprimir
        los productos, ahora va el total
    */
    $printer->text("------------------------------------------------\n");
    /*
        AHORA VAMOS A LISTAR LOS EGRESOS DETALLADOS
    COMENTAR TODO DE EGRESO
    */
    /*$printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->setFont(Printer::FONT_B);
    $printer->setTextSize(2, 2);
    $printer->text("DETALLES DE EGRESOS" . "\n\n");
    $total_e = 0;
    $printer->setFont(Printer::FONT_A);
    $printer->setTextSize(1, 1);
    $fecha_ini_caja = $datitos->caja_fecha_apertura;
    if ($datitos->caja_fecha_cierre == NULL) {
        $fecha_fin_caja = date('Y-m-d H:i:s');
    } else {
        $fecha_fin_caja = $datitos->caja_fecha_cierre;
    }
    $listar_egresos = $this->reporte->listar_egresos_descripcion($fecha_ini_caja, $fecha_fin_caja);
    foreach ($listar_egresos as $dp) {*/

        /*Alinear a la izquierda para la cantidad y el nombre*/
        //$printer->setJustification(Printer::JUSTIFY_LEFT);
        //$printer->text($dp->egreso_descripcion . "\n");

        /*Y a la derecha para el importe*/
        //$printer->setJustification(Printer::JUSTIFY_RIGHT);
        //$printer->text('S/ ' . $dp->egreso_monto . "\n");
        //$total_e = $total_e + $dp->egreso_monto;

    /*}
    $printer->text("------------------------------------------------\n");
    $printer->text("               TOTAL: S/ " . $total_e . "\n");

    $printer->text("\n");
    $printer->text("\n");*/


/*Alinear a la izquierda para la cantidad y el nombre*/
//$printer->setJustification(Printer::JUSTIFY_LEFT);
//if($venta->venta_totalgratuita > 0){
//    $printer->text("   OP. GRAT: S/ ". $venta->venta_totalgratuita ."\n");
//
//}
//$printer->text("   OP. EXON: S/ ". $venta->venta_totalexonerada ."\n");
//if($venta->venta_totalinafecta > 0){
//    $printer->text("   OP. INAF: S/ ". $venta->venta_totalinafecta ."\n");
//}
//$printer->text("    OP. GRAV: S/ ". $venta->venta_totalgravada ."\n");
//$printer->text("    IGV: S/ ". $venta->venta_totaligv ."\n");
//if($venta->venta_icbper > 0){
//    $printer->text("    ICBPER: S/ ". $venta->venta_icbper ."\n");
//}
//
//$printer->text("    TOTAL: S/ ". $venta->venta_total ."\n");
//if($venta->venta_pago_cliente > 0){
//    $printer->setFont(Printer::FONT_B);
//    $printer->setTextSize(1,1);
//    $printer->text("    PAGÓ CON: S/ ". $venta->venta_pago_cliente ."\n");
//    $printer->text("    Vuelto: S/ ". $venta->venta_vuelto ."\n");
//}
//$printer->setFont(Printer::FONT_A);
//$printer->setTextSize(1,1);
//$printer->setJustification(Printer::JUSTIFY_CENTER);
//$printer->text(CantidadEnLetra($venta->venta_total) ."\n");
//$printer->text("------------------------------------------------" . "\n");
//if($venta->venta_tipo == "07" || $venta->venta_tipo == "08"){
//    if($venta->tipo_documento_modificar == "03"){
//        $documento = "BOLETA";
//    }else{
//        $documento = "FACTURA";
//    }
//    $printer->setJustification(Printer::JUSTIFY_LEFT);
//    $printer->text("DOCUMENTO:              $documento" . "\n");
//    $printer->text("SERIE MODIFICADA:       $venta->serie_modificar" . "\n");
//    $printer->text("CORRELATIVO MODIFICADO: $venta->correlativo_modificar" . "\n");
//    $printer->text("MOTIVO: $motivo->tipo_nota_descripcion" . "\n");
//}
//try{
//    $logo = EscposImage::load("$ruta_qr", false);
//    $printer->bitImage($logo);
//}catch(Exception $e){/*No hacemos nada si hay error*/}
//
//
///*
//	Podemos poner también un pie de página
//*/
//$printer->setJustification(Printer::JUSTIFY_CENTER);
//$printer->setFont(Printer::FONT_C);
//$printer->setTextSize(1,1);
//$printer->text("BIENES TRANSFERIDOS EN LA AMAZONIA PARA" . "\n");
//$printer->text("SER CONSUMIDOS EN LA MISMA" . "\n");
//$printer->setJustification(Printer::JUSTIFY_CENTER);
//$printer->text("------------------------------------------------" . "\n");
//$printer->setFont(Printer::FONT_B);
//$printer->setTextSize(1,1);
//$printer->text("Digitaliza tu negocio, sistemas a medida" . "\n");
//$printer->text("con Facturación Electrónica... Whatsapp" . "\n");
//$printer->text("Business +51925642418 / bufeotec.com" . "\n");


/*Alimentamos el papel 3 veces*/
$printer->feed(2);

/*
	Cortamos el papel. Si nuestra impresora
	no tiene soporte para ello, no generará
	ningún error
*/
$printer->cut();

/*
	Por medio de la impresora mandamos un pulso.
	Esto es útil cuando la tenemos conectada
	por ejemplo a un cajón
*/
$printer->pulse();

/*
	Para imprimir realmente, tenemos que "cerrar"
	la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
*/
$printer->close();

?>

