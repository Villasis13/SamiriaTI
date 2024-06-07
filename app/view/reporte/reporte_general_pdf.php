<?php
//Llamamos a la libreria
require_once 'app/view/pdf/pdf_base.php';
//creamos el objeto
$pdf=new PDF();
//Añadimos una pagina
$pdf->AddPage();
//Define el marcador de posición usado para insertar el número total de páginas en el documento
$pdf->AliasNbPages();
$pdf->SetFont('Arial','U',10);
//Mover
$pdf->Cell(190,6,'Reporte General desde'." ".date('d-m-Y', strtotime($fecha_i))." ". 'hasta'." ".date('d-m-Y', strtotime($fecha_f)),0,1,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(190,6,'USUARIO : '.$usuario_->persona_nombre.' '.$usuario_->persona_apellido_paterno,0,1,'C');
$pdf->Ln(2);
$total = 0;
$apertura = 1;
$fecha_ini_caja = $datitos->caja_cierre_apertura_datetime;
if($datitos->caja_fecha_cierre==NULL){
    $fecha_fin_caja = date('Y-m-d H:i:s');
}else{
    $fecha_fin_caja = $datitos->caja_cierre_cierre_datetime;
}
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

$ventas_trans_yape_soles = $this->reporte->ventas_trans_yape($fecha_i, $fecha_f, $id_usuario);
$ventas_trans_yape_dolares = $this->reporte->ventas_trans_yape($fecha_i, $fecha_f, $id_usuario);

$ventas_trans_otros_soles = $this->reporte->ventas_trans_otros($fecha_i, $fecha_f, $id_usuario);
$ventas_trans_otros_dolares = $this->reporte->ventas_trans_otros($fecha_i, $fecha_f, $id_usuario);

//INICION
//monto de apertura de caja
$monto_caja_apertura_soles = $monto_caja_apertura->caja_cierre_apertura_mon_s ?? 0;
$monto_caja_apertura_dolares = $monto_caja_apertura->caja_cierre_apertura_mon_d;
//FUNCIONES PARA VENTAS SALON
$ventas_efectivo_soles  = $ventas_efectivo_salon_soles->total ?? 0;
$ventas_efectivo_dolares  = $ventas_efectivo_salon_dolares->total;

$ventas_tarjeta_s  = $ventas_tarjeta_salon_soles->total ?? 0;
$ventas_tarjeta_d  = $ventas_tarjeta_salon_dolares->total;

$ventas_trans_s  = $ventas_trans_soles->total ?? 0;
$ventas_trans_d  = $ventas_trans_dolares->total;

$ventas_trans_yape_s  = $ventas_trans_yape_soles->total ?? 0;
$ventas_trans_yape_d  = $ventas_trans_yape_dolares->total;

$ventas_trans_otros_s = $ventas_trans_otros_soles->total ?? 0;
$ventas_trans_otros_d = $ventas_trans_otros_dolares->total;

//FUNCIONES PARA DESGLOSAR SALIDA DE CAJA

$ingresos_total_de_ventas_soles = $ventas_efectivo_soles + $ventas_tarjeta_s + $ventas_trans_s + $ventas_trans_yape_s + $ventas_trans_otros_s ;
$ingresos_total_de_ventas_dolares = $ventas_efectivo_dolares + $ventas_tarjeta_d + $ventas_trans_d + $ventas_trans_yape_d + $ventas_trans_otros_d ;
//INGRESOS TOTAL DE VENTAS
$ingresos_totales_salon_soles = $ventas_efectivo_soles + $ventas_trans_s + $ventas_trans_yape_s + $ventas_trans_otros_s + $ventas_tarjeta_s;
$ingresos_totales_salon_dolares = $ventas_efectivo_dolares + $ventas_trans_d + $ventas_trans_yape_d + $ventas_trans_otros_d + $ventas_tarjeta_d;
//INGRESOS - EGRESOS
$ingresos_generales_soles = $ventas_efectivo_soles + $ventas_trans_s + $ventas_trans_yape_s + $ventas_trans_otros_s  + $ventas_tarjeta_s + $monto_caja_apertura_soles   ;
$ingresos_generales_dolares = $ventas_efectivo_dolares + $ventas_trans_d + $ventas_trans_yape_d + $ventas_trans_otros_d  + $ventas_tarjeta_d + $monto_caja_apertura_dolares   ;

$diferencia_soles = $monto_caja_apertura_soles  + $ventas_efectivo_soles;
$diferencia_dolares = $monto_caja_apertura_dolares  + $ventas_efectivo_dolares;
//FIN


$pdf->SetFont('Arial','BU',12);
$pdf->Cell(190,5,'REPORTE GENERAL',0,1,'C',0);
$pdf->Ln();
$pdf->SetFont('Arial','B',12);
$pdf->Cell(100,6,'TOTAL DE VENTAS DEL DIA',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$ingresos_total_de_ventas_soles ?? 0,0,1,'R',0);
$pdf->Cell(100,6,'INGRESOS - EGRESOS',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$ingresos_generales_soles ?? 0,0,1,'R',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(100,6,'- Apertura de Caja',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$monto_caja_apertura_soles ?? 0,0,1,'R',0);
//$pdf->Cell(100,6,'- Ingresos caja chica',0,0,'L',0);
//$pdf->Cell(18,6,'S/. '.$ingreso_caja_chica ?? 0,0,1,'R',0);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(100,6,'VENTAS EN SALON',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$ingresos_totales_salon_soles ?? 0,0,1,'R',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(100,6,'- Pagos Efectivo',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$ventas_efectivo_soles ?? 0,0,1,'R',0);
$pdf->Cell(100,6,'- Pagos Tarjeta',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$ventas_tarjeta_s ?? 0,0,1,'R',0);
$pdf->Cell(100,6,'- Pagos Transferencia Plin',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$ventas_trans_s ?? 0,0,1,'R',0);
$pdf->Cell(100,6,'- Pagos Transferencia Yape',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$ventas_trans_yape_s ?? 0,0,1,'R',0);
$pdf->Cell(100,6,'- Pagos Transferencia Otros',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$ventas_trans_otros_s ?? 0,0,1,'R',0);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(100,6,'N° VENTAS SALON',0,0,'L',0);
$pdf->Cell(18,6,'N° '.$n_ventas_salon->total ?? 0,0,1,'R',0);
//$pdf->SetFont('Arial','B',12);
//$pdf->Cell(100,6,'- Orden de Compras',0,0,'L',0);
//$pdf->Cell(18,6,'S/. '.$orden_pedido_total ?? 0,0,1,'C',0);
//$pdf->Cell(100,6,'TOTAL DE EGRESOS',0,0,'L',0);
//$pdf->Cell(18,6,'S/. '.$egresos_totales ?? 0,0,1,'R',0);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(100,6,'TOTAL EFECTIVO',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$diferencia_soles ?? 0,0,1,'R',0);

$pdf->Ln(2);
//$pdf->SetFont('Arial','BU',12);
//
//$pdf->Cell(140,10,'LISTADO DE EGRESOS',0,2,'C');
//$pdf->SetFillColor(232,232,232);
//$pdf->SetFont('Arial','',12);
//$pdf->Cell(12);
//$pdf->Cell(38,6,'Fecha',1,0,'C',1);
//$pdf->Cell(100,6,'Concepto',1,0,'C',1);
//$pdf->Cell(35,6,'Total',1,0,'C',1);
//$pdf->Ln();
//$egresos_servicios = 0;
//$pdf->SetFont('Arial','',10);
//foreach ($listar_egresos as  $le){
//    $pdf->Cell(12);
//    $pdf->CellFitSpace(38,6,$le->egreso_fecha_registro,1,0,'C',0);
//    $pdf->CellFitSpace(100,6,$le->egreso_descripcion,1,0,'C',0);
//    $pdf->CellFitSpace(35,6,'S/. '.$le->egreso_monto,1,1,'C',0);
//    $egresos_servicios = $egresos_servicios +$le->egreso_monto;
//
//}
//$pdf->SetFont('Arial','',12);
//$pdf->Cell(118,10,'TOTAL EGRESOS',0,0,'C',0);
//$pdf->Cell(30,10,'S/. '.$egresos_servicios,0,1,'R',0);
$pdf->Ln(2);
$pdf->SetFont('Arial','BU',12);
$pdf->Cell(30);
$pdf->Cell(130,10,'CANTIDAD DE VENTAS POR PRODUCTOS',0,1,'C');
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','',10);
$pdf->Cell(18);
$pdf->Cell(80,5,'PRODUCTO',1,0,'C',1);
$pdf->Cell(44,5,'RANGO DE FECHAS',1,0,'C',1);
$pdf->Cell(40,5,'CANTIDAD VENDIDA',1,0,'C',1);
$pdf->Ln();
$cantidad_vendida = 0;
$pdf->SetFont('Arial','',7);
$productos_ = $this->reporte->reporte_productos_2($fecha_i,$fecha_f, $id_usuario);
foreach ($productos_ as $p){
    $fecha_ini_caja = $datitos->caja_fecha_apertura;
    if($datitos->caja_fecha_cierre==NULL){
        $fecha_fin_caja = date('Y-m-d H:i:s');
    }else{
        $fecha_fin_caja = $datitos->caja_fecha_cierre;
    }
    $pdf->Cell(18);
    $pdf->CellFitSpace(80,5,$p->producto_nombre,1,0,'C',0);
    $pdf->CellFitSpace(44,5,$fecha_i.' / '.$fecha_f,1,0,'C',0);
    $pdf->CellFitSpace(40,5,$p->total,1,1,'C',0);
    $cantidad_vendida = $cantidad_vendida +$p->total;

}
$pdf->SetFont('Arial','',12);
$pdf->Cell(118,10,'TOTAL DE PRODUCTOS VENDIDOS',0,0,'C',0);
$pdf->Cell(30,10,$cantidad_vendida,0,1,'R',0);

$pdf->Ln();

//REGISTRO PARA INGRESAR NUEVA PAGINA
$pdf->AddPage();
$pdf->SetFont('Arial','BU',12);
$pdf->Cell(30);
$pdf->Cell(130,6,'REPORTE DE PEDIDOS PENDIENTES DE PAGO',0,1,'C');
$pdf->Ln(2);
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','',9);
$pdf->Cell(115,6,'Hab. / Cant. / Pedido / N° Comanda',1,0,'C',1);
$pdf->Cell(30,6,'Fecha de Comanda',1,0,'C',1);
$pdf->Cell(28,6,'Envio Habitación',1,0,'C',1);
$pdf->Cell(20,6,'Monto',1,1,'C',1);
$pdf->SetWidths(array(115,30,28,20));
$cantidad_vendida=0;
foreach ($hab_x_cobrar as $p){
    $pdf->SetFont('Arial','',7);
    $pdf->Row(array($p->habitacion_nro.' | '.$p->comanda_detalle_cantidad.' | '.$p->producto_nombre.' | '.$p->comanda_correlativo,date('d-m-Y H:i:s', strtotime($p->comanda_detalle_fecha_registro)),date('d-m-Y H:i:s', strtotime($p->rack_detalle_datetime)),'S/. '.$p->comanda_detalle_total));
//    $pdf->CellFitSpace(115,5,$p->habitacion_nro.' | '.$p->comanda_detalle_cantidad.' | '.$p->producto_nombre.' | '.$p->comanda_correlativo,1,0,'L',0);
//    $pdf->CellFitSpace(30,5,date('d-m-Y H:i:s', strtotime($p->comanda_detalle_fecha_registro)),1,0,'C',0);
//    $pdf->CellFitSpace(28,5,date('d-m-Y H:i:s', strtotime($p->rack_detalle_datetime)),1,0,'C',0);
//    $pdf->CellFitSpace(20,5,'S/. '.$p->comanda_detalle_total,1,1,'C',0);
    $cantidad_vendida = $cantidad_vendida +$p->comanda_detalle_total;

}
$pdf->SetFont('Arial','',12);
$pdf->Cell(118,10,'TOTAL PENDIENTE DE COBRAR EN HABITACIONES',0,0,'C',0);
$pdf->Cell(30,10,'S/. '.$cantidad_vendida,0,1,'R',0);


$pdf->Ln(2);
$pdf->SetFont('Arial','BU',12);
$pdf->Cell(30);
$pdf->Cell(130,10,'REPORTE DE PEDIDOS PAGADOS',0,1,'C');
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','',9);
$pdf->Cell(23,6,'Comprobante',1,0,'C',1);
$pdf->Cell(26,6,'Tipo Pago',1,0,'C',1);
$pdf->Cell(25,6,'Fecha Venta',1,0,'C',1);
$pdf->Cell(101,6,'Hab. / Cant. / Pedido / N° Comanda',1,0,'C',1);
$pdf->Cell(20,6,'Monto',1,1,'C',1);
$pdf->SetWidths(array(23,26,25,101,20));
$cantidad_vendida_=0;
foreach ($hab_cobrado as $p){
    $pdf->SetFont('Arial','',6);
    $pdf->Row(array($p->venta_serie.' - '.$p->venta_correlativo,$p->tipo_pago_nombre,$p->venta_fecha,$p->habitacion_nro.' | '.$p->comanda_detalle_cantidad.' | '.$p->producto_nombre.' | '.$p->comanda_correlativo,'S/.'.$p->rack_detalle_subtotal));
//    $pdf->CellFitSpace(23,5,$p->venta_serie.' - '.$p->venta_correlativo,1,0,'C',0);
//    $pdf->CellFitSpace(24,5,$p->tipo_pago_nombre,1,0,'C',0);
//    $pdf->CellFitSpace(25,5,$p->venta_fecha,1,0,'C',0);
//    $pdf->CellFitSpace(101,5,$p->habitacion_nro.' | '.$p->comanda_detalle_cantidad.' | '.$p->producto_nombre.' | '.$p->comanda_correlativo,1,0,'C',0);
//    $pdf->CellFitSpace(20,5,'S/.'.$p->rack_detalle_subtotal,1,1,'C',0);
    $cantidad_vendida_ = $cantidad_vendida_ +$p->rack_detalle_subtotal;

}
$pdf->SetFont('Arial','',12);
$pdf->Cell(118,10,'TOTAL COBRADO EN RECEPCION',0,0,'C',0);
$pdf->Cell(30,10,'S/. '.$cantidad_vendida_,0,1,'R',0);


$pdf->Ln();

$pdf->Output();
?>