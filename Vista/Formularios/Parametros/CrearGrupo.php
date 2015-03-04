<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Contabilidad.php';
    include '../../../Clases/cls_Parametros.php';
    session_start();
    if (isset($_SESSION['login']) != '') {


        $Master = new Master();
        $menu = $Master->Menu();
        $Contabilidad = new cls_Contabilidad();
        $Parametros = new cls_Parametros();

        $txtNombre = '';
        $cmbCtas = '<option value ="0">-- Seleccione --</option>';

        foreach ($Contabilidad->TraeCuentas($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
            $cmbCtas .= '<option value ="' . $valor['ID_CUENTA'] . '">' . $valor['CODIGO'] . ' - ' . $valor['NOMBRE'] . '</option>';
        }

        if (isset($_POST['btnGuardar']) != '') {

            $Parametros->InsertaGrupos($_POST['txtNombre'], $_POST['cmbCtaInventario'], $_POST['cmbCtaVentas'], $_POST['cmbCtaCosto'], $_POST['cmbCtaDevoluciones'], $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"]);

            echo '<script >
                    alert("Se creó el grupo correctamente.");
                    self.location = "ProductosServiciosGrupos.php?me=2";
                    </script>';

        }


    } else {
        echo '<script >
        self.location = "/";
	</script>';
    }
?>
<html>
<head>
    <title>Crear Grupo</title>

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
                    <h3><b>CREAR GRUPO</b></h3><br>
                    <table style="width: 35%;color: #33373d;">
                        <tr>
                        <tr>
                            <td>Nombre</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <input type="text" id="txtNombre" name="txtNombre" value="<?= $txtNombre; ?>"
                                       placeholder="Ingrese el nombre " required>
                                <br><br></td>
                        </tr>
                        <tr>
                            <td>Cta Inventario</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <select id="cmbCtaInventario" class="chosen-select" name="cmbCtaInventario">
                                    <?= $cmbCtas; ?>
                                </select><br><br>
                            </td>
                        </tr>
                        <tr>
                            <td>Cta Ventas</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <select id="cmbCtaVentas" class="chosen-select" name="cmbCtaVentas">
                                    <?= $cmbCtas; ?>
                                </select><br><br>
                            </td>
                        </tr>
                        <tr>
                            <td>Cta Costo</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <select id="cmbCtaCosto" name="cmbCtaCosto" class="chosen-select">
                                    <?= $cmbCtas; ?>
                                </select><br><br>
                            </td>
                        </tr>
                        <tr>
                            <td>Cta Devoluciones</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <select id="cmbCtaDevoluciones" name="cmbCtaDevoluciones" class="chosen-select">
                                    <?= $cmbCtas; ?>
                                </select><br><br>
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
