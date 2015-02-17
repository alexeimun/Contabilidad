<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Documentos.php';
    include '../../../Clases/cls_Parametros.php';

    session_start();
    $Parametros = new cls_Parametros();
    $Documentos = new cls_Documentos();

    $tabla = '';

    if (isset($_SESSION['login']) == '')
        echo '<script > self.location = "/"</script>';

    if ($_GET['action'] == 'cambiatabla') {

        switch ($_GET['id']) {
            case 0 :
                $tabla = '<table id="table" class="table" style="width:90%;">
           <thead><tr> <th style="text-align:left;">CONSECUTIVO</th>
            <th style="text-align:left;">TERCERO</th>
            <th style="text-align:left;">FECHA</th>
            <th style="text-align:left;">TIPO PAGO</th>
            <th style="text-align:right;">VALOR</th>
            <th style="text-align:center;">ACCIÓN</th></tr></thead><tbody>';

                foreach ($Documentos->TraeFacturasReimpresion($_SESSION['login'][0]["ID_EMPRESA"], 'CO') as $llave => $valor) {
                    $tabla .= '<tr><td style="text-align:left;">' . $valor['CONSECUTIVO'] . '</td>';
                    $tabla .= '<td style="text-align:left;">' . $valor['NOMBRE1'] . ' ' . $valor['NOMBRE2'] . ' ' . $valor['APELLIDO1'] . ' ' . $valor['APELLIDO2'] . '</td>';
                    $tabla .= '<td style="text-align:left;">' . $valor['FECHA_REGISTRO'] . '</td>';
                    $tabla .= '<td style="text-align:left;">' . $valor['TIPO_PAGO'] . '</td>';
                    $tabla .= '<td style="text-align:right;">' . number_format($valor['VALOR'], 0, '', ',') . '</td>';
                    $tabla .= '<td style="text-align:center;">';

                    $tabla .= '<a href="ReImprime.php?id=0&consecutivo=' . $valor['CONSECUTIVO'] . '"><img src="../../Imagenes/print.png" title="Imprimir"></a>';

                    if ($valor['ANULADO'] == 0)
                        $tabla .= '<a href="" onclick="AnularFactura(' . $valor['CONSECUTIVO'] . ');return false"><img src="../../Imagenes/cancel.png" title="Anular"></a></td>';

                    $tabla .= '</tr>';
                }

                $tabla .= '</tbody></table>';
                break;

            case 1 :
                $tabla = '<table id="table" class="table" style="width:90%;">
             <thead><tr> <th style="text-align:left;">CONSECUTIVO</th>
            <th style="text-align:left;">TERCERO</th>
            <th style="text-align:left;">FECHA</th>
            <th style="text-align:right;">ABONADO</th>
            <th style="text-align:right;">VALOR</th>
            <th style="text-align:center;">ACCIÓN</th></tr></thead><tbody>';

                foreach ($Documentos->TraeRecibos($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
                    $tabla .= '<tr ><td style="text-align:left;">' . $valor['CONSECUTIVO_RECIBO'] . '</td>';
                    $tabla .= '<td style="text-align:left;">' . $valor['NOMBRE1'] . ' ' . $valor['NOMBRE2'] . ' ' . $valor['APELLIDO1'] . ' ' . $valor['APELLIDO2'] . '</td>';
                    $tabla .= '<td style="text-align:left;">' . $valor['FECHA_REGISTRO'] . '</td>';
                    $tabla .= '<td style="text-align:right;">' . number_format($valor['ABONADO'], 0, '', ',') . '</td>';
                    $tabla .= '<td style="text-align:right;">' . number_format($valor['VALOR'], 0, '', ',') . '</td>';
                    $tabla .= '<td style="text-align:center;">';
                    $tabla .= '<a href="ReImprime.php?id=1&consecutivo=' . $valor['CONSECUTIVO_RECIBO'] . '&
                    pagos=' . $valor['CONSECUTIVO_FACTURA'] . '"><img src="../../Imagenes/print.png" title="Imprimir"></a>';

                    if ($valor['ANULADO'] == 0)
                        $tabla .= '<a href="" onclick="AnularRecibo(' . $valor['CONSECUTIVO_RECIBO'] . ');return false"><img src="../../Imagenes/cancel.png" title="Anular"></a></td>';

                    $tabla .= '</tr>';
                }

                $tabla .= '</tbody></table>';

                break;

            case 2 :

                $tabla = '<table id="table" class="table" style="width:90%;">
           <thead>
           <tr>
            <th style="text-align:left;">PAGADO A</th>
            <th style="text-align:left;">CONSECUTIVO</th>
            <th style="text-align:left;">CÓDIGO</th>
            <th style="text-align:right;">VALOR</th>
             <th style="text-align:left;">FECHA</th>
            <th style="text-align:center;">ACCIÓN</th></tr></thead><tbody>';

                foreach ($Documentos->TraeCajaMenorReimpresion($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
                    $tabla .= '<tr>
                    <td style="text-align:left;">' . $valor['NOMBRE1'] . ' ' . $valor['NOMBRE2'] . ' ' . $valor['APELLIDO1'] . ' ' . $valor['APELLIDO2'] . '</td>';
                    $tabla .= '<td style="text-align:left;">' . $valor['CONSECUTIVO'] . '</td>';
                    $tabla .= '<td style="text-align:left;">' . $valor['CODIGO'] . '</td>';
                    $tabla .= '<td style="text-align:left;">' . number_format($valor['VALOR'], 0, '', ',') . '</td>';
                    $tabla .= '<td style="text-align:left;">' . $valor['FECHA_REGISTRO'] . '</td>';
                    $tabla .= '<td style="text-align:center;">';
                    $tabla .= '<a href="ReImprime.php?id=2&consecutivo=' . $valor['CONSECUTIVO'] . '"><img src="../../Imagenes/print.png" title="Imprimir"></a>';

                    if ($valor['ANULADO'] == 0)
                        $tabla .= '<a href="" onclick="AnularFactura(' . $valor['CONSECUTIVO'] . ');return false"><img src="../../Imagenes/cancel.png" title="Anular"></a></td>';

                    $tabla .= '</tr>';
                }

                $tabla .= '</tbody></table>';
                break;

            case 3:

                $tabla = '<table id="table" class="table" style="width:90%;">
           <thead>
           <tr>
           <th style="text-align:left;">CONSECUTIVO</th>
           <th style="text-align:left;">TERCERO</th>
            <th style="text-align:left;">ABONADO</th>
            <th style="text-align:left;">VALOR</th>
             <th style="text-align:left;">FECHA</th>
            <th style="text-align:center;">ACCIÓN</th></tr></thead><tbody>';

                foreach ($Documentos->TraeEgresosReimpresion($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
                    $tabla .= '<tr>
                    <td style="text-align:left;">' . $valor['CONSECUTIVO_GASTOS'] . '</td>';
                    $tabla .= '<td style="text-align:left;">' . $valor['NOMBRE1'] . ' ' . $valor['NOMBRE2'] . ' ' . $valor['APELLIDO1'] . ' ' . $valor['APELLIDO2'] . '</td>';
                    $tabla .= '<td style="text-align:left;">' . number_format($valor['ABONADO'], 0, '', ',') . '</td>';
                    $tabla .= '<td style="text-align:left;">' . number_format($valor['VALOR'], 0, '', ',') . '</td>';
                    $tabla .= '<td style="text-align:left;">' . $valor['FECHA_REGISTRO'] . '</td>';
                    $tabla .= '<td style="text-align:center;">';
                    $tabla .= '<a href="ReImprime.php?id=3&consecutivog=' . $valor['CONSECUTIVO_GASTOS'] . '&consecutivoe=' . $valor['CONSECUTIVO_EGRESOS'] . '
                    &tipo=' . $valor['TIPO_PAGO'] . '"><img src="../../Imagenes/print.png" title="Imprimir"></a>';

                    $doc = $valor['TIPO_DOC'] == 'E' ? 0 : 1;
                    if ($valor['ANULADO'] == 0)
                        $tabla .= '<a href="" onclick="AnularEgreso(' . $valor['CONSECUTIVO_GASTOS'] . ',' . $doc . ');return false"><img src="../../Imagenes/cancel.png" title="Anular"></a></td>';

                    $tabla .= '</tr>';
                }

                $tabla .= '</tbody></table>';
                break;
        }

        echo $tabla;
    }
?>

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

</script>