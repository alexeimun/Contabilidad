<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Empresas.php';
    session_start();
    if (isset($_SESSION['login']) == '' || (new cls_Usuarios())->TienePermiso(__FILE__, $_SESSION['login'][0]['ID_USUARIO']))
        echo '<script > self.location = "/"</script>';
    $Master = new Master();
    $menu = $Master->Menu();
    $Empresa = new cls_Empresas();

    $txtNombre = '';
    $txtNit = '';
    $txtDireccion = '';
    $txtTelefono = '';
    $txtEmail = '';
    $Logo = '';
    $cmbRegimen = '';


    foreach ($Empresa->TraeDatosEmpresa($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
        $txtNombre = $valor['NOMBRE_EMPRESA'];
        $txtNit = $valor['NIT'];
        $txtEmail = $valor['EMAIL'];
        $txtDireccion = $valor['DIRECCION'];
        $txtTelefono = $valor['TELEFONO'];
        $Logo = $valor['LOGO'];
        $Regimen = $valor['ID_REGIMEN'];
    }
    foreach ($Empresa->TraeRegimenes() as $llave => $valor) {
        if ($valor['ID_REGIMEN'] == $Regimen)
            $cmbRegimen .= '<option style="text-align:left;" value ="' . $valor['ID_REGIMEN'] . '" selected>' . $valor['NOMBRE'] . '</option>';
        else  $cmbRegimen .= '<option style="text-align:left;" value ="' . $valor['ID_REGIMEN'] . '">' . $valor['NOMBRE'] . '</option>';
    }


    if (isset($_POST['btnGuardar']) != '') {

        $img = 'z' . rand(1000000000, 999999999) . '_' . $_FILES["file"]["name"];
        if ($_FILES["file"]["name"] == '') {
            $img = $Logo;
        }
        $Empresa->ActualizaEmpresa($_SESSION['login'][0]["ID_EMPRESA"], $_POST['txtNombre'], $_POST['txtNit'], $_POST['txtDireccion'], $_POST['txtTelefono'], $_POST['txtEmail'], $_POST['cmbRegimen'], $img);

        move_uploaded_file($_FILES["file"]["tmp_name"], $img);

        echo '<script > alert("Se modificaron los datos de la empresa correctamente"); self.location = "InformacionEmpresa.php"; </script>';
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
    <?php include '../../Css/css.php' ?>
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
        <a href=""><img src="<?= $_SESSION['login'][0]["LOGO_EMPRESA"] ?>"></a>

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
                    <table style="width: 65%;color: #33373d;">
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
                                            onkeypress="return validarNro(event);" value="<?= $txtNit; ?>"
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
                            <td><br>Tipo de Régimen:</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <br> <select class="chosen-select" name="cmbRegimen">
                                    <?= $cmbRegimen ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Logo:</td>
                            <td style="text-align: center;padding: 10px;">
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
