<?php

    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Documentos.php';
    include '../../fpdf/fpdf.php';
    include '../../../Clases/cls_NumerosALetras.php';

    session_start();

    //************************************************************
    //*********************Informe************************
    //*************************************************************

    $pdf = new FPDF();
    $NumerosALetras = new cls_numerosALetras();
    $Documentos = new cls_Documentos();


    //*************ENCABEZADO
    $pdf->AddPage();
    foreach ($Documentos->TraeDetalleCM($_SESSION['ConsecutivoCM'], $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {

        $NumerosALetras->convertir($valor['VALOR']);
        $pdf->SetDrawColor(40, 110, 100);
        $pdf->SetFillColor(40, 140, 100);
        $pdf->SetX(120);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(60, 6, 'RECIBO DE CAJA MENOR', 1, 0, 'C', true) . $pdf->Ln();
        $pdf->SetX(120);
        $pdf->SetTextColor(40, 130, 100);

        $pdf->Cell(60, 6, '    No. ' . $valor['CONSECUTIVO'], 1, 0, 'L') . $pdf->Ln();
        $pdf->SetX(30);
        $pdf->SetFont('Arial', 'B', 8);

        $pdf->Cell(150, 6, 'CIUDAD Y FECHA: ' .ucfirst( strtolower( $valor['NOMBRE_CIUDAD'])) . ' ' . $valor['FECHA_REGISTRO'], 1, 0, 'L') . $pdf->Ln();
        $pdf->SetX(30);
        $pdf->Cell(100, 6, 'PAGADO A: ' . $valor['N_COMPLETO'], 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(255, 255, 231);//Fill
        $pdf->SetFillColor(255, 255, 231);//Fill
        $pdf->Cell(50, 6, '$ ' . number_format($valor['VALOR'], 0, '', ','), 1, 0, 'L', true) . $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(30);
        $pdf->Cell(150, 6, 'POR CONCEPTO DE: ' . $valor['OBS'], 1, 0, 'L') . $pdf->Ln();
        $pdf->SetX(30);
        $pdf->Cell(150, 6, '', 1, 0, 'L') . $pdf->Ln();
        $pdf->SetX(30);
        $pdf->Cell(150, 6, '', 1, 0, 'L') . $pdf->Ln();

        $pdf->SetX(30);
        $pdf->Cell(150, 6, 'VALOR (EN LETRAS): ' . (strlen($NumerosALetras->resultado) < 35 ? ucwords($NumerosALetras->resultado) : ''), 1, 0, 'L', true) . $pdf->Ln();
        $pdf->SetX(30);
        $pdf->Cell(150, 6, '', 1, 0, 'L', true) . $pdf->Ln();
        $pdf->SetX(30);
        $pdf->Cell(60, 6, utf8_decode('CÓDIGO: ') . $valor['CODIGO'], 1, 0, 'L', true) . $pdf->Ln();
        $pdf->SetX(30);
        $pdf->Cell(60, 6, 'APROBADO', 1, 0, 'L', true) . $pdf->Ln();
        $pdf->SetY(64);
        $pdf->SetX(90);
        $pdf->Cell(90, 12, '', 1, 0, 'L', true);
        $pdf->Text(91, 67, 'FIRMA Y SELLO DEL BENEFICIARIO');

        $pdf->SetY(72);
        $pdf->SetX(90);
        $pdf->Cell(90, 4, 'C.C / NIT.: ' . $_SESSION['login'][0]["NIT"], 1, 0, 'L', true);
        if ($valor['ANULADO'] == 1) {
            $pdf->SetY(76);
            $pdf->SetX(120);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(60, 6, 'ANULADO', 1, 0, 'L') . $pdf->Ln();
        }
    }

    $pdf->Output();

    $pdf->Cell($pdf->PageNo());

    include '../../View/Formularios/Informes/vw_Informes.php';