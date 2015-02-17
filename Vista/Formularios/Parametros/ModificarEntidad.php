<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/Componentes.php';
    session_start();
    if (isset($_SESSION['login']) != '') {

        if ($_GET['id'] == "")
            echo '<script >self.location = "Conceptos.php"  </script>';

        $Master = new Master();
        $menu = $Master->Menu();
        $Componentes = new Componentes();

        foreach ($Componentes->TraeEntidad($_GET['id']) as $llave => $valor) {
            $Nombre = $valor["NOMBRE_ENTIDAD"];
            $Tipo = $valor['TIPO'];
        }

        if (!empty($_POST)) {

            $Componentes->ActualizaEntidad($_POST['Nombre'], $_POST['Tipo'], $_SESSION['login'][0]["ID_USUARIO"], $_GET['id']);
            echo '<script> alert("Se modificó la entidad correctamente.");self.location = "Entidades.php" </script>';
        }

    } else echo '<script>self.location = "/" </script>';

?>
<html>
<head>
    <title>Modificar Entidad</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>
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
</style>

<script>
    function Validar() {
        $("#botones").load("CrearConcepto.php?action=validarcuenta");
    }

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
                    <h3><b>MODIFICAR ENTIDAD</b></h3><br>
                    <table style="width: 35%;color: #33373d">
                        <tr>
                        <tr>
                            <td>Nombre</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <input type="text" name="Nombre" value="<?= $Nombre ?>"
                                       placeholder="Ingrese el nombre " required>
                                <br><br></td>
                        </tr>
                        <tr>
                            <td>Tipo</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <input type="text" name="Tipo" value="<?= $Tipo ?>" placeholder="Ingrese el tipo"
                                       required>
                                <br><br></td>
                        </tr>
                    </table>
                    <br>

                    <ul id="botones"><br><input type="submit" class="btnAzul" id="btnGuardar" name="btnGuardar"
                                                value="GUARDAR" style="width:200px;"/>
                    </ul>
                </center>

            </form>
        </div>
    </div>

</div>

</body>
</html>
