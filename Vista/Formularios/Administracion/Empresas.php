<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Empresas.php';

    session_start();
    if (isset($_SESSION['login']) != '' && $_SESSION['login'][0]["NIVEL"] == 1) {

        $Master = new Master();
        $menu = $Master->Menu();
        $Empresas = new cls_Empresas();

        $tabla = '<table id="table" class="table" style="width:95%;"> <thead><tr>
            <th style="text-align:left;">NOMBRE</th>
            <th style="text-align:left;">NIT</th>
            <th style="text-align:left;">DIRECCIÓN</th>
            <th style="text-align:left;">TELEFONO</th>
            <th style="text-align:left;">EMAIL</th>
            <th style="text-align:left;">CANTIDAD USUARIOS</th>
            <th style="text-align:left;">FECHA DE REGISTRO</th>
            <th style="text-align:left;">ESTADO</th>';

        $cont = 0;
        $Array = $Empresas->TraeEmpresas($_SESSION['login'][0]["ID_VENDEDOR"]);
        if (count($Array) != 0) {
            $tabla .= '<th style="text-align:right;">ACCIÓN</th></tr></thead><tbody>';
            foreach ($Array as $llave => $valor) {
                $cont ++;
                $idEmpresa = $valor['ID_EMPRESA'];
                $tabla .= '<tr><td style="text-align:left;">' . $valor['NOMBRE'] . '</td>';
                $tabla .= '<td style="text-align:left;">' . $valor['NIT'] . '</td>';
                $tabla .= '<td style="text-align:left;">' . $valor['DIRECCION'] . '</td>';
                $tabla .= '<td style="text-align:left;">' . $valor['TELEFONO'] . '</td>';
                $tabla .= '<td style="text-align:left;">' . $valor['EMAIL'] . '</td>';
                $tabla .= '<td style="text-align:left;"><b>' . $valor['CANT_USUARIOS'] . '</b></td>';
                $tabla .= '<td style="text-align:left;">' . $valor['FECHA_REGISTRO'] . '</td>';
                if($valor['ESTADO_EMPRESA']=='Activa') $tabla .= '<td style="text-align:left;color:#5ab400;">';
                else $tabla .= '<td style="text-align:left;color:red;">';
                $tabla .=  $valor['ESTADO_EMPRESA'] . '</td>';
                $tabla .= '<td  style="text-align:right;">
          <a href="AdministrarEmpresa.php?id=' . $idEmpresa . '"><img src="../../Imagenes/machine.png" title="Administrar"></img></a>
                </td></tr>';

            }
        }
        if ($cont == 0) {
            $tabla .= '<tr><td colspan=8 style="text-align:center;"><a href="CrearEmpresa.php"><img src="../../Imagenes/add.png" title="Nuevo"></img></a> </td></tr>';
        }
        $tabla .= '</tbody></table>';


    } else {
        echo '<script > self.location = "../Otros/Login.php"</script>';
    }
?>
<html>
<head>
    <title>Empresas</title>
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


</script>

<body>
<div id="wrap">
    <div id="header">
        <a href=""><img src="<?= $_SESSION['login'][0]["LOGO_VENDEDOR"] ?>"></img></a>
        <img style="float: right;margin-top: 10px;" src="../../Imagenes/logo.png">

        <h1 id="logo"><span class="gray">Administración&nbsp&nbsp; <span
                    style="font-size: 28px"><?= $_SESSION['login'][0]['NOMBRE_VENDEDOR'] ?></span></span></h1>

    </div>
    <div id="content-wrap">
        <?= $menu ?>

        <div id="main">
            <center>
                <h3><b>EMPRESAS&nbsp;&nbsp;&nbsp;<a href="CrearEmpresa.php"><img src="../../Imagenes/add.png"
                                                                                 title="Agregar"></img></a></b></h3><br>
                <?= $tabla ?>

            </center>
        </div>
    </div>

</div>

</body>
</html>
