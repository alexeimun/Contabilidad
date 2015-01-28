<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Empresas.php';
    session_start();
    if (isset($_SESSION['login']) == '' || $_SESSION['permisos'][29][1] == 0)
        echo '<script language = javascript> self.location = "../Otros/Login.php"</script>';
    $Master = new Master();
    $menu = $Master->Menu();
    $Empresa = new cls_Empresas();

    $txtNombre = '';
    $txtNit = '';
    $txtDireccion = '';
    $txtTelefono = '';
    $txtEmail = '';
    $Logo = '';

    foreach ($Empresa->TraeDatosEmpresa($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
        $txtNombre = $valor['NOMBRE'];
        $txtNit = $valor['NIT'];
        $txtEmail = $valor['EMAIL'];
        $txtDireccion = $valor['DIRECCION'];
        $txtTelefono = $valor['TELEFONO'];
        $Logo = $valor['LOGO'];
    }

    if (isset($_POST['btnGuardar']) != '') {

        $img = 'z' . rand(1000000000, 999999999) . '_' . $_FILES["file"]["name"];
        if ($_FILES["file"]["name"] == '') {
            $img = $Logo;
        }
        $Empresa->ActualizaEmpresa($_SESSION['login'][0]["ID_EMPRESA"], $_POST['txtNombre'], $_POST['txtNit'], $_POST['txtDireccion'], $_POST['txtTelefono'], $_POST['txtEmail'], $img);

        move_uploaded_file($_FILES["file"]["tmp_name"], $img);

        echo '<script language = javascript>
                    alert("Se modificaron los datos de la empresa correctamente")
                    self.location = "InformacionEmpresa.php"
                    </script>';

    }

?>
<html>
<head>
    <title>Información de Empresa</title>

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

</style>

<script>
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
            <form method="post" enctype="multipart/form-data">
                <center>
                    <h3><b>INFORMACIÓN DE EMPRESA</b></h3><br>
                    <table style="width: 65%;color: #33373d">
                        <tr>
                            <td>Nombre:</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <input type="text" id="txtNombre" name="txtNombre" value="<?= $txtNombre; ?>"
                                       placeholder="Ingrese el nombre completo" required>
                            </td>
                        </tr>
                        <tr>
                            <td><br>Nit:</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <br> <input type="text" id="txtNit" name="txtNit"
                                            onkeypress="javascript:return validarNro(event)" value="<?= $txtNit; ?>"
                                            placeholder="Ingrese el Nit" required>
                            </td>
                        </tr>
                        <tr>
                            <td><br>Dirección:</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <br> <input type="text" id="txtDireccion" name="txtDireccion"
                                            value="<?= $txtDireccion; ?>" placeholder="Ingrese la dirección" required>
                            </td>
                        </tr>
                        <tr>
                            <td><br>Telefono:</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <br><input type="text" id="txtTelefono" name="txtTelefono" value="<?= $txtTelefono; ?>"
                                           placeholder="Ingrese el telefono" required>
                            </td>
                        </tr>
                        <tr>
                            <td><br>Correo:</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <br><input type="email" id="txtEmail" name="txtEmail" value="<?= $txtEmail; ?>"
                                           placeholder="Ingrese el correo" required>
                            </td>
                        </tr>
                        <tr>
                            <td>Logo:</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <img style="width: 140px;" src="../../Formularios/Empresas/<?= $Logo; ?>"><br>
                                <input type="file" name="file" id="file" style="margin-right: 85px;">
                            </td>
                        </tr>

                    </table>
                    <br>

                    <input type="submit" class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"
                           style="width:200px;"/>

                </center>

            </form>


        </div>
    </div>

</div>

</body>
</html>
