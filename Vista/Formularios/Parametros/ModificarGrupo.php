<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Contabilidad.php';
    include '../../../Clases/cls_Parametros.php';
    session_start();
    if (isset($_SESSION['login']) != '') {


        if ($_GET['id'] == "") {
            echo '<script >
            self.location = "ProductosServiciosGrupos.php?me=2"
            </script>';
        }

        $Master = new Master();
        $menu = $Master->Menu();
        $Contabilidad = new cls_Contabilidad();
        $Parametros = new cls_Parametros();

        $txtNombre = '';
        $cmbCtaInventario = '<option value ="0">-- Seleccione --</option>';
        $cmbCtaVentas = '<option value ="0">-- Seleccione --</option>';
        $cmbCtaCosto = '<option value ="0">-- Seleccione --</option>';
        $cmbCtaDevoluciones = '<option value ="0">-- Seleccione --</option>';

        foreach ($Parametros->TraeDatosGrupo($_GET['id']) as $llave => $valor) {
            $txtNombre = $valor['NOMBRE'];
            $CtaInventario = $valor['CTA_INVENTARIO'];
            $CtaVentas = $valor['CTA_VENTAS'];
            $CtaCosto = $valor['CTA_COSTO'];
            $CtaDevoluciones = $valor['CTA_DEVOLUCIONES'];

            foreach ($Contabilidad->TraeCuentas($_SESSION['login'][0]["ID_EMPRESA"]) as $llave1 => $valor1) {
                if ($valor1["ID_CUENTA"] == $CtaInventario) {
                    $cmbCtaInventario .= '<option value ="' . $valor1['ID_CUENTA'] . '" selected>' . $valor1['CODIGO'] . ' - ' . $valor1['NOMBRE'] . '</option>';
                } else {
                    $cmbCtaInventario .= '<option value ="' . $valor1['ID_CUENTA'] . '">' . $valor1['CODIGO'] . ' - ' . $valor1['NOMBRE'] . '</option>';
                }

                if ($valor1["ID_CUENTA"] == $CtaVentas) {
                    $cmbCtaVentas .= '<option value ="' . $valor1['ID_CUENTA'] . '" selected>' . $valor1['CODIGO'] . ' - ' . $valor1['NOMBRE'] . '</option>';
                } else {
                    $cmbCtaVentas .= '<option value ="' . $valor1['ID_CUENTA'] . '">' . $valor1['CODIGO'] . ' - ' . $valor1['NOMBRE'] . '</option>';
                }

                if ($valor1["ID_CUENTA"] == $CtaCosto) {
                    $cmbCtaCosto .= '<option value ="' . $valor1['ID_CUENTA'] . '" selected>' . $valor1['CODIGO'] . ' - ' . $valor1['NOMBRE'] . '</option>';
                } else {
                    $cmbCtaCosto .= '<option value ="' . $valor1['ID_CUENTA'] . '">' . $valor1['CODIGO'] . ' - ' . $valor1['NOMBRE'] . '</option>';
                }

                if ($valor1["ID_CUENTA"] == $CtaDevoluciones) {
                    $cmbCtaDevoluciones .= '<option value ="' . $valor1['ID_CUENTA'] . '" selected>' . $valor1['CODIGO'] . ' - ' . $valor1['NOMBRE'] . '</option>';
                } else {
                    $cmbCtaDevoluciones .= '<option value ="' . $valor1['ID_CUENTA'] . '">' . $valor1['CODIGO'] . ' - ' . $valor1['NOMBRE'] . '</option>';
                }


            }

        }


        if (isset($_POST['btnGuardar']) != '') {

            $Parametros->ActualizaGrupo($_GET['id'], $_POST['txtNombre'], $_POST['cmbCtaInventario'], $_POST['cmbCtaVentas'], $_POST['cmbCtaCosto'], $_POST['cmbCtaDevoluciones']);
            echo '<script >
                    alert("Se modificó el grupo correctamente.")
                    self.location = "ProductosServiciosGrupos.php?me=2"
                    </script>';

        }


    } else {
        echo '<script >
        self.location = "../Otros/Login.php"
	</script>';
    }
?>
<html>
<head>
    <title>Modificar Grupo</title>

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

    input[type='checkbox'] {
        width: 20px;
        height: 20px
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
            <form method="POST">
                <center>
                    <h3><b>MODIFICAR GRUPO</b></h3><br>
                    <table style="width: 35%;color: #33373d">
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
                                <select id="cmbCtaInventario" name="cmbCtaInventario" class="chosen-select">
                                    <?= $cmbCtaInventario; ?>
                                </select><br><br>
                            </td>
                        </tr>
                        <tr>
                            <td>Cta Ventas</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <select id="cmbCtaVentas" name="cmbCtaVentas" class="chosen-select">
                                    <?= $cmbCtaVentas; ?>
                                </select><br><br>
                            </td>
                        </tr>
                        <tr>
                            <td>Cta Costo</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <select id="cmbCtaCosto" name="cmbCtaCosto" class="chosen-select">
                                    <?= $cmbCtaCosto; ?>
                                </select><br><br>
                            </td>
                        </tr>
                        <tr>
                            <td>Cta Devoluciones</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <select id="cmbCtaDevoluciones" name="cmbCtaDevoluciones" class="chosen-select">
                                    <?= $cmbCtaDevoluciones; ?>
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
