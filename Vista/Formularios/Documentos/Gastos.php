<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Parametros.php';
    include '../../../Clases/cls_Documentos.php';
    include '../../../Clases/cls_Egresos.php';
    include '../../../Clases/cls_Factura.php';

    session_start();

    if (isset($_SESSION['login']) == '' || (new cls_Usuarios())->TienePermiso(__FILE__, $_SESSION['login'][0]['ID_USUARIO']))
        echo '<script> self.location = "/"</script>';

    $Parametros = new cls_Parametros();
    $Documentos = new cls_Documentos();
    $Egresos = new cls_Egresos();
    $Factura = new cls_Factura();

    $cmbConcepto = '<option value ="0">-- Seleccione Un Concepto --</option>';
    $cmbTercero = '<option value ="0">-- Seleccione Un Tercero --</option>';
    $cmbfPago = '<option value ="0">-- Seleccione Forma de Pago --</option>';

    $Egresos->TraeConsecutivoGastos($_SESSION['login'][0]["ID_EMPRESA"]);

    foreach ($Parametros->TraeFormasPago($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor)
        $cmbfPago .= '<option style="text-align:left;" value ="' . $valor['ID_F_PAGO'] . '" >' . $valor['NOMBRE_F_PAGO'] . '</option>';


    foreach ($Parametros->TraeTerceros($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor)
        $cmbTercero .= '<option style="text-align:left;" value ="' . $valor['ID_TERCERO'] . '">' . $valor['N_COMPLETO'] . '</option>';

    foreach ($Parametros->TraeConceptos($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor)
        $cmbConcepto .= '<option style="text-align:left;" value ="' . $valor['ID_CONCEPTO'] . '">' . $valor['CODIGO'] . " - " . $valor['CONCEPTO'] . " - " . $valor['NOMBRE_CUENTA'] . '</option>';


    $Master = new Master();
    $menu = $Master->Menu();


    if (!empty($_POST)) {
        if ($_POST['cmbTercero'] == '0') echo '<script>alert("Debe elecccionar un tercero.")	 </script>';
        else if ($_POST['cmbConcepto'] == '0') echo '<script>alert("Debe seleccionar un concepto.")	 </script>';

        else {
            $Secuencia = 0;

            $Egresos->TraeCuentas($_SESSION['login'][0]["ID_EMPRESA"]);
            if (isset($_SESSION['ConsecutivoEgresos'])) unset($_SESSION['ConsecutivoEgresos']);
            $_SESSION['ReciboEgresos'] = 'no';


            //Ingreso del valor base
            $Documentos->InsertaMovimiento($_POST['cmbTercero'], 0, $_POST['cmbConcepto'], 'G', $Egresos->_ConsecutivoGastos, 0, ++ $Secuencia, '', 'C',
                1, $_POST['txtValorBase'], 0, '', $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], $_POST['bienSer'],0, 0, $_POST['cmbTipoPago']);

            //Ingreso del IVA
            $Documentos->InsertaMovimiento($_POST['cmbTercero'], 0, $Egresos->_IdCuentaGastos, 'G', $Egresos->_ConsecutivoGastos, 0, ++ $Secuencia, '', 'C',
                1, $_POST['txtIVA'], 0, '', $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], 'I',0,0, $_POST['cmbTipoPago']);

            //Ingreso del Consumo
            $Documentos->InsertaMovimiento($_POST['cmbTercero'], 0, $Egresos->_IdCuentaConsumo, 'G', $Egresos->_ConsecutivoGastos, 0, ++ $Secuencia, '', 'C',
                1, $_POST['txtConsumo'], 0, '', $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], 'Com', 0,0, $_POST['cmbTipoPago']);


            $TotalValores = $_POST['txtValorBase'] + $_POST['txtIVA'] + $_POST['txtConsumo'];
            //TOTAL DE GASTOS
            //Si es a crédito
            if ($_POST['cmbTipoPago'] == 'CR') {
                $Egresos->TraeConsecutivoEgresos($_SESSION['login'][0]["ID_EMPRESA"]);
                $_SESSION['ConsecutivoEgresos'] = $Egresos->_ConsecutivoEgresos;
                $_SESSION['Tipo'] = 'CR';
                $_SESSION['ReciboEgresos'] = 'ok';

                //FORMAS DE PAGO
                foreach ($Factura->TraePagoTemporal($_SESSION['login'][0]["ID_USUARIO"]) as $llave => $valor) {
                    $Documentos->InsertaMovimiento($_POST['cmbTercero'], 0, 0, 'G', $Egresos->_ConsecutivoGastos, $valor['ID_F_PAGO'], ++ $Secuencia, "ABONO GASTOS" . $Egresos->_ConsecutivoEgresos,
                        'D', 0, $valor['VALOR'], 0, '', $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], 'Pa', 0,0, '', $valor['ID_ENTIDAD'], $valor['NUMERO']);
                }

                $Documentos->InsertaMovimiento($_POST['cmbTercero'], 0, 0, 'E',  $Egresos->_ConsecutivoEgresos, '', ++ $Secuencia,
                    'TOTAL', '', 1, $TotalValores, 0, '', $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], '',$_POST['cmbConcepto'],$Egresos->_ConsecutivoGastos, $_POST['cmbTipoPago'], 0, '', 0, '', '', $_SESSION['TOTAL2']);

                $Documentos->ActualizaConsecutivo($Egresos->_ConsecutivoEgresos + 1, $_SESSION['login'][0]["ID_EMPRESA"], 'EGRESOS');
            } else //De Contado
            {
                $_SESSION['ReciboEgresos'] = 'no';
                //FORMAS DE PAGO
                foreach ($Factura->TraePagoTemporal($_SESSION['login'][0]["ID_USUARIO"]) as $llave => $valor) {
                    $Documentos->InsertaMovimiento($_POST['cmbTercero'], 0, 0, 'G', $Egresos->_ConsecutivoGastos, $valor['ID_F_PAGO'], ++ $Secuencia, "ABONO GASTOS" . $Egresos->_ConsecutivoGastos,
                        'D', 0, $valor['VALOR'], 0, '', $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], 'Pa', 0, 0,'', $valor['ID_ENTIDAD'], $valor['NUMERO']);
                }
                $_SESSION['Tipo'] = 'CO';
                $Documentos->InsertaMovimiento($_POST['cmbTercero'], 0, 0, 'E', $Egresos->_ConsecutivoGastos, $_POST['cmbfPago'], ++ $Secuencia,
                    'TOTAL', '', 1, $TotalValores, 0, '', $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], '',$_POST['cmbConcepto'], 0, $_POST['cmbTipoPago'], 0, '', 0, '', '', $_SESSION['TOTAL2']);
            }

            $Documentos->EliminaPagosFinal($_SESSION['login'][0]["ID_USUARIO"]);
            $Documentos->ActualizaConsecutivo($Egresos->_ConsecutivoGastos + 1, $_SESSION['login'][0]["ID_EMPRESA"], 'GASTOS');
            $_SESSION['ConsecutivoGastos'] = $Egresos->_ConsecutivoGastos;
            $_SESSION['Total'] = 'no';

            echo '<script >alert("Se ha creado el gasto con éxito..");window.open("ImpresionReciboEgresos.php");self.location = "Gastos.php"	; </script>';
        }
    }

