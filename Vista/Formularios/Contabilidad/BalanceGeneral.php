<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Parametros.php';
    include '../../../Clases/cls_Contabilidad.php';
    include '../../../Clases/Componentes.php';

    session_start();
    if (isset($_SESSION['login']) == '' || (new cls_Usuarios())->TienePermiso(__FILE__, $_SESSION['login'][0]['ID_USUARIO']))
        echo '<script > self.location = "/"</script>';

    $Master = new Master();
    $menu = $Master->Menu();
    $Contabilidad = new cls_Contabilidad();
    $Componenetes = new Componentes();
    $Exportar = '';
    $tabla1 = '';
    $tabla2 = '';
    $activos = 0;
    $pasivos = 0;
    $patrimonio = 0;

    if (!empty($_POST)) {
        $Exportar = '<table id="Expa" style="color: #000000;">
        <thead>
        <tr><th style="text-align:center;">' . $_POST['fecha'] . '</th><th></th><th></th></tr>
        <tr><th style="text-align:center;">HORA : ' . abs(date("H") - 5) . ':' . date("i") . '</th><th></th><th></th></tr>
        <tr><th style="text-align:center;"colspan="3">' . $_SESSION['login'][0]['NOMBRE_EMPRESA'] . '</th></tr>
        <tr><th style="text-align:center;"colspan="3">NIT No. ' . $_SESSION['login'][0]['NIT'] . '</th></tr>
        <tr><th style="text-align:center;"colspan="3">BALANCE GENERAL FORMATO HORIZONTAL CIERRE SIMULADO</th></tr>
        <tr><th style="text-align:center;"colspan="3"></th></tr>
         <tr><th style="text-align:center;border-bottom-style:dashed;border-top-style:dashed;"colspan="3">ACTIVOS</th></tr>
            <tr>
            <th style="text-align:center;">CÓDIGO</th>
            <th style="text-align:center;">NOMBRE</th>
            <th style="text-align:center;">SALDO</th></tr></thead><tbody>';

        $tabla1 = '<table id="table1" class="table" style="width:90%;">
        <thead><tr>
            <th style="text-align:center;">CÓDIGO</th>
            <th style="text-align:center;">NOMBRE</th>
            <th style="text-align:center;">SALDO</th></tr></thead><tbody>';

        $tabla2 = '<table id="table2" class="table" style="width:90%;">
        <thead><tr>
            <th style="text-align:center;">CÓDIGO</th>
            <th style="text-align:center;">NOMBRE</th>
            <th style="text-align:center;">SALDO</th></tr></thead><tbody>';

        $Activos = [];
        $Pasivos = [];
        $Patrimonio = [];

        foreach ($Contabilidad->TraeCuentas($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
            $codigo = $valor['CODIGO'];
            if ($codigo[0] == 1)
                $Activos[] = $valor;

            else if ($codigo[0] == 2)
                $Pasivos[] = $valor;

            else if ($codigo[0] == 3)
                $Patrimonio[] = $valor;
        }

        $Componenetes->OrdenaCuentas($Activos);
        $Componenetes->OrdenaCuentas($Pasivos);
        $Componenetes->OrdenaCuentas($Patrimonio);
        $activos = 0;
        $pasivos = 0;
        $patrimonio = 0;
        $Cta1 = $Componenetes->TraeBalanceGeneral($Activos, $_POST['fecha'], $activos);
        $Cta2 = $Componenetes->TraeBalanceGeneral($Pasivos, $_POST['fecha'], $pasivos);
        $Cta3 = $Componenetes->TraeBalanceGeneral($Patrimonio, $_POST['fecha'], $patrimonio);


        //   echo var_dump( count($Cta2)) .'Cuentas';
        //   echo var_dump( count($Cta3)) .'Cuentas2';


        $Tam = count($Cta1);
        #Activos
        $filas = '';
        for ($i = $Tam - 1; $i > - 1; $i --) {
            $filas .= '<tr>
        <td style="text-align: left;">' . $Cta1[$i]['CODIGO'] . '</td>
        <td style="text-align: left;">' . $Cta1[$i]['NOMBRE'] . '</td>
        <td style="text-align: right;">' . number_format($Cta1[$i]['SALDO'], 2, ',', '.') . '</td></tr>';
        }
        $tabla1 .= $filas;
        $Exportar .= $filas;

        #Pasivos + Patrimonio
        $Exportar .= '<tr><th style="text-align:center;border-bottom-style:dashed;border-top-style:dashed;"colspan="3" >PASIVOS + PATRIMONIO</th></tr>';
        $Tam = count($Cta2);
        $filas = '';
        for ($i = $Tam - 1; $i > - 1; $i --) {
            $filas .= '<tr>
        <td style="text-align: left;">' . $Cta2[$i]['CODIGO'] . '</td>
        <td style="text-align: left;">' . $Cta2[$i]['NOMBRE'] . '</td>
        <td style="text-align: right;">' . number_format($Cta2[$i]['SALDO'], 2, ',', '.') . '</td></tr>';
        }
        $tabla2 .= $filas;
        $Exportar .= $filas;

        $Tam = count($Cta3);
        $filas = '';
        for ($i = $Tam - 1; $i > - 1; $i --) {
            $filas .= '<tr>
        <td style="text-align: left;">' . $Cta3[$i]['CODIGO'] . '</td>
        <td style="text-align: left;">' . $Cta3[$i]['NOMBRE'] . '</td>
        <td style="text-align: right;">' . number_format($Cta3[$i]['SALDO'], 2, ',', '.') . '</td></tr>';
        }
        $tabla2 .= $filas;
        $Exportar .= $filas;

        $tabla1 .= '</tbody></table>';
        $tabla2 .= '</tbody></table>';

        $Exportar .= '<th>TOTAL ACTIVOS: $ ' . number_format($activos, 2, ',', '.') . '</th><th></th><th>TOTAL PASIVOS + PATRIMONIO: $ ' . number_format($pasivos + $patrimonio, 2, ',', '.') . '</th>';
        $Exportar .= '</tbody></table>';
    }
?>
<html>
<head>
    <title>Balance General</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>
    <script type="text/javascript" language="javascript" src="../../Js/jquery.dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>
    <script type="text/javascript" src="../../Js/Excel/jquery.battatech.excelexport.js"></script>
</head>
<script>

    $(document).ready(function () {

        $('#table1').dataTable({
            "aaSorting": [],
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        });
        $('#table2').dataTable({
            "aaSorting": [],
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        });
    });
</script>
<style type="text/css">
    #wrap {
        height: 300%;
    }
