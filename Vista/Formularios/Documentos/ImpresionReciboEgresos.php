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
        $pdf->Cell(165, 11, '', 1, 0, 'L');
        $pdf->SetX(20);
        $pdf->Cell(98, 11, '', 1, 0, 'L');
        $pdf->SetX(20);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Text(120, 15, 'COMPROBANTE DE');
        $pdf->Text(120, 19, 'EGRESO No. ' . $_SESSION['ConsecutivoGastos']);

        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(20);
        $pdf->Cell(30, 6, utf8_decode('CÓDIGO'), 1, 0, 'C');

        $pdf->SetX(50);
        $pdf->Cell(100, 6, utf8_decode('CONCEPTO'), 1, 0, 'C');

        $pdf->SetX(150);
        $pdf->Cell(35, 6, utf8_decode('VALOR'), 1, 0, 'C');


        #FIN COLUMNAS


        $pdf->Ln();
        $pdf->SetX(20);
        $pdf->Cell(40, 6, utf8_decode('CHEQUE N°'), 1, 0, 'L');

        $pdf->SetX(60);
        $pdf->Cell(28, 6, utf8_decode('EFECTIVO'), 1, 0, 'L');

        $pdf->SetXY(78, 28);
        $pdf->Cell(6, 4, '', 1, 0, 'L');

        $pdf->SetXY(20, 33);
        $pdf->Cell(68, 6, 'BANCO', 1, 0, 'L');

        $pdf->SetXY(20, 39);
        $pdf->Cell(68, 6, 'DEBITESE A', 1, 0, 'L');
        $pdf->SetXY(20, 45);
        $pdf->Cell(68, 6, '', 1, 0, 'L');

        $pdf->SetXY(20, 51);
        $pdf->Cell(34, 12, 'PREPARADO', 1, 0, 'L');

        $pdf->SetX(54);
        $pdf->Cell(34, 12, 'REVISADO', 1, 0, 'L');

        $pdf->SetXY(88, 51);
        $pdf->Cell(50, 12, 'APROBADO', 1, 0, 'L');

        $pdf->SetX(138);
        $pdf->Cell(47, 12, 'CONTABILIZADO', 1, 0, 'L');

        $pdf->SetXY(88, 27);
        $pdf->Cell(97, 18, '', 1, 0, 'L');
        $pdf->Text(90, 30, 'FIRMA Y SELLO DEL BENEFICIARIO');

        $pdf->SetXY(88, 45);
        $pdf->Cell(97, 6, 'C.C. / NIT', 1, 0, 'L');


        //-----------------------------------------------------------
//
//        if($valor['ANULADO']==1) {
//            $pdf->SetFont('Arial', '', 8);
//            $pdf->SetX(20);
//            $pdf->Cell(60, 6, 'ANULADO', 0, 0, 'L', FALSE) . $pdf->Ln();
//        }
//        $pdf->Ln();
//        $pdf->SetX(20);
//
//        $pdf->Cell(165, 6, 'FECHA: ' . $valor['FECHA_REGISTRO'], 1, 0, 'L') . $pdf->Ln();
//        $pdf->SetX(20);
//        $pdf->Cell(110, 6, 'PAGADO A: ' . $valor['NOMBRE1'] . ' ' . $valor['NOMBRE2'] . ' ' . $valor['APELLIDO1'] . ' ' . $valor['APELLIDO2'], 1, 0, 'L');
//        $pdf->SetFont('Arial', 'B', 9);
//        $pdf->Cell(55, 6, '$ ' . number_format($valor['VALOR'], 0, '', ','), 1, 0, 'L') . $pdf->Ln();
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->SetX(20);
//        $pdf->Cell(165, 6, 'POR CONCEPTO DE: ' . $valor['CONCEPTO'], 1, 0, 'L') . $pdf->Ln();
//        $pdf->SetX(20);
//        $pdf->Cell(165, 6, '', 1, 0, 'L') . $pdf->Ln();
//        $pdf->SetX(20);
//        $pdf->Cell(165, 6, 'VALOR (EN LETRAS): ' . (strlen($NumerosALetras->resultado) < 35 ? ucwords($NumerosALetras->resultado) : ''), 1, 0, 'L') . $pdf->Ln();
//
//
//        if ($valor['ANULADO'] == 1) {
//            $pdf->SetY(76);
//            $pdf->SetX(120);
//            $pdf->SetFont('Arial', '', 12);
//            $pdf->Cell(60, 6, 'ANULADO', 1, 0, 'L') . $pdf->Ln();
//        }
    }
//
//    $pdf->Ln();
//    $pdf->SetFont('Arial', '', 9);
//    $pdf->SetX(20);
//    $pdf->Cell(165, 6, "FORMAS DE PAGO", 0, 1, 'C', FALSE);

    //Salto de línea

//    $pdf->TraePagos($_SESSION['ConsecutivoGastos'], 'ABONO GASTOS', 'G', isset($_SESSION['ConsecutivoEgresos']) ? $_SESSION['ConsecutivoEgresos'] : 0);
//
//    $pdf->Ln();
//    $pdf->Ln();
//    $pdf->SetFont('Arial', '', 9);
//    $pdf->SetX(0);
//
//    $pdf->Cell(80, 6, "Aprobado por: " . $_SESSION['login'][0]["NOMBRE_USUARIO"], 0, 1, 'C', FALSE);

    $pdf->Output();
    $pdf->Cell($pdf->PageNo());

    include '../../View/Formularios/Informes/vw_Informes.php';
