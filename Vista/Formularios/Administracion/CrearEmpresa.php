<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Empresas.php';
    include '../../../Clases/cls_Documentos.php';
    session_start();
    if (isset($_SESSION['login']) != '') {


        $Master = new Master();
        $menu = $Master->Menu();
        $Usuarios = new cls_Usuarios();
        $Empresas = new cls_Empresas();
        $Documentos = new cls_Documentos();
        $identi = rand(10000000, 99999999);

        $Empresas->CantidadEmpresas($_SESSION['login'][0]["ID_VENDEDOR"]);
        if ($Empresas->_CantEmpresas >= $_SESSION['login'][0]["CANT_EMPRESAS"])
            echo '<script> alert("No se puede crear más empresas.");self.location = "Empresas.php" </script>';

        if (isset($_POST['btnGuardar']) != '') {

            $Empresas->InsertaEmpresa($_POST['txtNombre'], $_POST['txtNit'], $_POST['txtDireccion'], $_POST['txtTelefono'], $_POST['txtEmail'], $_POST['txtCantUsuarios'], $_SESSION['login'][0]["ID_VENDEDOR"]);

            $Usuarios->InsertaUsuarioDefault($_POST['txtNit'], date("ymdh") + date("i") + rand(0, date("s")), $identi);

            $Documentos->InsertaDocumento($identi, $_POST['txtNit']);

            foreach ($Usuarios->traeModulosPadres() as $llave => $valor) {
                $idMod = $valor['ID_MODULO'];

                foreach ($Usuarios->traeModulosHijos($idMod) as $llave => $valor)
                    $Usuarios->registraPermisos($identi, $valor['ID_MODULO'], 1, $_SESSION['login'][0]['ID_VENDEDOR']);

            }
            echo '<script >alert("Se creó la empresa correctamente."); self.location = "Empresas.php"; </script>';
        }
    } else echo '<script> self.location = "/";</script>';

?>
<html>
<head>
    <title>Crear Empresa</title>

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
        $("#botones").load("../Parametros/Validaciones.php?action=insertarempresa&txtnit=" + document.getElementById('txtNit').value
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

        <h1 id="logo"><span class="gray">Administración&nbsp&nbsp; <span
                    style="font-size: 28px;"><?= $_SESSION['login'][0]['NOMBRE_VENDEDOR']; ?></span></span></h1>


    </div>

    <div id="content-wrap">
        <?= $menu ?>

        <div id="main">
            <form method="POST">
                <center>
                    <h3><b>CREAR EMPRESA</b></h3><br>
                    <table style="width: 35%;color: #33373d">
                        <tr>
                            <td>Nombre</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <input type="text" id="txtNombre" name="txtNombre" value=""
                                       placeholder="Ingrese el nombre completo" required>
                            </td>
                        </tr>
                        <tr>
                            <td><br>Nit</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <br><input type="text" id="txtNit" name="txtNit" onkeyup="Valida();"
                                           onkeypress="javascript:return validarNro(event);" value=""
                                           placeholder="Ingrese Documento" required>
                            </td>
                        </tr>
                        <tr>
                            <td><br>Dirección</td>
                            <td style="padding-left: 10px;text-align: right;">
                                <br><input type="text" id="txtDireccion" name="txtDireccion" value=""
                                           placeholder="Ingrese la Dirección" required>
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
                            <td><br>Cantidad de Usuarios</td>
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
