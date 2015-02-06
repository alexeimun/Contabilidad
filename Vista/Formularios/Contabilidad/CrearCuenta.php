<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Contabilidad.php';
    session_start();
    if (isset($_SESSION['login']) != '') {


        $Master = new Master();
        $menu = $Master->Menu();
        $Contabilidad = new cls_Contabilidad();


        if (isset($_POST['btnGuardar']) != '') {
            $Contabilidad->InsertaCuenta($_POST['txtCodigo'],ucfirst($_POST['txtNombre']), $_POST['txtManejaTercero'], $_POST['txtManejaDocCruce'],
                $_POST['rbNaturaleza'], $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"]);
            echo '<script >alert("Se creó la cuenta correctamente.");self.location = "PUC.php";</script>';
        }

    } else echo '<script >self.location = "../Otros/Login.php";</script>';

?>
<html>
<head>
    <title>Crear Cuenta Contable</title>

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

    input[type='checkbox'] {
        width: 20px;
        height: 20px;
    }
</style>

<script>

    function ValidaCodigo() {
        $("#botones").load("../Parametros/Validaciones.php?action=insertarcuentacontable&txtCodigo=" + document.getElementById('txtCodigo').value);

    }

    function check() {
        document.getElementById('txtManejaDocCruce').value = "0";
        if (document.getElementById('chkManejaDocCruce').checked) {
            document.getElementById('txtManejaDocCruce').value = "1";
        }

    }

    function check2() {
        document.getElementById('txtManejaTercero').value = "0";
        if (document.getElementById('chkManejaTercero').checked) {
            document.getElementById('txtManejaTercero').value = "1";
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
            <form method="POST">
                <center>
                    <h3><b>CREAR CUENTA CONTABLE</b></h3><br>
                    <table style="width: 35%;color: #33373d">
                        <tr>
                            <td>Código</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <input type="text" id="txtCodigo" name="txtCodigo" onkeyup="ValidaCodigo();"
                                       onkeypress="javascript:return validarNro(event);" value=""
                                       placeholder="Ingrese el código" required>
                                <br><br></td>
                        </tr>
                        <tr>
                            <td>Nombre</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <input type="text" id="txtNombre" name="txtNombre" value=""
                                       placeholder="Ingrese el nombre " required>
                                <br><br></td>
                        </tr>
                        <tr>
                            <td>Maneja Tercero</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <input type="checkbox" id="chkManejaTercero" name="chkManejaTercero"
                                       onClick="check2();">
                                <input type="hidden" value="0" id="txtManejaTercero" name="txtManejaTercero"><br><br>
                            </td>
                        </tr>
                        <tr>

                            <td>Maneja Doc. Cruce</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <br><input type="checkbox" id="chkManejaDocCruce" name="chkManejaDocCruce"
                                           onClick="check();">
                                <input type="hidden" value="0" id="txtManejaDocCruce" name="txtManejaDocCruce"><br><br>
                            </td>
                        </tr>
                        <tr>
                            <td>Naturaleza</td>
                            <td style="padding-left: 10px;text-align: center;">
                                Crédito&nbsp;&nbsp;<input type="radio" id="rbNaturaleza" name="rbNaturaleza" value="C"
                                                          required="">
                                Débito&nbsp;&nbsp;<input type="radio" id="rbNaturaleza" name="rbNaturaleza" value="D">
                            </td>
                        </tr>

                    </table>
                    <br>

                    <ul id="botones"><br><input type="submit" class="btnAzul" id="btnGuardar" name="btnGuardar"
                                                value="GUARDAR" style="width:200px;"/>
                    </ul>
                </center>

            </form>
        </div>
    </div>

</div>

</body>
</html>
