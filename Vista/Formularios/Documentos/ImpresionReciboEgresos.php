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

    $pdf->SetX(20);
    $pdf->Cell(170, 11, '', 1, 0, 'L');
    $pdf->SetX(20);
    $pdf->Cell(98, 11, '', 1, 0, 'L');
    $pdf->SetX(20);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Text(120, 15, 'COMPROBANTE DE');
    $pdf->Text(120, 19, utf8_decode('EGRESO N° ' . $_SESSION['ConsecutivoGastos']));

    $pdf->SetFont('Arial', '', 8);

    $pdf->Ln();
    $pdf->SetX(20);
    $pdf->Cell(35, 6, utf8_decode('CÓDIGO'), 1, 0, 'C');

    $pdf->SetX(55);
    $pdf->Cell(100, 6, utf8_decode('CONCEPTO'), 1, 0, 'C');

    $pdf->SetX(155);
    $pdf->Cell(35, 6, utf8_decode('VALOR'), 1, 0, 'C');
    $h = 6;
    $TOTAL = 0;

    foreach ($Documentos->TraeGastosSubtotales($_SESSION['ConsecutivoGastos'], $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
        $pdf->Ln();
        $pdf->SetX(20);
        $pdf->Cell(35, 6, $valor['CODIGO'], 1, 0, 'C');

        $pdf->SetX(55);
        $pdf->Cell(100, 6, utf8_decode($valor['OBS']), 1, 0, 'C');

        $pdf->SetX(155);
        $pdf->Cell(35, 6, '$ ' . number_format($valor['VALOR'], 0, '', '.'), 1, 0, 'C');
        $h += 6;
        $TOTAL += $valor['VALOR'];
    }
    ##Fila de más
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Ln();

    $pdf->SetX(20);
    $pdf->Cell(135, 6, 'TOTAL   ', 1, 0, 'R');

    $pdf->SetX(155);
    $pdf->Cell(35, 6, '$ ' . number_format($TOTAL, 0, '', '.'), 1, 0, 'C');


    $pdf->SetFont('Arial', '', 8);
    #FIN COLUMNAS
    foreach ($Documentos->TraeDetalleGastos($_SESSION['ConsecutivoGastos'], $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {

        $pdf->Ln();
        $pdf->SetX(20);
        $pdf->Cell(40, 6, utf8_decode('CHEQUE N°') . ' ' . $valor['NUMERO'], 1, 0, 'L');

        $pdf->SetX(60);
        $pdf->Cell(45, 6, utf8_decode('EFECTIVO:'), 1, 0, 'L');

        $pdf->Text(78, 31 + $h,'$ '. number_format($valor['VALOR'], 0, '', '.'));

        $pdf->SetXY(20, 33 + $h);
        $pdf->Cell(85, 6, 'BANCO: ' . $valor['NOMBRE_ENTIDAD'], 1, 0, 'L');

        $pdf->SetXY(20, 39 + $h);
        $pdf->Cell(85, 6, 'DEBITESE A', 1, 0, 'L');
        $pdf->SetXY(20, 45 + $h);
        $pdf->Cell(85, 6, '', 1, 0, 'L');

        $pdf->SetXY(20, 51 + $h);
        $pdf->Cell(42, 12, '', 1, 0, 'L');
        $pdf->Text(21, 55 + $h, 'PREPARADO');

        $pdf->SetXY(62, 51 + $h);
        $pdf->Cell(43, 12, '', 1, 0, 'L');
        $pdf->Text(63, 55 + $h, 'REVISADO');

        $pdf->SetXY(105, 51 + $h);
        $pdf->Cell(42, 12, '', 1, 0, 'L');
        $pdf->Text(106, 55 + $h, 'APROBADO');

        $pdf->SetX(147, 51 + $h);
        $pdf->Cell(43, 12, '', 1, 0, 'L');
        $pdf->Text(148, 55 + $h, 'CONTABILIZADO');


        $pdf->SetXY(105, 27 + $h);
        $pdf->Cell(85, 18, '', 1, 0, 'L');
        $pdf->Text(106, 30 + $h, 'FIRMA Y SELLO DEL BENEFICIARIO');

        $pdf->SetXY(105, 45 + $h);
        $pdf->Cell(85, 6, 'C.C. / NIT: ' . $_SESSION['login'][0]['NIT'], 1, 0, 'L');
    }

    $pdf->SetXY(20, 63 + $h);
    $pdf->Cell(30, 6, '', 1, 0, 'L');

    $pdf->SetXY(50, 63 + $h);
    $pdf->Cell(106, 6, '', 1, 0, 'L');

    $pdf->SetXY(156, 63 + $h);
    $pdf->Cell(34, 6, '', 1, 0, 'L');

    $pdf->Output();
    $pdf->Cell($pdf->PageNo());