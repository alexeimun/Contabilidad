<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Usuarios.php';
    include '../../../Clases/Master.php';

    $Usuarios = new cls_Usuarios();

    $txtLogin = '';
    $txtPass = '';

    session_start();
    function LimpiarSql($value)
    {
        $value = trim(htmlentities($value)); // Evita introducción código HTML
        if (get_magic_quotes_gpc()) $value = stripslashes($value);
        $value = mysql_real_escape_string($value);
        return $value;
    }

    if (isset($_SESSION['login']) != '') {
        switch ($_SESSION['login'][0]["NIVEL"]) {
            case 0 :
                header("Location:../Otros/Inicio.php");
                break;
            case 1 :
                header("Location:../Otros/InicioAdminUsuario.php");
                break;
            case 2 :
                header("Location:../Otros/InicioAdminGlobal.php");
                break;
        }
    }

    if (isset($_POST['btnIngresar']) != '') {

        #Sí algún usuario malintencionado esta tratando de aplicar una INYECCIÓN SQL a nuestro sitio, se imprime un mensaje que consiga amedrentarlo
        if (preg_match("/([0-9]{0,20}[a-zA-Z]{0,20}|[a-zA-Z]{0,20}[0-9]{0,20})?'( ){0,20}?([o-zA-Z]{2})( ){0,20}?'([1-9]{0,20}[a-zA-Z]{1,20}|[a-zA-Z]{0,20}[0-9]{0,20})'( ){0,20}?=( ){0,20}?'([0-9]{0,20}[a-zA-Z]{0,20}|[a-zA-Z]{0,20}[0-9]{0,20})( ){0,20}?/",$_POST['txtLogin'] )
        || preg_match("/([0-9]{0,20}[a-zA-Z]{0,20}|[a-zA-Z]{0,20}[0-9]{0,20})?'( ){0,20}?([o-zA-Z]{2})( ){0,20}?'([1-9]{0,20}[a-zA-Z]{1,20}|[a-zA-Z]{0,20}[0-9]{0,20})'( ){0,20}?=( ){0,20}?'([0-9]{0,20}[a-zA-Z]{0,20}|[a-zA-Z]{0,20}[0-9]{0,20})( ){0,20}?/",$_POST['txtPass'] )) {
            echo '<script>alert("Me estás tratando de joder el sitio. Pero te hemos captura la ip y se ha enviado tu ubicación a la base de datos. Estar atento a próximas denuncias") </script>';
        } else if ($Usuarios->validarCredenciales(LimpiarSql($_POST['txtLogin']), LimpiarSql($_POST['txtPass']))) {

            switch ($_SESSION['login'][0]["NIVEL"]) {
                case 0 :
                    header("Location:../Otros/Inicio.php");
                    break;
                case 1 :
                    header("Location:../Otros/InicioAdminUsuario.php");
                    break;
                case 2 :
                    header("Location:../Otros/InicioAdminGlobal.php");
                    break;
            }


        } else {
            echo '<script>alert("Credenciales Incorrectas." ) </script>';

            $txtLogin = $_POST['txtLogin'];
            $txtPass = $_POST['txtPass'];
        }
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

<script></script>

<body>
<div id="wrap">
    <div id="header">
        <!--    <h1 id="logo">azul<span class="gray">media</span></h1>
        <h2 id="slogan">Put your site slogan here...</h2>-->
        <img style="float: none;margin-top: 10px;" src="../../Imagenes/logo.png">
    </div>
    <div id="content-wrap">
        <form id="form" method="POST">
            <center>
                <br>
                <br>
                <br>
                <table style="width: 25%;color: #33373d">
                    <tr>
                        <td>Correo</td>
                        <td style="padding-left: 10px;text-align: right;">
                            <input type="text" id="txtLogin" name="txtLogin" value="<?= $txtLogin; ?>"
                                   placeholder="Ingrese el correo" required>
                        </td>
                    </tr>
                    <tr>
                        <td>Contaseña</td>
                        <td style="padding-left: 10px;text-align: right;">
                            <input type="password" id="txtPass" name="txtPass" value="<?= $txtPass; ?>"
                                   placeholder="Ingrese la contraseña" required>
                        </td>
                    </tr>
                </table>
                <br>

                <input type="submit" class="btnAzul" id="btnIngresar" name="btnIngresar" value="INGRESAR"
                       style="width:200px;"/>

            </center>
        </form>

    </div>
</div>
</body>
</html>
