<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Documentos.php';

    session_start();

    /*
     * Se enumeran todas las posibles acciones
     * de la sección de reportes
     */

    if (isset($_POST['generar'])) {
        try {
            $venta = 0;
            $compra = 0;
            $pago = 0;
            $iva = 0;
            $saldo = 0;
            $tventa = 0;
            $tcompra = 0;
            $tpago = 0;
            $tiva = 0;
            $tsaldo = 0;
            $Documentos = new cls_Documentos();

            $fila = '';
            for ($i = 0; $i < date("t", mktime(0, 0, 0, $_POST['mes']/*mes*/, 1, $_POST['ano'] /*año*/)); $i ++) {

                $venta = $Documentos->TraeProductosServicios($_POST['ano'], $_POST['mes'], $i + 1, $_SESSION['login'][0]["ID_EMPRESA"]);
                $compra = $Documentos->TraeCompraBienes($_POST['ano'], $_POST['mes'], $i + 1, $_SESSION['login'][0]["ID_EMPRESA"]);
                $pago = $Documentos->TraePagoServicios($_POST['ano'], $_POST['mes'], $i + 1, $_SESSION['login'][0]["ID_EMPRESA"]);
                $iva = $Documentos->TraeIVA($_POST['ano'], $_POST['mes'], $i + 1, $_SESSION['login'][0]["ID_EMPRESA"]);
                $saldo = $venta - ($compra + $pago + $iva);

                $fila .= '<tr><td style="text-align: center;">' . ($i + 1) . '</td>
             <td>' . number_format($venta, 0, '', '.') . '</td>
             <td>' . number_format($compra, 0, '', '.') . '</td>
             <td>' . number_format($pago, 0, '', '.') . '</td>
             <td>' . number_format($iva, 0, '', '.') . '</td>
             <td>' . number_format($saldo, 0, '', '.') . '</td>
             </tr>';

                $tventa += $venta;
                $tcompra += $compra;
                $tpago += $pago;
                $tiva += $iva;
                $tsaldo += $saldo;
            }
            $fila .= '<tr><td>TOTALES:</td>
                <td>' . $tventa . '</td>
                <td>' . $tcompra . '</td>
                <td>' . $tpago . '</td>
                <td>' . $tiva . '</td>
                <td>' . $tsaldo . '</td></tr>';

            echo $fila;
        } catch (Exception $ex) {
        }
        exit;
    }