<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Documentos.php';
    include '../../../Clases/cls_Parametros.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Factura.php';

    session_start();

    if (isset($_SESSION['login']) == '' || (new cls_Usuarios())->TienePermiso(__FILE__, $_SESSION['login'][0]['ID_USUARIO']))
        echo '<script> self.location = "/"</script>';
    $Parametros = new cls_Parametros();
    $Documentos = new cls_Documentos();
    $Factura = new cls_Factura();
    $Master = new Master();
    $menu = $Master->Menu();

    $Factura->TraeParametrosRecibo($_SESSION['login'][0]["ID_EMPRESA"]);
    $ConsecutivoRecibo = $Factura->_ConsecutivoRecibo;

    $tabla = '<table id="table" class="table" style="width:90%;">
           <thead><tr> <th style="text-align:left;">CONSECUTIVO FACTURA</th>
            <th style="text-align:left;">TERCERO</th>
            <th style="text-align:left;">FECHA</th>
            <th style="text-align:right;">ABONADO</th>
            <th style="text-align:right;">VALOR</th>
            <th style="text-align:center;">ACCIÓN</th></tr></thead><tbody>';

    foreach ($Documentos->TraeRecibos($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
        if ($valor['ANULADO'] != 1) {
            $tabla .= '<tr ><td style="text-align:left;">' . $valor['CONSECUTIVO_FACTURA'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['NOMBRE1'] . ' ' . $valor['NOMBRE2'] . ' ' . $valor['APELLIDO1'] . ' ' . $valor['APELLIDO2'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['FECHA_REGISTRO'] . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format($valor['ABONADO'], 0, '', ',') . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format($valor['VALOR'], 0, '', ',') . '</td>';
            $tabla .= '<td style="text-align:center;">';
            $tabla .= "<a onclick='GeneraPago(" . $valor['CONSECUTIVO_FACTURA'] . "," . $valor['VALOR'] . "," . $valor['ABONADO'] . "," . $valor['ID_TERCERO'] . ");return false'>
                    <img style='width:30px;height:32px;' src='../../Imagenes/pay.png' title='Pagar'></a>";
            $tabla .= "&nbsp;&nbsp;<a onclick='Antecedentes(" . $valor['CONSECUTIVO_FACTURA'] . ")'><img style='width:30px;height:32px;' src='../../Imagenes/reg.png' title='Antecedentes'></a></tr>";

        }
    }

    $tabla .= '</tbody></table>';

    if (isset($_POST['btnFinalizar']) != '') {

        //FORMAS DE PAGO
        $Secuencia = 0;
        foreach ($Factura->TraePagoTemporal($_SESSION['login'][0]["ID_USUARIO"]) as $llave => $valor) {
            $Secuencia ++;
            $Documentos->InsertaMovimiento($_POST['txtTercero'], 0, 0, 'R', $_POST['ConsecutivoFactura'], $valor['ID_F_PAGO'], $Secuencia, "ABONO FACT" . $ConsecutivoRecibo, 'D',
                1, $valor['VALOR'], 0, '', $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], 'Pa', 0, 0, '', $valor['ID_ENTIDAD'], $valor['NUMERO']);
        }

        $Documentos->InsertaMovimiento($_POST['txtTercero'], 0, 0, 'R', $ConsecutivoRecibo, $_POST['cmbfPago'], 0, '', '', 1, $_SESSION['valor'],
            0, '', $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], 'R', 0, $_POST['ConsecutivoFactura'], '', 0, '', 0, $_SESSION['TOTAL2']);

        $_SESSION['ConsecutivoRECIBO'] = $ConsecutivoRecibo;
        $_SESSION['Pagos'] = $_POST['ConsecutivoFactura'];
        $_SESSION['Total'] = 'no';

        $Documentos->ActualizaConsecutivo($ConsecutivoRecibo + 1, $_SESSION['login'][0]["ID_EMPRESA"], 'RECIBO');
        $Documentos->ActualizaReciboAbono($_POST['ConsecutivoFactura'], $_SESSION['login'][0]["ID_EMPRESA"], $_SESSION['TOTAL2'] + $_SESSION['Abonado']);
        $Documentos->EliminaPagosFinal($_SESSION['login'][0]["ID_USUARIO"]);

        echo '<script>alert("Se creó el recibo correctamente.");  window.open("ImpresionRecibo.php");self.location="ReciboFactura.php";</script>';
    }

?>
<html>
<head>
    <title>Recibo de factura</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>

    <script type="text/javascript" language="javascript" src="../../Js/jquery.dataTables.js"></script>

    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>
</head>
<style type="text/css">
    #wrap {
        height: 300%;
    }
</style>

<script>

    $(document).ready(function () {
        $('#table').dataTable({
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        });
    });

    function EliminarCuenta(id) {
        if (confirm("Seguro que quieres eliminar esta cuenta ?")) {
            window.location.href = 'EliminarCuenta.php?id=' + id;
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
            <center>
                <h3><b>RECIBO DE FACTURA</b></h3><br>
                <?= $tabla ?>
                <br><br>

                <form method="post">

                    <div style="solid;width: 85%;">
                        <ul id="formas"></ul>
                    </div>
                </form>

            </center>
        </div>
    </div>

</div>
<script>

    function agregarpago() {
        if (document.getElementById('cmbfPago').value != "0") {
            var cmbentidad = 0;
            var txtnumero = "";
            var txtvalor = "";

            if (document.getElementById('cmbEntidad'))
                cmbentidad = document.getElementById('cmbEntidad').value;

            if (document.getElementById('txtNumero'))
                txtnumero = document.getElementById('txtNumero').value;

            if (document.getElementById('txtValor'))
                txtvalor = document.getElementById('txtValor').value;

            $("#pagos").load("procesaProductosFactura.php?action=agregarpago&idpago=" + document.getElementById('cmbfPago').value +
                "&valor=" + txtvalor + "&entidad=" + cmbentidad + "&numero=" + txtnumero, function () {
                    $("#validaciones").load("ProcesaRecibo.php?action=validarecibo");
                }
            );
        }
    }
    function Change() {
        $("#botones").load("procesaProductosFactura.php?action=consultafpago&id=" + document.getElementById('cmbfPago').value);
    }
    function GeneraPago(consecutivo, valor, abonado, tercero) {
        $("#formas").load("ProcesaRecibo.php?action=ReciboPago&id=" + consecutivo + "&valor=" + valor + "&Tercero=" + tercero + "&abonado=" + abonado,
            function () {
                $("#pagos").load("procesaProductosFactura.php?action=listarpagos", function () {
                    $("#validaciones").load("ProcesaRecibo.php?action=validarecibo");
                });
            });
    }


    function EliminarPago(id) {
        $("#pagos").load("procesaProductosFactura.php?action=eliminarpago&id=" + id, function () {
            $("#validaciones").load("ProcesaRecibo.php?action=validarecibo");
        });

    }
    function Antecedentes(id) {
        $("#formas").load("ProcesaRecibo.php?action=Antecedentes&id=" + id, function () {
            $('#tabla').dataTable({
                "language": {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sSearch": "Buscar:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
        });
    }

</script>
</body>
</html>