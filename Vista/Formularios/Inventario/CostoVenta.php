<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Inventario.php';
    session_start();
    if (isset($_SESSION['login']) == '' || (new cls_Usuarios())->TienePermiso(__FILE__, $_SESSION['login'][0]['ID_USUARIO']))
        echo '<script > self.location = "/"</script>';
    $table='<table id="table" class="table" style="width:90%;">
        <thead><tr>
            <th style="text-align:left;"></th>
            <th style="text-align:left;"></th>
            <th style="text-align:left;">DEBITO</th>
            <th style="text-align:left;">CREDIRO</th></tr>
        <tr>
        <td colspan="2">Inventario Inicial</td><td>deb</td><td>cre</td>

</tr>';

    $Master = new Master();
    $menu = $Master->Menu();

?>
<html>
<head>
    <title>Costo Venta</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>
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
                <h3><b>COSTO VENTA</b></h3><br>

                <form method="post">
                    Mes:<input type="number" name="mes" min="1" max="12" value="<?= round(date("m")) ?>"
                               style="width: 50px;text-align: center;margin-right: 40px;"/>

                    Año:<input type="number" name="ano" min="2000" max="2050"
                               value="<?= date("Y") ?>"
                               style="width: 60px;text-align: center;"/>

                    <br><br>
                    <input type="button" id="btnGenerar" class="btnAzul" name="btnGenerar" value="Generar"
                           style="width:100px;"/>
                    <input type="hidden" name="costoventa"></form>

                <br><br>
                <div class="total"></div>
            </center>
        </div>
    </div>
</div>
</body>

<script>
    $(document).ready(function () {
        $('#btnGenerar').click(function () {

            $.post('Actions.php', $('form').serialize(), function (data) {
               $('div.total').html('<h3>'+data+'</h3>');
            });
        });
    });
</script>
</html>