<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_parametros.php';
    include '../../../Clases/cls_contabilidad.php';
    session_start();
    if (isset($_SESSION['login']) != '') {

        if ($_GET['id'] == "")
            echo '<script >self.location = "Conceptos.php"  </script>';

        $Master = new Master();
        $menu = $Master->Menu();
        $Usuarios = new cls_Usuarios();
        $Parametros = new cls_Parametros();
        $Contabilidad = new cls_Contabilidad();
        $options = '<option value ="0">-- Seleccione Una Cuenta --</option>';

        foreach ($Parametros->TraeConcepto($_GET['id']) as $llave => $valor) {
            $txtCodigo = $valor["CODIGO"];
            $cmbconcepto = '';
            $txtComentarios = $valor['DESCRIPCION'];

            if ($valor['CONCEPTO'] == "0")
                $cmbconcepto .= '<option value="0" selected>Gastos</option> <option value="1">Ingresos</option>';
            else $cmbconcepto .= '<option value="0">Gastos</option> <option value="1" selected>Ingresos</option>';

            foreach ($Contabilidad->TraeCuentas($_SESSION['login'][0]["ID_EMPRESA"]) as $llave1 => $valor1) {
                if ($valor['ID_CUENTA'] == $valor1['ID_CUENTA']) {
                    $options .= '<option value ="' . $valor1['ID_CUENTA'] . '" selected>' . $valor1['NOMBRE'] . '</option>';
                } else {
                    $options .= '<option value ="' . $valor1['ID_CUENTA'] . '">' . $valor1['NOMBRE'] . '</option>';
                }
            }
        }

        if (isset($_POST['btnGuardar']) != '') {
            if ($_POST['txtCuenta'] != 0) {

                $Parametros->ActualizaConcepto($_POST['txtCodigo'], $_POST['txtConcepto'], $_POST['txtDescripcion'], $_POST['txtCuenta'],
                    $_SESSION['login'][0]["ID_USUARIO"], $_GET['id']);

                echo '<script > alert("Se modificó el concepto correctamente.");self.location = "Conceptos.php" </script>';

            } else echo '<script >alert("Debe seleccionar una cuenta."); self.location = "ModificarConcepto.php" </script>';
        }
    } else echo '<script >self.location = "/" </script>';

?>
<html>
<head>
    <title>Modificar Concepto</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>
    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>
    <?php include '../../Css/css.php'; ?>
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
    function Validar() {
        $("#botones").load("CrearConcepto.php?action=validarcuenta");
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
                    <h3><b>MODIFICAR CONCEPTO</b></h3><br>
                    <table style="width: 35%;color: #33373d">
                        <tr>
                            <td>Código</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <input type="text" id="txtCodigo" name="txtCodigo" value="<?= $txtCodigo; ?>"
                                       placeholder="Ingrese el código" required>
                            </td>
                        </tr>
                        <tr>
                            <td><br>Concepto</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <br><select name="txtConcepto" class="chosen-select" style="width: 290px;">
                                    <?= $cmbconcepto; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><br>Cuenta</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <br><select name="txtCuenta" class="chosen-select" style="width: 290px;">
                                    <?= $options; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><br>Decripción</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <br><textarea style="width: 290px;" name="txtDescripcion" maxlength="500"
                                              placeholder="Ingrese los comentarios"><?= $txtComentarios; ?></textarea>
                                <br> Máximo 500 caracteres
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
