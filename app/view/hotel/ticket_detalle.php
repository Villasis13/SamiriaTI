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
$pdf->SetFont('Helvetica','',10);
$pdf->Cell(120,0,'','',0);
$pdf->Cell(60,0,'','T',1,'R');
$pdf->Cell(170,8, "RUC  	20607969877",0,1,'R',0);
//$pdf->Cell(60,4, "DESARROLLO HUMANO",0,1,'C',0);
$pdf->Cell(120,8,"",0,0,'R',0);
$pdf->Cell(60,8,"DETALLE DE LA CUENTA",0,1,'C',1);
$pdf->Cell(165,8,"",0,1,'R',0);
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
$pdf->Cell(17,4,"HUÉSPED:",0,0,'L');
$pdf->SetFont('Helvetica','',7);
$pdf->CellFitSpace(100,4, ($cliente->id_tipodocumento==4)?$cliente->cliente_razonsocial:$cliente->cliente_nombre,0,0,'L',false);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(10,4,$cliente->tipo_documento_abr.": ",0,0,'L');
$pdf->SetFont('Helvetica','',7);
$pdf->Cell(60,4, $cliente->cliente_numero,0,1,'L',false);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(33,4,"TIPO DE HABITACIÓN:",0,0,'L');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(84,4,"$data_rack->habitacion_tipo_nombre",0,0,'');

$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(30,4,"N° DE HABITACIÓN:",0,0,'L');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(30,4,"$data_rack->habitacion_nro",0,1,'L');

$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(15,4,"DESDE :",0,0,'L');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(30,4,$this->validar->obtener_nombre_fecha($data_rack->rack_in,"Date","Date","es"),0,0,'L');

$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(15,4,"HASTA :",0,0,'L');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(30,4,$this->validar->obtener_nombre_fecha($data_rack->rack_out,"Date","Date","es"),0,0,'L');

$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(15,4,"NOCHES :",0,0,'L');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(12,4,$data_rack->rack_noches,0,0,'L');

$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(20,4,"DESAYUNOS :",0,0,'L');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(30,4,$data_rack->rack_desayuno,0,1,'L');
$pdf->Ln(3);
$pdf->Cell(180,0,'','T',1,'R');
$pdf->Ln(3);
$pdf->SetFillColor(180,180,180);
$pdf->SetFont('Helvetica','B',7);
$pdf->Cell(10, 10, 'ITEM', 1,'','C',1);
$pdf->Cell(15, 10, 'CANT',1,0,'C',1);
$pdf->Cell(115, 10, 'DESCRIPCION', 1,'','C',1);
$pdf->Cell(20, 10, 'P.U.',1,0,'C',1);
$pdf->Cell(20, 10, 'P.VENTA',1,1,'C',1);
$pdf->SetWidths(array(10,15,115,20,20));
//PRODUCTOS
$aa=1;
$filas_tot = 0;
$total=0;
$total_p=0;
foreach ($detalle_rack as $d) {
    //INICIO - MODIFICACION DE LOS DETALLES
    $nombre= $d->producto_nombre;
    $complemento = ($d->rack_detalle_correlativo_comanda != Null)? ' / '.$d->rack_detalle_correlativo_comanda : "";
    $pdf->Row(array($aa,$d->rack_detalle_cantidad,$d->producto_nombre.''.$complemento,$data_rack->simbolo." ".$d->rack_detalle_preciounit,$data_rack->simbolo." ".$d->rack_detalle_subtotal));
    //FIN - MODIFICACION DE LOS DETALLES
    $total_p+=$d->rack_detalle_subtotal;
    $cant = strlen($d->venta_detalle_nombre_producto);
    $filas = ceil($cant / 65);
    if($filas==0){$filas=1;}
    $filas_tot+=$filas;
    $he = 4 * $filas;
    $aa++;
}
$pdf->Ln(7);
$pdf->Cell(70,3,'','T',0,'R');
$pdf->Cell(70,0,'',0,0,'L');
$pdf->Cell(40,3,'','T',1,'R');
$pdf->SetFont('Helvetica', '', 8);
$pdf->Cell(70, 3, "$montoLetras", 0,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Helvetica', '', 7);
$pdf->Cell(70,0,'Bufeo Tec Company - Digitalízate',0,0,'L');
$pdf->SetFont('Helvetica', 'B', 9);
$pdf->Cell(110, 1, "TOTAL: S/. $total_p", 0,'1','R');
$pdf->SetFont('Helvetica', '', 7);
$pdf->Ln(3);
$pdf->Cell(60,0,'bufeotec.com',0,1,'L');
$pdf->Ln(3);
$pdf->Cell(70,3,'','T',0,'R');
$pdf->Cell(70,0,'',0,0,'L');
$pdf->Cell(40,3,'','T',1,'R');

// PIE DE PAGINA
$pdf->Ln();

if($filas_tot<4) {
    $hei = 96 + (5 * $filas_tot);
}else{
    $hei = 94 + (5 * $filas_tot);
}

$pdf->Ln(3);
//$ruta_guardado = 'media/comprobantes/'."$serie_correlativo-" .date('Ymd').'.pdf';
$pdf->Output();
?>
