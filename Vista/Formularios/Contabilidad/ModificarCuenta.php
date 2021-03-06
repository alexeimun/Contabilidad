<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Contabilidad.php';
    session_start();
    if (isset($_SESSION['login']) != '') {

        if ($_GET['id'] == "") {
            echo '<script >
            self.location = "PUC.php";
            </script>';
        }

        $Master = new Master();
        $menu = $Master->Menu();
        $Contabilidad = new cls_Contabilidad();

        $txtNombre = '';
        $txtCodigo = '';

        $Naturaleza = '';

        $ManejaTercero = '<input type="checkbox" id="chkManejaTercero" name="chkManejaTercero" onClick="check2();" >
                 <input type="hidden" value="0" id="txtManejaTercero" name="txtManejaTercero"><br><br>';

        $ManejaDocCruce = '<br><input type="checkbox" id="chkManejaDocCruce" name="chkManejaDocCruce" onClick="check();">
                <input type="hidden" value="0" id="txtManejaDocCruce" name="txtManejaDocCruce"><br><br>';

        $cxc = '<input type="checkbox" id="chkcxc" name="chkcxc" onClick="check3();">
        <input type="hidden" value="0" id="txtcxc" name="txtcxc">';

        $cxp = '<input type="checkbox" id="chkcxp" name="chkcxp" onClick="check4();">
        <input type="hidden" value="0" id="txtcxp" name="txtcxp">';

        foreach ($Contabilidad->TraeDatosCuenta($_GET['id']) as $llave => $valor) {
            $txtNombre = $valor['NOMBRE'];
            $txtCodigo = $valor['CODIGO'];
            $txtNaturaleza = $valor['NATURALEZA'];


            if ($valor['CXC'] == 1) {
                $cxc = '<input type="checkbox" id="chkcxc" name="chkcxc" onClick="check3();" checked>
        <input type="hidden" value="0" id="txtcxc" name="txtcxc">';
            }

            if ($valor['CXP'] == 1) {
                $cxp = '<input type="checkbox" id="chkcxp" name="chkcxp" onClick="check4();" checked>
        <input type="hidden" value="0" id="txtcxp" name="txtcxp">';
            }

            if ($valor['MANEJA_TERCERO'] == 1) {
                $ManejaTercero = '<input type="checkbox" id="chkManejaTercero" name="chkManejaTercero" onClick="check2();" checked>
                 <input type="hidden" value="1" id="txtManejaTercero" name="txtManejaTercero"><br><br>';
            }

            if ($valor['MANEJA_DOC_CRUCE'] == 1) {
                $ManejaDocCruce = '<br><input type="checkbox" id="chkManejaDocCruce" name="chkManejaDocCruce" checked onClick="check();">
                <input type="hidden" value="1" id="txtManejaDocCruce" name="txtManejaDocCruce"><br><br>';
            }

            if ($valor['NATURALEZA'] == 'D') {
                $Naturaleza = 'Crédito&nbsp;&nbsp;<input type="radio"  id="rbNaturaleza" name="rbNaturaleza" value="C">
             Débito&nbsp;&nbsp;<input type="radio"  id="rbNaturaleza" name="rbNaturaleza" value="D" checked>';
            } else {
                $Naturaleza = 'Crédito&nbsp;&nbsp;<input type="radio"  id="rbNaturaleza" name="rbNaturaleza" value="C" checked>
             Débito&nbsp;&nbsp;<input type="radio"  id="rbNaturaleza" name="rbNaturaleza" value="D">';
            }

        }

        if (isset($_POST['btnGuardar']) != '') {

            $Contabilidad->ActualizaCuenta($_GET['id'], $_POST['txtCodigo'], ucfirst($_POST['txtNombre']), $_POST['txtManejaTercero'], $_POST['txtManejaDocCruce'], $_POST['rbNaturaleza'], $_POST['txtcxc'], $_POST['txtcxp']);

            echo '<script >alert("Se modificó la cuenta correctamente.");self.location = "PUC.php";</script>';
        }
    } else
        echo '<script >self.location = "/";</script>';
?>
<html>
<head>
    <title>Modificar Cuenta Contable</title>

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
        $("#botones").load("../Parametros/Validaciones.php?action=editarcuentacontable&txtCodigo=" + document.getElementById('txtCodigo').value + "&id=" +<?= $_GET['id'] ?>);
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

    function check3() {
        document.getElementById('txtcxc').value = "0";
        if (document.getElementById('chkcxc').checked) {
            document.getElementById('txtcxc').value = "1";
            document.getElementById('chkcxp').checked = false;
            document.getElementById('txtcxp').value = "0";
        }

    }

    function check4() {
        document.getElementById('txtcxp').value = "0";
        if (document.getElementById('chkcxp').checked) {
            document.getElementById('txtcxp').value = "1";
            document.getElementById('chkcxc').checked = false;
            document.getElementById('txtcxc').value = "0";
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
                    <h3><b>MODIFICAR CUENTA CONTABLE</b></h3><br>
                    <table style="width: 35%;color: #33373d">
                        <tr>
                            <td>Código</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <input type="text" id="txtCodigo" name="txtCodigo" onkeyup="ValidaCodigo();"
                                       onkeypress="javascript:return validarNro(event);" value="<?= $txtCodigo; ?>"
                                       placeholder="Ingrese el código" required>
                                <br><br></td>
                        </tr>
                        <tr>
                            <td>Nombre</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <input type="text" id="txtNombre" name="txtNombre" value="<?= $txtNombre; ?>"
                                       placeholder="Ingrese el nombre" required>
                                <br><br></td>
                        </tr>
                        <tr>
                            <td>Maneja Tercero</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <?= $ManejaTercero; ?>
                            </td>
                        </tr>
                        <tr>

                            <td>Maneja Doc. Cruce</td>
                            <td style="padding-left: 10px;text-align: center;">

                                <?= $ManejaDocCruce; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Naturaleza</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <?= $Naturaleza; ?>
                            </td>
                        </tr>

                        <tr>
                            <td><br><br>CTA</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <br><br>
                                CXC <?= $cxc; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CXP<?= $cxp; ?>
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
