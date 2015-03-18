<?php
    include_once 'cls_Usuarios.php';

    class Master
    {
        public function Menu()
        {
            if ($_SESSION['login'][0]["NIVEL"] == 0) {

                $Usuario = new cls_Usuarios();
                $_SESSION['permisos'] = $valor = $Usuario->traePermisos($_SESSION['login'][0]["ID_USUARIO"]);

                $menu = '<div id="wrapper">
            <ul class="menu">
                <li class="item1"><a href="#">Documentos</a>
                    <ul>';
                if ($valor[0][1] == '1')
                    $menu .= '  <li ><a href="../Documentos/Factura.php">Factura</a></li>';

                if ($valor[1][1] == 1)
                    $menu .= '<li ><a href="../Documentos/ReciboFactura.php">Recibo (Factura)</a></li>';
                if ($valor[2][1] == 1)
                    $menu .= '<li ><a href="../Documentos/ReciboCajaMenor.php">Recibo Caja Menor</a></li>';
                if ($valor[3][1] == 1)
                    $menu .= '<li ><a href="../Documentos/Gastos.php">Gastos</a></li>';
//                if ($valor[4][1] == 1)
//                    $menu .= '<li ><a href="../Documentos/Egresos.php">Egresos</a></li>';
                if ($valor[5][1] == 1)
                    $menu .= '<li ><a href="../Documentos/NotaContable.php">Nota Contable</a></li>';
                if ($valor[6][1] == 1)
                    $menu .= '<li ><a href="../Documentos/SaldosIniciales.php">Saldos Iniciales</a></li>';
                $menu .= ' <li ><a href="../Documentos/ReimpresionDocumentos.php?id=0">Reimpresión Documentos</a></li>
            </ul>
        </li>
        <li class="item2"><a href="#">Contabilidad</a>
            <ul>';
                if ($valor[9][1] == 1)
                    $menu .= '<li ><a href="../Contabilidad/PUC.php">PUC</a></li>';
                if ($valor[10][1] == 1)
                    $menu .= '<li ><a href="../Contabilidad/MovimientoContable.php">Movimiento Contable</a></li>';
                if ($valor[11][1] == 1)
                    $menu .= '<li ><a href="../Contabilidad/MovimientoTercero.php">Movimiento Por Tercero</a></li>';
                if ($valor[12][1] == 1)
                    $menu .= '<li ><a href="../Contabilidad/BalanceComprobacion.php">Balance Comprobación</a></li>';
                if ($valor[13][1] == 1)
                    $menu .= '<li ><a href="../Contabilidad/EstadoResultado.php">Estado de Resultado</a></li>';
                if ($valor[14][1] == 1)
                    $menu .= '<li ><a href="../Contabilidad/BalanceGeneral.php">Balance General</a></li>';
                $menu .= '
              <!--<li ><a href="#">Automatic Fails <span>2</span></a></li>--> 
            </ul>
        </li>
        <li class="item3"><a href="#">Tributario</a>
            <ul>';
                if ($valor[16][1] == 1)
                    $menu .= '<li><a href="../Tributario/DeclaracionImpuestoConsumo.php">Declaración Impuesto Consumo</a></li>';
                if ($valor[17][1] == 1)
                    $menu .= ' <li ><a href="../Tributario/DeclaracionRenta.php">Declaración Renta</a></li>';

                $menu .= '
            </ul>
        </li>
        <li class="item4"><a href="#">Inventario</a>
            <ul>';
                if ($valor[18][1] == 1)
                    $menu .= '<li ><a href="../Inventario/InventarioInicial.php">Inventario Inicial</a></li>';
                if ($valor[19][1] == 1)
                    $menu .= ' <li ><a href="../Inventario/Compras.php">Compras</a></li>';
                if ($valor[20][1] == 1)
                    $menu .= ' <li ><a href="../Inventario/InventarioFinal.php">Inventario Final</a></li>';
                if ($valor[21][1] == 1)
                    $menu .= '<li ><a href="../Inventario/DevolucionesCompras.php">Devoluciones Compras</a></li>';
                if ($valor[22][1] == 1)
                    $menu .= '<li ><a href="../Inventario/DescuentosCompras.php">Descuentos Compras</a></li>';
                if ($valor[23][1] == 1)
                    $menu .= '<li ><a href="../Inventario/CostoVenta.php">Costo Venta</a></li>';
                $menu .= '
             </ul>
        </li>
        <li class="item5"><a href="#">Reportes</a>
            <ul>';
                if ($valor[24][1] == 1)
                    $menu .= '<li ><a href="../Reportes/CuadreCaja.php">Cuadre de Caja</a></li>';
                if ($valor[25][1] == 1)
                    $menu .= '<li ><a href="../Reportes/CajaDiaria.php">Libro Diario</a></li>';
                if ($valor[26][1] == 1)
                    $menu .= '<li ><a href="../Reportes/CxC.php">CxC</a></li>';
                if ($valor[27][1] == 1)
                    $menu .= '<li ><a href="../Reportes/CxP.php">CxP</a></li>';
                if ($valor[28][1] == 1)
                    $menu .= ' <li ><a href="../Reportes/LibroFiscalOperacionesDiarias.php">Libro Fiscal Operaciones Diarias</a></li>';
                $menu .= '
            </ul>
        </li>
          
        <li class="item5"><a href="#">Parametros</a>  
            <ul>';
                $menu .= ' <li ><a href="../Parametros/Terceros.php">Terceros</a></li>';
                if ($valor[29][1] == 1)
                    $menu .= '<li ><a href="../Empresas/InformacionEmpresa.php">Información de Empresa</a></li>';
                if ($valor[30][1] == 1)
                    $menu .= '<li ><a href="../Usuarios/Usuarios.php">Usuarios</a></li>';
                if ($valor[31][1] == 1)
                    $menu .= '<li ><a href="../Documentos/Documento.php">Documentos</a></li>';
                if ($valor[32][1] == 1)
                    $menu .= '<li ><a href="../Parametros/ProductosServiciosGrupos.php?me=1">Poductos y Servicios</a></li>
                     <li ><a href="../Parametros/FormasDePago.php">Formas de Pago</a></li>';
                $menu .= '<li ><a href="../Parametros/Entidades.php">Entidades</a></li>';
                $menu .= '<li ><a href="../Parametros/ConceptosGI.php">Conceptos Gastos/Ingresos</a></li>';
                $menu .= '<li ><a href="../Parametros/ConceptosInventario.php">Conceptos Inventarios</a></li>';
                $menu .= '
            </ul>
        </li>
        <a style="font-family:Helvetica, Arial, sans-serif;color: #fff;font-weight:600;padding-left:35px;" href="../Otros/Logout.php">Salir</a>
    </ul>
</div>';
                return $menu;
            } else if ($_SESSION['login'][0]["NIVEL"] == 1) {
                return '<div id="wrapper">
                <ul class="menu">
                    <li class="item5"><a href="#">Administración</a>
                        <ul style="text-align:left;">
                             <li ><a href="../Administracion/Empresas.php">Empresas</a></li>
                              <li ><a href="../Clientes/InformacionCliente.php">Modificar Datos</a></li>
                    </ul>
                </li>
        <a style="font-family:Helvetica, Arial, sans-serif;color: #fff;font-weight:600;padding-left:35px;" href="../Otros/Logout.php">Salir</a> 
    </ul>
</div>';
            } else {
                return '<div id="wrapper">
                <ul class="menu">
                    <li class="item5"><a href="#">Administración</a>
                        <ul>
                             <li ><a href="../AdministracionGlobal/Clientes.php">Clientes</a></li>
                           <!---  <li ><a href="../AdministracionGlobal/">Administradores</a></li>-->
                        </ul>
                    </li>
                    <a style="font-family:Helvetica, Arial, sans-serif;color: #fff;font-weight:600;padding-left:35px;" href="../Otros/Logout.php">Salir</a>
                </ul></div>';
            }
        }
    }