<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Empresas.php';
    session_start();

    if (isset($_SESSION['login']) !== '' && $_SESSION['login'][0]["NIVEL"] === 1)
        echo '<script> self.location = "../Otros/Login.php"</script>';


    if ($_GET['id'] == "")
        echo '<script>  self.location = "Empresas.php"</script>';

    $Master = new Master();
    $menu = $Master->Menu();
    $Empresa = new cls_Empresas();
    $Usuarios = new cls_Usuarios();

    $txtNombre = '';
    $txtNit = '';
    $CantidadUsuarios = 0;
    $txtTelefono = '';
    $txtEmail = '';
    $Logo = '';
    $Estado = '';

    foreach ($Empresa->TraeInfoEmpresa($_GET['id']) as $llave => $valor) {
        $txtNombre = $valor['NOMBRE'];
        $txtNit = $valor['NIT'];
        $txtEmail = $valor['EMAIL'];
        $txtDireccion = $valor['DIRECCION'];
        $txtTelefono = $valor['TELEFONO'];
        $Logo = $valor['LOGO'];
        $Estado = $valor['ESTADO'];
        $CantidadUsuarios = $valor['CANT_USUARIOS'];
    }

    if ($Estado == 1) {
        $a = "'d'";
        $Boton = ' <input type="button" class="btnRojo" onclick="ActivaOdesactiva(' . $a . ',' . $_GET['id'] . ')" id="btnDesactivar" name="btnDesactivar" value="DESACTIVAR"  style="width:200px;"/>';
    } else {
        $a = "'a'";
        $Boton = ' <input type="button" class="btnVerde" onclick="ActivaOdesactiva(' . $a . ',' . $_GET['id'] . ')"  id="btnActivar" name="btnActivar" value="ACTIVAR"  style="width:200px;"/>';
    }

    if (isset($_POST['btnGuardar']) != '' && isset($_POST['cantidad']) != '') {
        $Empresa->ActualizarCantUsuarios($_POST['cantidad'], $_GET['id']);

        foreach ($Empresa->TraeInfoEmpresa($_GET['id']) as $llave => $valor)
            $CantidadUsuarios = $valor['CANT_USUARIOS'];
    }
    $tablaUsuarios = '<table id="table" class="table" style="width:60%;">
           	<thead>
					<tr>
<th style="text-align:left;">NOMBRE</th>
            <th style="text-align:left;">DOCUMENTO</th>
            <th style="text-align:left;">E-MAIL</th>
            <th style="text-align:left;">CLAVE</th><th style="text-align:left;">FECHA REGISTRO</th></tr></thead><tbody>
';
    $cont = 0;
    foreach ($Usuarios->TraeUsuariosEmpresa($_GET['id']) as $llave => $valor) {
        $idEmpresa = $valor['ID_EMPRESA'];
        if($valor['RAIZ']==1)$tablaUsuarios.= '<tr style="background: #ecf0f3;">';
        else $tablaUsuarios.='<tr>';

        $tablaUsuarios .= '<td style="text-align:left;">' . $valor['NOMBRE'] . '</td>';
        $tablaUsuarios .= '<td style="text-align:left;">' . $valor['DOCUMENTO'] . '</td>';
        $tablaUsuarios .= '<td style="text-align:left;">' . $valor['EMAIL'] . '</td>';
        $tablaUsuarios .= '<td style="text-align:left;">' . $valor['PASSWORD'] . '</td>';
        $tablaUsuarios .= '<td style="text-align:left;">' . $valor['FECHA_REGISTRO'] . '</td></tr>';
        $cont ++;
    }

    $tablaUsuarios .= '</tbody></table>';

?>
<html>
<head>
    <title>Administrar Empresa</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>
    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>


</head>
<style type="text/css">
    select {
        width: 300px;
    }

    input[type='text'] {
        width: 287px;
    }

    input[type='password'] {
        width: 287px;
    }

    input[type='email'] {
        width: 287px;
    }

    .spana {
        font: bolder 17px 'Trebuchet MS', Arial, Sans-serif;
        color: #093C67;
    }

    .spanb {
        font: bolder 15px 'Trebuchet MS', Arial, Sans-serif;
        color: #000;
    }

</style>

<script>
    function ActivaOdesactiva(aa, id) {

        if (aa == 'a') {
            if (confirm("Seguro que quieres activar esta cuenta ?")) {
                window.location.href = 'ActivarOdesactivar.php?id=' + id + '&a=' + aa;
            }
        }
        else {
            if (confirm("Seguro que quieres desactivar esta cuenta ?")) {
                window.location.href = 'ActivarOdesactivar.php?id=' + id + '&a=' + aa;
            }
        }

    }
</script>

<body>
<div id="wrap">
    <div id="header">
        <a href=""><img src="<?= $_SESSION['login'][0]["LOGO_VENDEDOR"] ?>"></a>
        <img style="float: right;margin-top: 10px;" src="../../Imagenes/logo.png">

        <h1 id="logo"><span class="gray">Administración&nbsp&nbsp; <span
                    style="font-size: 28px;"><?= $_SESSION['login'][0]['NOMBRE_VENDEDOR'] ?></span></span></h1>

    </div>

    <div id="content-wrap">
        <?= $menu ?>

        <div id="main">
            <form method="post" enctype="multipart/form-data">
                <center>
                    <h3><b>ADMINISTRACIÓN DE EMPRESA</b></h3><br>

                    <span class="spanb">Empresa:</span><span class="spana"><?= $txtNombre; ?></span>&nbsp;&nbsp;&nbsp;
                    <span class="spanb">Nit:</span><span class="spana"><?= $txtNit; ?></span>&nbsp;&nbsp;&nbsp;
                    <span class="spanb">Dirección:</span><span class="spana"><?= $txtDireccion; ?></span>&nbsp;&nbsp;&nbsp;
                    <span class="spanb">Telefono:</span><span class="spana"><?= $txtTelefono; ?></span>&nbsp;&nbsp;&nbsp;
                    <span class="spanb">E-Mail:</span><span class="spana"><?= $txtEmail; ?></span><br><br><br>
                    <img style="width: 140px;" src="../../Formularios/Empresas/<?= $Logo; ?>"><br>

                    <h4><b>Capacidad de Usuarios</b></h4>
                    <input type="number" id="num" name="cantidad" max="999" min="10"   value='<?= $CantidadUsuarios; ?>'
                           style="text-align: center;width:60px;" max="999" min=10/>
                    <input type="submit" value="Guardar" class="btnAzul" name="btnGuardar" onclick="Guardar();"/>
                    </br>
                    <h4><b>Usuarios</b></h4>
                    <?= $tablaUsuarios; ?>
                    <br><?= $Boton; ?>
                </center>
            </form>
        </div>
    </div>
</div>
<script></script>
</body>
</html>
