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
        $pdf->SetX(120);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(60, 6, 'RECIBO DE CAJA MENOR', 1, 0, 'L') . $pdf->Ln();
        $pdf->SetX(120);
        $pdf->Cell(60, 6, 'No. ' . $valor['CONSECUTIVO'], 1, 0, 'L') . $pdf->Ln();
        $pdf->SetX(30);
        $pdf->SetFont('Arial', '', 7);

        $pdf->Cell(150, 6, 'CIUDAD Y FECHA: ' . $valor['NOMBRE_CIUDAD'] . ' ' . $valor['FECHA_REGISTRO'], 1, 0, 'L') . $pdf->Ln();
        $pdf->SetX(30);
        $pdf->Cell(100, 6, 'PAGADO A: ' . $valor['NOMBRE1'] . ' ' . $valor['NOMBRE2'] . ' ' . $valor['APELLIDO1'] . ' ' . $valor['APELLIDO2'], 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 6, '$ ' . number_format($valor['VALOR'], 0, '', ','), 1, 0, 'L') . $pdf->Ln();
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetX(30);
        $pdf->Cell(150, 6, 'POR CONCEPTO DE: ' . $valor['CONCEPTO'], 1, 0, 'L') . $pdf->Ln();
        $pdf->SetX(30);
        $pdf->Cell(150, 6, '', 1, 0, 'L') . $pdf->Ln();
        $pdf->SetX(30);
        $pdf->Cell(150, 6, '', 1, 0, 'L') . $pdf->Ln();
        $pdf->SetX(30);
        $pdf->Cell(150, 6, 'VALOR (EN LETRAS): ' . (strlen($NumerosALetras->resultado) < 35 ? ucwords($NumerosALetras->resultado) : ''), 1, 0, 'L') . $pdf->Ln();
        $pdf->SetX(30);
        $pdf->Cell(150, 6, '', 1, 0, 'L') . $pdf->Ln();
        $pdf->SetX(30);
        $pdf->Cell(60, 6, utf8_decode('CÓDIGO: ') . $valor['CODIGO'], 1, 0, 'L') . $pdf->Ln();
        $pdf->SetX(30);
        $pdf->Cell(60, 6, 'APROBADO', 1, 0, 'L') . $pdf->Ln();
        $pdf->SetY(64);
        $pdf->SetX(90);
        $pdf->Cell(90, 12, 'FIRMA Y SELLO DEL BENEFICIARIO', 1, 0, 'L');

        $pdf->SetY(73);
        $pdf->SetX(90);
        $pdf->Cell(90, 3, 'C.C / NIT.: ' . $_SESSION['login'][0]["NIT"], 1, 0, 'L');
        if ($valor['ANULADO'] == 1) {
            $pdf->SetY(76);
            $pdf->SetX(120);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(60, 6, 'ANULADO', 1, 0, 'L') . $pdf->Ln();
        }
    }

    $pdf->Output();

    $pdf->Cell($pdf->PageNo());

    include '../../View/Formularios/Informes/vw_Informes.php';