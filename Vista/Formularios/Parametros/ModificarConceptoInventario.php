<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_parametros.php';
    include '../../../Clases/cls_contabilidad.php';
    session_start();
    if (isset($_SESSION['login']) != '') {

        if ($_GET['id'] == "")
            echo '<script >self.location = "ConceptosInventario.php"  </script>';

        $Master = new Master();
        $menu = $Master->Menu();
        $Usuarios = new cls_Usuarios();
        $Parametros = new cls_Parametros();
        $Contabilidad = new cls_Contabilidad();
        $options = '<option value ="0">-- Seleccione Una Cuenta --</option>';

        foreach ($Parametros->TraeConcepto($_GET['id']) as $llave => $valor) {
            $cmbconcepto = '';
            $txtComentarios = $valor['DESCRIPCION'];

            $cmbconcepto .= ' <option value="2" ' . ($valor['CONCEPTO'] == 2 ? 'selected' : '') . '>Inventario Inicial</option>';
            $cmbconcepto .= ' <option value="3" ' . ($valor['CONCEPTO'] == 3 ? 'selected' : '') . '>Inventario Final</option>';
            $cmbconcepto .= ' <option value="4" ' . ($valor['CONCEPTO'] == 4 ? 'selected' : '') . '>Compras</option>';
            $cmbconcepto .= ' <option value="5" ' . ($valor['CONCEPTO'] == 6 ? 'selected' : '') . '>Devoluciones Compras</option>';
            $cmbconcepto .= ' <option value="6" ' . ($valor['CONCEPTO'] == 5 ? 'selected' : '') . '>Descuento Compras</option>';

            foreach ($Contabilidad->TraeCuentas($_SESSION['login'][0]["ID_EMPRESA"]) as $llave1 => $valor1) {
                if ($valor['ID_CUENTA'] == $valor1['ID_CUENTA']) {
                    $options .= '<option value ="' . $valor1['ID_CUENTA'] . '" selected>' . $valor1['CODIGO'] . " - " . $valor1['NOMBRE'] . '</option>';
                } else
                    $options .= '<option value ="' . $valor1['ID_CUENTA'] . '">' . $valor1['CODIGO'] . " - " . $valor1['NOMBRE'] . '</option>';
            }
        }

        if (isset($_POST['btnGuardar']) != '') {
            if ($_POST['txtCuenta'] != 0) {

                $Parametros->ActualizaConcepto($_POST['txtConcepto'], $_POST['txtDescripcion'], $_POST['txtCuenta'],
                    $_SESSION['login'][0]["ID_USUARIO"], $_GET['id']);

                echo '<script > alert("Se modificó el concepto correctamente.");self.location = "ConceptosInventario.php" </script>';

            } else echo '<script >alert("Debe seleccionar una cuenta."); self.location = "ModificarConceptoInventario.php" </script>';
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
                    <table style="width: 35%;color: #33373d;">
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
                                                value="GUARDAR" style="width:200px;"/></ul>
                </center>

            </form>
        </div>
    </div>

</div>

</body>
</html>
