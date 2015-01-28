<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Parametros.php';

    session_start();
    if (isset($_SESSION['login']) == '' || (new cls_Usuarios())->TienePermiso(__FILE__,$_SESSION['login'][0]['ID_USUARIO']))
        echo '<script language = javascript> self.location = "../Otros/Login.php"</script>';

    $Master = new Master();
    $menu = $Master->Menu();
    $Parametros = new cls_Parametros();

    $me = '';

    $tablaProductos = '<table id="table" class="table" style="width:90%;">
            <thead><tr>
            <th style="text-align:left;">CÓDIGO</th>
            <th style="text-align:left;">NOMBRE PRODUCTO</th>
            <th style="text-align:left;">PRECIO</th>            
            <th style="text-align:left;">TIPO</th>
            <th style="text-align:left;">GRUPO</th>
            <th style="text-align:center;">ACCIÓN</th></tr></thead>';

    $tablaGrupos = '<table id="table" class="table" style="width:90%;">
            <thead><tr>
            <th style="text-align:left;">NOMBRE GRUPO</th>
            <th style="text-align:left;">CTA INVENTARIO</th>
            <th style="text-align:left;">CTA VENTAS</th>
            <th style="text-align:left;">CTA COSTO</th>
            <th style="text-align:left;">CTA DEVOLUCIONES</th>
            <th style="text-align:center;">ACCIÓN</th></tr></thead><tbody>';
    $cont = 0;

    foreach ($Parametros->TraeGrupos($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
        $cont ++;
        $tablaGrupos .= '<tr><td style="text-align:left;">' . $valor['NOMBRE'] . '</td>';
        $tablaGrupos .= '<td style="text-align:left;">' . $valor['N_CTA_INVENTARIO'] . '</td>';
        $tablaGrupos .= '<td style="text-align:left;">' . $valor['N_CTA_VENTAS'] . '</td>';
        $tablaGrupos .= '<td style="text-align:left;">' . $valor['N_CTA_COSTO'] . '</td>';
        $tablaGrupos .= '<td style="text-align:left;">' . $valor['N_CTA_DEVOLUCIONES'] . '</td>';
        $tablaGrupos .= '<td style="text-align:center;">
          <a href="CrearGrupo.php"><img src="../../Imagenes/add.png" title="Nuevo"></img></a>
          <a href="ModificarGrupo.php?id=' . $valor['ID_GRUPO'] . '"><img src="../../Imagenes/edit.png" title="Editar"></img></a>
          <a onclick="EliminarGrupo(' . $valor['ID_GRUPO'] . ');return false"><img src="../../Imagenes/delete.png" title="Eliminar"></img></a>
                </td></tr>';
    }

    if ($cont == 0) {
        $tablaGrupos .= '<tr><td colspan=6 style="text-align:center;"><a href="CrearGrupo.php"><img src="../../Imagenes/add.png" title="Nuevo"></img></a> </td></tr>';
    }
    $tablaGrupos .= '</tbody></table>';


    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%PRODUCTOS
    $cont2 = 0;

    foreach ($Parametros->TraeProductos($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
        $cont2 ++;
        $tablaProductos .= '<tr><td style="text-align:left;">' . $valor['CODIGO'] . '</td>';
        $tablaProductos .= '<td style="text-align:left;">' . $valor['DESCRIPCION'] . '</td>';
        $tablaProductos .= '<td style="text-align:left;">' . number_format($valor['PRECIO'], 0, '', '.') . '</td>';
        if ($valor['TIPO'] == 'S') {
            $tablaProductos .= '<td style="text-align:left;">Servicio</td>';
        } else {
            $tablaProductos .= '<td style="text-align:left;">Producto</td>';
        }
        $tablaProductos .= '<td style="text-align:left;">' . $valor['NOMBRE_GRUPO'] . '</td>';
        $tablaProductos .= '<td style="text-align:center;">
           <a href="CrearProducto.php"><img src="../../Imagenes/add.png" title="Nuevo"></img></a> 
          <a href="ModificarProducto.php?id=' . $valor['ID_PRODUCTO'] . '"><img src="../../Imagenes/edit.png" title="Editar"></img></a>
          <a onclick="EliminarProducto(' . $valor['ID_PRODUCTO'] . ');return false"><img src="../../Imagenes/delete.png" title="Eliminar"></img></a>
                </td></tr>';
    }

    if ($cont2 == 0) {
        $tablaProductos .= '<tr><td colspan=6 style="text-align:center;"><a href="CrearProducto.php"><img src="../../Imagenes/add.png" title="Nuevo"></img></a> </td></tr>';
    }
    $tablaProductos .= '</tbody></table>';


    //    ***************************************

    if ($_GET['me'] == "1") {
        $title = 'Productos y Servicios';
        $me = '<li id="li1" class="current"><a style="cursor: pointer;" onclick="tabing(1)">Productos y Servicios</a></li>
       <li id="li2" class=""><a style="cursor: pointer;" onclick="tabing(2)">Grupos</a></li>';

        $tabs = '<div id="tab1" style="display:inline;">
       <br><br><br>
        ' . $tablaProductos . '
      </div>
    <div id="tab2" style="display: none;">
        <br><br><br>
        ' . $tablaGrupos . '
    </div>';

    } else {
        $title = 'Grupos';
        $me = '<li id="li1" class=""><a style="cursor: pointer;" onclick="tabing(1)">Productos y Servicios</a></li>
       <li id="li2" class="current"><a style="cursor: pointer;" onclick="tabing(2)">Grupos</a></li>';

        $tabs = '<div id="tab1" style="display: none;">
           <br><br><br>
            ' . $tablaProductos . '
          </div>
        <div id="tab2" style="display: inline;">
            <br><br><br>
            ' . $tablaGrupos . '
        </div>';

    }

?>
<html>
<head>
    <title><?= $title ?></title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>
    <script type="text/javascript" language="javascript" src="../../Js/jquery.js"></script>
    <script type="text/javascript" language="javascript" src="../../Js/jquery.dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/tabs.css"/>

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

    function tabing(aa) {
        window.location.href = 'ProductosServiciosGrupos.php?me=' + aa;
    }

    function EliminarProducto(id) {
        if (confirm("Seguro que quieres eliminar este producto?")) {
            window.location.href = 'EliminarProducto.php?id=' + id;
        }
    }

    function EliminarGrupo(id) {
        if (confirm("Seguro que quieres eliminar este grupo?")) {
            window.location.href = 'EliminarGrupo.php?id=' + id;
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
                <h3><b><?= strtoupper($title) ?></b></h3><br>


                <ul class="tabs-menu">
                    <?= $me ?>
                </ul>

                <?= $tabs ?>
            </center>
        </div>
    </div>

</div>


</body>
</html>
