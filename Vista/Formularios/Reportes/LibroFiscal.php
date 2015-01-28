<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Parametros.php';
    include '../../../Clases/cls_Documentos.php';
    session_start();

    if (isset($_SESSION['login']) == '' || $_SESSION['permisos'][28][1] == 0)
        echo '<script language = javascript> self.location = "../Otros/Login.php"</script>';

    $Master = new Master();
    $menu = $Master->Menu();
    $Parametros = new cls_Parametros();
    $Documentos = new cls_Documentos();
    $date = date("t", mktime(0, 0, 0, 12/*mes*/, 1, 2014 /*mes*/));
    $me = '';
    $tabs = '';
    $btnaplicar = '';
    $formulario = '';

    $manual = '<table id="manual" cellpadding="1" cellspacing="1">
            <thead>
            <tr>
                <th></th>
                <th>Ingresos Diarios Global</th>
                <th colspan="3">Egresos Diarios Global</th>
                <th></th>
            </tr>
            <tr>
                <th>Día</th>
                <th>Ventas y/o prestación de servicios</th>
                <th>Compra de bienes(sin IVA)</th>
                <th>Pago de servicios(sin IVA)</th>
                <th>IVA</th>
                <th>Saldo</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>';

    if (!empty($_POST)) {

        $cont = 1;
        $valor = [];

        for ($i = 0; $i < $date; $i ++) {

            $valor = $Documentos->TraeLibroFiscal($_POST['ano'], $_POST['mes'], $_SESSION['login'][0]["ID_EMPRESA"], $cont);

        }
    }

?>
<html>
<head>
    <title>Libro Fiscal</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>

    <script type="text/javascript" language="javascript" src="../../Js/jquery.js"></script>
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script type="text/javascript" language="javascript" src="../../Js/jquery.dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/tabs.css"/>
    <script type="text/javascript" src="../../Js/Excel/jquery.battatech.excelexport.js"></script>
    <script src="../../Js/PrintArea.js"></script>


    <style type="text/css">


        #wrapper {
            height: 210%;
        }

        #automatico td {
            border: 1px solid #A9A9A9;
        }

        #automatico td :first-of-type {
            text-align: center;
        }

        table thead tr th {
            border: 1px solid black;
            text-align: center;
            font-family: "Helvetica Neue Light", "HelveticaNeue-Light", "Helvetica Neue", Calibri, Helvetica, Arial, sans-serif;
            font-size: 15px;
            background: #5E83A3;
            color: #f1f6f4;
        }

        table td table td input {
            font-family: "Helvetica Neue Light", "HelveticaNeue-Light", "Helvetica Neue", Calibri, Helvetica, Arial, sans-serif;
            font-size: 16px;
            height: 20px;
        }

        table tr td:first-of-type, table tr td:first-of-type input, table thead tr th:first-of-type {
            width: 80px;
        }

        table tr td:last-of-type, table tr td:last-of-type input, table thead tr th:last-of-type {
            width: 120px;
        }

        table tr td:nth-last-of-type(2), table tr td:nth-last-of-type(2) input, table thead tr th:nth-last-of-type(2) {
            width: 120px;
        }

        table tbody tr td:nth-of-type(2) input {
            width: 216px;
        }

        table tbody tr td:nth-of-type(2) input {
            width: 216px;
        }

        table thead tr:first-of-type th:last-of-type, tr:first-of-type th:first-of-type {
            background: inherit;
            border: inherit;
        }

        #tabs ul {
            width: 260px;
            margin-left: 30px;
            height: 46px;
            border: 0;
        }

        #tabs {
            font-size: 11pt;
            border: 0;
            margin: 5px;
        }

        table tbody tr td:first-of-type input {
            text-align: center;
        }
    </style>
</head>

<script>

    function tabing(index) {
        window.location.href = 'LibroFiscal.php?me=' + index;
    }
    $(function () {
        $("#tabs").tabs();
    });
</script>

