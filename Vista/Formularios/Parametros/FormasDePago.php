<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Parametros.php';
    session_start();
    if (isset($_SESSION['login']) != '') {

        $Master = new Master();
        $menu = $Master->Menu();
        $Parametros = new cls_Parametros();

        $tabla = '<table id="table" class="table" style="width:90%;">
        <thead><tr>
            <th style="text-align:left;">NOMBRE</th>
            <th style="text-align:left;">CÓDIGO</th>
            <th style="text-align:left;">CUENTA</th>
            <th style="text-align:left;">REQUIERE ENTIDAD</th>
            <th style="text-align:left;">REQUIERE NÚMERO</th>
            <th style="text-align:center;">ACCIÓN</th></tr></thead><tbody>';

        $cont = 0;

        $formas = $Parametros->TraeFormasPago($_SESSION['login'][0]["ID_EMPRESA"]);
        foreach ($formas as $llave => $valor) {
            $cont ++;
            $tabla .= '<tr><td style="text-align:left;">' . $valor['NOMBRE_F_PAGO'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['CODIGO'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['NOMBRE'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['REQUIERE_ENTIDAD'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['REQUIERE_NUMERO'] . '</td>';

            $tabla .= '<td style="text-align:center;">
           <a href="CrearFormaPago.php"><img src="../../Imagenes/add.png" title="Nuevo"></a>
          <a href="ModificarFormaPago.php?id=' . $valor['ID_F_PAGO'] . '"><img src="../../Imagenes/edit.png" title="Editar"></a>
          <a onclick="EliminarFormaPago(' . $valor['ID_F_PAGO'] . ');return false"><img src="../../Imagenes/delete.png" title="Eliminar"></a>
                </td></tr>';
        }


        if ($cont == 0) {
            $tabla .= '<tr><td colspan=7 style="text-align:center;"><a href="CrearFormaPago.php"><img src="../../Imagenes/add.png" title="Nuevo"></a> </td></tr>';
        }

        $tabla .= '</tbody></table>';


    } else echo '<script >self.location = "/"</script>';

?>
<html>
<head>
    <title>Formas de Pago</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>
    <script type="text/javascript" language="javascript" src="../../Js/jquery.dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>
</head>
<style type="text/css">

</style>

<script>

    $(document).ready(function () {
        $('#table').dataTable({
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


    function EliminarFormaPago(id) {
        if (confirm("Seguro que quieres eliminar esta forma de pago?")) {
            window.location.href = 'EliminarFormaPago.php?id=' + id;
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
                <h3><b>FORMAS DE PAGO</b></h3><br>
                <?= $tabla ?>

            </center>
        </div>
    </div>

</div>

</body>
</html>
