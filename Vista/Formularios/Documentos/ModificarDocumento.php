<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Parametros.php';
    include '../../../Clases/cls_Contabilidad.php';
    session_start();
    if (isset($_SESSION['login']) != '') {

        $Master = new Master();
        $menu = $Master->Menu();
        $Parametros = new cls_Parametros();
        $Contabilidad = new cls_Contabilidad();

        $txtTipo = '';
        $txtNombre = '';
        $txtNombreImpreso = '';
        $txtConsecutivo = '';
        $txtComentarios = '';

        $cmbCta = '<tr><td>Cuenta Contable</td><td style="padding-left: 10px;text-align: center;">
             <br> <select id="cmbCta" class="chosen-select" name="cmbCta"><option value ="0">-- Seleccione una cuenta--</option>';

        //Cuenta para gastos (impuesto al consumo)
        //Consumo
        $cmbConsumo = '';
        //CxP
        $cmbCxP = '';

        $TipoInterno = '';

        if ($_GET['id'] == "")
            echo '<script language = javascript> self.location = "Documentos.php";</script>';

        foreach ($Parametros->TraeDatosDocumento($_GET['id']) as $llave => $valor) {
            $TipoInterno = $valor['TIPO_INTERNO'];
            $txtTipo = $valor['TIPO'];
            $txtNombre = $valor['NOMBRE_DOCUMENTO'];
            $txtNombreImpreso = $valor['NOMBRE_IMPRESO'];
            $txtConsecutivo = $valor['CONSECUTIVO'];
            $txtComentarios = $valor['LEYENDA'];


            $Cta = $valor['ID_CUENTA'];
            foreach ($Contabilidad->TraeCuentas($_SESSION['login'][0]["ID_EMPRESA"]) as $llave1 => $valor1) {
                if ($valor1["ID_CUENTA"] == $Cta)
                    $cmbCta .= '<option value ="' . $valor1['ID_CUENTA'] . '" selected>' . $valor1['CODIGO'] . ' - ' . $valor1['NOMBRE'] . '</option>';
                else
                    $cmbCta .= '<option value ="' . $valor1['ID_CUENTA'] . '">' . $valor1['CODIGO'] . ' - ' . $valor1['NOMBRE'] . '</option>';
            }
        }
        $cmbCta .= '</select><br> </td></tr>';

        if ($TipoInterno == 'GASTOS') {
            $cmbConsumo = '<tr><td>Impuesto Consumo</td><td style="padding-left: 10px;text-align: center;">
             <br> <select  class="chosen-select" name="cmbConsumo"><option value ="0">-- Seleccione una cuenta--</option>';

            foreach ($Parametros->TraeDocumentoConsumo($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
                $Cta = $valor['ID_CUENTA'];

                foreach ($Contabilidad->TraeCuentas($_SESSION['login'][0]["ID_EMPRESA"]) as $llave1 => $valor1) {
                    if ($valor1["ID_CUENTA"] == $Cta)
                        $cmbConsumo .= '<option value ="' . $valor1['ID_CUENTA'] . '" selected>' . $valor1['CODIGO'] . ' - ' . $valor1['NOMBRE'] . '</option>';
                    else
                        $cmbConsumo .= '<option value ="' . $valor1['ID_CUENTA'] . '">' . $valor1['CODIGO'] . ' - ' . $valor1['NOMBRE'] . '</option>';
                }
            }
            $cmbConsumo .= '</select><br><br> </td></tr>';
            //CUENTAS POR PAGAR CxP
            $cmbCxP = '<tr><td>CxP</td><td style="padding-left: 10px;text-align: center;">
              <select  class="chosen-select" name="cmbCxP"><option value ="0">-- Seleccione una cuenta--</option>';

            foreach ($Parametros->TraeDocumentoCxP($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
                $Cta = $valor['ID_CUENTA'];

                foreach ($Contabilidad->TraeCuentas($_SESSION['login'][0]["ID_EMPRESA"]) as $llave1 => $valor1) {
                    if ($valor1["ID_CUENTA"] == $Cta)
                        $cmbCxP .= '<option value ="' . $valor1['ID_CUENTA'] . '" selected>' . $valor1['CODIGO'] . ' - ' . $valor1['NOMBRE'] . '</option>';
                    else
                        $cmbCxP .= '<option value ="' . $valor1['ID_CUENTA'] . '">' . $valor1['CODIGO'] . ' - ' . $valor1['NOMBRE'] . '</option>';
                }
            }
            $cmbCxP .= '</select><br> </td></tr>';

        }

        if (!empty($_POST)) {

            $Parametros->ActualizaDocumento($_GET['id'], $_POST['txtTipo'], $_POST['txtNombre'], $_POST['txtNombreImpreso'], $_POST['txtConsecutivo'],
                $_POST['cmbCta'], $_POST['txtComentarios'], $_SESSION['login'][0]["ID_USUARIO"]);

            if ($TipoInterno == 'GASTOS')
                $Parametros->ActualizaDocGastos($_POST['cmbConsumo'], $_POST['cmbCxP'], $_SESSION['login'][0]["ID_EMPRESA"]);


            echo '<script>
              alert("Se modificó el documento correctamente.");
              self.location = "Documentos.php";
              </script>';
        }

    } else {
        echo '<script>
        self.location = "../Otros/Login.php";
	</script>';
    }
?>
<html>
<head>
    <title>Modificar Documento</title>

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
        width: 290px;
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
                    <h3><b>MODIFICAR DOCUMENTO</b></h3><br>
                    <table style="width: 55%;color: #33373d;">
                        <tr>
                            <td>Tipo</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <input type="text" id="txtTipo" name="txtTipo" value="<?= $txtTipo; ?>"
                                       placeholder="Ingrese el tipo" required>
                            </td>
                        </tr>
                        <tr>
                            <td><br>Nombre</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <br><input type="text" id="txtNombre" name="txtNombre" value="<?= $txtNombre; ?>"
                                           placeholder="Ingrese el nombre" required>
                            </td>
                        </tr>
                        <tr>
                            <td><br>Nombre Impreso</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <br><input type="text" id="txtNombreImpreso" name="txtNombreImpreso"
                                           value="<?= $txtNombreImpreso; ?>"
                                           placeholder="Ingrese el nombre impreso" required>
                            </td>
                        </tr>
                        <tr>
                            <td><br>Consecutivo</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <br><input type="text" id="txtConsecutivo" name="txtConsecutivo"
                                           value="<?= $txtConsecutivo; ?>" placeholder="Ingrese el consecutivo"
                                           required>
                            </td>
                        </tr>

                        <?= $cmbCta; ?>

                        <?= $cmbConsumo; ?>

                        <?= $cmbCxP; ?>

                        <tr>
                            <td><br>Leyenda</td>
                            <td style="padding-left: 10px;text-align: center;">
                                <br><textarea id="txtComentarios" name="txtComentarios" cols="6" style="width: 300px;"
                                              placeholder="Ingrese la leyenda" title="Texto" maxlength="500" rows="8"
                                              style="width: 380px; height: 70px; font-size: 11px;"><?= $txtComentarios; ?></textarea>
                            </td>
                        </tr>

                    </table>
                    <br>
                    <br><input type="submit" class="btnAzul" id="btnGuardar" name="btnGuardar" value="GUARDAR"
                               style="width:200px;"/>

                </center>

            </form>
        </div>
    </div>

</div>

</body>
</html>
