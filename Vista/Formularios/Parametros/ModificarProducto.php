<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Contabilidad.php';
    include '../../../Clases/cls_Parametros.php';
    session_start();
    if (isset($_SESSION['login']) != '') {

        if ($_GET['id'] == "") {
            echo '<script >self.location = "ProductosServiciosGrupos.php?me=1"  </script>';
        }

        $Master = new Master();
        $menu = $Master->Menu();
        $Parametros = new cls_Parametros();

        $txtCodigo = '';
        $txtPrecio = '';
        $txtNombre = '';
        $cmbGrupo = '<option value ="0">-- Seleccione --</option>';
        $rbTipo = '';

        foreach ($Parametros->TraeDatosProducto($_GET['id']) as $llave => $valor) {
            $txtCodigo = $valor["CODIGO"];
            $txtPrecio = number_format($valor['PRECIO'], 0, '', '.');
            $txtNombre = $valor["DESCRIPCION"];
            $Grupo = $valor["ID_GRUPO"];

            foreach ($Parametros->TraeGrupos($_SESSION['login'][0]["ID_EMPRESA"]) as $llave1 => $valor1) {
                if ($Grupo == $valor1['ID_GRUPO']) {
                    $cmbGrupo .= '<option value ="' . $valor1['ID_GRUPO'] . '" selected>' . $valor1['NOMBRE'] . '</option>';
                } else {
                    $cmbGrupo .= '<option value ="' . $valor1['ID_GRUPO'] . '">' . $valor1['NOMBRE'] . '</option>';
                }
            }

            if ($valor["TIPO"] == 'S') {
                $rbTipo = 'Producto&nbsp;&nbsp;<input type="radio"  id="rbTipo" name="rbTipo" value="P" required="">
                           Servicio&nbsp;&nbsp;<input type="radio"  id="rbTipo" name="rbTipo" value="S" checked><br><br>';
            } else {
                $rbTipo = 'Producto&nbsp;&nbsp;<input type="radio"  id="rbTipo" name="rbTipo" value="P" required="" checked>
                          Servicio&nbsp;&nbsp;<input type="radio"  id="rbTipo" name="rbTipo" value="S"><br><br>';
            }
        }


        if (isset($_POST['btnGuardar']) != '') {


            $Parametros->ActualizaProducto($_GET['id'], $_POST['txtCodigo'], $_POST['txtNombre'], $_POST['rbTipo'], str_replace(".", "", $_POST['txtPrecio']), $_POST['cmbGrupo']);

            echo '<script >
                    alert("Se modificó el producto correctamente.")
                    self.location = "ProductosServiciosGrupos.php?me=1"
                    </script>';
        }


    } else {
        echo '<script >
        self.location = "/"
	</script>';
    }
?>
<html>
<head>
    <title>Modificar Producto</title>

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
</style>

<script>
    function ValidaCodigo() {
        $("#botones").load("Validaciones.php?action=editarproducto&txtCodigo=" + document.getElementById('txtCodigo').value + "&id=" +<?= $_GET['id'] ?>);
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
                    <h3><b>MODIFICAR PRODUCTO</b></h3><br>
                    <table style="width: 35%;color: #33373d">
                        <tr>
                        <tr>
                            <td>Código</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <input type="text" id="txtCodigo" onkeyup="ValidaCodigo()" name="txtCodigo"
                                       value="<?= $txtCodigo; ?>" placeholder="Ingrese el código" required>
                                <br><br></td>
                        </tr>
                        <tr>
                            <td>Nombre</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <input type="text" id="txtNombre" name="txtNombre" value="<?= $txtNombre; ?>"
                                       placeholder="Ingrese el nombre " required>
                                <br><br></td>
                        </tr>
                        <tr>
                            <td>Precio</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <input type="text" id="txtPrecio" name="txtPrecio"
                                       onkeypress="javascript:return validarNro(event)" onkeyup="format(this)"
                                       value="<?= $txtPrecio; ?>" placeholder="Ingrese el precio" required>
                                <br><br></td>
                        </tr>
                        <tr>
                            <td>Tipo</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <?= $rbTipo; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Grupo</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <select id="cmbGrupo" name="cmbGrupo" class="chosen-select">
                                    <?= $cmbGrupo; ?>
                                </select><br><br>
                            </td>
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
