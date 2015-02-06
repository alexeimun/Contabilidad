<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Parametros.php';
    include '../../../Clases/cls_Documentos.php';
    include '../../../Clases/cls_Factura.php';
    session_start();

    if (isset($_SESSION['login']) == '' || (new cls_Usuarios())->TienePermiso(__FILE__,$_SESSION['login'][0]['ID_USUARIO']))
        echo '<script> self.location = "../Otros/Login.php"</script>';

    $Master = new Master();
    $menu = $Master->Menu();
    $Parametros = new cls_Parametros();
    $Documentos = new cls_Documentos();
    $Factura = new cls_Factura();

    $Factura->TraeParametrosFactura($_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"]);

    $txtComentarios = '';
    $cmbTercero = '<option value ="0">-- Seleccione Un Tercero --</option>';
    $cmbProducto = '<option value ="0">-- Seleccione Un Prodcuto --</option>';
    $cmbfPago = '<option value ="0">-- Seleccione Forma de Pago --</option>';

    foreach ($Parametros->TraeFormasPago($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor)
        $cmbfPago .= '<option style="text-align:left;" value ="' . $valor['ID_F_PAGO'] . '" >' . $valor['NOMBRE_F_PAGO'] . '</option>';

    foreach ($Parametros->TraeTerceros($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor)
        $cmbTercero .= '<option style="text-align:left;" value ="' . $valor['ID_TERCERO'] . '">' . $valor['N_COMPLETO'] . '</option>';

    foreach ($Parametros->TraeProductos($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor)
        $cmbProducto .= '<option style="text-align:left;" value ="' . $valor['ID_PRODUCTO'] . '">' . $valor['DESCRIPCION'] . '</option>';


    if (!empty($_POST)) {
        if ($_POST['cmbTercero'] == '0' || $Factura->_CantidadProductos == 0) {
            if ($_POST['cmbTercero'] == '0')
                echo '<script >alert("Debe seleccionar un tercero.")	 </script>';
            else if ($Factura->_CantidadProductos == 0)
                echo '<script >alert("Debe agregar minimo un producto.")	 </script>';

            $cmbTercero = '<option value ="0">-- Seleccione Un Tercero --</option>';
            $Ter = $_POST['cmbTercero'];
            foreach ($Parametros->TraeTerceros($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
                if ($Ter == $valor['ID_TERCERO'])
                    $cmbTercero .= '<option style="text-align:left;" value ="' . $valor['ID_TERCERO'] . '" selected>' . $valor['N_COMPLETO'] . '</option>';
                else  $cmbTercero .= '<option style="text-align:left;" value ="' . $valor['ID_TERCERO'] . '">' . $valor['N_COMPLETO'] . '</option>';
            }
            $txtComentarios = $_POST['txtComentarios'];
        } else {
            $Consecutivo = $Factura->_Consecutivo;
            $Secuencia = 0;
            $Descuento = 0;
            $Total = 0;
            $Valor = 0;
//            $IdFormaPago = $_POST['cmbfPago'];

            foreach ($Documentos->TraeProductosFinal($_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {

                $Valor = $valor['PRECIO'] * $valor['CANTIDAD'];
                $Des = $valor['PRECIO'] * ($valor['DESCUENTO'] / 100);
                $Secuencia ++;
                $Documentos->InsertaMovimiento($_POST['cmbTercero'], $valor['ID_PRODUCTO'], $valor['CTA_COSTO'], 'F', $Consecutivo, 0, $Secuencia, $valor['DESCRIPCION'], 'C', $valor['CANTIDAD'], ($Valor / $valor['CANTIDAD']), $Des, $_POST['txtComentarios'], $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], 'P');

                $Secuencia ++;
                $Documentos->InsertaMovimiento($_POST['cmbTercero'], $valor['ID_PRODUCTO'], $valor['CTA_INVENTARIO'], 'F', $Consecutivo, 0, $Secuencia, $valor['DESCRIPCION'], 'C', $valor['CANTIDAD'], ($Valor / $valor['CANTIDAD']), $Des, $_POST['txtComentarios'], $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], 'P');

                $Secuencia ++;
                $Documentos->InsertaMovimiento($_POST['cmbTercero'], $valor['ID_PRODUCTO'], $valor['CTA_VENTAS'], 'F', $Consecutivo, 0, $Secuencia, $valor['DESCRIPCION'], 'D', $valor['CANTIDAD'], ($Valor / $valor['CANTIDAD']), $Des, $_POST['txtComentarios'], $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], 'P');

                $Total += $Valor;
                $Descuento += $Des;
            }

            $TotalPagos = 0;
            //FORMAS DE PAGO
            foreach ($Factura->TraePagoTemporal($_SESSION['login'][0]["ID_USUARIO"]) as $llave => $valor) {
                $Documentos->InsertaMovimiento($_POST['cmbTercero'], 0, 0, 'F', $Consecutivo, $valor['ID_F_PAGO'], ++ $Secuencia, "CXC FACT" . $Consecutivo, 'D',
                    1, $valor['VALOR'], 0, $_POST['txtComentarios'], $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], 'Pa',0,0, '', $valor['ID_ENTIDAD']
                    , $valor['NUMERO']);
                $TotalPagos += $valor['VALOR'];
            }

            //TOTAL PAGOS

            $Documentos->InsertaMovimiento($_POST['cmbTercero'], 0,0, 'F', $Consecutivo, 0, ++ $Secuencia, "TOTAL", '', 0, $Total, 0
                , $_POST['txtComentarios'], $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], '',0,0, $_POST['cmbTipoPago'], 0, '', 0, '', '', $TotalPagos);

            //Si es a crédito se genera Recibo
            if ($_POST['cmbTipoPago'] == 'CR') {
                $Factura->TraeParametrosRecibo($_SESSION['login'][0]["ID_EMPRESA"]);
                //Inserto el Total del recibo
                $Documentos->InsertaMovimiento($_POST['cmbTercero'], 0, 0, 'R', $Factura->_ConsecutivoRecibo, $_POST['cmbfPago'], ++ $Secuencia, 'TOTAL', '',
                    1, $Total, 0, '', $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], '',$Consecutivo, '', 0,0, '', 0, '', 'RECIBO', $_SESSION['TOTAL2']);
                $Documentos->ActualizaConsecutivo($Factura->_ConsecutivoRecibo + 1, $_SESSION['login'][0]["ID_EMPRESA"], 'RECIBO');
            }

            $_SESSION['Total'] = 'no';
            $_SESSION['ConsecutivoFACT'] = $Consecutivo;
            $Documentos->EliminaProductosFinal($_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"]);
            $Documentos->EliminaPagosFinal($_SESSION['login'][0]["ID_USUARIO"]);
            $Documentos->ActualizaConsecutivo(($Consecutivo + 1), $_SESSION['login'][0]["ID_EMPRESA"], 'FACTURA');

            echo '<script> alert("Se creó la factura correctamente.");window.open("ImpresionFactura.php");self.location = "Factura.php";</script>';
        }
    }
?>
<html>
<head>
    <title>Factura</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>
    <script src="../../Js/menu.js"></script>
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

    input[type='number'] {
        width: 50px;
    }

    #wrapper {
        height: 150%;
    }


</style>
<script>
    function agregar() {
        $("#productos").load("procesaProductosFactura.php?action=Agregar&idempresa=" + <?= $_SESSION['login'][0]["ID_EMPRESA"] ?>+"&idusuario=" + <?= $_SESSION['login'][0]["ID_USUARIO"] ?> +"&descuento=" + document.getElementById('txtDescuento').value + "&cantidad=" + document.getElementById('txtCantidad').value + "&idproducto=" + document.getElementById('cmbProducto').value);
        document.getElementById('txtDescuento').value = 0;
        $("#validaciones").load("procesaProductosFactura.php?action=validar&tipopago=" + document.getElementById('cmbTipoPago').value);
    }

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
                $("#validaciones").load("procesaProductosFactura.php?action=validar&tipopago=" + document.getElementById('cmbTipoPago').value);
            });
        }
    }

    function Precio() {
        $("#LblPrecio").load("procesaProductosFactura.php?action=precio&idprod=" + document.getElementById('cmbProducto').value);
    }

    function EliminarPago(id) {
        $("#pagos").load("procesaProductosFactura.php?action=eliminarpago&id=" + id, function () {
            $("#validaciones").load("procesaProductosFactura.php?action=validar&tipopago=" + document.getElementById('cmbTipoPago').value);
        });
    }

    function EliminarProducto(id) {
        $("#productos").load("procesaProductosFactura.php?action=eliminarproducto&id=" + id, function () {
            $("#validaciones").load("procesaProductosFactura.php?action=validar&tipopago=" + document.getElementById('cmbTipoPago').value);
        });
    }
    function CambiaTipoPago() {
        console.log('aqui');
        $("#validaciones").load("procesaProductosFactura.php?action=validar&tipopago=" + document.getElementById('cmbTipoPago').value);
    }

    $(document).ready(function () {
        $("#productos").load("procesaProductosFactura.php?action=listarproductos", function () {
            $("#pagos").load("procesaProductosFactura.php?action=listarpagos", function () {
                $("#validaciones").load("procesaProductosFactura.php?action=validar&tipopago=" + document.getElementById('cmbTipoPago').value);
            });
        });
    });

