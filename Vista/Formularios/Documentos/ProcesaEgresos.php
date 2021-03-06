<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Egresos.php';
    include '../../../Clases/cls_Factura.php';
    include '../../../Clases/cls_Parametros.php';
    include '../../../Clases/cls_Documentos.php';
    include_once '../../Css/css.php';

    session_start();
    $Parametros = new cls_Parametros();
    $Factura = new cls_Factura();
    $Documentos = new cls_Documentos();
    $Egresos = new cls_Egresos();

    $tabla = '';

    $cmbfPago = '<option value ="0">-- Seleccione Forma de Pago --</option>';
    foreach ($Parametros->TraeFormasPago($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor)
        $cmbfPago .= '<option style="text-align:left;" value ="' . $valor['ID_F_PAGO'] . '" >' . $valor['NOMBRE_F_PAGO'] . '</option>';

    if ($_GET['action'] == 'validaegreso') {

        if ($_SESSION['valor'] == $_SESSION['Abonado'])
            echo '<span class="Error">EL GASTO SE HA PAGADO</span><br><br><br>
       <input type="submit" class="btnAzul"  id="btnFinalizar" name="btnFinalizar" value="FINALIZAR" style="width:200px; background-color: #A9A9A9;cursor: auto;" disabled/> ';

        else if ($_SESSION['valor'] < $_SESSION['TOTAL2'] + $_SESSION['Abonado'])
            echo '<span class="Error">EL VALOR  ES MAYOR QUE EL TOTAL A PAGAR</span><br><br><br>
       <input type="submit" class="btnAzul"  id="btnFinalizar" name="btnFinalizar" value="FINALIZAR" style="width:200px; background-color: #A9A9A9;cursor: auto;" disabled/> ';
        else
            echo '<input type="submit" class="btnAzul"  id="btnFinalizar" name="btnFinalizar" value="FINALIZAR" style="width:200px;" /> ';

    } else if ($_GET['action'] == 'EgresoPago') {

        $_SESSION['Abonado'] = $_GET['abonado'];
        $_SESSION['valor'] = $_GET['valor'];
        echo '<table style="width: 95%;color: #33373d;" ><tr>
		<td style="font-weight: bold;">Consecutivo Gasto No. ' . $_GET['id'] . '</td>
		<td style="font-weight: bold;"> Abonado: ' . number_format($_GET['abonado'], 0, '', ',') . ' $</td>
		<td style="font-weight: bold;"> Total a pagar: ' . number_format($_GET['valor'], 0, '', ',') . ' $</td></tr>
         <input type="hidden" value="' . $_GET['id'] . ' " name="ConsecutivoGastos">
         <input type="hidden" value="' . $_GET['concepto'] . ' " name="Concepto">

                  <tr> <td  colspan="3" style="text-align: center;">
                 <br>  <select style="width: 220px;"  id="cmbfPago" name="cmbfPago" class="chosen-select" onchange="Change();" >
                      ' . $cmbfPago . '
                 </select> <label style="color:#5E83A3;font-weight: bold;" ></label>
             </td>
            </tr>
      </table>
      <hr>
       <ul id="botones"> </ul>

        <br><br >
            <input type="button"  id="btnAgregarpago" class="btnAzul" onclick="agregarpago();" name="btnAgregarpago" value="Agregar pago"  style="width:120px;"/>
            <br>

    <table>
             <tr>
        <td colspan="2" style="text-align: center;"><br>
                      <ul id="validaciones"></ul>
                     </td>
         </tr>

            <tr></td><br > <ul id="pagos">  </ul><br></tr>
            <input type="hidden" name="txtTercero" value="' . $_GET['Tercero'] . '" />
     </table> <br>';
    } else if ($_GET['action'] == 'Antecedentes') {

        $tabla = '<span STYLE="font-weight: bold;" >ANTECEDENTES DE PAGOS</span>';
        $tabla .= '<table id="tabla" class="table" style="width:90%;">
             <thead><tr> <th style="text-align:left;">CONSECUTIVO</th>
            <th style="text-align:left;">TERCERO</th>
            <th style="text-align:left;">FECHA</th>
            <th style="text-align:right;">ABONADO</th>
             <th style="text-align:center;">ACCIÓN</th></tr></thead><tbody>';

        foreach ($Documentos->TraeAntecedenteEgresos($_GET['id'], $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
            $tabla .= '<tr ><td style="text-align:left;">' . $valor['CONSECUTIVO_EGRESOS'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['NOMBRE1'] . ' ' . $valor['NOMBRE2'] . ' ' . $valor['APELLIDO1'] . ' ' . $valor['APELLIDO2'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['FECHA_REGISTRO'] . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format($valor['ABONADO'], 0, '', ',') . '</td>';
            $tabla .= '<td style="text-align:center;">';

            $tabla .= '<a href="Imprime.php?id=1&consecutivoE=' . $valor['CONSECUTIVO_EGRESOS'] . '&
                consecutivoG=' . $valor['CONSECUTIVO_GASTOS'] . ' "><img src="../../Imagenes/print.png" title="Imprimir"></a>';

            $tabla .= '</tr>';
        }

        $tabla .= ' </tbody></table>';

        echo $tabla;
    }