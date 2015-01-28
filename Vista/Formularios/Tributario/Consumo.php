<?
    require('../../fpdf/rotation.php');

    $pdf = new PDFR();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 8);

    $pdf->SetXY(5, 3);
    $pdf->Cell(200, 7, '', 1, 0, 'L', false);

    $pdf->Text(81, 6, utf8_decode('Declaración del Régimen Simplificado del'));
    $pdf->Text(85, 9, utf8_decode('del Impuesto Nacional al Consumo'));
    $pdf->SetFont('Arial', '', 6);

    //Datos del declarante
    $pdf->SetFillColor(242, 249, 244);
    $pdf->SetDrawColor(60, 50, 50);

    $pdf->SetXY(12, 10);
    $pdf->Cell(193, 4, '', 0, 0, 'L', true);

    $pdf->SetX(5);
    $pdf->Cell(7, 18, '', 1, 0, 'L', false);
    $pdf->RotatedText(7, 23, 'Datos del', 90);
    $pdf->RotatedText(10, 24, 'declarante', 90);

    $pdf->SetXY(12, 10);
    $pdf->Cell(193, 18, '', 1, 0, 'L', false);
    $pdf->SetX(12);
    $pdf->Cell(193, 9, '', 1, 0, 'L', false);
    $pdf->SetX(12);


    $pdf->SetXY(12, 10);
    $pdf->Cell(48, 9, '', 1, 0, 'L', false);


//    $pdf->Cell(50,9,'d',1,0,'L',false);


    $pdf->SetXY(12, 10);
    $pdf->Cell(7, 5, utf8_decode('Número de Indentificación Tributaria(NIT)'));
    $pdf->SetX(55);
    $pdf->Cell(40, 5, 'DV');

    $pdf->SetXY(60, 10);
    $pdf->Cell(40, 5, 'Primer apellido');
    $pdf->SetX(90);
    $pdf->Cell(40, 5, 'Segndo apellido');

    $pdf->SetX(120);
    $pdf->Cell(40, 5, 'Primer nombre');

    $pdf->SetX(150);
    $pdf->Cell(40, 5, 'Otros nombres');

    $pdf->SetY(19);
    $pdf->SetX(12);
    $pdf->Cell(193, 6, '', 0, 0, '', true);//
    $pdf->SetY(10);
    $pdf->SetX(5);
    $pdf->Cell(7, 18, '', 1, 0, 'L', false);

    $pdf->SetY(19);
    $pdf->SetX(12);
    $pdf->Cell(7, 5, utf8_decode('Razón Social'));

    $pdf->SetY(19);
    $pdf->SetX(180);
    $pdf->Cell(25, 9, '', 1, 0, 'L', false);

    $pdf->SetY(18);
    $pdf->SetX(180);
    $pdf->Cell(7, 5, utf8_decode('Cód. Dirección'));

    $pdf->SetY(21);
    $pdf->SetX(180);
    $pdf->Cell(7, 5, utf8_decode('Seccional'));


    //Ingresos
    $pdf->SetY(28);
    $pdf->SetX(5);
    $pdf->Cell(200, 10, '', 1, 0, 'L', false);
    //Valores


    $pdf->SetY(28);
    $pdf->SetX(5);
    $pdf->Cell(200, 4, '', 1, 0, 'L', false);

    $pdf->Text(6, 31, utf8_decode('Si es una correción indique:        Cód.                            No. Formulario anterior'));

    $pdf->SetY(32);
    $pdf->SetX(5);
    $pdf->Cell(145, 6, 'Ingresos y gastos', 1, 0, 'C', false);

    $pdf->SetFillColor(166, 214, 180);
    $pdf->SetY(32);
    $pdf->SetX(150);
    $pdf->Cell(55, 6, 'Valores', 1, 0, 'C', true);
    $pdf->SetFillColor(242, 249, 244);

    $pdf->SetY(38);
    $pdf->SetX(5);
    $pdf->Cell(7, 18, '', 1, 0, 'L', false);
    $pdf->RotatedText(9, 50, 'Ingresos', 90);

    $pdf->SetY(38);
    $pdf->SetX(12);
    $pdf->Cell(193, 6, 'Ingresos por servicios de restaurante', 1, 0, 'L', true);

    $pdf->SetY(44);
    $pdf->SetX(12);
    $pdf->Cell(193, 6, 'Ingresos por servicios de bares, tabernas y discotecas', 1, 0, 'L', false);

    $pdf->SetFont('Arial', 'B', 6);
    $pdf->SetY(50);
    $pdf->SetX(12);
    $pdf->Cell(193, 6, 'Total ingreos', 1, 0, 'L', true);
    $pdf->SetFont('Arial', '', 6);


    $pdf->SetY(56);
    $pdf->SetX(5);
    $pdf->Cell(7, 48, '', 1, 0, 'L', false);
    $pdf->RotatedText(9, 90, 'Costos y Gastos', 90);

    $pdf->SetY(56);
    $pdf->SetX(12);
    $pdf->Cell(193, 6, 'Costo de los insumos por prestar de restaurante', 1, 0, 'L', false);

    $pdf->SetY(62);
    $pdf->SetX(12);
    $pdf->Cell(193, 6, 'Costo de los insumos por servicios de bares, tabernas y discotecas', 1, 0, 'L', true);

    $pdf->SetY(68);
    $pdf->SetX(12);
    $pdf->Cell(193, 6, 'Costo de arrendamiento', 1, 0, 'L', false);

    $pdf->SetY(74);
    $pdf->SetX(12);
    $pdf->Cell(193, 6, utf8_decode('Gastos de nómina'), 1, 0, 'L', true);

    $pdf->SetY(80);
    $pdf->SetX(12);
    $pdf->Cell(193, 6, 'Otros gastos', 1, 0, 'L', false);

    $pdf->SetY(86);
    $pdf->SetX(12);
    $pdf->Cell(193, 6, utf8_decode('Aportes parafiscales SENA, ICBF y cajas de compesación'), 1, 0, 'L', true);

    $pdf->SetY(92);
    $pdf->SetX(12);
    $pdf->Cell(193, 6, 'Aportes al sistema de seguridad social', 1, 0, 'L', false);

    $pdf->SetFont('Arial', 'B', 6);
    $pdf->SetY(98);
    $pdf->SetX(12);
    $pdf->Cell(193, 6, 'Total costos y gastos', 1, 0, 'L', true);

    $pdf->SetY(104);
    $pdf->SetX(5);
    $pdf->Cell(200, 6, utf8_decode('Total impuestos pagados en la adquisicioón de bienes que constituyen costo en renta'), 1, 0, 'L', false);

    $pdf->SetY(110);
    $pdf->SetX(5);
    $pdf->Cell(200, 6, utf8_decode('Total impuestos pagados en la adquisicioón de bienes y servicios que constituyen dedución en renta'), 1, 0, 'L', true);

    $pdf->SetY(116);
    $pdf->SetX(5);
    $pdf->Cell(200, 6, utf8_decode('sanción por extemporaneidad'), 1, 0, 'L', false);

    $pdf->SetFillColor(166, 214, 180);
    $pdf->SetY(122);
    $pdf->SetX(5);
    $pdf->Cell(200, 8, '', 1, 0, 'L', true);

    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetY(122);
    $pdf->SetX(5);
    $pdf->Cell(40, 8, utf8_decode('Número de empleados '), 1, 0, 'L', true);

    $pdf->SetY(28);
    $pdf->SetX(150);
    $pdf->Cell(55, 94, '', 1, 0, '', false);


    $pdf->Output();
    $pdf->Cell($pdf->PageNo());

