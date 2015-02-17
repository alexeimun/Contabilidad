<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    session_start();
    if (isset($_SESSION['login']) == '' || (new cls_Usuarios())->TienePermiso(__FILE__, $_SESSION['login'][0]['ID_USUARIO']))
        echo '<script > self.location = "/"</script>';
    $Master = new Master();
    $menu = $Master->Menu();
    $Usuarios = new cls_Usuarios();

    $tabla = '<table id="table" class="table" style="width:90%;">
           	<thead><tr>
           <th style="text-align:left;">NOMBRE</th>
            <th style="text-align:left;">DOCUMENTO</th>
            <th style="text-align:left;">E-MAIL</th>
            <th style="text-align:right;">ACCIÓN</th>
					</tr> </thead><tbody>';
    $cont = 0;
    foreach ($Usuarios->TraeUsuariosEmpresa($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
        $tabla .= '<tr><td style="text-align:left;">' . $valor['NOMBRE'] . '</td>';
        $tabla .= '<td style="text-align:left;">' . $valor['DOCUMENTO'] . '</td>';
        $tabla .= '<td style="text-align:left;">' . $valor['EMAIL'] . '</td>';
        $tabla .= '<td style="text-align:right;">
          <a href="CrearUsuario.php"><img src="../../Imagenes/add.png" title="Nuevo"></a>
          <a href="ModificarUsuario.php?id=' . $valor['ID_USUARIO'] . '"><img src="../../Imagenes/edit.png" title="Editar"></a>
          <a onclick="EliminarUsuario(' . $valor['ID_USUARIO'] . ',' . $valor['RAIZ'] . ');return false"><img src="../../Imagenes/delete.png" title="Eliminar"></a>
                </td></tr>';
        $cont ++;
    }

    if ($cont == 0) {
        $tabla .= '<tr><td colspan=5 style="text-align:center;"><a href="CrearDocumento.php"><img src="../../Imagenes/add.png" title="Nuevo"></a> </td></tr>';
    }
    $tabla .= '</tbody></table>';

?>
<html>
<head>
    <title>Usuarios</title>

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

    function EliminarUsuario(id, root) {
        if (root == 1) {
            alert("No se puede eliminar un usuario principal");
        }
        else if (<?= $_SESSION['login'][0]['ID_USUARIO']; ?>==id
    )

        alert("Acción inválida.");
    else
        if (confirm("Seguro que quieres eliminar este usuario?"))
            window.location.href = 'EliminarUsuario.php?id=' + id;
    }


    $('#table1').dataTable();
    //
    //}
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
                <h3><b>USUARIOS</b></h3><br>
                <?= $tabla ?>

            </center>
        </div>
    </div>
</div>
</body>
</html>