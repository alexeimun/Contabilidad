<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Contabilidad.php';
    include '../../../Clases/cls_Parametros.php';
    session_start();
    if (isset($_SESSION['login']) != '') {

        if ($_GET['id'] == "") echo '<script >self.location = "FormasDePago.php";</script>';

        $Master = new Master();
        $menu = $Master->Menu();
        $Contabilidad = new cls_Contabilidad();
        $Parametros = new cls_Parametros();

        $txtNombre = '';
        $cmbCtas = '<option value ="0">-- Seleccione --</option>';
        $RequiereNumero = '';
        $RequiereEntidad = '';

        foreach ($Parametros->TraeDatosFormaPago($_GET['id']) as $llave => $valor) {
            $txtNombre = $valor['NOMBRE_F_PAGO'];
            $cta = $valor['ID_CUENTA'];

            foreach ($Contabilidad->TraeCuentas($_SESSION['login'][0]["ID_EMPRESA"]) as $llave1 => $valor1) {
                if ($cta == $valor1['ID_CUENTA'])
                    $cmbCtas .= '<option value ="' . $valor1['ID_CUENTA'] . '" selected>' . $valor1['CODIGO'] . ' - ' . $valor1['NOMBRE'] . '</option>';
                else
                    $cmbCtas .= '<option value ="' . $valor1['ID_CUENTA'] . '">' . $valor1['CODIGO'] . ' - ' . $valor1['NOMBRE'] . '</option>';
            }

            if ($valor['REQUIERE_ENTIDAD'] == 1) {
                $RequiereEntidad = '<input type="checkbox" id="chkRequiereEntidad" checked name="chkRequiereEntidad" onClick="check2();">
                 <input type="hidden" value="1" id="txtRequiereEntidad" name="txtRequiereEntidad"><br><br>';
            } else {
                $RequiereEntidad = '<input type="checkbox" id="chkRequiereEntidad" name="chkRequiereEntidad" onClick="check2();">
                 <input type="hidden" value="0" id="txtRequiereEntidad" name="txtRequiereEntidad"><br><br>';
            }

            if ($valor['REQUIERE_NUMERO'] == 1) {
                $RequiereNumero = '<br><input type="checkbox" checked id="chkRequiereNumero" name="chkRequiereNumero" onClick="check();">
                 <input type="hidden" value="1" id="txtRequiereNumero" name="txtRequiereNumero"><br><br>';
            } else {
                $RequiereNumero = '<br><input type="checkbox" id="chkRequiereNumero" name="chkRequiereNumero" onClick="check();">
                 <input type="hidden" value="0" id="txtRequiereNumero" name="txtRequiereNumero"><br><br>';
            }
        }


        if (!empty($_POST)) {
            $Parametros->ActualizaFormaPago($_GET['id'], $_POST['txtNombre'], $_POST['cmbCuenta'], $_POST['txtRequiereEntidad'], $_POST['txtRequiereNumero']);
            echo '<script > alert("Se modificó la forma de pago correctamente.");self.location = "FormasDePago.php";</script>';
        }
    } else  echo '<script > self.location = "/";</script>';
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
        width: 290px;
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
                            <td>Nombre</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <input type="text" id="txtNombre" name="txtNombre" value="<?= $txtNombre; ?>"
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
                                <?= $RequiereEntidad; ?>
                            </td>
                        </tr>
                        <tr>

                            <td>Requiere Número</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <?= $RequiereNumero; ?>
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
