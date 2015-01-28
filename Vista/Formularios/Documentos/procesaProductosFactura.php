<?php
    session_start();

    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Factura.php';
    include '../../../Clases/cls_Parametros.php';
    include '../../../Clases/Componentes.php';

    $Parametros = new cls_Parametros();
    $Factura = new cls_Factura();
    $Componentes = new Componentes();
    $tabla = '';
    $tabla2 = '';

    if ($_GET['action'] == 'validar') {
        if (($_SESSION['TOTAL1'] > $_SESSION['TOTAL2']) && $_GET['tipopago'] == 'CO')
            echo '<span class="Error">LA CANTIDAD A PAGAR ES MENOR QUE EL TOTAL DE LOS PRODUCTOS</span><br><br><br>
           <input type="submit" class="btnAzul"  id="btnFinalizar" name="btnFinalizar" value="FINALIZAR" style="width:200px; background-color: #A9A9A9;cursor: auto;" disabled/> ';
        else if ($_SESSION['TOTAL1'] < $_SESSION['TOTAL2'])
            echo '<span class="Error">LA CANTIDAD A PAGAR ES MAYOR QUE EL TOTAL DE LOS PRODUCTOS</span><br><br><br>
       <input type="submit" class="btnAzul"  id="btnFinalizar" name="btnFinalizar" value="FINALIZAR" style="width:200px; background-color: #A9A9A9;cursor: auto" disabled/> ';
        else
            echo '<input type="submit" class="btnAzul"  id="btnFinalizar" name="btnFinalizar" value="FINALIZAR" style="width:200px;" /> ';
    } else if ($_GET['action'] == 'listarproductos') {

        $tabla .= '<table class="table" style="width:90%;">
            <th style="text-align:left;">Código</th>
            <th style="text-align:left;">Producto</th>
            <th style="text-align:right;">Precio Unidad</th>   
            <th style="text-align:right;">Descuento</th>
            <th style="text-align:left;">Cantidad</th>
            <th style="text-align:right;">Total</th>
            <th style="text-align:right;">Acción</th>';

        $Totalproducto = 0;
        foreach ($Factura->TraeProductos($_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
            $tabla .= '<tr><td style="text-align:left;">' . $valor['CODIGO'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['DESCRIPCION'] . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format($valor['PRECIO'], 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format((($valor['PRECIO'] * ($valor['DESCUENTO'] / 100)) * $valor['CANTIDAD']), 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['CANTIDAD'] . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format((($valor['PRECIO'] * $valor['CANTIDAD']) - (($valor['PRECIO'] * ($valor['DESCUENTO'] / 100)) * $valor['CANTIDAD'])), 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:right;">
          <a onclick="EliminarProducto(' . $valor['ID'] . ');return false;"><img src="../../Imagenes/delete.png" title="Eliminar"></a>
                </td></tr>';
            $Totalproducto += (($valor['PRECIO'] * $valor['CANTIDAD']) - (($valor['PRECIO'] * ($valor['DESCUENTO'] / 100)) * $valor['CANTIDAD']));
        }
        $_SESSION['TOTAL1'] = $Totalproducto;
        $tabla .= '<tr><td></td><td></td><td></td><td></td><td style="text-align:right;"><b>Total:</b></td><td style="text-align:right;"><b>$ ' . number_format($Totalproducto, 0, '', '.') . '</b></td><td></td></tr>';
        $tabla .= '</table>';
        $Total1 = $Totalproducto;
        echo $tabla;

    } else if ($_GET['action'] == 'precio') {
        $Factura->TraeInfoProducto($_GET['idprod']);
        echo number_format($Factura->_PrecioProducto, 0, '', '.');

    } else if ($_GET['action'] == 'listarpagos') {
        $Componentes->TablaPagos();
    } else if ($_GET['action'] == 'eliminarproducto') {

        $Factura->EliminarProducto($_GET['id']);

        $tabla = '<table class="table" style="width:80%;">
            <th style="text-align:left;">Código</th>
            <th style="text-align:left;">Producto</th>
            <th style="text-align:right;">Precio Unidad</th>   
             <th style="text-align:right;">Descuento</th>
            <th style="text-align:left;">Cantidad</th>
            <th style="text-align:right;">Total</th>
            <th style="text-align:right;">Acción</th>';

        $Totalproducto = 0;
        foreach ($Factura->TraeProductos($_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
            $tabla .= '<tr><td style="text-align:left;">' . $valor['CODIGO'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['DESCRIPCION'] . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format($valor['PRECIO'], 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format((($valor['PRECIO'] * ($valor['DESCUENTO'] / 100)) * $valor['CANTIDAD']), 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['CANTIDAD'] . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format((($valor['PRECIO'] * $valor['CANTIDAD']) - (($valor['PRECIO'] * ($valor['DESCUENTO'] / 100)) * $valor['CANTIDAD'])), 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:right;">
          <a onclick="EliminarProducto(' . $valor['ID'] . ');return false;"><img src="../../Imagenes/delete.png" title="Eliminar"></a>
                </td></tr>';
            $Totalproducto += (($valor['PRECIO'] * $valor['CANTIDAD']) - (($valor['PRECIO'] * ($valor['DESCUENTO'] / 100)) * $valor['CANTIDAD']));
        }
        $_SESSION['TOTAL1'] = $Totalproducto;
        $tabla .= '<tr><td></td><td></td><td></td><td></td><td style="text-align:right;"><b>Total:</b></td><td style="text-align:right;"><b>$ ' . number_format($Totalproducto, 0, '', '.') . '</b></td><td></td></tr>';
        $tabla .= '</table>';

        echo $tabla;

    } else if ($_GET['action'] == 'eliminarpago') {

        $Componentes->EliminarPagoTemporal($_GET['id']);
        $Componentes->TablaPagos();
    } else if ($_GET['action'] == 'agregarpago') {
        $msg = $Componentes->ValidaAgregaPago($_GET['idpago'], $_GET['valor'], $_GET['entidad'], $_GET['numero'], $_SESSION['login'][0]["ID_USUARIO"]);
        $msg .= $Componentes->TablaPagos();
        echo $msg;
    } else if ($_GET['action'] == 'consultafpago') {
        if ($_GET['id'] != 0)
            $Componentes->FormasPagos();

    } else {
        //AGREGAR PRODUCTO
        $Factura->ValidaProducto($_GET['idproducto'], $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"]);

        if ($_GET['idproducto'] == 0)
            $tabla = '<span class="Error">POR FAVOR SELECCIONE UN PRODUCTO.</span><br><br><br>';

        else if ($Factura->_ExisteProducto == 1)
            $tabla .= '<span class="Error">ESTE PRODUCTO YA FUE AGREGADO.</span><br><br><br>';
        else
            $Factura->InsertaProducto($_GET['idproducto'], $_GET['cantidad'], $_GET['descuento'], $_GET['idusuario'], $_GET['idempresa']);

        $tabla .= '<table class="table" style="width:80%;">
            <th style="text-align:left;">Código</th>
            <th style="text-align:left;">Producto</th>
            <th style="text-align:right;">Precio Unidad</th>   
             <th style="text-align:right;">Descuento</th>
            <th style="text-align:left;">Cantidad</th>
            <th style="text-align:right;">Total</th>
            <th style="text-align:center;">right</th>';

        $Totalproducto = 0;
        foreach ($Factura->TraeProductos($_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
            $tabla .= '<tr><td style="text-align:left;">' . $valor['CODIGO'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['DESCRIPCION'] . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format($valor['PRECIO'], 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format((($valor['PRECIO'] * ($valor['DESCUENTO'] / 100)) * $valor['CANTIDAD']), 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['CANTIDAD'] . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format((($valor['PRECIO'] * $valor['CANTIDAD']) - (($valor['PRECIO'] * ($valor['DESCUENTO'] / 100)) * $valor['CANTIDAD'])), 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:right;">
          <a onclick="EliminarProducto(' . $valor['ID'] . ');return false;"><img src="../../Imagenes/delete.png" title="Eliminar"></a>
                </td></tr>';
            $Totalproducto += (($valor['PRECIO'] * $valor['CANTIDAD']) - (($valor['PRECIO'] * ($valor['DESCUENTO'] / 100)) * $valor['CANTIDAD']));
        }
        $_SESSION['TOTAL1'] = $Totalproducto;
        $tabla .= '<tr><td></td><td></td><td></td><td></td><td style="text-align:right;"><b>Total:</b></td><td style="text-align:right;"><b>$ ' . number_format($Totalproducto, 0, '', '.') . '</b></td><td></td></tr>';
        $tabla .= '</table>';

        echo $tabla;
    }
?>
