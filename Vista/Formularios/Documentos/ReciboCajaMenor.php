<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Parametros.php';
    include '../../../Clases/cls_Documentos.php';
    include '../../../Clases/cls_CajaMenor.php';

    session_start();
    if (isset($_SESSION['login']) == '' || (new cls_Usuarios())->TienePermiso(__FILE__, $_SESSION['login'][0]['ID_USUARIO']))
        echo '<script > self.location = "/"</script>';
    $cmbTercero = '<option value ="0">-- Seleccione Un Tercero --</option>';

    $Master = new Master();
    $menu = $Master->Menu();
    $Parametros = new cls_Parametros();
    $Documentos = new cls_Documentos();
    $CajaMenor = new cls_CajaMenor();

    $cmbTercero = '<option value ="0">-- Seleccione Un Tercero --</option>';
    $cmbCiudad = '<option value ="0">-- Seleccione Una Ciudad --</option>';
    $cmbConcepto = '<option value ="0">-- Seleccione Un Concepto --</option>';

    foreach ($Parametros->TraeTerceros($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor)
        $cmbTercero .= '<option style="text-align:left;" value ="' . $valor['ID_TERCERO'] . '">' . $valor['N_COMPLETO'] . '</option>';

    foreach ($Parametros->TraeCiudades() as $llave => $valor)
        $cmbCiudad .= '<option style="text-align:left;" value ="' . $valor['ID_CIUDAD'] . '">' . $valor['NOMBRE'] . ',&nbsp;&nbsp;' . $valor['DEPARTAMENTO'] . '</option>';

    foreach ($Parametros->TraeConceptos($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor)
        $cmbConcepto .= '<option style="text-align:left;" value ="' . $valor['ID_CONCEPTO'] . '">' . $valor['CODIGO'] . " - " . $valor['CONCEPTO'] . " - " . $valor['NOMBRE_CUENTA'] . '</option>';

    $CajaMenor->TraeParametrosCajaMenor($_SESSION['login'][0]["ID_EMPRESA"]);

    if (isset($_POST['btnFinalizar']) != '') {

        if ($_POST['cmbCiudad'] == '0')
            echo '<script >alert("Debe seleccionar una ciudad.")	 </script>';
        else if ($_POST['cmbTercero'] == '0')
            echo '<script >alert("Debe elecccionar un tercero.")	 </script>';
        else if ($_POST['cmbConcepto'] == '0')
            echo '<script >alert("Debe seleccionar un concepto.")	 </script>';


        else {
            $Consecutivo = $CajaMenor->_Consecutivo;
            $Documentos->InsertaMovimiento($_POST['cmbTercero'], 0, 0, 'C', $Consecutivo, 0, 0, 'TOTAL', 'D',
                1, $_POST['txtValor'], 0, '', $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], 'Con', $_POST['cmbConcepto'], 0, '', 0, '',
                $_POST['cmbCiudad'], $_POST['Codigo'], 'RECIBO_CAJA_MENOR');

            $_SESSION['ConsecutivoCM'] = $Consecutivo;

            $Documentos->ActualizaConsecutivo($Consecutivo + 1, $_SESSION['login'][0]["ID_EMPRESA"], 'RECIBO_CAJA_MENOR');

            echo '<script >
          alert("Se creó el recibo correctamente.");
        window.open("ImpresionReciboCajaMenor.php");
        self.location = "ReciboCajaMenor.php"	;
        </script>';
        }
    }
?>
<html>
<head>
    <title>Recibo Caja Menor</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>
    <?php include '../../Css/css.php' ?>
    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>
</head>

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
                    <h3><b>RECIBO CAJA MENOR <?= $CajaMenor->_Consecutivo ?></b></h3><br>
                    <table style="width: 30%;color: #33373d;">
                        <tr>
                            <td style="text-align: right;">Ciudad</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <select id="cmbCiudad" name="cmbCiudad" class="chosen-select" style="width:250px;">
                                    <?= $cmbCiudad; ?>

                                </select>
                            </td>

                        </tr>
                    </table>
                    <hr>
                    <br>
                    <table style="width: 95%;color: #33373d;">
                        <tr>
                            <td style="text-align: right;"><br>Pagado a</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <br>
                                <select id="cmbTercero" class="chosen-select" name="cmbTercero" style="width:250px;">
                                    <?= $cmbTercero; ?>
                                </select>
                            </td>
                            <td style="text-align: right;"><br>Valor$</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <br> <input type="text" id="txtValor" name="txtValor" required/>
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <br>
                    <table style="width: 100%;color: #33373d;">
                        <tr>
                            <td style="text-align: right;"><br> Por concepto de</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <br>
                                <select name="cmbConcepto" class="chosen-select" id="cmbConcepto" style="width: 220px;">
                                    <?= $cmbConcepto; ?>
                                </select>
                            </td>
                            <td style="text-align: right;"><br> Código</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <br>
                                <input type="text" name="Codigo" required/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: center;"><br><br><br>

                                <ul id="validaciones">
                                    <input type="submit" class="btnAzul" id="btnFinalizar" name="btnFinalizar"
                                           value="FINALIZAR" style="width:200px;"/>
                                </ul>
                            </td>
                        </tr>
                    </table>
                    <br><br>
                </center>
            </form>
        </div>
    </div>
</div>
</body>
</html>
