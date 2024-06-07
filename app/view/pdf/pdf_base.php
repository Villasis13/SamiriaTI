<?php
require 'app/view/pdf/fpdf/fpdf.php';

class PDF extends FPDF{

    //Cabecera de pagina
    function Header(){
        //Arial bold 15
        $this->SetFont('Arial','B',16);
        //Mover
        $this->Cell(30);
        //Titulo
        $this->Cell(130,7,'SAMIRIA JUNGLE HOTEL E.I.R.L',0,1,'C');
        $this->SetFont('Arial','B',12);
        $this->Cell(190,7,'RUC: 20607969877',0,1,'C');
        //$this->SetFont('Arial','B',12);
        //$this->Cell(190,10,'PROFORMA DE VENTA',0,1,'C');
        $this->SetFont('Arial','',12);
        $this->Cell(190,7,'JR. RICARDO PALMA NRO. 159',0,1,'C');
        $this->SetFont('Arial','',12);
        $this->Cell(190,7,'LORETO - MAYNAS - IQUITOS',0,1,'C');
        $this->SetFont('Arial','',12);
        $this->Cell(190,6,'---------------------------------------------------------------------------------------------------------------------------',0,0,'C');
        $this->Image('styles/login/images/samiria2.jpg',16,10,35);
        $this->Image('styles/login/images/samiria2.jpg',160,10,35);
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