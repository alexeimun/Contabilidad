<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Usuarios.php';
    session_start();
    if (isset($_SESSION['login']) != '') {
        $Master = new Master();
        $menu = $Master->Menu();
        $Usuarios = new cls_Usuarios();

        $tabla = '<table id="table" class="table" style="width:90%;">
           	<thead>
					<tr>
            <th style="text-align:left;">NOMBRE</th>
            <th style="text-align:left;">DOCUMENTO</th>
            <th style="text-align:left;">E-MAIL</th>
            <th style="text-align:left;">CLAVE</th>
            <th style="text-align:right;">ACCIÓN</th>
					</tr>
				</thead><tbody>
';
        $cont = 0;
        foreach ($Usuarios->TraeUsuariosVendedor() as $llave => $valor) {
            $idVendedor = $valor['ID_VENDEDOR'];
            $tabla .= '<tr><td style="text-align:left;">' . $valor['NOMBRE'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['DOCUMENTO'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['EMAIL'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['PASSWORD'] . '</td>';
            $tabla .= '<td style="text-align:right;">
          <a href="CrearUsuario.php"><img src="../../Imagenes/add.png" title="Nuevo"></a>
          <a href="ModificarUsuario.php?id=' . $valor['ID_USUARIO'] . '"><img src="../../Imagenes/edit.png" title="Editar"></a>
          <a onclick="EliminarUsuario(' . $valor['ID_USUARIO'] . ');return false;"><img src="../../Imagenes/delete.png" title="Eliminar"></a>
                </td></tr>';
            $cont ++;
        }

        if ($cont == 0)
            $tabla .= '<tr><td colspan=5 style="text-align:center;"><a href="CrearUsuario.php"><img src="../../Imagenes/add.png" title="Nuevo"></a> </td></tr>';

        $tabla .= '</tbody></table>';
    } else echo '<script > self.location = "../Otros/Login.php"</script>';

?>
<html>
<head>
    <title>Administradores</title>

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

    function EliminarUsuario(id) {
        if (confirm("Seguro que quieres eliminar este usuario?")) window.location.href = 'EliminarUsuario.php?id=' + id;

    }


    $('#table1').dataTable();
    //
    //}
</script>

<body>
<div id="header">
    <a href=""><img src="../../Imagenes/logo.png"></img></a>

    <h1 id="logo"><span class="gray">Administración&nbsp&nbsp; <span
                style="font-size: 28px;"><?= $_SESSION['login'][0]['NOMBRE'] ?></span></span></h1>

</div>

<div id="content-wrap">
    <?= $menu ?>

    <div id="main">
        <center>
            <h3><b>ADMINISTRADORES</b></h3><br>
            <?= $tabla ?>

        </center>
    </div>
</div>

</div>
<script type="text/javascript">
    function dev() {
        var ele = document.getElementById("idel").value;
        if (ele.length == 0 || isNaN(ele))return false;
        else return true;
    }
    function devl() {
        var el = document.getElementById("idid").value;
        if (el.length == 0 || isNaN(el))  return false;
        else return false;
    }
</script>
</body>
</html>
