<?php

    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Documentos.php';
    include '../../../Clases/cls_Parametros.php';
    include '../../fpdf/fpdf.php';

    session_start();

    $Desde = $_SESSION['DESDE_CUADRE_CAJA'];
    $Hasta = $_SESSION['HASTA_CUADRE_CAJA'];
    //************************************************************
    //*********************Informe************************
    //*************************************************************
    $pdf = new FPDF();
    $Documentos = new cls_Documentos();
    $Parametros = new cls_Parametros();

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
    $pdf->Cell(60, 10, 'CAJA DIARIA') . $pdf->Ln(8);
    $pdf->SetFont('Arial', 'I', 12);
    $pdf->SetX(58);
    $pdf->Cell(40, 10, 'Desde: ' . $Desde);
    $pdf->SetX(108);
    $pdf->Cell(40, 10, 'Hasta: ' . $Hasta);
    $pdf->SetX(80);
    $pdf->Ln(14);
    //***************DATOS DE TERCERO

    $pdf->SetX(20);

    $pdf->SetFont('', '', 7);
    $pdf->Cell(25, 6, utf8_decode("CÓDIGO CUENTA"), 0, 0, 'L');
    $pdf->Cell(30, 6, "NOMBRE CUENTA", 0, 0, 'L');
    $pdf->Cell(50, 6, "COMPROBANTE", 0, 0, 'L');
    $pdf->Cell(30, 6, utf8_decode("DÉBITOS"), 0, 0, 'R');
    $pdf->Cell(30, 6, utf8_decode("CRÉDITOS"), 0, 0, 'R') . $pdf->Ln(9);


    $pdf->SetX(20);
    $pdf->SetFont('Arial', '', 7);

    $TotalCuentaCre = 0;
    $TotalCuentaDeb = 0;
    foreach ($Documentos->TraeCuentasMovidas($Desde, $Hasta, $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
        $TotalCuentaCre = 0;
        $TotalCuentaDeb = 0;
        if ($valor["V"] > 0) {
            $pdf->SetFont('', 'B', 7);
            $pdf->SetX(20);
            $pdf->Cell(25, 6, $valor["CODIGO"], 0, 0, 'C');
            $pdf->Cell(60, 6, utf8_decode($valor["NOMBRE"]), 0, 0, 'L') . $pdf->Ln(6);
        }
        foreach ($Parametros->TraeDocumentos($_SESSION['login'][0]["ID_EMPRESA"]) as $llave1 => $valor1) {
//                    $valor1['ID_DOCUMENTO'];

            foreach ($Documentos->TraeCreditosyDebitosParamCuenta($Desde, $Hasta, $_SESSION['login'][0]["ID_EMPRESA"], $valor1['ID_DOCUMENTO'], $valor["ID_CUENTA_MOV"]) as $llave2 => $valor2) {
                if ($valor2['CREDITOS'] != 0 || $valor2['DEBITOS'] != 0) {
                    $pdf->SetFont('', '', 7);
                    $pdf->Cell(25, 6, '', 0, 0, 'L');
                    $pdf->Cell(40, 6, "", 0, 0, 'L');
                    $pdf->Cell(50, 6, $valor1['TIPO'] . '   ' . $valor1['NOMBRE_DOCUMENTO'], 0, 0, 'L');
                    $pdf->Cell(30, 6, number_format($valor2['CREDITOS'], 0, '', ','), 0, 0, 'R');
                    $pdf->Cell(30, 6, number_format($valor2['DEBITOS'], 0, '', ','), 0, 0, 'R') . $pdf->Ln(4);
                    $TotalCuentaCre += $valor2['CREDITOS'];
                    $TotalCuentaDeb += $valor2['DEBITOS'];
                }
            }
        }
        if ($TotalCuentaDeb != 0 || $TotalCuentaCre != 0) {
            $pdf->SetFont('', 'B', 8);
            $pdf->Cell(25, 6, '', 0, 0, 'L');
            $pdf->Cell(40, 6, "", 0, 0, 'L');
            $pdf->Cell(50, 6, "TOTAL CUENTA ===>", 0, 0, 'L');
            $pdf->Cell(30, 6, number_format($TotalCuentaCre, 0, '', ','), 0, 0, 'R');
            $pdf->Cell(30, 6, number_format($TotalCuentaDeb, 0, '', ','), 0, 0, 'R') . $pdf->Ln(2);

            $pdf->SetX(20);
            $pdf->Cell(100, 6, "________________________________________________________________________________________________________", 0, 0, 'L');
            $pdf->Ln(9);
        }

    }


    $pdf->Output();

    $pdf->Cell($pdf->PageNo());
