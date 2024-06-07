<?php
require 'app/view/pdf/fpdf/fpdf.php';

class PDF extends FPDF{

    //Cabecera de pagina
    function Header(){
        //Arial bold 15
        $this->Image('styles/login/images/logofire.png',87,4,35,0);
        $this->Cell(130,10,'',0,8,'C');
        $this->Ln();
        $this->Ln();

        $this->SetFont('Arial','B',16);
        //Mover
        $this->Cell(30);
        //Titulo
        $this->Cell(130,10,'EFA IMPORT E.I.R.L',0,1,'C');
        $this->SetFont('Arial','B',12);
        $this->Cell(190,10,'RUC: 20606136618',0,1,'C');
        $this->SetFont('Arial','B',12);
        $this->Cell(190,10,'JR. CONDAMINE N° 589 - LORETO - MAYNAS - IQUITOS',0,1,'C');
        $this->SetFont('Arial','B',12);
        $this->Cell(190,10,'Ventas de accesorios y ropa deportiva al por menor y mayor precio.',0,1,'C');
        $this->SetFont('Arial','B',16);
        $this->Cell(190,10,'PROFORMA DE VENTA',0,1,'C');
        $this->SetFont('Arial','B',12);
        $this->Cell(190,10,'---------------------------------------------------------------------------------------------------------------------------',0,0,'C');

        //Salto de linea
        $this->Ln();
    }

    //Pie de pagina
    function Footer(){
        //$this->Image('media/fire/firma2.png',50,235,110);
        //Posicion: a 1.5 cm del final
        $this->SetY(-15);
        //Arial italic 8
        $this->SetFont('Arial','I',8);
        //Numero de Ipagina
        $fecha = date('d-m-Y h:i:s');
        $this->Cell(0,10,'Pagina ' . $this->PageNo().'/{nb}'." "."|| Hora y fecha de descarga"." ".$fecha." || bufeotec.com",0,0,'C');
    }
}
?>