<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    session_start();
    if (isset($_SESSION['login']) != '') {


        $menu = (new Master())->Menu();
        $Usuarios = new cls_Usuarios();

        $Usuarios->CantidadUsuarios($_SESSION['login'][0]["ID_EMPRESA"]);
        if ($Usuarios->_CantUsuarios >= $_SESSION['login'][0]["CANT_USUARIOS"])
            echo '<script> alert("No se puede crear más usuarios.");self.location = "Usuarios.php" </script>';


        if (!empty($_POST)) {

            $Usuarios->InsertaUsuario($_POST['txtNombre'], $_POST['txtDocumento'], $_POST['txtEmail'], rand(100000, 999999), $_SESSION['login'][0]["ID_EMPRESA"]);

            foreach ($Usuarios->traeModulosPadres() as $llave => $valor) {
                $idMod = $valor['ID_MODULO'];

                foreach ($Usuarios->traeModulosHijos($idMod) as $llave => $valor) {
                    $Usuarios->registraPermisos($_POST['txtDocumento'], $valor['ID_MODULO'], 1, $_SESSION['login'][0]['ID_USUARIO']);
                }
            }

            echo '<script > alert("Se creó el usuario correctamente.");self.location = "Usuarios.php" </script>';


        }
    } else {
        echo '<script >self.location = "/";</script>';
    }
?>
<html>
<head>
    <title>Crear Usuario</title>

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
    function ValidaUsuario() {
        $("#botones").load("../Parametros/Validaciones.php?action=insertarusuario&txtEmail=" + document.getElementById('txtEmail').value + "&txtDocumento=" + document.getElementById('txtDocumento').value);
    }

    function check(aa) {
        document.getElementById('txt' + aa).value = "0";
        if (document.getElementById('chk' + aa).checked) {
            document.getElementById('txt' + aa).value = "1";
        }

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
                    <h3><b>CREAR USUARIO</b></h3><br>
                    <table style="width: 35%;color: #33373d">
                        <tr>
                            <td>Nombre</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <input type="text" id="txtNombre" name="txtNombre" value=""
                                       placeholder="Ingrese el nombre completo" required>
                            </td>
                        </tr>
                        <tr>
                            <td><br>Documento</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <br><input type="text" id="txtDocumento" name="txtDocumento" onkeyup="ValidaUsuario();"
                                           onkeypress="javascript:return validarNro(event);" value=""
                                           placeholder="Ingrese Documento" required>
                            </td>
                        </tr>
                        <tr>
                            <td><br>Correo</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <br><input type="email" name="txtEmail" onkeyup="ValidaUsuario();"
                                           placeholder="Ingrese el correo" required>
                            </td>
                        </tr>
                        <tr>
                            <td><br>Clave</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <br><input type="password" name="txtClave" onkeyup="ValidaUsuario();"
                                           placeholder="Ingrese su clave" required>
                            </td>
                        </tr>
                        <tr>
                            <td><br>Confirme Clave</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <br><input type="password" onkeyup="ValidaUsuario();" placeholder="Confirme su clave"
                                           required>
                            </td>
                        </tr>
                    </table>
                    <br>

                    <ul id="botones"><br><input type="submit" class="btnAzul" id="btnGuardar" name="btnGuardar"
                                                value="GUARDAR" style="width:200px;"/>
                    </ul>

                    <div id="val"></div>
                </center>

            </form>

        </div>
    </div>

</div>
<script>
    $('form').submit(function () {

        if ($('input:password:first').val() != $('input:password:last').val()) {
            event.preventDefault();
            $('#val').html('<br><br><span class="Error">LAS CLAVES NO COINSIDEN</span><br><br>');
        }
        else $('#val').html('');
    });

</script>
</body>

</html>