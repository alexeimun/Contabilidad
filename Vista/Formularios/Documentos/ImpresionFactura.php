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
    $Transportador='';
    $NombreTercero = '';
    $DocumentoTercero = '';
    $DireccionTercero = '';
    $TelefonoTercero = '';
    $FechaDoc = '';
    $FechaVence = '';
    $LogoEmpresa = '';
    $Obs = '';
    $Leyenda = '';
    $Ciudad = '';
    $TipoPago = '';

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
        $TipoPago=$valor['TIPO_PAGO'];

        $FechaVence = $valor['FECHA_REGISTRO'];

        $Transportador = $valor['TRANSPORTADOR'];

        $Anulado = $valor['ANULADO'];
        $Ciudad=$valor['CIUDAD'];
        $Obs = $valor['OBS'];
        $Leyenda = $valor['LEYENDA'];
    }

    //*************ENCABEZADO
    $pdf->AddPage();

    //$pdf->Ln(7);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetY(19);
    $pdf->Ln(4);
    $pdf->SetX(130);
    $pdf->Cell(55, 12, '', 1, 0, 'C', FALSE);
    $pdf->Text(132,28,'FACTURA');
    $pdf->Text(132,34,utf8_decode('N°  ') . $Consecutivo);
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
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetX(80);

    $pdf->Cell(40, 10,strtoupper(utf8_decode($NombreEmpresa)),0,0,'C') . $pdf->Ln(5);
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->SetX(80);
    $pdf->Cell(40, 10, 'Nit: '. $NitEmpresa.utf8_decode(' - Regimen Común')) . $pdf->Ln(5);
    $pdf->SetX(80);
    $pdf->Cell(40, 10, $DireccionEmpresa.' - Tel: '. $TelefonoEmpresa) . $pdf->Ln(5);
    $pdf->Ln(14);
    //***************DATOS DE TERCERO

    $pdf->SetFont('Arial', '', 9);
    $pdf->SetX(20);
    $pdf->Cell(165,20,'',1);

    $pdf->SetX(20);
    $pdf->Cell(115,15,'',1);

    $pdf->SetX(20);
    $pdf->Cell(65,15,'',1);

    $pdf->Text(22,41,utf8_decode('Vendido a: '.$NombreTercero));
    $pdf->Text(22,50,'C.C. o NIT: '.$NitEmpresa);
    $pdf->Text(22,55,utf8_decode('Direccción a Despachar: '.$DireccionTercero));
    $pdf->SetXY(85,37);
    $pdf->Cell(100,5,utf8_decode('Ciudad: '.ucfirst(strtolower($Ciudad))),1);

    $pdf->SetXY(85,42);
    $pdf->Cell(100,5,'Orden del cliente No.',1);

    $pdf->SetXY(85,47);
    $pdf->Cell(100,5,utf8_decode('Vendedor: '.$Elaboro),1);

    $pdf->Text(136,41,'Fecha');

    $pdf->SetXY(150,37);
    $pdf->Cell(12,5,substr($FechaDoc, 8, 2),1,0,'C');

    $pdf->SetXY(162,37);
    $pdf->Cell(12,5, substr($FechaDoc, 5, 2),1,0,'C');

    $pdf->SetXY(174,37);
    $pdf->Cell(11,5,substr($FechaDoc, 0, 4),1,0,'C');


    $pdf->Text(136,45,utf8_decode('Forma de pago: '.$TipoPago));
    $pdf->Text(136,50,utf8_decode('Transportador: '.$Transportador));


    $pdf->Ln(22);
    $Datos = [];

    // Arial 12
    $pdf->SetFont('Arial', '', 9);
    // Color de fondo
    $pdf->SetFillColor(205, 228, 233);
    // Título
//    $pdf->SetX(20);
//    $pdf->Cell(165, 6, "DETALLE DE LA FACTURA", 0, 1, 'C', FALSE);

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
$pdf->Ln(2);
    $pdf->SetX(20);
    $pdf->Cell(165, 6, number_format($total, 0, '', ','), 1, 0, 'R');

    $pdf->SetX(20);
    $pdf->Cell(115, 6, 'TOTAL $', 1, 0, 'R') . $pdf->Ln();



    $pdf->SetX(20);
    $pdf->Cell(165, 6, "FORMAS DE PAGO", 0, 1, 'C', FALSE);
    $pdf->TraePagos($Consecutivo, 'CXC FACT', 'F');


//    $pdf->Ln(1);
//    $yyy = $pdf->GetY();
//    $pdf->SetX(20);
//    $pdf->SetFont('Arial', 'B', 9);
//    $pdf->Cell(115, 6, "Observaciones", 0, 1, 'L', true);
//    $pdf->SetX(20);
//    $pdf->SetFont('Arial', '', 9);
//    $pdf->MultiCell(115, 4, utf8_decode($Obs), 0, 'J', true);
//
//
//    $pdf->SetY($yyy);
//    $pdf->SetFont('Arial', 'B', 9);
//    $pdf->SetX(135);
//    $pdf->Cell(25, 6, 'Valor Bruto:', 0, 0, 'R', true);
//    $pdf->SetFont('Arial', '', 10);
//    $pdf->Cell(25, 6, '$ ' . number_format($total, 0, '', ','), 0, 0, 'R', true) . $pdf->Ln();
//    $pdf->SetX(135);
//    $pdf->SetFont('Arial', 'B', 9);
//    $pdf->Cell(25, 6, 'Descuento:', 0, 0, 'R', true);
//    $pdf->SetFont('Arial', '', 10);
//    $pdf->Cell(25, 6, '$ ' . number_format($descuento, 0, '', ','), 0, 0, 'R', true) . $pdf->Ln();
//    $pdf->SetX(135);
//    $pdf->SetFont('Arial', 'B', 9);
//    $pdf->Cell(25, 6, 'Valor Neto:', 0, 0, 'R', true);
//    $pdf->SetFont('Arial', '', 10);
//    $pdf->Cell(25, 6, '$ ' . number_format(($total - $descuento), 0, '', ','), 0, 0, 'R', true) . $pdf->Ln();
//    $pdf->Ln(2);
//    $pdf->SetX(20);
//
//    $pdf->SetFont('Arial', '', 9);
//    $pdf->MultiCell(165, 5, utf8_decode($Leyenda), 0, 'J', FALSE);

//
//    $pdf->Ln();
//    $pdf->SetX(20);
//    $pdf->Cell(60, 8, utf8_decode('Elaboró: ') . $Elaboro, 1, 0, 'L', false);
//    $pdf->Cell(50, 8, utf8_decode('Aprobó: '), 1, 0, 'L', false);
//    $pdf->Cell(55, 8, utf8_decode('Recibí Conforme: '), 1, 0, 'L', false);
//    $pdf->Ln();
//    $pdf->SetX(20);
//    $pdf->Cell(165, 0, '', 'T');


    $pdf->Output();

    $pdf->Cell($pdf->PageNo());

    include '../../View/Formularios/Informes/vw_Informes.php';