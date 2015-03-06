<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Documentos.php';
    include '../../fpdf/fpdf.php';

    session_start();
    $pdf = new FPDF();


    //************************************************************
    //**********************Informe***********************
    //*************************************************************

    //*************ENCABEZADO
    $pdf->AddPage();

    $pdf->SetFont('Arial', 'B', 11);

    $pdf->SetX(80);
    $pdf->Cell(40, 10, $_SESSION['login'][0]["NOMBRE_EMPRESA"], 1, 0, 'C', FALSE);
    $pdf->Ln();
    $pdf->SetX(82);
    $pdf->Cell(40, 10, $_SESSION['login'][0]["NIT"], 1, 0, 'C', FALSE);


    $pdf->Output();

    $pdf->Cell($pdf->PageNo());