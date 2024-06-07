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
$printer->text("REPORTE DE PRODUCTOS" . "\n");
$printer->setFont(Printer::FONT_A);
$printer->setTextSize(1,1);
//$printer->text("$dato_pago->empresa_nombre" . "\n");
$printer->text("DEL DIA : " . "$nueva_fecha_i AL $nueva_fecha_f\n");//AQUI IRIA LA FECHA
//$printer->text("$empresa->empresa_domiciliofiscal" . "\n");
//$printer->text("CAL. YAVARI NRO. 1360" . "\n");
//$printer->text("LORETO - MAYNAS - PUNCHANA" . "\n");

//$printer->setFont(Printer::FONT_B);
//$printer->setTextSize(2,2);
//$printer->text("$venta_tipo" . "\n");
//$printer->text("$venta->venta_serie-$venta->venta_correlativo" . "\n\n");
# Vamos a alinear al centro lo próximo que imprimamos
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("------------------------------------------------" . "\n");
/*
	Ahora vamos a imprimir los
	productos
*/

# Para mostrar el total
/*	AHORA VAMOS A LISTAR LOS PRODUCTOS
*/
$printer->setFont(Printer::FONT_B);
$printer->setTextSize(2,2);
$printer->text("PRODUCTOS VENDIDOS" . "\n\n");
$printer->setFont(Printer::FONT_A);
$printer->setTextSize(1,1);
//LA FUNCION DE reporte_productos_2 trabaja con el usuario pero la reporte_productos_3 trabaja solo con fechas
$productos_ = $this->reporte->reporte_productos_3($fecha_i,$fecha_f);
$printer->text('Producto' . '               Cant. Vendida  ' . 'Sin Cobrar'."\n\n");
foreach ($productos_ as $dp) {
    $pedidos_sin_cancelar=$this->reporte->pedidos_sin_pagar($fecha_i,$fecha_f,$dp->id_producto);
    $sin_cobrar=$pedidos_sin_cancelar->total ?? 0;
    /*Alinear a la izquierda para la cantidad y el nombre*/
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text($dp->producto_nombre . "\n");

    /*Y a la derecha para el importe*/
    $printer->setJustification(Printer::JUSTIFY_RIGHT);
    $printer->text($dp->total.'              ');
    $printer->text($sin_cobrar.'   ' . "\n");
    $cantidad_vendida = $cantidad_vendida + $dp->total;
    $cantidad_debe = $cantidad_debe + $sin_cobrar;
}
$printer->text("------------------------------------------------\n");
$printer->text("                             TOTAL VENDIDO:" . $cantidad_vendida . "\n");
$printer->text("                             TOTAL SIN COBRAR:" . $cantidad_debe . "\n");
$printer->text("\n");
$printer->text("\n");


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

