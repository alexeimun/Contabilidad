<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Parametros.php';
    include '../../../Clases/Componentes.php';
    session_start();

    if (isset($_SESSION['login']) == '')
        echo '<script language = javascript> self.location = "../Otros/Login.php"</script>';

    $Master = new Master();
    $menu = $Master->Menu();
    $Componentes = new Componentes();

    $tabla = '<table id="table" class="table" style="width:90%;">
        <thead><tr>
            <th style="text-align:left;">NOMBRE</th>
            <th style="text-align:left;">TIPO</th>
            <th style="text-align:center;">ACCIÓN</th></tr></thead><tbody>';
    $cont = 0;

    foreach ($Componentes->TraeEntidades($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
        $cont ++;
        $tabla .= '<tr><td style="text-align:left;">' . $valor['NOMBRE_ENTIDAD'] . '</td>';
        $tabla .= '<td style="text-align:left;">' . $valor['TIPO'] . '</td>';
        $tabla .= '<td style="text-align:right;">
           <a href="CrearEntidad.php"><img src="../../Imagenes/add.png" title="Nuevo"></img></a>
          <a href="ModificarEntidad.php?id=' . $valor['ID_ENTIDAD'] . '"><img src="../../Imagenes/edit.png" title="Editar"></img></a>
          <a onclick="EliminarEntidad(' . $valor['ID_ENTIDAD'] . ');return false"><img src="../../Imagenes/delete.png" title="Eliminar"></img></a>
                </td></tr>';
    }
    if ($cont == 0) {
        $tabla .= '<tr><td colspan=6 style="text-align:center;"><a href="CrearEntidad.php"><img src="../../Imagenes/add.png" title="Nuevo"></img></a> </td></tr>';
    }

    $tabla .= '</tbody></table>';

?>
<html>
<head>
    <title>Entidades</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>
    <script type="text/javascript" language="javascript" src="../../Js/jquery.js"></script>
    <script type="text/javascript" language="javascript" src="../../Js/jquery.dataTables.js"></script>

    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>
</head>
<style type="text/css">

</style>

<script>

    $(document).ready(function () {
        if (<?= $cont; ?>>
        0
        )
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

    function EliminarEntidad(id) {
        if (confirm("Seguro que quieres eliminar esta entidad ?")) {
            window.location.href = 'EliminarEntidad.php?id=' + id;
        }
    }
</script>

<body>
<div id="wrap">
    <div id="header">
        <a href=""><img src="<?= $_SESSION['login'][0]["LOGO_EMPRESA"] ?>"></img></a>

        <h1 id="logo"><span class="gray"><?= $_SESSION['login'][0]["NOMBRE_EMPRESA"] ?></span></h1>

        <h3><span><?= $_SESSION['login'][0]["NOMBRE_USUARIO"] ?></span></h3>
        <img style="float: right;margin-top: 10px;" src="../../Imagenes/logo.png">
    </div>

    <div id="content-wrap">
        <?= $menu ?>

        <div id="main">
            <center>
                <h3><b>ENTIDADES</b></h3><br>
                <?= $tabla ?>

            </center>
        </div>
    </div>

</div>

</body>
</html>
