<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Usuarios.php';
    include '../../../Clases/cls_Vendedores.php';
    session_start();
    if (isset($_SESSION['login']) != '') {


        $Master = new Master();
        $menu = $Master->Menu();
        $Usuarios = new cls_Usuarios();
        $Vendedor = new cls_Vendedores();
        $identi = rand(10000000, 99999999);


        if (isset($_POST['btnGuardar']) != '') {

            $Vendedor->InsertaVendedor($_POST['txtNombre'], $_POST['txtDoc'], $_POST['txtTelefono'], $_POST['txtEmail'], $_POST['txtCantUsuarios'], $_SESSION['login'][0]["ID_ADMIN"]);


            echo '<script >
                    alert("Se creó el vendedor correctamente.");
                    self.location = "Vendedores.php";
                    </script>';
        }

    } else {
        echo '<script > self.location = "../Otros/Login.php";</script>';
    }
?>
<html>
<head>
    <title>Crear Vendedor</title>

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

    input[type='number'] {
        width: 70px;
    }
</style>

<script>
    function Valida() {
        $("#botones").load("../Parametros/Validaciones.php?action=insertarvendedor&txtdoc=" + document.getElementById('txtDoc').value
        + "&txtemail=" + document.getElementById('txtEmail').value);
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
                    <h3><b>CREAR VENDEDOR</b></h3><br>
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
                                <br><input type="text" id="txtDoc" name="txtDoc" onkeyup="Valida();"
                                           onkeypress="javascript:return validarNro(event);" value=""
                                           placeholder="Ingrese Documento" required>
                            </td>
                        </tr>
                        <tr>
                            <td><br>Telefono</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <br><input type="text" id="txtTelefono" name="txtTelefono" value=""
                                           placeholder="Ingrese Telefono" required>
                            </td>
                        </tr>
                        <tr>
                            <td><br>E-Mail</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <br><input type="email" id="txtEmail" name="txtEmail" onkeyup="Valida();" value=""
                                           placeholder="Ingrese el correo" required>
                            </td>
                        </tr>

                        <tr>
                            <td><br>Cantidad de Empresas</td>
                            <td style="padding-left: 33px;text-align: left;">
                                <br><input type="number" min="10" max="1000" id="txtCantUsuarios" name="txtCantUsuarios"
                                           value="10" required>
                            </td>
                        </tr>

                    </table>
                    <br>

                    <div id="div_Permisos">
                        <h3></h3>
                    </div>
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