<body>
<div id="wrap">
    <div id="header">
        <a href=""><img src="<?= $_SESSION['login'][0]["LOGO_EMPRESA"] ?>"></a>

        <h1 id="logo"><span class="gray"><?= $_SESSION['login'][0]["NOMBRE_EMPRESA"] ?></span></h1>

        <h3><span><?= $_SESSION['login'][0]["NOMBRE_USUARIO"] ?></span></h3>
        <img style="float: right;margin-top: 10px;" src="../../Imagenes/logo.png">
    </div>

    <div id="content-wrap">
        <?= $menu ?>

        <div id="main">
            <center>
                <h3><b>LIBRO FISCAL</b></h3><br>

                <form action="">
                    Mes:<input type="number" name="mes" min="1" max="12" value="<?= date("m") ?>"
                               style="width: 50px;text-align: center;"/>

                    Año:<input type="number" name="ano" min="2000" max="2050"
                               value="<?= date("Y") ?>"
                               style="width: 60px;text-align: center;"/>
                    <br><br>

                    <div id="tabs">
                        <ul>
                            <li><a href="#tabs-1">Generar</a></li>
                            <li><a href="#tabs-2">Manualmente</a></li>
                        </ul>
                        <div id="tabs-1">

                            <input type="button" class="btnAzul" name="generar" value="Generar">
                            <input type="button" class="btnAzul" name="exportar" value="Exportar">
                            <input type="button" class="btnAzul" name="imprimirauto" value="Imprimir">
                            <br><br>
                            <table id="automatico" cellpadding="1" cellspacing="1">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Ingresos Diarios Global</th>
                                    <th colspan="3">Egresos Diarios Global</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>Día</th>
                                    <th>Ventas y/o prestación de servicios</th>
                                    <th>Compra de bienes(sin IVA)</th>
                                    <th>Pago de servicios(sin IVA)</th>
                                    <th>IVA</th>
                                    <th>Saldo</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div id="tabs-2">
                            <input type="button" class="btnAzul" value="Evaluar">
                            <input type="button" class="btnAzul" name="exportar" value="Exportar">
                            <input type="button" class="btnAzul" name="imprimirmanual" value="Imprimir">
                            <br><br>
                            <?= $manual ?>
                        </div>
                    </div>

                </form>
                <!-- Con esta tabla puedo convertir los inputs a td's-->
                <div id="printera" style="margin-left:20%;padding: 50%;">
                    <table id="temp" style="display: none;"></table>
                </div>
            </center>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {

        $('input[name=imprimirmanual]').click(function () {
            ArmarTabla('manual');

            $('#temp').css({'display': 'block', 'color': 'black', 'margin-left': '10%'});
            $('#printera').printArea();
            $('#temp').css('display', 'none');
        });

        $('input[name=imprimirauto]').click(function () {
            ArmarTabla('automatico');
            $('#temp').css({'display': 'block', 'color': 'black', 'margin-left': '10%'});
            $('#printera').printArea();
            $('#temp').css('display', 'none');
        });

        $('input[name=generar]').click(function () {
            $.ajax({
                url: 'Actions.php',
                type: 'post',
                data: {'generar': 'generar', 'ano': $('[name=ano]').val(), 'mes': $('[name=mes]').val()},
                success: function (data) {$('#automatico').append(data);},
                error: function () {alert('Ha ocurrido un error en el sistema');}
            });
        });

        var dias = Days(new Date().getMonth(), new Date().getYear());

        function Days(Month, Year) {
            return new Date(Year || new Date().getFullYear(), Month, 0).getDate();
        }

        function Fila(i) {
            return '<tr>'
            + '<td><input readonly value="' + (i + 1) + '"></td>'
            + '<td><input></td>'
            + '<td><input></td>'
            + '<td><input></td>'
            + '<td><input></td>'
            + '<td><input readonly></td>'
            + '</tr>';
        }

        function ArmarTabla(tabla) {
            var table = $('#' + tabla).clone();
            table.find('thead tr:first').prepend('<tr><th></th><th style="border: 1px solid #000;">Mes:' + $('input[name=mes]').val() +
            '</th><th style="border: 1px solid #000;">Año:' + $('input[name=ano]').val() + '</th></tr>');

            var rows = table.find('tbody > tr td > input');
            rows.each(function (index, element) {
                $(element).parent().text($(element).val());
                $(element).remove();
            });
            $('#temp').html(table.html());
        }

        $('input[type=number]').change(function () {
            var dateuser = Days($('input[type=number][name=mes]').val(), $('input[type=number][name=ano]').val());

            if (dateuser > dias) {
                $('table#manual tbody tr:last-of-type').remove(); //Remueve totales
                for (; dias < dateuser; dias++)
                    $('table#manual tbody').append(Fila(dias));
            }
            else if (dateuser < dias) {
                for (var i = dateuser; i <= dias; i++)
                    $('table#manual tbody tr:last-of-type').remove();
            }
            Totales();
            dias = dateuser;
        });
        for (var i = 0; i < dias; i++) {
            $('table#manual tbody').append(Fila(i));
        }

        Totales();

        function Totales() {
            $('table#manual tbody').append
            (
                '<tr><td><input readonly value="TOTALES:"></td>'
                + '<td><input readonly></td>'
                + '<td><input readonly></td>'
                + '<td><input readonly></td>'
                + '<td><input readonly></td>'
                + '<td><input readonly></td></tr>'
            );
        }

        $('input[name=exportar]').on('click', function () {
            ArmarTabla('manual');
            $("#temp").battatech_excelexport({
                containerid: "temp", datatype: 'table'
            });
        });

        $('input[value=Evaluar]').on('click', function () {

                var dim = $('table#manual tbody tr');

                var venta = 0, compra = 0, pago = 0, iva = 0, saldo = 0;
                dim.each(function (index, element) {

                    if (index < dim.length - 1) {
                        //Totales Horizontales (Saldos)
                        $(element).find('td:last input').val(Number($(element).find('td:nth-of-type(2) input').val()) -
                        (Number($(element).find('td:nth-of-type(3) input').val()) + Number($(element).find('td:nth-of-type(4) input').val()) +
                        Number($(element).find('td:nth-of-type(5) input').val())
                        ));
                        //Totales Verticales
                        venta += Number($(element).find('td:nth-of-type(2) input').val());
                        compra += Number($(element).find('td:nth-of-type(3) input').val());
                        pago += Number($(element).find('td:nth-of-type(4) input').val());
                        iva += Number($(element).find('td:nth-of-type(5) input').val());
                        saldo += Number($(element).find('td:last input').val());
                    }
                });

                $('table#manual tbody tr:last td:nth-of-type(2) input ').val(venta);
                $('table#manual tbody tr:last td:nth-of-type(3) input').val(compra);
                $('table#manual tbody tr:last td:nth-of-type(4) input').val(pago);
                $('table#manual tbody tr:last td:nth-of-type(5) input').val(iva);
                $('table#manual tbody tr:last td:last input').val(saldo);
            }
        );
    });
</script>
</body>
</html>
