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

    if ($_GET['action'] == 'validagasto') {

        if (($_GET['valor'] > $_SESSION['TOTAL2']) && $_GET['tipopago'] == 'CO')
            echo '<span class="Error">LA CANTIDAD A PAGAR ES MENOR QUE EL TOTAL </span><br><br><br>
           <input type="submit" class="btnAzul"  value="FINALIZAR" style="width:200px; background-color: #A9A9A9;cursor: auto;" disabled/> ';
        else if ($_GET['valor'] < $_SESSION['TOTAL2'])
            echo '<span class="Error">LA CANTIDAD A PAGAR ES MAYOR QUE EL TOTAL</span><br><br><br>
       <input type="submit" class="btnAzul"  value="FINALIZAR" style="width:200px; background-color: #A9A9A9;cursor: auto" disabled/> ';
        else
            echo '<input type="submit" class="btnAzul"  value="FINALIZAR" style="width:200px;" /> ';

    }