</script>
<body>
<div id="wrap">
    <div id="header">
        <a href=""><img src="<?= $_SESSION['login'][0]["LOGO_EMPRESA"] ?>"/></a>

        <h1 id="logo"><span class="gray"><?= $_SESSION['login'][0]["NOMBRE_EMPRESA"] ?></span></h1>

        <h3><span><?= $_SESSION['login'][0]["NOMBRE_USUARIO"] ?></span></h3>
        <img style="float: right;margin-top: 10px;" src="../../Imagenes/logo.png">
    </div>

    <div id="content-wrap">
        <?= $menu ?>

        <div id="main">
            <form method="POST">
                <center>
                    <h3><b>FACTURA NÚMERO <?= $Factura->_Consecutivo ?></b></h3><br>
                    <table style="width: 85%;color: #33373d;">
                        <tr>
                            <td style="text-align: right;">Tercero</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <select id="cmbTercero" name="cmbTercero" class="chosen-select" style="width:260px;"
                                        required>
                                    <?= $cmbTercero; ?>
                                </select>
                            </td>
                            <td style="text-align: right;">Forma de Pago</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <select id="cmbTipoPago" name="cmbTipoPago" onchange="CambiaTipoPago();"
                                        class="chosen-select" style="width:150px;">
                                    <option value="CO">CONTADO</option>
                                    <option value="CR">CREDITO</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <br>
                    <table style="width: 95%;color: #33373d;">
                        <tr>
                            <td style="text-align: right;"><br>Producto</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <br> <select id="cmbProducto" name="cmbProducto" class="chosen-select"
                                             onchange="Precio();">
                                    <?= $cmbProducto; ?>
                                </select> <label style="color:#5E83A3;font-weight: bold;" id="LblPrecio"></label>
                            </td>
                            <td style="text-align: right;"><br>Cantidad</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <br> <input type="number" id="txtCantidad" name="txtCantidad" value="1" max="9999" min="1" required/>
                            </td>
                            <td style="text-align: right;"><br>Descuento</td>

                            <td style="padding-left: 10px;text-align: left;">
                                <br> <input type="number" id="txtDescuento" name="txtDescuento" max="100" min="0" value="0" required/> %
                            </td>
                        </tr>

                    </table>

                    <br><br>
                    <input type="button" id="btnAgregar" class="btnAzul" onclick="agregar();" name="btnAgregar"
                           value="Agregar" style="width:100px;"/>
                    <br><br>

                    <div style="width: 95%;">

                        <ul id="productos"></ul>
                        <br>
                        <hr>
                        <table style="width: 95%;color: #33373d;">
                            <tr>

                                <td style="padding-left: 10px;text-align: center;">
                                    <br> <select style="width: 220px;" id="cmbfPago" name="cmbfPago"
                                                 class="chosen-select" style="width:350px;"
                                                 onchange="Change();">
                                        <?= $cmbfPago; ?>
                                    </select> <label style="color:#5E83A3;font-weight: bold;" id="LblPago"></label>
                                </td>
                            </tr>

                        </table>
                        <ul id="botones"></ul>

                        <br>
                        <input type="button" id="btnAgregarpago" class="btnAzul" onclick="agregarpago();"
                               name="btnAgregarpago" value="Agregar pago" style="width:120px;"/>
                        <br>
                        <tr><br>
                            <ul id="pagos"></ul>
                            <br></tr>
                        <hr>
                        <table>
                            <tr>
                                <td>Observaciones</td>
                                <td style="padding-left: 15px;text-align: right;">
                                    <textarea id="txtComentarios" name="txtComentarios" cols="6"
                                              placeholder="Ingrese los comentarios" title="Texto" maxlength="500"
                                              rows="8"
                                              style="width: 380px; height: 70px; font-size: 11px;"><?= $txtComentarios; ?></textarea>
                                    <br> Máximo 500 caracteres
                                </td>

                            </tr>

                            <tr>
                                <td colspan="2" style="text-align: center;"><br>

                                    <ul id="validaciones"></ul>
                                </td>
                            </tr>
                        </table>
                        <br>
                    </div>
                    <br><br>
                </center>
            </form>
        </div>
    </div>
</div>
<script>
    function Change() {
        $("#botones").load("procesaProductosFactura.php?action=consultafpago&id=" + document.getElementById('cmbfPago').value, function () {
            $("#validaciones").load("procesaProductosFactura.php?action=validar&tipopago=" + document.getElementById('cmbTipoPago').value);
        });
    }
</script>
</body>
</html>
