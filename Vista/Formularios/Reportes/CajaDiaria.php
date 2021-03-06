<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Parametros.php';
    session_start();
    if (isset($_SESSION['login']) == '' || (new cls_Usuarios())->TienePermiso(__FILE__, $_SESSION['login'][0]['ID_USUARIO']))
        echo '<script > self.location = "/"</script>';

    $Master = new Master();
    $menu = $Master->Menu();

    $Desde = date("Y") . '-' . date("m") . '-' . date("d");
    $Hasta = date("Y") . '-' . date("m") . '-' . date("d");

    if (isset($_POST['btnGenerar']) != '') {

        $_SESSION['DESDE_CUADRE_CAJA'] = $_POST['txtDesde'];
        $_SESSION['HASTA_CUADRE_CAJA'] = $_POST['txtHasta'];

        $Desde = $_POST['txtDesde'];
        $Hasta = $_POST['txtHasta'];

        echo '<script >window.open("ImpresionCajaDiaria.php"); </script>';
    }

?>
<html>
<head>
    <title>Libro Diario</title>

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
            <form method="POST">
                <center>
                    <h3><b>CAJA DIARIA </b></h3><br>

                    Desde <input type="date" id="txtDesde" name="txtDesde" value="<?= $Desde; ?>" required>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Hasta <input type="date" id="txtHasta" name="txtHasta" value="<?= $Hasta; ?>" required>
                    <br><br><br>
                    <input type="submit" id="btnGenerar" class="btnAzul" name="btnGenerar" value="Generar"
                           style="width:100px;"/>
                </center>
            </form>
        </div>
    </div>
</div>
</body>
</html>
