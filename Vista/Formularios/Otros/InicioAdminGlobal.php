
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';

    session_start();
    if (isset($_SESSION['login']) != '') {
        $Master = new Master();
        $menu = $Master->Menu();

    } else echo '<script > self.location = "/"</script>';

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

        <h1 id="logo"><span class="gray">ADMINISTRACIÓN</span></h1>

        <img style="float: left;margin-top: 10px;" src="../../Imagenes/logo.png">
    </div>

    <div id="content-wrap">
        <?= $menu ?>

        <div id="main">
            <center>
                <h3><b>INICIO</b></h3><br>
            </center>
        </div>
    </div>
</div>
</body>
</html>