</style>
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
                <h3><b>BALANCE GENERAL</b></h3>

                <form method="post">
                    Fecha: <input type="date" name="fecha" value="<?= date("Y") . '-' . date("m") . '-' . date("d") ?>"
                                  required>
                    <input type="submit" value="Generar" class="btnAzul"/>
                    <input type="button" value="Exportar" class="btnAzul"/>
                </form>

                <br><br>
                <span
                    style="font-size: 15pt; color:lightslategrey;">Activos = $ <?= number_format($activos, 2, ',', '.') ?></span>
                <hr/>
                <?= $tabla1 ?>
                <!--   PASIVOS-->
                <br><br>
                <span
                    style="font-size: 15pt; color:lightslategrey;">Pasivos + Patrimonios = $ <?= number_format($pasivos + $patrimonio, 2, ',', '.') ?></span>
                <hr/>
                <?= $tabla2 ?>
                <div style="display: none;" id="exportar"><?= $Exportar ?></div>
            </center>
        </div>
    </div>
</div>
</body>
<script>

    $('input[value=Exportar]').on('click', function () {
        $("#temp").battatech_excelexport({
            containerid: "exportar", datatype: 'table', worksheetName: 'Balance General (<?= $_SESSION['login'][0]["NOMBRE_EMPRESA"] ?>)'
        });
    });
</script>
</html>
