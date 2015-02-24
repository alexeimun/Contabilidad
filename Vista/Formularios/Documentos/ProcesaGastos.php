<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Egresos.php';
    include '../../../Clases/cls_Factura.php';
    include '../../../Clases/cls_Parametros.php';
    include '../../../Clases/cls_Documentos.php';

    session_start();
    $Parametros = new cls_Parametros();
    $Factura = new cls_Factura();
    $Documentos = new cls_Documentos();
    $Egresos = new cls_Egresos();

    $tabla = '';

    $cmbfPago = '<option value ="0">-- Seleccione Forma de Pago --</option>';
    foreach ($Parametros->TraeFormasPago($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor)
        $cmbfPago .= '<option style="text-align:left;" value ="' . $valor['ID_F_PAGO'] . '" >' . $valor['NOMBRE_F_PAGO'] . '</option>';

    if (isset($_GET['action']) && $_GET['action'] == 'frame') {
        $tabla = '<table class="table" style="width:95%;">
            <th style="text-align:left;">Forma Pago</th>
            <th style="text-align:left;">Concepto</th>
            <th style="text-align:left;">Por</th>
            <th style="text-align:left;">Detalle</th>
            <th style="text-align:left;">Valor Base</th>
            <th style="text-align:left;">IVA</th>
            <th style="text-align:left;">Imp Consumo</th>
            <th style="text-align:left;">Valor</th>
            <th style="text-align:left;">Acción</th>';
        $Total = 0;

        foreach ($Egresos->TraeGastosTemp($_SESSION['login'][0]["ID_USUARIO"]) as $llave => $valor) {
            $Valor = $valor['VALOR_BASE'] + $valor['IVA'] + $valor['IMPU_CONSUMO'];
            $tabla .= '<tr>';
            $tabla .= '<td style="text-align:left;">' . $valor['FORMA_PAGO'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['CONCEPTO'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['POR'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['DETALLE'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . number_format($valor['VALOR_BASE'], 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:left;">' . number_format($valor['IVA'], 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:left;">' . number_format($valor['IMPU_CONSUMO'], 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:left;">' . number_format($Valor, 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:left;">
          <a onclick="EliminarGasto(' . $valor['ID_GASTO_TEMP'] . ');return false;"><img src="../../Imagenes/delete.png" title="Eliminar"></a></td></tr>';
            $Total += $Valor;
        }
        $tabla .= '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td style="text-align:right;"><b>Total:</b><td colspan="2" style="text-align: center;"><b>' . number_format($Total, 0, '', '.') . '</b></td></tr>';

        echo $tabla;

        exit;
    } else if (isset($_GET['action']) && $_GET['action'] == 'eliminarpago') {
        $Egresos->EliminarGasto($_GET['id']);
        $tabla = '<table class="table" style="width:95%;">
            <th style="text-align:left;">Forma Pago</th>
            <th style="text-align:left;">Concepto</th>
            <th style="text-align:left;">Por</th>
            <th style="text-align:left;">Detalle</th>
            <th style="text-align:left;">Valor Base</th>
            <th style="text-align:left;">IVA</th>
            <th style="text-align:left;">Imp Consumo</th>
             <th style="text-align:left;">Valor</th>

            <th style="text-align:left;">Acción</th>';
        $Total = 0;

        foreach ($Egresos->TraeGastosTemp($_SESSION['login'][0]["ID_USUARIO"]) as $llave => $valor) {
            $Valor = $valor['VALOR_BASE'] + $valor['IVA'] + $valor['IMPU_CONSUMO'];
            $tabla .= '<tr>';
            $tabla .= '<td style="text-align:left;">' . $valor['FORMA_PAGO'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['CONCEPTO'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['POR'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['DETALLE'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . number_format($valor['VALOR_BASE'], 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:left;">' . number_format($valor['IVA'], 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:left;">' . number_format($valor['IMPU_CONSUMO'], 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:left;">' . number_format($Valor, 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:left;">
          <a onclick="EliminarGasto(' . $valor['ID_GASTO_TEMP'] . ');return false;"><img src="../../Imagenes/delete.png" title="Eliminar"></a></td></tr>';
            $Total += $Valor;
        }
        $tabla .= '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td style="text-align:right;"><b>Total:</b><td colspan="2" style="text-align: center;"><b>' . number_format($Total, 0, '', '.') . '</b></td></tr>';

        echo $tabla;
    } else if (isset($_POST)) {
        try {
            $Egresos->InsertaGastoTemp($_POST['cmbConcepto'], $_SESSION['login'][0]["ID_USUARIO"], $_POST['cmbTipoPago'],
                $_POST['txtPor'], $_POST['txtDetalle'], $_POST['txtValorBase'], $_POST['txtIVA'], $_POST['txtConsumo']);

            $tabla = '<table class="table" style="width:95%;">
            <th style="text-align:left;">Forma Pago</th>
            <th style="text-align:left;">Concepto</th>
            <th style="text-align:left;">Por</th>
            <th style="text-align:left;">Detalle</th>
            <th style="text-align:left;">Valor Base</th>
            <th style="text-align:left;">IVA</th>
            <th style="text-align:left;">Imp Consumo</th>
            <th style="text-align:left;">Valor</th>
            <th style="text-align:left;">Acción</th>';

            $Total = 0;

            foreach ($Egresos->TraeGastosTemp($_SESSION['login'][0]["ID_USUARIO"]) as $llave => $valor) {
                $Valor = $valor['VALOR_BASE'] + $valor['IVA'] + $valor['IMPU_CONSUMO'];
                $tabla .= '<tr>';
                $tabla .= '<td style="text-align:left;">' . $valor['FORMA_PAGO'] . '</td>';
                $tabla .= '<td style="text-align:left;">' . $valor['CONCEPTO'] . '</td>';
                $tabla .= '<td style="text-align:left;">' . $valor['POR'] . '</td>';
                $tabla .= '<td style="text-align:left;">' . $valor['DETALLE'] . '</td>';
                $tabla .= '<td style="text-align:left;">' . number_format($valor['VALOR_BASE'], 0, '', '.') . '</td>';
                $tabla .= '<td style="text-align:left;">' . number_format($valor['IVA'], 0, '', '.') . '</td>';
                $tabla .= '<td style="text-align:left;">' . number_format($valor['IMPU_CONSUMO'], 0, '', '.') . '</td>';
                $tabla .= '<td style="text-align:left;">' . number_format($Valor, 0, '', '.') . '</td>';
                $tabla .= '<td style="text-align:left;">
          <a onclick="EliminarGasto(' . $valor['ID_GASTO_TEMP'] . ');return false;"><img src="../../Imagenes/delete.png" title="Eliminar"></a></td></tr>';
                $Total += $Valor;
            }
            $tabla .= '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td style="text-align:right;"><b>Total:</b><td colspan="2" style="text-align: center;"><b>' . number_format($Total, 0, '', '.') . '</b></td></tr>';

            echo $tabla;
        } catch (Exception $e) {
            exit;
        }
    }