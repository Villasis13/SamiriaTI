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
$pdf->Cell(15,4, "Pago:",0,0,'L',false);
$pdf->SetFont('Helvetica','',7);
$pdf->Cell(60,4, "$tipo_pago",0,1,'L',false);
if($dato_venta->id_tipodocumento != 4){
    $pdf->SetFont('Helvetica','B',8);
    $pdf->Cell(15,4,"DNI:",0,0,'L');
}else{
    $pdf->SetFont('Helvetica','B',8);
    $pdf->Cell(15,4,"RUC:",0,0,'L');
}
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
$pdf->SetWidths(array(10,15,100,20,15,20));
//PRODUCTOS
$aa=1;
$filas_tot = 0;
foreach ($detalle_venta as $f){
    //INICIO - MODIFICACION DE LOS DETALLES
    $precio_uni = number_format(round("$f->venta_detalle_precio_unitario",3), 3, '.', ' ');
    $precio_total = number_format(round("$f->venta_detalle_valor_total",2), 2, '.', ' ');
    $pdf->Row(array($aa, $f->venta_detalle_cantidad ,$f->venta_detalle_nombre_producto,$precio_uni, '0.00', $precio_total));
    //FIN - MODIFICACION DE LOS DETALLES

    $cant = strlen($f->venta_detalle_nombre_producto);
    $filas = ceil($cant / 65);
    if($filas==0){$filas=1;}
    $filas_tot+=$filas;
    $he = 4 * $filas;
    /*$pdf->SetFont('Helvetica', '', 7);
    $pdf->Cell(10, $he, "$aa", 1,'','C');
    //$pdf->Cell(15, 20, number_format(round("$f->venta_detalle_cantidad ",2), 2, ',', ' '), 1,'','C');
    $pdf->Cell(15, $he, "$cant $filas $he", 1, 0,'C');
    $pdf->MultiAlignCell(100,4,"$f->venta_detalle_nombre_producto",1,0,'L');
    //$pdf->CellFitSpace(100,5,"$f->venta_detalle_nombre_producto",1,0,'L');
    $pdf->Cell(20, $he, number_format(round("$f->venta_detalle_precio_unitario",2), 2, ',', ' '),1,0,'C');
    $pdf->Cell(15, $he, "0.00", 1, 0, 'C');
    $pdf->Cell(20, $he, number_format(round("$f->venta_detalle_valor_total",2), 2, ',', ' '),1,1,'C');*/
    $aa++;
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

if($filas_tot<4) {
    $hei = 96 + (5 * $filas_tot);
}else{
    $hei = 94 + (5 * $filas_tot);
}

$pdf->Image("$ruta_qr",'8',$hei, '20', '20', '', '');

$pdf->Ln(3);
//$ruta_guardado = 'media/comprobantes/'."$serie_correlativo-" .date('Ymd').'.pdf';
$pdf->Output();
?>
