
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Contabilidad.php';
    include '../../../Clases/cls_Parametros.php';
    session_start();
    if (isset($_SESSION['login']) != '') {

        $Master = new Master();
        $menu = $Master->Menu();
        $Parametros = new cls_Parametros();

        $cmbCiudad = '<option value ="0">-- Seleccione Una Ciudad--</option>';

        foreach ($Parametros->TraeCiudades() as $llave => $valor) {
            $cmbCiudad .= '<option style="text-align:left;" value ="' . $valor['ID_CIUDAD'] . '">' . $valor['NOMBRE'] . ',&nbsp;&nbsp;' . $valor['DEPARTAMENTO'] . '</option>';
        }

        if (isset($_POST['btnGuardar']) != '') {

            $Parametros->InsertaTercero($_POST['txtNombre1'] . $_POST['txtRazonSocial'], $_POST['txtNombre2'], $_POST['txtApellido1'], $_POST['txtApellido2'],
                $_POST['rbTipoDoc'], str_replace(".", "", $_POST['txtNumDoc']), $_POST['txtDireccion'], $_POST['txtTelefono'],
                $_POST['txtCelular'], $_POST['txtEmail'], $_POST['cmbCiudad'], $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"]);

            echo '<script > alert("Se creó el tercero correctamente."); self.location = "Terceros.php" </script>';
        }

    } else   echo '<script >self.location = "/"</script>';

?>
<html>
<head>
    <title>Crear Tercero</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>
    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>
    <?php include '../../Css/css.php' ?>
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

    input[type='checkbox'] {
        width: 20px;
        height: 20px;
    }

    #wrapper {
        height: 140%;
    }
</style>

<script>

    function ValidaDocumento() {
        $("#botones").load("Validaciones.php?action=insertartercero&txtNumDoc=" + document.getElementById('txtNumDoc').value);
    }

    function TipoDoc(tipo) {
        document.getElementById('tblOtro').style.display = "none";
        document.getElementById('tblNit').style.display = "none";
        if (tipo == 2) {
            document.getElementById('txtNombre1').required = true;
            document.getElementById('txtNombre1').required = false;
            document.getElementById('txtNombre2').required = false;
            document.getElementById('txtApellido1').required = false;
            document.getElementById('txtApellido2').required = false;

            document.getElementById('txtNombre1').value = "";
            document.getElementById('txtNombre2').value = "";
            document.getElementById('txtApellido1').value = "";
            document.getElementById('txtApellido2').value = "";

            document.getElementById('tblNit').style.display = "";
        }
        else {
            document.getElementById('tblOtro').style.display = "";
            document.getElementById('txtNombre1').required = true;
            document.getElementById('txtNombre2').required = true;
            document.getElementById('txtApellido1').required = true;
            document.getElementById('txtApellido2').required = true;
            document.getElementById('txtNombre1').required = false;

            document.getElementById('txtNombre1').value = "";
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
                    <h3><b>CREAR TERCERO</b></h3><br>
                    <table style="width: 40%;color: #33373d">
                        <tr>
                            <td><br>Tipo Documento:<br><br></td>
                            <td style="padding-left: 10px;text-align: center;">
                                CC&nbsp;&nbsp;<input type="radio" onchange="TipoDoc(1);" checked id="rbTipoDoc"
                                                     name="rbTipoDoc" value="CC" required="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                CE&nbsp;&nbsp;<input type="radio" onchange="TipoDoc(1);" id="rbTipoDoc" name="rbTipoDoc"
                                                     value="CE" required="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                TI&nbsp;&nbsp;<input type="radio" onchange="TipoDoc(1);" id="rbTipoDoc" name="rbTipoDoc"
                                                     value="TI" required="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                NIT&nbsp;&nbsp;<input type="radio" onchange="TipoDoc(2);" id="rbTipoDoc"
                                                      name="rbTipoDoc"
                                                      value="NIT">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><br>
                            </td>
                        </tr>

                        <tr>
                            <td>Número Documento:</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <input type="text" id="txtNumDoc" name="txtNumDoc" onkeyup="ValidaDocumento();"
                                       onkeypress="javascript:return validarNro(event);" onkeyup="format(this);"
                                       value="" placeholder="Ingrese el número de documento" required>
                                <br><br></td>
                        </tr>
                    </table>
                    <table style="width: 40%;color: #33373d;display: none;" id="tblNit">

                        <tr>
                            <td>Razón Social:</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <input type="text" id="txtRazonSocial" name="txtRazonSocial" value=""
                                       placeholder="Ingrese la razón social">
                                <br><br></td>
                        </tr>
                    </table>
                    <table style="width: 40%;color: #33373d" id="tblOtro">

                        <tr>
                            <td>Primer Nombre:</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <input type="text" id="txtNombre1" name="txtNombre1" value=""
                                       placeholder="Ingrese el primer nombre" required>
                                <br><br></td>
                        </tr>
                        <tr>
                            <td>Segundo Nombre:</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <input type="text" id="txtNombre2" name="txtNombre2" value=""
                                       placeholder="Ingrese el segundo nombre" required>
                                <br><br></td>
                        </tr>
                        <tr>
                            <td>Primer Apellido:</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <input type="text" id="txtApellido1" name="txtApellido1" value=""
                                       placeholder="Ingrese el primer apellido" required>
                                <br><br></td>
                        </tr>
                        <tr>
                            <td>Segundo Apellido:</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <input type="text" id="txtApellido2" name="txtApellido2" value=""
                                       placeholder="Ingrese el segundo apellido" required>
                                <br><br></td>
                        </tr>
                    </table>
                    <table style="width: 40%;color: #33373d">
                        <tr>
                            <td>Dirección:</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <input type="text" id="txtDireccion" name="txtDireccion" value=""
                                       placeholder="Ingrese la dirección" required>
                                <br><br></td>
                        </tr>
                        <tr>
                            <td>Telefono:</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <input type="text" id="txtTelefono" name="txtTelefono" value=""
                                       placeholder="Ingrese el telefono" required>
                                <br><br></td>
                        </tr>
                        <tr>
                            <td>Celular:</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <input type="text" id="txtCelular" name="txtCelular" value=""
                                       placeholder="Ingrese el celular" required>
                                <br><br></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <input type="email" id="txtEmail" name="txtEmail" value=""
                                       placeholder="Ingrese el email" required>
                                <br><br></td>
                        </tr>
                        <tr>
                            <td>Ciudad:</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <select class="chosen-select" id="cmbCiudad" name="cmbCiudad">
                                    <?= $cmbCiudad; ?>
                                </select><br><br>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <ul id="botones"><br><input type="submit" class="btnAzul" id="btnGuardar" name="btnGuardar"
                                                value="GUARDAR" style="width:200px;"/></ul>
                </center>

            </form>
        </div>
    </div>

</div>
</body>
</html>
