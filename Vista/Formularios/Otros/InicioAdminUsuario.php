
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';

    session_start();
    if (isset($_SESSION['login']) != '') {
        $Master = new Master();
        $menu = $Master->Menu();

    } else {
        echo '<script >self.location = "/"</script>';
    }
?>
<html>
<head>
    <title>Inicio</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>
    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>
</head>
<style type="text/css">
</style>

<script></script>

<body>
<div id="wrap">
    <div id="header">
        <a href=""><img src="<?= $_SESSION['login'][0]["LOGO_VENDEDOR"] ?>"></a>
        <img style="float: right;margin-top: 10px;" src="../../Imagenes/logo.png">

        <h1 id="logo"><span class="gray">Administración&nbsp&nbsp; <span
                    style="font-size: 28px"><?= $_SESSION['login'][0]['NOMBRE_VENDEDOR'] ?></span></span></h1>

    </div>

    <div id="content-wrap">
        <?= $menu
        ?>

        <div id="main">
            <center>
                <h3><b>INICIO</b></h3>
                <br>

            </center>
        </div>
    </div>

</div>

</body>
</html>
