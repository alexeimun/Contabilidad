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


//        $Detalle = $Documentos->TraeDetalleReciboEgresos($_SESSION['ConsecutivoEgresos'], $_SESSION['ConsecutivoGastos'], $_SESSION['login'][0]["ID_EMPRESA"]);

    $pdf->SetX(20);
    $pdf->Cell(165, 11, '', 1, 0, 'L');
    $pdf->SetX(20);
    $pdf->Cell(98, 11, '', 1, 0, 'L');
    $pdf->SetX(20);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Text(120, 15, 'COMPROBANTE DE');
    $pdf->Text(120, 19, utf8_decode('EGRESO N° ' . $_SESSION['ConsecutivoGastos']));

    $pdf->SetFont('Arial', '', 8);

    $pdf->Ln();
    $pdf->SetX(20);
    $pdf->Cell(30, 6, utf8_decode('CÓDIGO'), 1, 0, 'C');

    $pdf->SetX(50);
    $pdf->Cell(100, 6, utf8_decode('CONCEPTO'), 1, 0, 'C');

    $pdf->SetX(150);
    $pdf->Cell(35, 6, utf8_decode('VALOR'), 1, 0, 'C');
    $h = 0;
    $TOTAL = 0;

    foreach ($Documentos->TraeGastosSubtotales($_SESSION['ConsecutivoGastos'], $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
        $pdf->Ln();
        $pdf->SetX(20);
        $pdf->Cell(30, 6, $valor['CODIGO'], 1, 0, 'C');

        $pdf->SetX(50);
        $pdf->Cell(100, 6, utf8_decode($valor['OBS']), 1, 0, 'C');

        $pdf->SetX(150);
        $pdf->Cell(35, 6, '$ ' . number_format($valor['VALOR'], 0, '', '.'), 1, 0, 'C');
        $h += 6;
        $TOTAL += $valor['VALOR'];
    }
    ##Fila de más
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Ln();

    $pdf->SetX(20);
    $pdf->Cell(130, 6, 'TOTAL   ', 1, 0, 'R');

    $pdf->SetX(150);
    $pdf->Cell(35, 6, '$ ' . number_format($TOTAL, 0, '', '.'), 1, 0, 'C');
    $h += 6;


    $pdf->SetFont('Arial', '', 8);
    #FIN COLUMNAS
    foreach ($Documentos->TraeDetalleGastos($_SESSION['ConsecutivoGastos'], $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {

        $pdf->Ln();
        $pdf->SetX(20);
        $pdf->Cell(40, 6, utf8_decode('CHEQUE N°') . ' ' . $valor['NUMERO'], 1, 0, 'L');

        $pdf->SetX(60);
        $pdf->Cell(28, 6, utf8_decode('EFECTIVO'), 1, 0, 'L');

        $pdf->SetXY(78, 28 + $h);
        $pdf->Cell(6, 4, '', 1, 0, 'L');

        $pdf->SetXY(20, 33 + $h);
        $pdf->Cell(68, 6, 'BANCO: ' . $valor['NOMBRE_ENTIDAD'], 1, 0, 'L');

        $pdf->SetXY(20, 39 + $h);
        $pdf->Cell(68, 6, 'DEBITESE A', 1, 0, 'L');
        $pdf->SetXY(20, 45 + $h);
        $pdf->Cell(68, 6, '', 1, 0, 'L');

        $pdf->SetXY(20, 51 + $h);
        $pdf->Cell(34, 12, 'PREPARADO', 1, 0, 'L');

        $pdf->SetX(54);
        $pdf->Cell(34, 12, 'REVISADO', 1, 0, 'L');

        $pdf->SetXY(88, 51 + $h);
        $pdf->Cell(50, 12, 'APROBADO', 1, 0, 'L');

        $pdf->SetX(138);
        $pdf->Cell(47, 12, 'CONTABILIZADO', 1, 0, 'L');

        $pdf->SetXY(88, 27 + $h);
        $pdf->Cell(97, 18, '', 1, 0, 'L');
        $pdf->Text(90, 30 + $h, 'FIRMA Y SELLO DEL BENEFICIARIO');

        $pdf->SetXY(88, 45 + $h);
        $pdf->Cell(97, 6, 'C.C. / NIT: ' . $_SESSION['login'][0]['NIT'], 1, 0, 'L');
    }

    $pdf->SetXY(20, 63 + $h);
    $pdf->Cell(25, 6, '', 1, 0, 'L');

    $pdf->SetXY(45, 63 + $h);
    $pdf->Cell(100, 6, '', 1, 0, 'L');

    $pdf->SetXY(145, 63 + $h);
    $pdf->Cell(40, 6, '', 1, 0, 'L');


    $pdf->Output();
    $pdf->Cell($pdf->PageNo());