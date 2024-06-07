<?php
//Llamamos a la libreria
require_once 'app/view/pdf/pdf_base.php';
//creamos el objeto
$pdf=new PDF();
//Añadimos una pagina
$pdf->AddPage();
//Define el marcador de posición usado para insertar el número total de páginas en el documento
$pdf->AliasNbPages();
$pdf->SetFont('Arial','U',14);
//Mover
$pdf->Cell(30);
$pdf->Cell(130,6,'Reporte General desde'." ".$fecha_i." ". 'hasta'." ".$fecha_f,0,1,'C');
$pdf->Ln(2);
$pdf->SetFont('Arial','BU',12);
$pdf->Cell(30);
$pdf->Cell(130,6,'REPORTE DE PEDIDOS PENDIENTES DE CANCELAR',0,1,'C');
$pdf->Ln(2);
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','',9);
$pdf->Cell(115,6,'Hab. / Cant. / Pedido / N° Comanda',1,0,'C',1);
$pdf->Cell(30,6,'Fecha de Comanda',1,0,'C',1);
$pdf->Cell(28,6,'Envio Habitación',1,0,'C',1);
$pdf->Cell(20,6,'Monto',1,1,'C',1);
$cantidad_vendida=0;
foreach ($hab_x_cobrar as $p){
    $pdf->SetFont('Arial','',7);
    $pdf->CellFitSpace(115,5,$p->habitacion_nro.' | '.$p->comanda_detalle_cantidad.' | '.$p->producto_nombre.' | '.$p->comanda_correlativo,1,0,'L',0);
    $pdf->CellFitSpace(30,5,date('d-m-Y H:i:s', strtotime($p->comanda_detalle_fecha_registro)),1,0,'C',0);
    $pdf->CellFitSpace(28,5,date('d-m-Y H:i:s', strtotime($p->rack_detalle_datetime)),1,0,'C',0);
    $pdf->CellFitSpace(20,5,'S/. '.$p->comanda_detalle_total,1,1,'C',0);
    $cantidad_vendida = $cantidad_vendida +$p->comanda_detalle_total;

}
$pdf->SetFont('Arial','',12);
$pdf->Cell(118,10,'TOTAL PENDIENTE DE COBRAR',0,0,'C',0);
$pdf->Cell(30,10,'S/. '.$cantidad_vendida,0,1,'R',0);


$pdf->Ln(2);
$pdf->SetFont('Arial','BU',12);
$pdf->Cell(30);
$pdf->Cell(130,10,'REPORTE DE PEDIDOS CANCELADOS',0,1,'C');
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','',9);
$pdf->Cell(27,6,'Comprobante',1,0,'C',1);
$pdf->Cell(27,6,'Fecha Venta',1,0,'C',1);
$pdf->Cell(119,6,'Hab. / Cant. / Pedido / N° Comanda',1,0,'C',1);
$pdf->Cell(20,6,'Monto',1,1,'C',1);
$cantidad_vendida_=0;
foreach ($hab_cobrado as $p){
    $pdf->SetFont('Arial','',7);
    $pdf->CellFitSpace(27,5,$p->venta_serie.' - '.$p->venta_correlativo,1,0,'C',0);
    $pdf->CellFitSpace(27,5,$p->venta_fecha,1,0,'C',0);
    $pdf->CellFitSpace(119,5,$p->habitacion_nro.' | '.$p->comanda_detalle_cantidad.' | '.$p->producto_nombre.' | '.$p->comanda_correlativo,1,0,'C',0);
    $pdf->CellFitSpace(20,5,'S/.'.$p->rack_detalle_subtotal,1,1,'C',0);
    $cantidad_vendida_ = $cantidad_vendida_ +$p->rack_detalle_subtotal;

}
$pdf->SetFont('Arial','',12);
$pdf->Cell(118,10,'TOTAL COBRADO',0,0,'C',0);
$pdf->Cell(30,10,'S/. '.$cantidad_vendida_,0,1,'R',0);


$pdf->Ln();
$pdf->Output();
?>