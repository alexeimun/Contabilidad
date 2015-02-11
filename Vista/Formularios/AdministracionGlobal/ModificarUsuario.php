<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Usuarios.php';
    session_start();
    if (isset($_SESSION['login']) != '') {

        if ($_GET['id'] == "") {
            echo '<script >
        self.location = "Usuarios.php";
	</script>';
        }

        $Master = new Master();
        $menu = $Master->Menu();
        $Usuarios = new cls_Usuarios();

        $txtNombre = '';
        $txtDocumento = '';
        $txtEmail = '';
        $txtPass = '';

        foreach ($Usuarios->TraeDatosUsuarios($_GET['id']) as $llave => $valor) {
            $txtNombre = $valor['NOMBRE'];
            $txtDocumento = $valor['DOCUMENTO'];
            $txtEmail = $valor['EMAIL'];
            $txtPass = $valor['PASSWORD'];
        }

        $checks = '';

        foreach ($Usuarios->traeModulosXUsuarioAdmin($_GET['id']) as $llave => $valor) {
            if ($valor['SI_O_NO'] == 1) {
                $checks .= $valor['NOMBRE'] . '&nbsp;<input type="checkbox" checked onClick="check(' . $valor['ID_MODULO'] . ')" id="chk' . $valor['ID_MODULO'] . '" name="chk' . $valor['ID_MODULO'] . '"></input><input type="hidden" value="' . $valor['SI_O_NO'] . '" id="txt' . $valor['ID_MODULO'] . '" name="txt' . $valor['ID_MODULO'] . '">&nbsp;&nbsp;&nbsp;&nbsp;';
            } else {
                $checks .= $valor['NOMBRE'] . '&nbsp;<input type="checkbox"  onClick="check(' . $valor['ID_MODULO'] . ')" id="chk' . $valor['ID_MODULO'] . '" name="chk' . $valor['ID_MODULO'] . '"></input><input type="hidden" value="' . $valor['SI_O_NO'] . '" id="txt' . $valor['ID_MODULO'] . '" name="txt' . $valor['ID_MODULO'] . '">&nbsp;&nbsp;&nbsp;&nbsp;';
            }
        }


        if (isset($_POST['btnGuardar']) != '') {


            $Usuarios->ActualizaUsuario($_GET['id'], $_POST['txtNombre'], $_POST['txtDocumento'], $_POST['txtEmail'], $_POST['txtPass']);

            foreach ($Usuarios->traeModulosXUsuarioAdmin($_GET['id']) as $llave => $valor) {

                $Usuarios->actualizaPermisos($_GET['id'], $valor['ID_MODULO'], $_POST['txt' . $valor['ID_MODULO']]);
            }


            echo '<script>alert("Se modificó el usuario correctamente.");self.location = "Usuarios.php";</script>';


        }

    } else echo '<script>self.location = "/";</script>';

?>
<html>
<head>
    <title>Modificar Usuario</title>

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
        $("#botones").load("../Parametros/Validaciones.php?action=editarusuario&txtEmail="
        + document.getElementById('txtEmail').value + "&txtDocumento=" + document.getElementById('txtDocumento').value + "&id="
        +<?= $_GET['id'] ?>);
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
        <a href=""><img src="../../Imagenes/logo.png"></a>

        <h1 id="logo"><span class="gray">Administración</span></h1>

    </div>

    <div id="content-wrap">
        <?= $menu ?>

        <div id="main">
            <form method="POST">
                <center>
                    <h3><b>MODIFICAR USUARIO</b></h3><br>
                    <table style="width: 35%;color: #33373d">
                        <tr>
                            <td>Nombre</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <input type="text" id="txtNombre" name="txtNombre" value="<?= $txtNombre; ?>"
                                       placeholder="Ingrese el nombre completo" required>
                            </td>
                        </tr>
                        <tr>
                            <td><br>Documento</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <br><input type="text" id="txtDocumento" name="txtDocumento" onkeyup="ValidaUsuario();"
                                           onkeypress="javascript:return validarNro(event);"
                                           value="<?= $txtDocumento; ?>" placeholder="Ingrese Documento"
                                           required>
                            </td>
                        </tr>
                        <tr>
                            <td><br>Correo</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <br> <input type="email" id="txtEmail" onkeyup="ValidaUsuario();" name="txtEmail"
                                            value="<?= $txtEmail; ?>" placeholder="Ingrese el correo" required>
                            </td>
                        </tr>
                        <tr>
                            <td><br>Contraseña</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <br> <input type="password" id="txtPass" name="txtPass" value="<?= $txtPass; ?>"
                                            required>
                            </td>
                        </tr>
                    </table>
                    <br>


                    <div id="div_Permisos">
                        <h3>Permisos</h3>
                        <?= $checks; ?>
                    </div>

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
