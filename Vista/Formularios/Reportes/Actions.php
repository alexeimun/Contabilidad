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


//            for ($i = 0; $i < $date; $i ++)
//                $valor = $Documentos->TraeLibroFiscal($_POST['ano'], $_POST['mes'], $_SESSION['login'][0]["ID_EMPRESA"], $cont);

            $fila = '';
            for ($i = 0; $i < date("t", mktime(0, 0, 0, $_POST['mes']/*mes*/, 1, $_POST['ano'] /*año*/)); $i ++) {
                $fila .= '<tr><td style="text-align: center;">' . ($i + 1) . '</td>
             <td></td>
             <td></td>
             <td ></td>
             <td></td>
             <td ></td>
             </tr>';
            }
            echo $fila;
        } catch (Exception $ex) {

        }
        exit;
    }