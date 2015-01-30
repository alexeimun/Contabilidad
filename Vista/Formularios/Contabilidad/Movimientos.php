<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Parametros.php';
    include_once '../../Css/css.php';


    $Parametros = new cls_Parametros();

    session_start();


    if (isset($_POST['contable'])) {
        $tabla = '<table id="table" class="table" style="width:90%;">
           <thead>
           <tr>
           <th style="text-align:left;">FECHA</th>
           <th style="text-align:left;">D/C</th>
            <th style="text-align:left;">VALOR</th>
            <th style="text-align:left;">PRODUCTO</th>
            <th style="text-align:left;">DESCRIPCION</th>
            <th style="text-align:left;">SALDO</th>
             </tr></thead><tbody>';
        $Saldo = 0;
        foreach ($Parametros->TraeMovContable($_SESSION['login'][0]["ID_EMPRESA"], $_POST['cmbCuenta'], date("Y-m-d", strtotime($_POST['desde'])), date("Y-m-d", strtotime($_POST['hasta']))) as $llave => $valor) {
            $tabla .= '<tr> <td style="text-align:left;">' . $valor['FECHA_REGISTRO'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['TIPO_MOV'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . number_format($valor['VALOR'], 0, '', ',') . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['PRODUCTO'] . '</td>';
            $tabla .= '<td style="text-align:left;">' . $valor['OBS'] . '</td>';
            if ($valor['TIPO_MOV'] == 'C') $Saldo -= $valor['VALOR']; else   $Saldo += $valor['VALOR'];
            $tabla .= '<td style="text-align:left;">' . number_format($Saldo, 0, '', ',') . '</td>';
        }

        $tabla .= '</tbody></table>';
        echo $tabla;
    } else if (isset($_POST['tercero'])) {

        try {
            $Dato = isset($_POST['cmbTercero']) ? $_POST['cmbTercero'] : $_POST['txtDoc'];

            $tabla = '';
            foreach ($Parametros->TraeTercero($_SESSION['login'][0]["ID_EMPRESA"], $Dato, (isset($_POST['cmbTercero']) ? 'Id' : 'Doc')) as $llave => $valor)
                $tabla .= '<div style="font-weight: bold;"><span><span style="color: #5E83A3;">Nombre: </span> ' . $valor['N_COMPLETO'] . '</span>
            <span style="margin-left:20px;"><span style="color: #5E83A3;">Documento: </span>' . $valor['NUM_DOCUMENTO'] . '</span>
            <span  style="margin-left:20px;"><span style="color: #5E83A3;">Email: </span>' . $valor['EMAIL'] . '</span></div>';

            $tabla .= '<table id="table" class="table" style="width:90%;">
           <thead>
           <tr>
           <th style="text-align:left;">FECHA</th>
           <th style="text-align:left;">D/C</th>
            <th style="text-align:left;">VALOR</th>
            <th style="text-align:left;">PRODUCTO</th>
             </tr></thead><tbody>';

            $Saldo = 0;

            if (isset($_POST['cmbTercero']))
                $Res = $Parametros->TraeMovTerceroporNombre($_SESSION['login'][0]["ID_EMPRESA"], $Dato, date("Y-m-d", strtotime($_POST['desde'])), date("Y-m-d", strtotime($_POST['hasta'])));
            else $Res = $Parametros->TraeMovTerceroporDoc($_SESSION['login'][0]["ID_EMPRESA"], $Dato, date("Y-m-d", strtotime($_POST['desde'])), date("Y-m-d", strtotime($_POST['hasta'])));

            foreach ($Res as $llave => $valor) {
                $tabla .= '<tr> <td style="text-align:left;">' . $valor['FECHA_REGISTRO'] . '</td>';
                $tabla .= '<td style="text-align:left;">' . $valor['TIPO_MOV'] . '</td>';
                $tabla .= '<td style="text-align:left;">' . number_format($valor['VALOR'], 0, '', ',') . '</td>';
                $tabla .= '<td style="text-align:left;">' . $valor['PRODUCTO'] . '</td>';
            }

            $tabla .= '</tbody></table>';
            echo $tabla;
        } catch (Exception $ex) {
        }
    } else if (isset($_POST['filtro'])) {
        if ($_POST['filtro'] == 'Tercero') {
            $cmbTercero = '<option value ="0">-- Seleccione Un Tercero --</option>';
            foreach ($Parametros->TraeTerceros($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor)
                $cmbTercero .= '<option style="text-align:left;" value ="' . $valor['ID_TERCERO'] . '">' . $valor['N_COMPLETO'] . '</option>';
            echo '<select name="cmbTercero" class="chosen-select" style="width:200px;">' . $cmbTercero . '</select>';
        } else echo '<input type="text" name="txtDoc" style="text-align: center;">';
        exit;
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