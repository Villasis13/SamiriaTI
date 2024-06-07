<?php
/**
 * Created by PhpStorm
 * User: LuisSalazar
 * Date: 13/12/2020
 * Time: 10:00 p. m.
 */
//LLAMAMOS A LA LIBRERIA que está en la vista de report
//require 'app/view/report/pdf_base.php';
//llamamos a la clase pdf_base.php que esta en la vista sellgas
//require_once 'pdf_base_ticket.php';
//se llama directo a la libreria
require 'app/view/pdf/fpdf/fpdf.php';
//require 'app/view/report/pdf_base.php';
// creamos el objeto
$pdf = new FPDF('P');
//Define el marcador de posición usado para insertar el número total de páginas en el documento
$pdf->AddPage();
//CABECERA DEL ARCHIVO
//Logo
$pdf->Image(_SERVER_.'media/logo/samiria.jpg',30,6, '70', '25', 'jpg');
$pdf->Ln(5);
$pdf->SetFillColor(220,220,220);
$pdf->SetFont('Helvetica','',9);
//$pdf->Cell(60,4, "$dato_pago->empresa_nombrecomercial",0,1,'C',0);
//$pdf->Cell(60,4,"$dato_venta->empresa_nombre",0,1,'C');
//$pdf->Cell(60,4, "EMPRESA DE SERVICIOS PARA EL ",0,1,'C',0);
//$pdf->Cell(60,4, "DESARROLLO HUMANO",0,1,'C',0);
$pdf->SetFont('Helvetica','',10);
$pdf->Cell(120,0,'','',0);
$pdf->Cell(60,0,'','T',1,'R');
$pdf->Cell(170,8, "RUC $dato_venta->empresa_ruc",0,1,'R',0);
//$pdf->Cell(60,4, "DESARROLLO HUMANO",0,1,'C',0);
$pdf->Cell(120,8,"",0,0,'R',0);
$pdf->Cell(60,8,"$tipo_comprobante",0,1,'R',1);
$pdf->Cell(165,8,"$serie_correlativo",0,1,'R',0);
$pdf->Cell(120,0,'','',0);
$pdf->Cell(60,0,'','T',1,'R');
$pdf->SetFont('Helvetica','B',9);
//$pdf->Cell(60,4,"$dato_pago->empresa_domiciliofiscal",0,1,'C');
$pdf->Cell(120,4,"SAMIRIA JUNGLE HOTEL EIRL",0,1,'C');
$pdf->SetFont('Helvetica','',7);
$pdf->Cell(120,4,"JR. RICARDO PALMA N° 159 - URB. JARDIN PAZ",0,1,'C');
$pdf->Ln(3);
$pdf->Cell(180,0,'','T',1,'R');
$pdf->Ln(3);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(15,4, "Fecha:",0,0,'L',false);
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(60,4, "$fecha_hoy",0,0,'L',false);
$pdf->SetFont('Helvetica','B',8);
if($dato_venta->venta_mostrar_tp==1){
    $pdf->Cell(15,4, "Pago:",0,0,'L',false);
    $pdf->SetFont('Helvetica','',7);
    $pdf->Cell(60,4, "$dato_venta->tipo_pago_nombre",0,1,'L',false);

}else{
    $pdf->Ln();
}
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(15,4,"$dnni:",0,0,'L');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(60,4, "$documento",0,0,'L',false);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(15,4,"Cliente:",0,0,'L');
if($dato_venta->id_tipodocumento != 4){
    $pdf->SetFont('Helvetica','',7);
    $pdf->MultiAlignCell(90,4, "$dato_venta->cliente_nombre",0,1,'L',false);
}else{
    $pdf->SetFont('Helvetica','',7);
    $pdf->MultiAlignCell(90,4, "$dato_venta->cliente_razonsocial",0,1,'L',false);
}
$pdf->SetFillColor(180,180,180);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(15,6,"Dirección:",0,0,'L');
$pdf->SetFont('Helvetica','',7);
$pdf->MultiCell(180,6,"$dato_venta->cliente_direccion",0,1,'');
$pdf->Cell(180,0,'','T',1,'R');
$pdf->Ln(3);
$pdf->SetFont('Helvetica','B',7);
$pdf->Cell(10, 10, 'ITEM', 1,'','C',1);
$pdf->Cell(15, 10, 'CANT',1,0,'C',1);
$pdf->Cell(100, 10, 'DESCRIPCION', 1,'','C',1);
$pdf->Cell(20, 10, 'P.U.',1,0,'C',1);
$pdf->Cell(15, 10, 'IGV',1,0,'C',1);
$pdf->Cell(20, 10, 'P.VENTA',1,1,'C',1);
//PRODUCTOS
$aa=1;
foreach ($detalle_venta as $f){
    $pdf->SetFont('Helvetica', '', 7);
    $pdf->Cell(10, 5, "$aa", 1,'','C');
    $pdf->Cell(15, 5, number_format(round("$f->venta_detalle_cantidad",2), 2, ',', ' '), 1,'','C');
    $pdf->CellFitSpace(100,5,"$f->venta_detalle_nombre_producto",1,0,'L');
    $pdf->Cell(20, 5, number_format(round("$f->venta_detalle_precio_unitario",2), 2, ',', ' '),1,0,'C');
    $pdf->Cell(15, 5, "0.00", 1, 0, 'C');
    $pdf->Cell(20, 5, number_format(round("$f->venta_detalle_valor_total",2), 2, ',', ' '),1,1,'C');
    $a++;
}
$pdf->Ln(7);
$pdf->Cell(70,3,'','T',0,'R');
$pdf->Cell(70,0,'',0,0,'L');
$pdf->Cell(40,3,'','T',1,'R');
$pdf->SetFont('Helvetica', '', 8);
$pdf->Cell(70, 3, "$montoLetras", 0,0,'L');
$pdf->Cell(110, 1, "Descuento: $dato_venta->simbolo $dato_venta->venta_totaldescuento", 0,1,'R');
$pdf->Ln(7);
$pdf->Cell(70,1,'BIENES TRANSFERIDOS EN LA',0,0,'R');
$pdf->Cell(110, 1, "Op.Grat: $dato_venta->simbolo $dato_venta->venta_totalgratuita", 0,1,'R');
$pdf->Ln(3);
$pdf->Cell(70,1,'AMAZONIA PARA SER CONSUMIDOS',0,0,'R');
$pdf->Cell(110, 1, "Op.Exon: $dato_venta->simbolo $dato_venta->venta_totalexonerada", 0,1,'R');
$pdf->Ln(3);
$pdf->Cell(70,1,'EN LA MISMA',0,0,'R');
$pdf->Cell(110, 1, "Op.Inaf: $dato_venta->simbolo $dato_venta->venta_totalinafecta", 0,1,'R');
$pdf->Ln(3);
$pdf->Cell(180, 1, "Op.Grav: $dato_venta->simbolo $dato_venta->venta_totalgravada", 0,1,'R');
$pdf->Ln(3);
$pdf->Cell(180, 1, "IGV: $dato_venta->simbolo $dato_venta->venta_totaligv", 0,1,'R');
$pdf->Ln(3);
$pdf->SetFont('Helvetica', '', 7);
$pdf->Cell(70,0,'Bufeo Tec Company - Digitalízate',0,0,'L');
$pdf->SetFont('Helvetica', 'B', 9);
$pdf->Cell(110, 1, "TOTAL: $dato_venta->simbolo $dato_venta->venta_total", 0,'1','R');
$pdf->SetFont('Helvetica', '', 7);
$pdf->Ln(3);
$pdf->Cell(60,0,'bufeotec.com',0,1,'L');
$pdf->Ln(3);
$pdf->Cell(70,3,'','T',0,'R');
$pdf->Cell(70,0,'',0,0,'L');
$pdf->Cell(40,3,'','T',1,'R');

