<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Factura.php';
    session_start();

    $Factura = new cls_Factura();
    $tabla = '';
    if ($_GET['action'] == 'listar') {

        $tabla .= '<table class="table" style="width:90%;">
            <th style="text-align:left;">Código</th>
            <th style="text-align:left;">Producto</th>
            <th style="text-align:right;">Precio Unidad</th>   
            <th style="text-align:right;">Descuento</th>
            <th style="text-align:left;">Cantidad</th>
            <th style="text-align:right;">Total</th>
            <th style="text-align:right;">Acción</th>';

        $Total = 0;
        foreach ($Factura->TraeProductos($_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
            $tabla .= '<tr><td style="text-align:left;">' . $valor['CODIGO'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['DESCRIPCION'] . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format($valor['PRECIO'], 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format((($valor['PRECIO'] * ($valor['DESCUENTO'] / 100)) * $valor['CANTIDAD']), 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['CANTIDAD'] . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format((($valor['PRECIO'] * $valor['CANTIDAD']) - (($valor['PRECIO'] * ($valor['DESCUENTO'] / 100)) * $valor['CANTIDAD'])), 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:right;">
          <a onclick="Eliminar(' . $valor['ID'] . ');return false"><img src="../../Imagenes/delete.png" title="Eliminar"></img></a>
                </td></tr>';
            $Total += (($valor['PRECIO'] * $valor['CANTIDAD']) - (($valor['PRECIO'] * ($valor['DESCUENTO'] / 100)) * $valor['CANTIDAD']));
        }

        $tabla .= '<tr><td></td><td></td><td></td><td></td><td style="text-align:right;"><b>Total:</b></td><td style="text-align:right;"><b>$ ' . number_format($Total, 0, '', '.') . '</b></td><td></td></tr>';
        $tabla .= '</table>';

        echo $tabla;

    } else if ($_GET['action'] == 'eliminar') {

        $Factura->EliminarProducto($_GET['id']);


        $tabla .= '<table class="table" style="width:80%;">
            <th style="text-align:left;">Código</th>
            <th style="text-align:left;">Producto</th>
            <th style="text-align:right;">Precio Unidad</th>   
             <th style="text-align:right;">Descuento</th>
            <th style="text-align:left;">Cantidad</th>
            <th style="text-align:right;">Total</th>
            <th style="text-align:right;">Acción</th>';

        $Total = 0;
        foreach ($Factura->TraeProductos($_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
            $tabla .= '<tr><td style="text-align:left;">' . $valor['CODIGO'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['DESCRIPCION'] . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format($valor['PRECIO'], 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format((($valor['PRECIO'] * ($valor['DESCUENTO'] / 100)) * $valor['CANTIDAD']), 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['CANTIDAD'] . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format((($valor['PRECIO'] * $valor['CANTIDAD']) - (($valor['PRECIO'] * ($valor['DESCUENTO'] / 100)) * $valor['CANTIDAD'])), 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:right;">
          <a onclick="Eliminar(' . $valor['ID'] . ');return false"><img src="../../Imagenes/delete.png" title="Eliminar"></img></a>
                </td></tr>';
            $Total += (($valor['PRECIO'] * $valor['CANTIDAD']) - (($valor['PRECIO'] * ($valor['DESCUENTO'] / 100)) * $valor['CANTIDAD']));
        }

        $tabla .= '<tr><td></td><td></td><td></td><td></td><td style="text-align:right;"><b>Total:</b></td><td style="text-align:right;"><b>$ ' . number_format($Total, 0, '', '.') . '</b></td><td></td></tr>';
        $tabla .= '</table>';

        echo $tabla;

    } else {
        //AGREGAR
        $Factura->ValidaProducto($_GET['idproducto'], $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"]);

        if ($Factura->_ExisteProducto == 1) {
            $tabla .= '<span class="Error">ESTE PRODUCTO YA FUE AGREGADO.</span><br><br><br>';
        } else {
            $Factura->InsertaProducto($_GET['idproducto'], $_GET['cantidad'], $_GET['descuento'], $_GET['idusuario'], $_GET['idempresa']);
        }

        $tabla .= '<table class="table" style="width:80%;">
            <th style="text-align:left;">Código</th>
            <th style="text-align:left;">Producto</th>
            <th style="text-align:right;">Precio Unidad</th>   
             <th style="text-align:right;">Descuento</th>
            <th style="text-align:left;">Cantidad</th>
            <th style="text-align:right;">Total</th>
            <th style="text-align:center;">right</th>';

        $Total = 0;
        foreach ($Factura->TraeProductos($_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
            $tabla .= '<tr><td style="text-align:left;">' . $valor['CODIGO'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['DESCRIPCION'] . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format($valor['PRECIO'], 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format((($valor['PRECIO'] * ($valor['DESCUENTO'] / 100)) * $valor['CANTIDAD']), 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['CANTIDAD'] . '</td>';
            $tabla .= '<td style="text-align:right;">' . number_format((($valor['PRECIO'] * $valor['CANTIDAD']) - (($valor['PRECIO'] * ($valor['DESCUENTO'] / 100)) * $valor['CANTIDAD'])), 0, '', '.') . '</td>';
            $tabla .= '<td style="text-align:right;">
          <a onclick="Eliminar(' . $valor['ID'] . ');return false"><img src="../../Imagenes/delete.png" title="Eliminar"></img></a>
                </td></tr>';
            $Total += (($valor['PRECIO'] * $valor['CANTIDAD']) - (($valor['PRECIO'] * ($valor['DESCUENTO'] / 100)) * $valor['CANTIDAD']));
        }

        $tabla .= '<tr><td></td><td></td><td></td><td></td><td style="text-align:right;"><b>Total:</b></td><td style="text-align:right;"><b>$ ' . number_format($Total, 0, '', '.') . '</b></td><td></td></tr>';
        $tabla .= '</table>';

        echo $tabla;

    }


?>