?>
<html>
<head>
    <title>Gastos</title>
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
            <center>
                <h3><b>GASTOS NÚMERO <?= $Egresos->_ConsecutivoGastos ?></b></h3><br>

                <form method="POST">
                    <table style="width: 80%;">
                        <tr>
                            <td style="text-align: right;">Tercero</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <select id="cmbTercero" name="cmbTercero" class="chosen-select" required
                                        style="width: 250px;">
                                    <?= $cmbTercero; ?>
                                </select>
                            </td>
                            <td style="text-align: right;">Forma de Pago</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <select id="cmbTipoPago" class="chosen-select" name="cmbTipoPago" onchange="Validar();">
                                    <option value="CO">CONTADO</option>
                                    <option value="CR">CREDITO</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <br><br>
                    <hr>
                    <br>
                    <table style="width: 55%;">
                        <tr>
                            <td style="text-align: right;">Concepto</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <select id="cmbTercero" class="chosen-select" name="cmbConcepto" required
                                        style="width: 210px;">
                                    <?= $cmbConcepto ?>
                                </select>
                            </td>
                            <td style="text-align: right;">Por</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <select id="bienSer" class="chosen-select" name="bienSer" onchange="Validar();">
                                    <option value="BN">BIENES</option>
                                    <option value="SV">SERVICIOS</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <table style="width: 90%;">
                        <tr>
                            <td style="text-align: right;">Valor base $</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <input type="text" name="txtValorBase" style="width: 150px;"
                                       onchange="return ValidarValor();" value="0" required>
                            </td>

                            <td style="text-align: right;">IVA $</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <input type="text" name="txtIVA" style="width: 150px;"
                                       onchange="return ValidarValor();" value="0" required>
                            </td>

                            <td style="text-align: right;">Impu Consumo $</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <input type="text" name="txtConsumo" style="width: 150px;"
                                       onchange="return ValidarValor();" value="0" required>
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <table style="width: 40%;">
                        <tr>
                            <td style="padding-left: 10px;text-align: center;">
                                <br> <select style="width: 220px;" id="cmbfPago" class="chosen-select" name="cmbfPago"
                                             onchange="Change();">
                                    <?= $cmbfPago; ?>
                                </select> <label style="color:#5E83A3;font-weight: bold;" id="LblPago"></label>
                            </td>
                        </tr>
                    </table>
                    <ul id="botones"></ul>
                    <br><br>
                    <ul id="pagos"></ul>
                    <br>
                    <br>
                    <input type="button" id="btnAgregarpago" class="btnAzul" onclick="agregarpago();"
                           name="btnAgregarpago" value="Agregar pago" style="width:120px;"/>
                    <br>
                    <hr>
                    <br> <br>
                    <ul id="validaciones"></ul>

                </form>
            </center>
        </div>
    </div>