// PIE DE PAGINA
$pdf->Ln();
if($dato_venta->venta_observaciones!=""){
$pdf->Cell(180,3,'','T',1,'R');
$pdf->MultiCell(180,8,"OBSERVACIONES: $dato_venta->venta_observaciones",0,1,'');
$pdf->Cell(180,3,'','T',1,'R');
}


if(count($detalle_venta)==1){
    $pdf->Image("$ruta_qr",'8','100', '20', '20', '', '');
}elseif(count($detalle_venta)==2){
    $pdf->Image("$ruta_qr",'5','105', '20', '20', '', '');
}elseif(count($detalle_venta)==3){
    $pdf->Image("$ruta_qr",'5','110', '20', '20', '', '');
}elseif(count($detalle_venta)==4){
    $pdf->Image("$ruta_qr",'5','115', '20', '20', '', '');
}elseif(count($detalle_venta)==5){
    $pdf->Image("$ruta_qr",'5','120', '20', '20', '', '');
}elseif(count($detalle_venta)==6){
    $pdf->Image("$ruta_qr",'5','125', '20', '20', '', '');
}elseif(count($detalle_venta)==7){
    $pdf->Image("$ruta_qr",'5','130', '20', '20', '', '');
}elseif(count($detalle_venta)==8){
    $pdf->Image("$ruta_qr",'5','135', '20', '20', '', '');
}elseif(count($detalle_venta)==9){
    $pdf->Image("$ruta_qr",'5','140', '20', '20', '', '');
}elseif(count($detalle_venta)==10){
    $pdf->Image("$ruta_qr",'5','145', '20', '20', '', '');
}elseif(count($detalle_venta)==11){
    $pdf->Image("$ruta_qr",'5','150', '20', '20', '', '');
}else{
    $pdf->Image("$ruta_qr",'5','155', '20', '20', '', '');
}
$pdf->Ln(3);
//$ruta_guardado = 'media/comprobantes/'."$serie_correlativo-" .date('Ymd').'.pdf';
$pdf->Output();
?>
