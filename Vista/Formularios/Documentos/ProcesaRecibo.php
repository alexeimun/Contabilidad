<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Factura.php';
    include '../../../Clases/cls_Parametros.php';
    include '../../../Clases/cls_Documentos.php';
    include '../../Css/css.php';

    session_start();
    $Parametros = new cls_Parametros();
    $Factura = new cls_Factura();
    $Documentos = new cls_Documentos();

    $tabla = '';
    $tabla2 = '';

    $cmbfPago = '<option value ="0">-- Seleccione Forma de Pago --</option>';
    foreach ($Parametros->TraeFormasPago($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor)
        $cmbfPago .= '<option style="text-align:left;" value ="' . $valor['ID_F_PAGO'] . '" >' . $valor['NOMBRE_F_PAGO'] . '</option>';

    if ($_GET['action'] == 'validarecibo') {

        if ($_SESSION['valor'] == $_SESSION['Abonado'])
            echo '<span class="Error">LA FACTURA SE HA PAGADO</span><br><br><br>
       <input type="submit" class="btnAzul"  id="btnFinalizar" name="btnFinalizar" value="FINALIZAR" style="width:200px; background-color: #A9A9A9;cursor: auto;" disabled/> ';

        else if ($_SESSION['valor'] < $_SESSION['TOTAL2'] + $_SESSION['Abonado'])
            echo '<span class="Error">EL VALOR  ES MAYOR QUE EL TOTAL A PAGAR</span><br><br><br>
       <input type="submit" class="btnAzul"  id="btnFinalizar" name="btnFinalizar" value="FINALIZAR" style="width:200px; background-color: #A9A9A9;cursor: auto;" disabled/> ';
        else
            echo '<input type="submit" class="btnAzul"  id="btnFinalizar" name="btnFinalizar" value="FINALIZAR" style="width:200px;" /> ';

    } else if ($_GET['action'] == 'ReciboPago') {

        $_SESSION['valor'] = $_GET['valor'];
        $_SESSION['Abonado'] = $_GET['abonado'];
        echo '<table style="width: 95%;color: #33373d;" ><tr>
		<td style="font-weight: bold;"> Factura No. ' . $_GET['id'] . '</td>
		<td style="font-weight: bold;"> Abonado: ' . number_format($_GET['abonado'], 0, '', ',') . ' $</td>
		<td style="font-weight: bold;"> Total a pagar: ' . number_format($_GET['valor'], 0, '', ',') . ' $</td></tr>

         <input type="hidden" value="' . $_GET['id'] . ' " name="ConsecutivoFactura">
                  <tr> <td  colspan="3" style="text-align: center;">
                 <br>  <select style="width: 220px;"  id="cmbfPago" class="chosen-select" name="cmbfPago" onchange="Change();" >
                      ' . $cmbfPago . '
                 </select> <label style="color:#5E83A3;font-weight: bold;" >
             </td>
              <td colspan="2">  Fecha <input type="date" name="Fecha"  value="'. date("Y").'-'.date("m").'-'.date("d").'" required></td>
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

        foreach ($Documentos->TraeAntecedenteRecibos($_GET['id'], $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
            $tabla .= '<tr ><td style="text-align:left;">' . $valor['CONSECUTIVO_RECIBO'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['NOMBRE1'] . ' ' . $valor['NOMBRE2'] . ' ' . $valor['APELLIDO1'] . ' ' . $valor['APELLIDO2'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['FECHA_REGISTRO'] . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format($valor['ABONADO'], 0, '', ',') . '</td>';
            $tabla .= '<td style="text-align:center;">';

            $tabla .= '<a href="Imprime.php?id=0&consecutivo=' . $valor['CONSECUTIVO_RECIBO'] . '&
                pagos=' . $valor['CONSECUTIVO_FACTURA'] . ' "><img src="../../Imagenes/print.png" title="Imprimir"></a>';

            $tabla .= '</tr>';
        }
        // $tabla.='<tr></td><td></td><td></td><td><td style="text-align:right;">Total:</td><td></td></tr>';

        $tabla .= ' </tbody></table>';

        echo $tabla;
    }