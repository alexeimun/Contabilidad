<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Parametros.php';

    session_start();
    if (isset($_SESSION['login']) == '' || (new cls_Usuarios())->TienePermiso(__FILE__, $_SESSION['login'][0]['ID_USUARIO']))
        echo '<script> self.location = "/"</script>';

    $Master = new Master();
    $menu = $Master->Menu();


?>
<html>
<head>
    <title>Movimiento por tercero</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>
    <script src="../../Js/jquery.dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>


</head>

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
            <center>
                <h3><b>MOVIMIENTO POR TERCERO</b></h3><br>

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

                            <input type="hidden" value="2" name="tercero"/>
                        </tr>
                    </table>

                    <br>Filtrar por:<br><br>
                    <input type="radio" name="rad2" style="margin-left:30px;" checked>Documento
                    <input type="radio" name="rad1" style="margin-left:30px;">Nombre
                    <br><br>

                    <div><input type="text" name="txtDoc" style="text-align: center;"></div>
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


        $('input:radio').on('click', function () {

            var radio = $(this);
            if (radio.attr('name') === 'rad1') {
                $('input[name=rad2]:radio').attr('checked', false);
                $('form div').load('Movimientos.php', {filtro: 'Tercero'});
            }
            else {
                $('input[name=rad1]:radio').attr('checked', false);
                $('form div').load('Movimientos.php', {filtro: 'Documento'});
            }
        });

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