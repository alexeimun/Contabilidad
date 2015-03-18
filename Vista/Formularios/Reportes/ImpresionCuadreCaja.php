<?php

    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Documentos.php';
    include '../../fpdf/fpdf.php';

    session_start();

    $Desde = $_SESSION['DESDE_CUADRE_CAJA'];
    $Hasta = $_SESSION['HASTA_CUADRE_CAJA'];
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
    $Transportador = '';
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

    foreach ($Documentos->TraeInformacionEmpresa($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {

        $NombreEmpresa = $valor['NOMBRE'];
        $NitEmpresa = $valor['NIT'];
        $DireccionEmpresa = $valor['DIR_EMPRESA'];
        $TelefonoEmpresa = $valor['TEL_EMPRESA'];
        $LogoEmpresa = $valor['LOGO'];

    }

    //*************ENCABEZADO
    $pdf->AddPage();

    //$pdf->Ln(7);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetY(19);
    $pdf->Ln(4);
    $pdf->Text(70, 28, '');
    $pdf->Ln(7);
    $pdf->SetX(147);
    $pdf->SetY(8);
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->SetX(195);


    $pdf->Image('../../Formularios/Empresas/' . $LogoEmpresa, 20, 10, 30, 22, '');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetX(80);

    $pdf->Cell(40, 10, strtoupper(utf8_decode($NombreEmpresa)), 0, 0, 'C') . $pdf->Ln(5);
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->SetX(80);
    $pdf->Cell(40, 10, 'Nit: ' . $NitEmpresa . ' - ' . utf8_decode($_SESSION['login'][0]["NOMBRE_REGIMEN"])) . $pdf->Ln(5);
    $pdf->SetX(80);
    $pdf->Cell(40, 10, $DireccionEmpresa . ' - Tel: ' . $TelefonoEmpresa) . $pdf->Ln(5);
    $pdf->Ln(14);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetX(78);
    $pdf->Cell(60, 10, 'CUADRE DE CAJA') . $pdf->Ln(8);
    $pdf->SetFont('Arial', 'I', 12);
    $pdf->SetX(58);
    $pdf->Cell(40, 10, 'Desde: ' . $Desde);
    $pdf->SetX(108);
    $pdf->Cell(40, 10, 'Hasta: ' . $Hasta);
    $pdf->SetX(80);
    $pdf->Ln(14);
    //***************DATOS DE TERCERO

    $pdf->SetX(20);

    $pdf->SetFont('', 'B', 8);
    $pdf->Cell(90, 6, "CONCEPTO", 0, 0, 'L');
    $pdf->Cell(35, 6, "CUENTA", 0, 0, 'L');
    $pdf->Cell(25, 6, "TOTAL", 0, 0, 'R') . $pdf->Ln(6);

    $pdf->SetX(20);
    $pdf->SetFont('', 'B', 12);
    $pdf->Cell(85, 7, utf8_decode("FACTURACIÓN CONTADO"), 0, 0, 'L') . $pdf->Ln(6);

    $pdf->SetFont('Arial', '', 10);

    $totalContado = 0;
    foreach ($Documentos->TraeFacturacionContado($Desde, $Hasta, $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
        $pdf->SetX(20);
        $pdf->Cell(90, 6, utf8_decode($valor["GRUPO"]), 0, 0, 'L');
        $pdf->Cell(35, 6, $valor["CUENTA"], 0, 0, 'L');
        $pdf->Cell(25, 6, number_format($valor["TOTAL"], 0, '', ','), 0, 0, 'R') . $pdf->Ln(6);
        $totalContado += $valor["TOTAL"];
    }
    $pdf->SetX(20);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(90, 6, utf8_decode("Total Facturación contado: "), 0, 0, 'L');
    $pdf->Cell(35, 6, "", 0, 0, 'L');
    $pdf->Cell(25, 6, number_format($totalContado, 0, '', ','), 0, 0, 'R') . $pdf->Ln(2);
    $pdf->SetX(20);
    $pdf->Cell(100, 6, "____________________________________________________________________________", 0, 0, 'L') . $pdf->Ln(6);
    $pdf->Ln(7);

    $pdf->SetFont('', 'B', 12);
    $pdf->SetX(20);
    $pdf->Cell(85, 7, utf8_decode("FACTURACIÓN CREDITO"), 0, 0, 'L') . $pdf->Ln(6);

    $pdf->SetFont('Arial', '', 10);

    $totalCRedito = 0;
    foreach ($Documentos->TraeFacturacionCredito($Desde, $Hasta, $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
        $pdf->SetX(20);
        $pdf->Cell(90, 6, utf8_decode($valor["GRUPO"]), 0, 0, 'L');
        $pdf->Cell(35, 6, $valor["CUENTA"], 0, 0, 'L');
        $pdf->Cell(25, 6, number_format($valor["TOTAL"], 0, '', ','), 0, 0, 'R') . $pdf->Ln(6);
        $totalCRedito += $valor["TOTAL"];
    }
    $pdf->SetX(20);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(90, 6, utf8_decode("Total Facturación credito: "), 0, 0, 'L');
    $pdf->Cell(35, 6, "", 0, 0, 'L');
    $pdf->Cell(25, 6, number_format($totalCRedito, 0, '', ','), 0, 0, 'R') . $pdf->Ln(2);
    $pdf->SetX(20);
    $pdf->Cell(100, 6, "____________________________________________________________________________", 0, 0, 'L') . $pdf->Ln(6);
    $pdf->Ln(8);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetX(20);
    $pdf->Cell(90, 6, utf8_decode("Total Facturación: "), 0, 0, 'L');
    $pdf->Cell(35, 6, "", 0, 0, 'L');
    $pdf->Cell(25, 6, number_format(($totalCRedito + $totalContado), 0, '', ','), 0, 0, 'R') . $pdf->Ln(6);


    //abonos
    $abonos = 0;
    foreach ($Documentos->TraeAbonos($Desde, $Hasta, $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
        $abonos = $valor["TOTAL"];
    }

    $pdf->Ln(7);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetX(20);
    $pdf->Cell(90, 6, "Abonos a Facturas credito: ", 0, 0, 'L');
    $pdf->Cell(35, 6, "", 0, 0, 'L');
    $pdf->Cell(25, 6, number_format($abonos, 0, '', ','), 0, 0, 'R') . $pdf->Ln(2);
    $pdf->SetX(20);
    $pdf->Cell(100, 6, "____________________________________________________________________________", 0, 0, 'L') . $pdf->Ln(6);

    //FORMAS DE PAGO%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    $pdf->Ln(10);
    $pdf->SetFont('', 'B', 12);
    $pdf->SetX(20);
    $pdf->Cell(85, 7, "FORMAS DE PAGO", 0, 0, 'L') . $pdf->Ln(6);

    $pdf->SetFont('Arial', '', 10);

    $totalFormasPago = 0;
    foreach ($Documentos->TraeFormasPagos($Desde, $Hasta, $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
        $pdf->SetX(20);
        $pdf->Cell(90, 6, utf8_decode($valor["NOMBRE"]), 0, 0, 'L');
        $pdf->Cell(35, 6, $valor["CUENTA"], 0, 0, 'L');
        $pdf->Cell(25, 6, number_format($valor["TOTAL"], 0, '', ','), 0, 0, 'R') . $pdf->Ln(6);
        $totalFormasPago += $valor["TOTAL"];
    }
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetX(20);
    $pdf->Cell(90, 6, "Total Formas de Pago: ", 0, 0, 'L');
    $pdf->Cell(35, 6, "", 0, 0, 'L');
    $pdf->Cell(25, 6, number_format($totalFormasPago, 0, '', ','), 0, 0, 'R') . $pdf->Ln(2);
    $pdf->SetX(20);
    $pdf->Cell(100, 6, "____________________________________________________________________________", 0, 0, 'L') . $pdf->Ln(6);


    $pdf->Output();

    $pdf->Cell($pdf->PageNo());
