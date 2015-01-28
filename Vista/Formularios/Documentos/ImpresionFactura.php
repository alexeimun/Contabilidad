<?php

    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Documentos.php';
    include '../../fpdf/fpdf.php';

    session_start();
    $Consecutivo = $_SESSION['ConsecutivoFACT'];

    //************************************************************
    //*********************Informe************************
    //*************************************************************

    $pdf = new FPDF();
    $Documentos = new cls_Documentos();
    $NombreEmpresa = '';
    $NitEmpresa = '';
    $DireccionEmpresa = '';
    $TelefonoEmpresa = '';
    $EmailEmpresa = '';

    $NombreTercero = '';
    $DocumentoTercero = '';
    $DireccionTercero = '';
    $TelefonoTercero = '';
    $FechaDoc = '';
    $FechaVence = '';
    $LogoEmpresa = '';
    $Obs = '';
    $Leyenda = '';

    $Elaboro = '';

    $Anulado = '';

    foreach ($Documentos->TraeInformacionFactura($Consecutivo, $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
        $Elaboro = $valor['NOMBRE_USUARIO'];

        $NombreEmpresa = $valor['NOMBRE'];
        $NitEmpresa = $valor['NIT'];
        $DireccionEmpresa = $valor['DIR_EMPRESA'];
        $TelefonoEmpresa = $valor['TEL_EMPRESA'];
        $EmailEmpresa = $valor['EMAIL'];
        $LogoEmpresa = $valor['LOGO'];

        $NombreTercero = $valor['NOMBRE1'] . ' ' . $valor['NOMBRE2'] . ' ' . $valor['APELLIDO1'] . ' ' . $valor['APELLIDO2'];
        $DocumentoTercero = $valor['NUM_DOCUMENTO'];
        $DireccionTercero = $valor['DIRECCION'];
        $TelefonoTercero = $valor['TELEFONO'];
        $FechaDoc = $valor['FECHA_REGISTRO'];

        $FechaVence = $valor['FECHA_REGISTRO'];

        $Anulado = $valor['ANULADO'];

        $Obs = $valor['OBS'];
        $Leyenda = $valor['LEYENDA'];
    }

    //*************ENCABEZADO
    $pdf->AddPage();

    $pdf->SetX(140);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(40, 10, $NombreEmpresa, 0, 0, 'C', FALSE);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Ln(5);
    $pdf->SetX(140);
    $pdf->Cell(40, 10, 'REGIMEN SIMPLIFICADO', 0, 0, 'C', FALSE);
    //$pdf->Ln(7);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetY(19);
    $pdf->Ln(4);
    $pdf->SetX(140);
    $pdf->Cell(40, 7, utf8_decode('FACTURA N°  ') . $Consecutivo, 1, 0, 'C', FALSE);
    $pdf->Ln(7);
    $pdf->SetX(147);
    $pdf->SetFont('Arial', 'BI', 10);
    if ($Anulado == 1) {
        $pdf->Cell(25, 7, 'ANULADA', 0, 0, 'C', FALSE);
    }
    $pdf->SetY(8);
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->SetX(195);


    $pdf->Image('../../Formularios/Empresas/' . $LogoEmpresa, 20, 10, 30, 22, '');
    $pdf->SetX(80);
    $pdf->Cell(40, 10, 'Nit: ' . '  ' . $NitEmpresa) . $pdf->Ln(5);
    $pdf->SetX(80);
    $pdf->Cell(40, 10, utf8_decode('Dirección: ') . '  ' . $DireccionEmpresa) . $pdf->Ln(5);
    $pdf->SetX(80);
    $pdf->Cell(40, 10, 'Telefono: ' . '  ' . $TelefonoEmpresa) . $pdf->Ln(5);
    $pdf->SetX(80);
    $pdf->Cell(40, 10, 'E-Mail: ' . '  ' . $EmailEmpresa);
    $pdf->Ln(14);
    //***************DATOS DE TERCERO
    $pdf->SetX(20);
    $pdf->Cell(166, 0, '', 'T');
    $pdf->SetX(20);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(28, 4, 'Nombre:  ', 'LR', 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(80, 4, $NombreTercero, 'LR', 0, 'L');
    $pdf->Cell(28, 4, '', 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(10, 4, 'DIA', 'LR', 0, 'C');
    $pdf->Cell(10, 4, 'MES', 'LR', 0, 'C');
    $pdf->Cell(10, 4, utf8_decode('AÑO'), 'LR', 0, 'C');
    $pdf->Ln();
    $pdf->SetX(20);
    //    $pdf->Cell(166,0,'','T');

    $pdf->SetX(20);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(28, 4, 'Documento:  ', 'LR', 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(80, 4, $DocumentoTercero, 'LR', 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(28, 4, 'Fecha Factura: ', 'LR', 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(10, 4, substr($FechaDoc, 8, 2), 'LR', 0, 'C');
    $pdf->Cell(10, 4, substr($FechaDoc, 5, 2), 'LR', 0, 'C');
    $pdf->Cell(10, 4, substr($FechaDoc, 0, 4), 'LR', 0, 'C');
    $pdf->Ln();
    $pdf->SetX(20);
    //    $pdf->Cell(166,0,'','T');

    $pdf->SetX(20);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(28, 4, utf8_decode('Dirección:  '), 'LR', 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(80, 4, $DireccionTercero, 'LR', 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(28, 4, 'Fecha Vence: ', 'LR', 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(10, 4, substr($FechaVence, 8, 2), 'LR', 0, 'C');
    $pdf->Cell(10, 4, substr($FechaVence, 5, 2), 'LR', 0, 'C');
    $pdf->Cell(10, 4, substr($FechaVence, 0, 4), 'LR', 0, 'C');
    $pdf->Ln();
    $pdf->SetX(20);
    //    $pdf->Cell(166,0,'','T');

    $pdf->SetX(20);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(28, 4, 'Telefono:   ', 'LR', 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(80, 4, $TelefonoTercero, 'LR', 0, 'L');
    $pdf->Cell(58, 4, '', 'LR', 0, 'C');
    $pdf->Ln();
    $pdf->SetX(20);
    $pdf->Cell(166, 0, '', 'T');

    $pdf->Ln(2);
    $Datos = [];

    // Arial 12
    $pdf->SetFont('Arial', '', 9);
    // Color de fondo
    $pdf->SetFillColor(205, 228, 233);
    // Título
    $pdf->SetX(20);
    $pdf->Cell(165, 6, "DETALLE DE LA FACTURA", 0, 1, 'C', FALSE);

    // Salto de línea
    $pdf->Ln(0);

    $total = 0;
    $descuento = 0;
    foreach ($Documentos->TraeDetalleFactura($Consecutivo, $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
        $pdf->SetFont('Arial', 'I', 7);
        $Datos [$llave][0] = $valor['DESCRIPCION'];
        $Datos [$llave][1] = number_format($valor['CANTIDAD'], 0, '', ',');
        $Datos [$llave][2] = number_format($valor['VALOR'], 0, '', ',');
        $Datos [$llave][3] = number_format(($valor['VALOR'] * $valor['CANTIDAD']), 0, '', ',');
        $total += ($valor['VALOR'] * $valor['CANTIDAD']);
        $descuento += $valor['DESCUENTO'];
    }
    $pdf->TablaFactura($Datos);
    $pdf->SetX(20);
    $pdf->Cell(165, 6, "FORMAS DE PAGO", 0, 1, 'C', FALSE);
    $pdf->TraePagos($Consecutivo, 'FACT', 'F');


    $pdf->Ln(1);
    $yyy = $pdf->GetY();
    $pdf->SetX(20);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(115, 6, "Observaciones", 0, 1, 'L', true);
    $pdf->SetX(20);
    $pdf->SetFont('Arial', '', 9);
    $pdf->MultiCell(115, 4, utf8_decode($Obs), 0, 'J', true);


    $pdf->SetY($yyy);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetX(135);
    $pdf->Cell(25, 6, 'Valor Bruto:', 0, 0, 'R', true);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(25, 6, '$ ' . number_format($total, 0, '', ','), 0, 0, 'R', true) . $pdf->Ln();
    $pdf->SetX(135);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(25, 6, 'Descuento:', 0, 0, 'R', true);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(25, 6, '$ ' . number_format($descuento, 0, '', ','), 0, 0, 'R', true) . $pdf->Ln();
    $pdf->SetX(135);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(25, 6, 'Valor Neto:', 0, 0, 'R', true);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(25, 6, '$ ' . number_format(($total - $descuento), 0, '', ','), 0, 0, 'R', true) . $pdf->Ln();
    $pdf->Ln(2);
    $pdf->SetX(20);

    $pdf->SetFont('Arial', '', 9);
    $pdf->MultiCell(165, 5, utf8_decode($Leyenda), 0, 'J', FALSE);


    $pdf->Ln();
    $pdf->SetX(20);
    $pdf->Cell(60, 8, utf8_decode('Elaboró: ') . $Elaboro, 1, 0, 'L', false);
    $pdf->Cell(50, 8, utf8_decode('Aprobó: '), 1, 0, 'L', false);
    $pdf->Cell(55, 8, utf8_decode('Recibí Conforme: '), 1, 0, 'L', false);
    $pdf->Ln();
    $pdf->SetX(20);
    $pdf->Cell(165, 0, '', 'T');


    $pdf->Output();

    $pdf->Cell($pdf->PageNo());

    include '../../View/Formularios/Informes/vw_Informes.php'

?>

