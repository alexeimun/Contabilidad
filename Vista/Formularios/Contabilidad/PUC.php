<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Contabilidad.php';
    include '../../../Clases/Componentes.php';
    session_start();

    if (isset($_SESSION['login']) == '' || (new cls_Usuarios())->TienePermiso(__FILE__, $_SESSION['login'][0]['ID_USUARIO']))
        echo '<script > self.location = "/"</script>';

    $Master = new Master();
    $menu = $Master->Menu();
    $Contabilidad = new cls_Contabilidad();
    $Componenetes = new Componentes();

    $tabla = '<table id="table" class="table" style="width:93%;">
        <thead><tr>
            <th style="text-align:left;">CÓDIGO</th>
            <th style="text-align:left;">NOMBRE</th>
            <th style="text-align:left;">MANEJA TERCERO</th>
            <th style="text-align:left;">MANEJA DOCUMENTO CRUCE</th>
            <th style="text-align:left;">NATURALEZA</th>
            <th style="text-align:center;">ACCIÓN</th></tr></thead><tbody>';

    $tablaExp = '<table id="Texp" class="table"  style="display: none;">
        <thead><tr>
           <tr> <th style="text-align:center;font-weight: bold;" colspan="5">' . strtoupper($_SESSION['login'][0]["NOMBRE_EMPRESA"]) . ' </th></tr>
            <th style="text-align:left;">CÓDIGO</th>
            <th style="text-align:left;">NOMBRE</th>
            <th style="text-align:left;">MANEJA TERCERO</th>
            <th style="text-align:left;">MANEJA DOCUMENTO CRUCE</th>
            <th style="text-align:left;">NATURALEZA</th></tr></thead><tbody>';
    $cont = 0;
    $Cuentas = [];

    foreach ($Contabilidad->TraeCuentas($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor)
        $Cuentas[] = $valor;

    $Componenetes->OrdenaCuentas($Cuentas);

    for ($i = 0; $i < count($Cuentas); $i ++) {
        $cont ++;
        $tabla .= '<tr><td style="text-align:left;">' . $Cuentas[$i]['CODIGO'] . '</td>';
        $tabla .= '<td style="text-align:left;">' . $Cuentas[$i]['NOMBRE'] . '</td>';
        $tabla .= '<td style="text-align:left;">' . $Cuentas[$i]['MANEJA_TERCERO'] . '</td>';
        $tabla .= '<td style="text-align:left;">' . $Cuentas[$i]['MANEJA_DOC_CRUCE'] . '</td>';
        $tabla .= '<td style="text-align:left;">' . $Cuentas[$i]['NATURALEZA'] . '</td>';
        $tabla .= '<td style="text-align:center;">
           <a href="CrearCuenta.php"><img src="../../Imagenes/add.png" title="Nuevo"></a>
          <a href="ModificarCuenta.php?id=' . $Cuentas[$i]['ID_CUENTA'] . '"><img src="../../Imagenes/edit.png" title="Editar"></a>
          <a onclick="EliminarCuenta(' . $Cuentas[$i]['ID_CUENTA'] . ');return false;"><img src="../../Imagenes/delete.png" title="Eliminar"></a>
                </td></tr>';

        $tablaExp .= '<tr><td style="text-align:left;">' . $Cuentas[$i]['CODIGO'] . '</td>';
        $tablaExp .= '<td style="text-align:left;">' . $Cuentas[$i]['NOMBRE'] . '</td>';
        $tablaExp .= '<td style="text-align:left;">' . $Cuentas[$i]['MANEJA_TERCERO'] . '</td>';
        $tablaExp .= '<td style="text-align:left;">' . $Cuentas[$i]['MANEJA_DOC_CRUCE'] . '</td>';
        $tablaExp .= '<td style="text-align:left;">' . $Cuentas[$i]['NATURALEZA'] . '</td>';

    }
    if ($cont == 0)
        $tabla .= '<tr><td colspan=6 style="text-align:center;"><a href="CrearCuenta.php"><img src="../../Imagenes/add.png" title="Nuevo"></a> </td></tr>';


    $tabla .= '</tbody></table>';
    $tablaExp .= '</tbody></table>';

?>
<html>
<head>
    <title>Plan de Cuentas</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>
    <script type="text/javascript" language="javascript" src="../../Js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="../../Js/Excel/jquery.battatech.excelexport.js"></script>

    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>
</head>


<script>

    $(document).ready(function () {

        $('#table').dataTable({
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

    function EliminarCuenta(id) {
        if (confirm("Seguro que quieres eliminar esta cuenta ?")) {
            window.location.href = 'EliminarCuenta.php?id=' + id;
        }
    }
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
                <h3><b>PLAN DE CUENTAS</b></h3>
                <input type="button" value="Exportar" class="btnAzul"/>
                <?= $tabla ?>
            </center>
            <div id="Exp" style="display:none;">
                <?= $tablaExp ?>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    $('input[value=Exportar]').on('click', function () {
        $("#temp").battatech_excelexport({
            containerid: "Exp", datatype: 'table',worksheetName:'PUC'
        });
    });
</script>
</html>
