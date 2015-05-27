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

    foreach ($Parametros->TraeConceptos($_SESSION['login'][0]["ID_EMPRESA"], 0) as $llave => $valor)
        $cmbConcepto .= '<option style="text-align:left;" value ="' . $valor['ID_CONCEPTO'] . '">' . $valor['CODIGO'] . " - " . $valor['CONCEPTO'] . " - " . $valor['NOMBRE_CUENTA'] . '</option>';

    $Master = new Master();
    $menu = $Master->Menu();

    $Documentos->_IdParam = $Egresos->_IdParam;

    if (!empty($_POST)) {
        if ($Egresos->TraeCantidadEgresosTemp($_SESSION['login'][0]["ID_USUARIO"]) == 0)
            echo '<script> alert("No se han agregados gastos a la tabla.") </script>';
        else {
            $Secuencia = 0;
            $Egresos->TraeCuentas($_SESSION['login'][0]["ID_EMPRESA"]);
            $_SESSION['ReciboEgresos'] = 'no';

            $Total = 0;

            $p = 0;
            foreach ($Egresos->TraeGastosTemp($_SESSION['login'][0]["ID_USUARIO"]) as $llave => $valor) {
                $Valor = $valor['VALOR_BASE'] + $valor['IVA'] + $valor['IMPU_CONSUMO'];
                $Total += $Valor;
                $p ++;
                //Ingreso del valor base
                $Documentos->InsertaMovimiento(0, 0, 0, 'G', $Egresos->_ConsecutivoGastos, 0, ++ $Secuencia, '', 'C',
                    1, $valor['VALOR_BASE'], 0, $valor['DETALLE'], $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], $_POST['Fecha'], $_POST['txtPor'], $valor['ID_CONCEPTO'], 0, $valor['FORMA_PAGO']);

                //Ingreso del IVA
                $Documentos->InsertaMovimiento(0, 0, $Egresos->_IdCuentaGastos, 'G', $Egresos->_ConsecutivoGastos, 0, ++ $Secuencia, '', 'C',
                    1, $valor['IVA'], 0, $valor['DETALLE'], $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], $_POST['Fecha'], 'I', 0, 0, $valor['FORMA_PAGO']);

                //Ingreso del Consumo
                $Documentos->InsertaMovimiento(0, 0, $Egresos->_IdCuentaConsumo, 'G', $Egresos->_ConsecutivoGastos, 0, ++ $Secuencia, '', 'C',
                    1, $valor['IMPU_CONSUMO'], 0, $valor['DETALLE'], $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], $_POST['Fecha'], 'Com', 0, 0, $valor['FORMA_PAGO']);

                $Documentos->InsertaMovimiento(0, 0, 0, 'G', $Egresos->_ConsecutivoGastos, 0, ++ $Secuencia, 'SUBTOTAL', '', 1, $Valor, 0, $valor['DETALLE'],
                    $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], $_POST['Fecha'], '', $valor['ID_CONCEPTO'], $Egresos->_ConsecutivoGastos, $valor['FORMA_PAGO']);
            }

            $Documentos->InsertaMovimiento(0, 0, 0, 'G', $Egresos->_ConsecutivoGastos, 0, ++ $Secuencia, 'TOTAL', '', 1, $Total, 0, '', $_SESSION['login'][0]["ID_USUARIO"],
                $_SESSION['login'][0]["ID_EMPRESA"], $_POST['Fecha'], '', 0, 0, 0, $_POST['cmbEntidad'], isset($_POST['txtNumero']) ? $_POST['txtNumero'] : '', 0, $_POST['txtValor']);

            $Documentos->ActualizaConsecutivo($Egresos->_ConsecutivoGastos + 1, $_SESSION['login'][0]["ID_EMPRESA"], 'GASTOS');
            $_SESSION['ConsecutivoGastos'] = $Egresos->_ConsecutivoGastos;
            $_SESSION['Total'] = 'no';
            $Egresos->EliminaGastosTemp($_SESSION['login'][0]["ID_USUARIO"]);

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
<style>
    td {
        color: black;
    }
</style>

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
                    <table style="width: 90%;">
                        <tr>
                            <td style="text-align: right;">Forma de Pago</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <select id="cmbTipoPago" class="chosen-select" name="cmbTipoPago">
                                    <option value="CO">CONTADO</option>
                                    <option value="CR">CREDITO</option>
                                </select>
                            </td>
                            <td style="text-align: right;">Concepto</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <select id="cmbConcepto" class="chosen-select" name="cmbConcepto" style="width: 210px;">
                                    <?= $cmbConcepto ?>
                                </select>
                            </td>
                            <td style="text-align: right;">Fecha</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <input type="date" name="Fecha" value="<?= date("Y") . '-' . date("m") . '-' . date("d") ?>" required>
                            </td>

                        </tr>
                    </table>
                    <br/><br/>
                    <table style="width: 70%;">
                        <tr>
                            <td style="text-align: right;">Por</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <select id="bienSer" class="chosen-select" name="txtPor">
                                    <option value="BN">BIENES</option>
                                    <option value="SV">SERVICIOS</option>
                                </select>
                            </td>
                            <td style="text-align: right;"> Detalle</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <input type="text" style="width:350px;" name="txtDetalle"/>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <table style="width: 90%;">
                        <tr>
                            <td style="text-align: right;">Valor base $</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <input type="text" name="txtValorBase" style="width: 150px;"
                                       onkeypress="return validarNro(event);" value="0">
                            </td>

                            <td style="text-align: right;">IVA $</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <input type="text" name="txtIVA" style="width: 150px;"
                                       onkeypress="return validarNro(event);" value="0">
                            </td>

                            <td style="text-align: right;">Impu Consumo $</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <input type="text" name="txtConsumo" style="width: 150px;"
                                       onkeypress="return validarNro(event);" value="0">
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <input type="button" id="agregar" value="Agregar Gasto" class="btnAzul"/>
                    <br> <br>

                    <div id="val"></div>
                    <div id="frame"></div>
                    <hr>
                    <br> <br>
                    <table style="width: 40%;">
                        <tr>
                            <td style="text-align: right;">Forma de pago</td>
                            <td>
                                <select class="chosen-select" id="formaPago">
                                    <?= $cmbfPago ?>
                                </select></td>
                        </tr>
                    </table>
                    <br/>
                    <ul id="botones"> </ul>
                    <table>
                        <tr>
                            <td colspan="2" style="text-align: center;"><br>
                                <ul id="validaciones"></ul>
                            </td>
                        </tr>
                        <tr></td><br>
                            <ul id="pagos"></ul>
                            <br></tr>
                    </table>

                    <input type="submit" id="finalizar" value="FINALIZAR" style="width: 200px;" class="btnAzul"/>
                    <br><br>

                    <div id="val1"></div>
                </form>
            </center>
        </div>
    </div>