</div>
<script>
    function agregarpago() {
        if (document.getElementById('cmbfPago').value != "0") {
            var txtnumero = "";
            var cmbentidad = 0;
            var txtvalor = "";

            if (document.getElementById('cmbEntidad'))
                cmbentidad = document.getElementById('cmbEntidad').value;

            if (document.getElementById('txtNumero')) {
                txtnumero = document.getElementById('txtNumero').value;
            }

            if (document.getElementById('txtValor')) {
                txtvalor = document.getElementById('txtValor').value;
            }

            $("#pagos").load("procesaProductosFactura.php?action=agregarpago&idpago=" + document.getElementById('cmbfPago').value + "&valor="
            + txtvalor + "&entidad=" + cmbentidad + "&numero=" + txtnumero, function () {
                Validar();
            });
        }
    }
    function ValidarValor() {

        if (validarNro(event)) {
            Validar();
            return true;
        }
        else return false;
    }

    function Validar() {
        var Valor = document.getElementsByName('txtValorBase')[0].value;

        var Iva = document.getElementsByName('txtIVA')[0].value;
        var Consumo = document.getElementsByName('txtConsumo')[0].value;

        var total = parseInt(Valor ? Valor : 0) + parseInt(Iva ? Iva : 0) + parseInt(Consumo ? Consumo : 0);

        $("#validaciones").load("procesaGastos.php?action=validagasto&tipopago=" + document.getElementById('cmbTipoPago').value + "&valor=" + total);
    }
    function EliminarPago(id) {
        $("#pagos").load("procesaProductosFactura.php?action=eliminarpago&id=" + id, function () {
            Validar();
        });
    }
    function Change() {
        $("#botones").load("procesaProductosFactura.php?action=consultafpago&id=" + document.getElementById('cmbfPago').value);
    }
    function CambiaTipoPago() {
        Validar();
    }

    $(document).ready(function () {
        $("#pagos").load("procesaProductosFactura.php?action=listarpagos", function () {

            Validar();
        });
    });


</script>
</body>
</html>
