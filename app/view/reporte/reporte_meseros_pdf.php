<?php
//Llamamos a la libreria
require_once 'app/view/pdf/pdf_base.php';
//creamos el objeto
$pdf=new PDF();

//Añadimos una pagina
$pdf->AddPage();
//Define el marcador de posición usado para insertar el número total de páginas en el documento
$pdf->AliasNbPages();
$pdf->SetFont('Arial','BU',14);
//Mover
$pdf->Cell(30);
$pdf->Cell(130,10,'REPORTE DE VENTAS POR MESEROS SEGUN FILTRO',0,1,'C');

$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','',8);
$pdf->Cell(8,6,'N°',1,0,'C',1);
$pdf->Cell(38,6,'FECHAS FILTRO',1,0,'C',1);
$pdf->Cell(62,6,'NOMBRE DEL MESERO',1,0,'C',1);
$pdf->Cell(40,6,'CANTIDAD DE VENTAS',1,0,'C',1);
$pdf->Cell(40,6,'TOTAL EN VENTAS',1,0,'C',1);
$pdf->Ln();
$cantidad_vendida = 0;
$pdf->SetFont('Arial','',8);
$a=1;
foreach ($meseros as $p){
    $pdf->CellFitSpace(8,5,$a,1,0,'C',0);
    $pdf->CellFitSpace(38,5,date('d-m-Y',strtotime($fecha_hoy)).' / '.date('d-m-Y',strtotime($fecha_fin)),1,0,'C',0);
    $pdf->CellFitSpace(62,5,$p->persona_nombre,1,0,'C',0);
    $pdf->CellFitSpace(40,5,$p->total ?? 0,1,0,'C',0);
    $pdf->CellFitSpace(40,5,'S/. '.$p->total_comanda ?? 0,1,1,'C',0);
    $cantidad_vendida = $cantidad_vendida + $p->total_comanda;
    $a++;
}
$pdf->SetFont('Arial','',12);
$pdf->Cell(118,10,'TOTAL DE VENTAS',0,0,'C',0);
$pdf->Cell(30,10,'S/.'.$cantidad_vendida,0,1,'R',0);
$pdf->Ln();
$pdf->Output();
?>