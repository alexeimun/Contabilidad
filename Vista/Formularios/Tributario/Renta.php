<?
    require('../../fpdf/rotation.php');

    $pdf = new PDFR();

    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 8);

    $pdf->SetXY(5, 3);
    $pdf->Cell(200, 7, '', 1, 0, 'L', false);

    $pdf->Text(70, 6, utf8_decode('Declaración de Renta y Complementarios Personas'));
    $pdf->Text(65, 9, utf8_decode('Naturales y Asimiladas No Obligadas a llevar contabilidad'));
    $pdf->SetFont('Arial', '', 6);
    //Datos del declarante
    $pdf->SetFillColor(242, 249, 244);
    $pdf->SetDrawColor(60, 50, 50);

    $pdf->SetXY(11, 10);
    $pdf->Cell(194, 4, '', 0, 0, 'L', true);

    $pdf->SetXY(5, 10);
    $pdf->Cell(6, 15, '', 1, 0, 'L', false);
    $pdf->RotatedText(7, 21, 'Datos del', 90);
    $pdf->RotatedText(10, 22, 'declarante', 90);

    $pdf->SetX(11);
    $pdf->Cell(194, 15, '', 1, 0, 'L', false);
    $pdf->SetX(11);
    $pdf->Cell(194, 9, '', 1, 0, 'L', false);


    $pdf->SetXY(11, 10);
    $pdf->Cell(48, 9, '', 1, 0, 'L', false);

    $pdf->SetXY(11, 10);
    $pdf->Cell(6, 5, utf8_decode('Número de Indentificación Tributaria(NIT)'));
    $pdf->SetXY(54, 10);
    $pdf->Cell(40, 5, 'DV');

    $pdf->SetXY(60, 10);
    $pdf->Cell(40, 5, 'Primer apellido');
    $pdf->SetXY(90, 10);
    $pdf->Cell(40, 5, 'Segndo apellido');

    $pdf->SetXY(110, 10);
    $pdf->Cell(40, 5, 'Primer nombre');

    $pdf->SetXY(150, 10);
    $pdf->Cell(40, 5, 'Otros nombres');

    $pdf->SetY(19);
    $pdf->SetX(11);
    $pdf->Cell(193, 5, '', 0, 0, '', false);//


    $pdf->SetY(19);
    $pdf->SetX(11);
    $pdf->Cell(6, 5, utf8_decode('Actividad ecónomica                                                 Si es una correción indique:        Cód.                            No. Formulario anterior'));

    //Cuadritos
    $pdf->SetXY(35, 20);
    $pdf->Cell(3, 3, '', 1, 0, 'L', false);
    $pdf->SetXY(38, 20);
    $pdf->Cell(3, 3, '', 1, 0, 'L', false);
    $pdf->SetXY(41, 20);
    $pdf->Cell(3, 3, '', 1, 0, 'L', false);
    $pdf->SetXY(44, 20);
    $pdf->Cell(3, 3, '', 1, 0, 'L', false);
    //

    $pdf->SetXY(180, 19);
    $pdf->Cell(25, 6, '', 1, 0, 'L', false);

    $pdf->SetXY(180, 18);
    $pdf->Cell(6, 5, utf8_decode('Cód. Dirección'));

    $pdf->SetXY(180, 21);
    $pdf->Cell(6, 4, utf8_decode('Seccional'));


    $pdf->SetXY(5, 25);
    $pdf->Cell(200, 5, utf8_decode('Fracción año gravab. ' . date("Y") . ' (Marque "X")'), 1, 0, 'L', false);

    $pdf->SetXY(46, 26);
    $pdf->Cell(5, 3, '', 1, 0, 'L', false);
    $pdf->SetXY(5, 25);
    $pdf->Cell(50, 5, '', 1, 0, 'L', false);

    $pdf->SetXY(55, 25);
    $pdf->Cell(85, 5, utf8_decode('Si es beneficiario de un convenio para evitar la doble tributación (Marque"X")'), 1, 0, 'L', false);

    $pdf->SetXY(131, 26);
    $pdf->Cell(5, 3, '', 1, 0, 'L', false);

    $pdf->SetXY(140, 25);
    $pdf->Cell(65, 5, 'Cambio titular inversión extranjera (Marque"X")', 1, 0, 'L', false);

    $pdf->SetXY(188, 26);
    $pdf->Cell(5, 3, '', 1, 0, 'L', false);


    //Patrimonio

    $pdf->SetXY(5, 30);
    $pdf->Cell(6, 15, '', 1, 0, 'L', false);
    $pdf->RotatedText(9, 42, 'Patrimonio', 90);
    //Filas
    $pdf->SetXY(11, 30);
    $pdf->Cell(90, 5, 'Total patrimonio bruto', 1, 0, 'L', true);

    $pdf->SetXY(11, 35);
    $pdf->Cell(90, 5, 'Deudas', 1, 0, 'L', false);

    $pdf->SetFont('Arial', 'B', 6);
    $pdf->SetXY(11, 40);
    $pdf->Cell(90, 5, utf8_decode('Total patrimonio líquido'), 1, 0, 'L', true);
    $pdf->SetFont('Arial', '', 6);
    //end filas

    //Ingresos
    $pdf->SetXY(5, 45);
    $pdf->Cell(6, 71, '', 1, 0, 'L', false);
    $pdf->RotatedText(9, 83, 'Ingresos', 90);
    //Filas

    $pdf->SetXY(11, 45);
    $pdf->Cell(90, 5, utf8_decode('Recibidos como empleado'), 1, 0, 'L', false);

    $pdf->SetXY(11, 50);
    $pdf->Cell(90, 6, '', 1, 0, 'L', true);
    $pdf->Text(12, 53, utf8_decode('Recibidos por pensiones jubilación, invalidez,'));
    $pdf->Text(12, 55, utf8_decode('vejez,de sobreviviente y riesgos profesionales'));

    $pdf->SetXY(11, 56);
    $pdf->Cell(90, 5, utf8_decode('Honorarios, comisiones y servicios'), 1, 0, 'L', false);

    $pdf->SetXY(11, 61);
    $pdf->Cell(90, 5, utf8_decode('Intereses y rendimientos financieros'), 1, 0, 'L', true);

    $pdf->SetXY(11, 66);
    $pdf->Cell(90, 5, utf8_decode('Dividendos y participaciones'), 1, 0, 'L', false);

    $pdf->SetXY(11, 71);
    $pdf->Cell(90, 5, utf8_decode('Otros (Arrendamientos, etc.)'), 1, 0, 'L', true);

    $pdf->SetXY(11, 76);
    $pdf->Cell(90, 5, utf8_decode('Obtenidos en el exterior'), 1, 0, 'L', false);

    $pdf->SetFont('Arial', 'B', 6);
    $pdf->SetXY(11, 81);
    $pdf->Cell(90, 5, utf8_decode('Total ingresos recibidos por concepto de renta'), 1, 0, 'L', true);
    $pdf->SetFont('Arial', '', 6);

    //Ingresos :: No Constitut. renta ni Gan Ocasional

    $pdf->SetXY(11, 86);
    $pdf->Cell(6, 25, '', 1, 0, 'L', false);
    $pdf->RotatedText(14, 108, 'No Constitut. renta', 90);
    $pdf->RotatedText(16, 107, 'ni Gan Ocasional', 90);

    $pdf->SetXY(17, 86);
    $pdf->Cell(84, 5, 'Dividendos y participaciones', 1, 0, 'L', false);

    $pdf->SetXY(17, 91);
    $pdf->Cell(84, 5, 'Donaciones', 1, 0, 'L', true);

    $pdf->SetXY(17, 96);
    $pdf->Cell(84, 5, '', 1, 0, 'L', false);
    $pdf->Text(18, 98, utf8_decode('Pagos a terceros (Salud, educación y'));
    $pdf->Text(18, 100, utf8_decode('alimentación)'));

    $pdf->SetXY(17, 101);
    $pdf->Cell(84, 5, 'Otros ingresos no constitutivos de renta', 1, 0, 'L', true);

    $pdf->SetXY(17, 106);
    $pdf->Cell(84, 5, 'Total ingresos no constitutivos de renta', 1, 0, 'L', false);

    $pdf->SetFont('Arial', 'B', 6);
    $pdf->SetXY(11, 111);
    $pdf->Cell(90, 5, utf8_decode('Total ingresos netos'), 1, 0, 'L', true);
    $pdf->SetFont('Arial', '', 6);


    //Costos y deduciones
    $pdf->SetXY(5, 116);
    $pdf->Cell(6, 30, '', 1, 0, 'L', false);
    $pdf->RotatedText(9, 140, 'Costos y deduciones', 90);

    $pdf->SetXY(11, 116);
    $pdf->Cell(90, 5, '', 1, 0, 'L', false);
    $pdf->Text(12, 118, utf8_decode('Gastos de nómina incluidos los aportes a seguridad'));
    $pdf->Text(12, 120, utf8_decode('social y parafiscales'));

    $pdf->SetXY(11, 121);
    $pdf->Cell(90, 5, utf8_decode('Deducción por dependientes económicos'), 1, 0, 'L', true);

    $pdf->SetXY(11, 126);
    $pdf->Cell(90, 5, utf8_decode('Deducción por pagos de intereses de vivienda'), 1, 0, 'L', false);

    $pdf->SetXY(11, 131);
    $pdf->Cell(90, 5, utf8_decode('Otros costos y deducciones'), 1, 0, 'L', true);

    $pdf->SetXY(11, 136);
    $pdf->Cell(90, 5, 'Costos y gastos incurridos en el exterior', 1, 0, 'L', false);

    $pdf->SetFont('Arial', 'B', 6);
    $pdf->SetXY(11, 141);
    $pdf->Cell(90, 5, 'Total costos y deducciones', 1, 0, 'L', true);
    $pdf->SetFont('Arial', '', 6);


    //Renta
    $pdf->SetXY(5, 146);
    $pdf->Cell(6, 70, '', 1, 0, 'L', false);
    $pdf->RotatedText(9, 180, 'Renta', 90);
    //Filas
    $pdf->SetXY(11, 146);
    $pdf->Cell(90, 5, utf8_decode('Renta líquida ordinaria del ejercicio'), 1, 0, 'L', false);

    $pdf->SetXY(11, 151);
    $pdf->Cell(90, 5, utf8_decode('o Pérdida líquida del ejercicio'), 1, 0, 'L', true);

    $pdf->SetXY(11, 156);
    $pdf->Cell(90, 5, utf8_decode('Compensaciones'), 1, 0, 'L', false);

    $pdf->SetXY(11, 161);
    $pdf->Cell(90, 5, utf8_decode('Renta líquida'), 1, 0, 'L', true);

    $pdf->SetXY(11, 166);
    $pdf->Cell(90, 5, utf8_decode('Renta presuntiva'), 1, 0, 'L', false);

    //Renta externa
    $pdf->SetXY(11, 171);
    $pdf->Cell(6, 35, '', 1, 0, 'L', false);
    $pdf->RotatedText(15, 190, 'Renta externa', 90);

    $pdf->SetXY(17, 171);
    $pdf->Cell(84, 5, '', 1, 0, 'L', true);
    $pdf->Text(18, 173, utf8_decode('Gastos de representación y otras rentas de'));
    $pdf->Text(18, 175, utf8_decode('trabajo'));

    $pdf->SetXY(17, 176);
    $pdf->Cell(84, 5, utf8_decode('Aportes obligatorios al fondo de pensión'), 1, 0, 'L', false);

    $pdf->SetXY(17, 181);
    $pdf->Cell(84, 5, utf8_decode('Aportes a fondos de pensiones voluntarios'), 1, 0, 'L', true);

    $pdf->SetXY(17, 186);
    $pdf->Cell(84, 5, utf8_decode('Aportes a cuentas AFC'), 1, 0, 'L', false);

    $pdf->SetXY(17, 191);
    $pdf->Cell(84, 5, utf8_decode('Otras rentas exentas'), 1, 0, 'L', true);

    $pdf->SetXY(17, 196);
    $pdf->Cell(84, 5, utf8_decode('Por pagos laborales (25%) y pensiones'), 1, 0, 'L', false);

    $pdf->SetFont('Arial', 'B', 6);
    $pdf->SetXY(17, 201);
    $pdf->Cell(84, 5, utf8_decode('Total renta exenta'), 1, 0, 'L', true);
    $pdf->SetFont('Arial', '', 6);

    $pdf->SetXY(11, 206);
    $pdf->Cell(90, 5, utf8_decode('Rentas gravables'), 1, 0, 'L', false);

    $pdf->SetFont('Arial', 'B', 6);
    $pdf->SetXY(11, 211);
    $pdf->Cell(90, 5, utf8_decode('Renta líquida gravable'), 1, 0, 'L', true);
    $pdf->SetFont('Arial', '', 6);

    /*
     Segunda Columna
     Ganancia Ocacional
   */

    $pdf->SetXY(101, 30);
    $pdf->Cell(6, 26, '', 1, 0, 'L', false);
    $pdf->RotatedText(105, 50, 'Ganan. Ocasional', 90);

    #Filas

    $pdf->SetXY(107, 30);
    $pdf->Cell(98, 5, utf8_decode('Ingresos por ganancias ocasionales en el país'), 1, 0, 'L', true);

    $pdf->SetXY(107, 35);
    $pdf->Cell(98, 5, 'Ingresos por ganancias ocasionales en el exterior', 1, 0, 'L', false);

    $pdf->SetXY(107, 40);
    $pdf->Cell(98, 5, 'Costos por ganancias ocasionales', 1, 0, 'L', true);

    $pdf->SetXY(107, 45);
    $pdf->Cell(98, 5, 'Ganancias ocasionales no gravadas y exentas', 1, 0, 'L', false);

    $pdf->SetXY(107, 50);
    $pdf->Cell(98, 6, 'Ganancias ocasionales gravables', 1, 0, 'L', true);

    //Determinación de la renta gravable alternativa - IMAN, para empleados

    $pdf->SetXY(101, 56);
    $pdf->Cell(6, 60, '', 1, 0, 'L', false);
    $pdf->RotatedText(104, 100, utf8_decode('Determinación de la renta gravable'), 90);
    $pdf->RotatedText(106, 100, utf8_decode('alternativa - IMAN, para empleados'), 90);

    #Filas

    $pdf->SetXY(107, 56);
    $pdf->Cell(98, 5, utf8_decode('Total ingresos obtenidos período gravable'), 1, 0, 'L', false);

    $pdf->SetXY(107, 61);
    $pdf->Cell(98, 5, utf8_decode('Dividendos y participaciones no gravados'), 1, 0, 'L', true);

    $pdf->SetXY(107, 66);
    $pdf->Cell(98, 5, '', 1, 0, 'L', false);
    $pdf->Text(108, 68, utf8_decode('Indemnizaciones en dinero o en especie por'));
    $pdf->Text(108, 70, utf8_decode('seguro de daño'));

    $pdf->SetXY(107, 71);
    $pdf->Cell(98, 5, '', 1, 0, 'L', true);
    $pdf->Text(108, 73, utf8_decode('Aportes obligatorios al sistema general de'));
    $pdf->Text(108, 75, utf8_decode('seguridad social a cargo del empleado'));

    $pdf->SetXY(107, 76);
    $pdf->Cell(98, 5, utf8_decode('Gastos de representación exentos'), 1, 0, 'L', false);

    $pdf->SetXY(107, 81);
    $pdf->Cell(98, 5, '', 1, 0, 'L', true);
    $pdf->Text(108, 83, utf8_decode('Pagos catastróficos en salud efectivamente'));
    $pdf->Text(108, 85, utf8_decode('certificados no cubiertos por el POS'));

    $pdf->SetXY(107, 86);
    $pdf->Cell(98, 5, utf8_decode('Pérdidas por desastres o calamidades públicas'), 1, 0, 'L', false);

    $pdf->SetXY(107, 91);
    $pdf->Cell(98, 5, '', 1, 0, 'L', true);
    $pdf->Text(108, 93, utf8_decode('Aportes obligatorios a seguridad social de un'));
    $pdf->Text(108, 95, utf8_decode('empleado del servicio doméstico'));

    $pdf->SetXY(107, 96);
    $pdf->Cell(98, 5, utf8_decode('Costo fiscal de los bienes enajenados'), 1, 0, 'L', false);

    $pdf->SetXY(107, 101);
    $pdf->Cell(98, 5, utf8_decode('Otras indemnizaciones Art 332 Lit. i) ET.'), 1, 0, 'L', true);

    $pdf->SetXY(107, 106);
    $pdf->Cell(98, 5, '', 1, 0, 'L', false);
    $pdf->Text(108, 108, utf8_decode('Retiros fondos de pensión de jubilación e'));
    $pdf->Text(108, 110, utf8_decode('invalidez; fondos de cesantías y cuentas AFC'));

    $pdf->SetXY(107, 111);
    $pdf->Cell(98, 5, utf8_decode('Renta Gravable Alternativa (Base del IMAN)'), 1, 0, 'L', true);


    //Liquidación privada

    $pdf->SetXY(101, 116);
    $pdf->Cell(6, 95, '', 1, 0, 'L', false);
    $pdf->RotatedText(105, 165, utf8_decode('Liquidación privada'), 90);

    #Filas

    $pdf->SetXY(107, 116);
    $pdf->Cell(98, 5, utf8_decode('Impuesto sobre la renta líquida gravable'), 1, 0, 'L', false);

    $pdf->SetXY(107, 121);
    $pdf->Cell(98, 5, '', 1, 0, 'L', true);
    $pdf->Text(108, 123, utf8_decode('Impuesto Mínimo Alternativo Nacional -IMAN,'));
    $pdf->Text(108, 125, utf8_decode('empleados'));

    //Liquidación privada :: Descuentos

    $pdf->SetXY(107, 126);
    $pdf->Cell(6, 25, '', 1, 0, 'L', false);
    $pdf->RotatedText(110, 145, utf8_decode('Descuentos'), 90);

    $pdf->SetXY(113, 126);
    $pdf->Cell(92, 5, '', 1, 0, 'L', false);
    $pdf->Text(114, 128, utf8_decode('Por impuestos pagados en el exterior de los'));
    $pdf->Text(114, 130, utf8_decode('literales a) a c) del art. 254 E.T.'));

    $pdf->SetXY(113, 131);
    $pdf->Cell(92, 5, '', 1, 0, 'L', true);
    $pdf->Text(114, 133, utf8_decode('Por impuestos pagados en el exterior del'));
    $pdf->Text(114, 135, utf8_decode('literal d) del art. 254 E.T.'));

    $pdf->SetXY(113, 136);
    $pdf->Cell(92, 5, '', 1, 0, 'L', false);
    $pdf->Text(114, 138, utf8_decode('Por impuestos pagados en el exterior,'));
    $pdf->Text(114, 140, utf8_decode('distintos a los registrados anteriormente'));

    $pdf->SetXY(113, 141);
    $pdf->Cell(92, 5, utf8_decode('Otros'), 1, 0, 'L', true);

    $pdf->SetFont('Arial', 'B', 6);
    $pdf->SetXY(113, 146);
    $pdf->Cell(92, 5, utf8_decode('Total descuentos tributarios'), 1, 0, 'L', false);
    $pdf->SetFont('Arial', '', 6);

    // Fin Descuentos

    $pdf->SetXY(107, 151);
    $pdf->Cell(98, 5, utf8_decode('Impuesto neto de renta'), 1, 0, 'L', true);

    $pdf->SetXY(107, 156);
    $pdf->Cell(98, 5, utf8_decode('Impuesto de ganancias ocasionales'), 1, 0, 'L', false);

    $pdf->SetXY(107, 161);
    $pdf->Cell(98, 5, '', 1, 0, 'L', true);
    $pdf->Text(108, 163, utf8_decode('Descuento por impuestos pagados en el exterior'));
    $pdf->Text(108, 165, utf8_decode('por ganancias ocasionales'));

    $pdf->SetXY(107, 166);
    $pdf->Cell(98, 5, utf8_decode('Impuesto neto de renta'), 1, 0, 'L', false);

    $pdf->SetXY(107, 171);
    $pdf->Cell(98, 5, utf8_decode('Anticipo renta por el año gravable ' . (Date("Y") - 1)), 1, 0, 'L', true);

    $pdf->SetXY(107, 176);
    $pdf->Cell(98, 5, '', 1, 0, 'L', false);
    $pdf->Text(108, 178, utf8_decode('Saldo a favor año ' . (Date("Y") - 2) . ' sin solicitud de devolución'));
    $pdf->Text(108, 180, utf8_decode('o compensación'));


    $pdf->SetXY(107, 181);
    $pdf->Cell(98, 5, utf8_decode('Total retenciones año gravable ' . (Date("Y") - 1)), 1, 0, 'L', true);

    $pdf->SetXY(107, 186);
    $pdf->Cell(98, 5, utf8_decode('Anticipo renta por el año gravable ' . Date("Y")), 1, 0, 'L', false);

    $pdf->SetXY(107, 191);
    $pdf->Cell(98, 5, utf8_decode('Saldo a pagar por impuesto'), 1, 0, 'L', true);

    $pdf->SetXY(107, 196);
    $pdf->Cell(98, 5, utf8_decode('Sanciones'), 1, 0, 'L', false);

    $pdf->SetFont('Arial', 'B', 6);
    $pdf->SetXY(107, 201);
    $pdf->Cell(98, 5, utf8_decode('Total a pagar'), 1, 0, 'L', true);

    $pdf->SetXY(107, 206);
    $pdf->Cell(98, 5, utf8_decode('o Total saldo a favor'), 1, 0, 'L', false);
    $pdf->SetFont('Arial', '', 6);
    //Fin Liquidación privada

    $pdf->SetXY(101, 211);
    $pdf->Cell(104, 5, utf8_decode('No. Identificación signatario'), 1, 0, 'L', true);

    //Cuadros de valores
    $pdf->SetXY(11, 30);
    $pdf->Cell(51, 186, '', 1, 0, 'L', false);

    $pdf->SetXY(11, 30);
    $pdf->Cell(151, 186, '', 1, 0, 'L', false);


    $pdf->Output();
    $pdf->Cell($pdf->PageNo());

