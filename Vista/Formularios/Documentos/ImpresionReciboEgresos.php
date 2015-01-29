<?php

    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Documentos.php';
    include '../../fpdf/fpdf.php';
    include '../../../Clases/cls_NumerosALetras.php';

    session_start();

    $pdf = new FPDF();
    $NumerosALetras = new cls_numerosALetras();
    $Documentos = new cls_Documentos();

    $pdf->AddPage();

    if ($_SESSION['ReciboEgresos'] == 'ok') {
        $Detalle = $Documentos->TraeDetalleReciboEgresos($_SESSION['ConsecutivoEgresos'], $_SESSION['ConsecutivoGastos'], $_SESSION['login'][0]["ID_EMPRESA"]);
    } else {

        if ($_SESSION['Tipo'] == 'CR')
            $Detalle = $Documentos->TraeDetalleEgresos($_SESSION['ConsecutivoEgresos'], $_SESSION['ConsecutivoGastos'], $_SESSION['login'][0]["ID_EMPRESA"]);
        else
            $Detalle = $Documentos->TraeDetalleGastos($_SESSION['ConsecutivoGastos'], $_SESSION['login'][0]["ID_EMPRESA"]);
    }

    foreach ($Detalle as $llave => $valor) {

        $NumerosALetras->convertir($valor['VALOR']);
        $pdf->SetX(20);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(60, 6, 'COMPROBANTE DE EGRESO No. ' . $valor['CONSECUTIVO'], 0, 0, 'L', FALSE) . $pdf->Ln();
        $pdf->Ln();
        $pdf->SetX(20);
        $pdf->SetFont('Arial', '', 8);

        $pdf->Cell(165, 6, 'FECHA: ' . $valor['FECHA_REGISTRO'], 1, 0, 'L') . $pdf->Ln();
        $pdf->SetX(20);
        $pdf->Cell(110, 6, 'PAGADO A: ' . $valor['NOMBRE1'] . ' ' . $valor['NOMBRE2'] . ' ' . $valor['APELLIDO1'] . ' ' . $valor['APELLIDO2'], 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(55, 6, '$ ' . number_format($valor['VALOR'], 0, '', ','), 1, 0, 'L') . $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(20);
        $pdf->Cell(165, 6, 'POR CONCEPTO DE: ' . $valor['CONCEPTO'], 1, 0, 'L') . $pdf->Ln();
        $pdf->SetX(20);
        $pdf->Cell(165, 6, '', 1, 0, 'L') . $pdf->Ln();
        $pdf->SetX(20);
        $pdf->Cell(165, 6, 'VALOR (EN LETRAS): ' . (strlen($NumerosALetras->resultado) < 35 ? ucwords($NumerosALetras->resultado) : ''), 1, 0, 'L') . $pdf->Ln();


        if ($valor['ANULADO'] == 1) {
            $pdf->SetY(76);
            $pdf->SetX(120);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(60, 6, 'ANULADO', 1, 0, 'L') . $pdf->Ln();
        }
    }

    $pdf->Ln();
    $pdf->SetFont('Arial', '', 9);
    $pdf->SetX(20);
    $pdf->Cell(165, 6, "FORMAS DE PAGO", 0, 1, 'C', FALSE);

    //Salto de línea

    $pdf->TraePagos($_SESSION['ConsecutivoGastos'], 'ABONO GASTOS', 'G', isset($_SESSION['ConsecutivoEgresos']) ? $_SESSION['ConsecutivoEgresos'] : 0);

    $pdf->Ln();
    $pdf->Ln();
    $pdf->SetFont('Arial', '', 9);
    $pdf->SetX(0);

    $pdf->Cell(80, 6, "Aprobado por: " . $_SESSION['login'][0]["NOMBRE_USUARIO"], 0, 1, 'C', FALSE);

    $pdf->Output();
    $pdf->Cell($pdf->PageNo());

    include '../../View/Formularios/Informes/vw_Informes.php';
