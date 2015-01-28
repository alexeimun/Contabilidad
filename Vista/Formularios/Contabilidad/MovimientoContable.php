<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Parametros.php';
    include '../../../Clases/cls_Contabilidad.php';

    session_start();
    if (isset($_SESSION['login']) == '' || $_SESSION['permisos'][10][1] == 0)
        echo '<script language = javascript> self.location = "../Otros/Login.php"</script>';

    $Master = new Master();
    $menu = $Master->Menu();
    $Contabilidad = new cls_Contabilidad();


    $Cuenta = '<option value ="0">-- Seleccione Una Cuenta --</option>';

    foreach ($Contabilidad->TraeCuentas($_SESSION['login'][0]["ID_EMPRESA"]) as $llave1 => $valor1)
        $Cuenta .= '<option value ="' . $valor1['ID_CUENTA'] . '" selected>' . $valor1['NOMBRE'] . '</option>';

?>
<html>
<head>
    <title>Moviemiento Contable</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>
    <script src="../../Js/jquery.dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>
    <?php include '../../Css/css.php' ?>
</head>


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
            <center>
                <h3><b>MOVIMIENTO CONTABLE</b></h3><br>

                <form action="">
                    <table style="width: 40%;color: #33373d;">
                        <tr>
                            <td style="text-align: right;">Desde:</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <input type="date" name="desde">
                            </td>
                            <td style="text-align: right;">Hasta:</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <input type="date" name="hasta">
                            </td>
                            <td style="text-align: right;">Cuenta</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <select name="cmbCuenta" class="chosen-select" style="width:200px;">
                                    <?= $Cuenta ?>
                                </select>
                            </td>
                            <input type="hidden" value="2" name="contable"/>
                        </tr>
                    </table>
                </form>
                <br>
                <input type="button" value="Generar" class="btnAzul"/>
                <br><br>

                <div id="busqueda"></div>

            </center>
        </div>
    </div>
</div>

<script>
    $(document).on('ready', function () {
        $('input[value=Generar]').on('click', function () {
            $.ajax(
                {
                    url: 'Movimientos.php',
                    type: 'post',
                    data: $('form').serialize(),
                    success: function (data) {
                        $('#busqueda').html(data);
                    }
                });
        });
    });
</script>
<style>

</style>
</body>
</html>