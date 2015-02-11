<!DOCTYPE html>
<?php
    include 'Config/Conexion/config.php';
    include 'Generic/Database/DataBase.php';
    include 'Clases/Master.php';

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
                header("Location:Vista/Formularios/Otros/Inicio.php");
                break;
            case 1 :
                header("Location:Vista/Formularios/Otros/InicioAdminUsuario.php");
                break;
            case 2 :
                header("Location:Vista/Formularios/Otros/InicioAdminGlobal.php");
                break;
        }
    }

    if (!empty($_POST) != '') {

        #Sí algún usuario malintencionado esta tratando de aplicar una INYECCIÓN SQL a nuestro sitio, se imprime un mensaje que consiga amedrentarlo
        if (preg_match("/([0-9]{0,20}[a-zA-Z]{0,20}|[a-zA-Z]{0,20}[0-9]{0,20})?'( ){0,20}?([o-zA-Z]{2})( ){0,20}?'([1-9]{0,20}[a-zA-Z]{1,20}|[a-zA-Z]{0,20}[0-9]{0,20})'( ){0,20}?=( ){0,20}?'([0-9]{0,20}[a-zA-Z]{0,20}|[a-zA-Z]{0,20}[0-9]{0,20})( ){0,20}?/",$_POST['txtLogin'] )
            || preg_match("/([0-9]{0,20}[a-zA-Z]{0,20}|[a-zA-Z]{0,20}[0-9]{0,20})?'( ){0,20}?([o-zA-Z]{2})( ){0,20}?'([1-9]{0,20}[a-zA-Z]{1,20}|[a-zA-Z]{0,20}[0-9]{0,20})'( ){0,20}?=( ){0,20}?'([0-9]{0,20}[a-zA-Z]{0,20}|[a-zA-Z]{0,20}[0-9]{0,20})( ){0,20}?/",$_POST['txtPass'] )) {
            echo '<script>alert("Me estás tratando de joder el sitio. Pero te hemos captura la ip y se ha enviado tu ubicación a la base de datos. Estar atento a próximas denuncias") </script>';
        } else if ($Usuarios->validarCredenciales($_POST['txtLogin'], $_POST['txtPass'])) {

            switch ($_SESSION['login'][0]["NIVEL"]) {
                case 0 :
                    header("Location:Vista/Formularios/Otros/Inicio.php");
                    break;
                case 1 :
                    header("Location:Vista/Formularios/Otros/InicioAdminUsuario.php");
                    break;
                case 2 :
                    header("Location:Vista/Formularios/Otros/InicioAdminGlobal.php");
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
    <link rel="stylesheet" type="text/css" href="Vista/Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="Vista/Css/style.css"/>
    <script src="Vista/Js/menu.js"></script>
    <link rel="stylesheet" type="text/css" href="Vista/Css/stilos.css"/>
    <link href="Vista/Css/login.css" rel="stylesheet" type="text/css" />
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

<script type="text/javascript">
    $(document).ready(function() {
        $(".username").focus(function() {
            $(".user-icon").css("left","-48px");
        });
        $(".username").blur(function() {
            $(".user-icon").css("left","0px");
        });

        $(".password").focus(function() {
            $(".pass-icon").css("left","-48px");
        });
        $(".password").blur(function() {
            $(".pass-icon").css("left","0px");
        });
    });
</script>

<body>
<div id="wrap">
    <div id="header">
        <!--    <h1 id="logo">azul<span class="gray">media</span></h1>
        <h2 id="slogan">Put your site slogan here...</h2>-->
        <img style="float: none;margin-top: 10px;" src="Vista/Imagenes/logo.png">
    </div>
    <div id="wrapper">

        <!--SLIDE-IN ICONS-->
        <div class="user-icon"></div>
        <div class="pass-icon"></div>
        <!--END SLIDE-IN ICONS-->

        <!--LOGIN FORM-->
        <form name="login-form" class="login-form" method="post">

            <!--HEADER-->
            <div class="header">
                <!--TITLE--><h1>Inicio de Sesión</h1><!--END TITLE-->
                <!--DESCRIPTION--><span>Bienvenido a Contasistin!<br>Por favor digite sus credenciales de acceso</span><!--END DESCRIPTION-->
            </div>
            <!--END HEADER-->

            <!--CONTENT-->
            <div class="content">
                <!--USERNAME--><input name="txtLogin" type="text" placeholder="Ingrese su correo" class="input username"  /><!--END USERNAME-->
                <!--PASSWORD--><input name="txtPass" type="password" placeholder="Ingrese su clave" class="input password" /><!--END PASSWORD-->
            </div>
            <!--END CONTENT-->

            <!--FOOTER-->
            <div class="footer">
                <!--LOGIN BUTTON--><input type="submit" name="submit" value="Ingresar" class="button btnAzul" /><!--END LOGIN BUTTON-->
            </div>
            <!--END FOOTER-->

        </form>
        <!--END LOGIN FORM-->

    </div>
</div>
</body>
</html>