</div>
<script>

    $('#formaPago').change(function () {
        console.log($(this).val());
        $("#botones").load("procesaProductosFactura.php?action=consultafpago&id=" +$(this).val());
    });

    $(document).on('ready', function () {
        $('#frame').load('ProcesaGastos.php?action=frame');
    });

    $('form').submit(function () {
        if ($('#cmbEntidad').val() == 0) {
            event.preventDefault();
            $('#val1').html('<span style="color:#ff0000;">DEBE SELECCIONAR UNA ENTIDAD</span><br> <br>');
        }
        else if ($('input[name=txtNumero]').val() == '') {
            event.preventDefault();
            $('#val1').html('<span style="color:#ff0000;">DEBE INGRESAR UN NUMERO DE CHEQUE</span><br> <br>');
            $('input[name=txtNumero]').css('border', '1px solid red');
        }
        else if ($('input[name=txtValor]').val() == '') {
            event.preventDefault();
            $('#val1').html('<span style="color:#ff0000;">DEBE INGRESAR UN VALOR</span><br> <br>');
            $('input[name=txtValor]').css('border', '1px solid red');
        }
        else {
            $('#val1').html('');
            $('input:text').css('border', '1px solid #CCCCCC');
        }
    });

    $('#agregar').click(function () {

        if ($('#cmbTercero').val() == 0) {
            $('#val').html('<span style="color:#ff0000;">DEBE SELECCIONAR UN TERCERO</span><br> <br>');
        }
        else if ($('#cmbConcepto').val() == 0) {
            $('#val').html('<span style="color:#ff0000;">DEBE SELECCIONAR UN CONCEPTO</span><br> <br>');
        }
        else if ($('input[name=txtValorBase]').val() == '') {
            $('#val').html('<span style="color:#ff0000;">DEBE INGRESAR UN VALOR BASE</span><br> <br>');
            $('input[name=txtValorBase]').css('border', '1px solid red');
        }

        else if ($('input[name=txtIVA]').val() == '') {
            $('#val').html('<span style="color:#ff0000;">DEBE INGRESAR UN VALOR EN IVA</span><br> <br>');
            $('input[name=txtIVA]').css('border', '1px solid red');
        }

        else if ($('input[name=txtConsumo]').val() == '') {
            $('#val').html('<span style="color:#ff0000;">DEBE INGRESAR UN VALOR EN IMPU CONSUMO</span><br> <br>');
            $('input[name=txtConsumo]').css('border', '1px solid red');
        }

        else if ($('input[name=txtDetalle]').val() == '') {
            $('#val').html('<span style="color:#ff0000;">DEBE INGRESAR EL DETALLE</span><br> <br>');
            $('input[name=txtDetalle]').css('border', '1px solid red');
        }
        else {
            $.ajax({
                url: 'ProcesaGastos.php', type: 'post', data: $('form').serialize(),
                success: function (data) {
                    $('#val').html('');
                    $('input:text').css('border', '1px solid #CCCCCC');
                    $('input:text').val('');
                    $('#frame').html(data);
                },
                error: function () {alert('Ha ocurrido un error en el sistema');}
            });
        }
    });

    function agregarpago() {
        if (document.getElementById('cmbfPago').value != "0") {
            var txtnumero = "";
            var cmbentidad = 0;
            var txtvalor = "";

            if (document.getElementById('cmbEntidad'))
                cmbentidad = document.getElementById('cmbEntidad').value;

            if (document.getElementById('txtNumero'))
                txtnumero = document.getElementById('txtNumero').value;

            if (document.getElementById('txtValor'))
                txtvalor = document.getElementById('txtValor').value;

            $("#pagos").load("procesaProductosFactura.php?action=agregarpago&idpago=" + document.getElementById('cmbfPago').value + "&valor="
            + txtvalor + "&entidad=" + cmbentidad + "&numero=" + txtnumero, function () {
            });
        }
    }
    function ValidarValor() {
        return validarNro(event);
    }

    function EliminarGasto(id) {
        $("#frame").load("ProcesaGastos.php?action=eliminarpago&id=" + id, function () {
        });
    }

</script>
</body>
</html>