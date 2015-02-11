<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Contabilidad.php';
    include '../../../Clases/cls_Parametros.php';
    include '../../../Clases/Componentes.php';
    session_start();
    if (isset($_SESSION['login']) != '') {


        $Master = new Master();
        $menu = $Master->Menu();
        $Contabilidad = new cls_Contabilidad();
        $Parametros = new cls_Parametros();
        $Componentes = new Componentes();

        $cmbEntidad = '<option value ="0">-- Seleccione Entidad --</option>';
        foreach ($Componentes->TraeEntidades($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor)
            $cmbEntidad .= '<option style="text-align:left;" value ="' . $valor['ID_ENTIDAD'] . '">' . $valor['NOMBRE_ENTIDAD'] . '</option>';

        $cmbCtas = '<option value ="0">-- Seleccione --</option>';

        foreach ($Contabilidad->TraeCuentas($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
            $cmbCtas .= '<option value ="' . $valor['ID_CUENTA'] . '">' . $valor['CODIGO'] . ' - ' . $valor['NOMBRE'] . '</option>';
        }

        if (isset($_POST['btnGuardar']) != '') {

            $Parametros->InsertaFormaPago($_POST['txtCodigo'], $_POST['txtNombre'], $_POST['cmbCuenta'], $_POST['txtRequiereEntidad'], $_POST['txtRequiereNumero'], $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"]);

            echo '<script >
                    alert("Se creó la forma de pago correctamente.")
                    self.location = "FormasDePago.php"
                    </script>';

        }


    } else {
        echo '<script >
        self.location = "/"
	</script>';
    }
?>
<html>
<head>
    <title>Crear Forma de Pago</title>


    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>
    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>
    <?php include '../../Css/css.php' ?>
</head>
<style type="text/css">
    select {
        width: 200px;
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
        height: 20px
    }
</style>

<script>

    function ValidaCodigo() {
        $("#botones").load("Validaciones.php?action=insertarformapago&txtCodigo=" + document.getElementById('txtCodigo').value);

    }


    function check() {
        document.getElementById('txtRequiereNumero').value = "0";
        if (document.getElementById('chkRequiereNumero').checked) {
            document.getElementById('txtRequiereNumero').value = "1";
        }

    }

    function check2() {
        document.getElementById('txtRequiereEntidad').value = "0";
        if (document.getElementById('chkRequiereEntidad').checked) {
            document.getElementById('txtRequiereEntidad').value = "1";
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
                    <h3><b>CREAR FORMA DE PAGO</b></h3><br>
                    <table style="width: 35%;color: #33373d">
                        <tr>
                            <td>Código</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <input type="text" id="txtCodigo" onkeyup="ValidaCodigo()" name="txtCodigo"
                                       onkeypress="javascript:return validarNro(event)" value=""
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
                            <td>Cuenta</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <select id="cmbCuenta" class="chosen-select" name="cmbCuenta">
                                    <?= $cmbCtas; ?>
                                </select><br><br>
                            </td>
                        </tr>
                        <tr>
                            <td>Requiere Entidad</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <input type="checkbox" id="chkRequiereEntidad" name="chkRequiereEntidad"
                                       onClick="check2()">
                                <input type="hidden" value="0" id="txtRequiereEntidad"
                                       name="txtRequiereEntidad"><br><br>
                            </td>
                        </tr>
                        <tr>

                            <td>Requiere Número</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <br><input type="checkbox" id="chkRequiereNumero" name="chkRequiereNumero"
                                           onClick="check()">
                                <input type="hidden" value="0" id="txtRequiereNumero" name="txtRequiereNumero"><br><br>
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
