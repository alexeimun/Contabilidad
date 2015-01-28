<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Parametros.php';
    session_start();
    if (isset($_SESSION['login']) == '' || $_SESSION['permisos'][22][1] == 0)
        echo '<script language = javascript> self.location = "../Otros/Login.php"</script>';

    $Master = new Master();
    $menu = $Master->Menu();

?>
<html>
<head>
    <title>Descuentos Compras</title>

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
        <a href=""><img src="<?= $_SESSION['login'][0]["LOGO_EMPRESA"] ?>"></img></a>

        <h1 id="logo"><span class="gray"><?= $_SESSION['login'][0]["NOMBRE_EMPRESA"] ?></span></h1>

        <h3><span><?= $_SESSION['login'][0]["NOMBRE_USUARIO"] ?></span></h3>
        <img style="float: right;margin-top: 10px;" src="../../Imagenes/logo.png">
    </div>

    <div id="content-wrap">
        <?= $menu ?>

        <div id="main">
            <center>
                <h3><b>DESCUENTOS COMPRAS</b></h3><br>


            </center>
        </div>
    </div>

</div>

</body>
</html>
