<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Inventario.php';

    session_start();
    $Inventario = new cls_Inventarios();
    /*
     * Se enumeran todas las posibles acciones
     * de la sección de documentos
     */
    if (isset($_POST['inicial'])) {
        try {

            for ($i = 0; $i < count($_POST['Valor']); $i ++)
                $Inventario->InsertaMovimientoInventario($_POST['cmbConcepto'][$i], $_POST['Valor'][$i], 'I', 'D', $_POST['Fecha'][$i]);
            echo 'Se han agregado los campos correctamentos...';
        } catch (Exception $ex) {
            echo 'Ha ocurrido un error...';
        }
        exit;
    }
    else if (isset($_POST['compras']))
    {
        try {
            for ($i = 0; $i < count($_POST['Valor']); $i ++)
                $Inventario->InsertaMovimientoInventario($_POST['cmbConcepto'][$i], $_POST['Valor'][$i], 'C', 'D', $_POST['Fecha'][$i]);
            echo 'Se han agregado los campos correctamentos...';
        } catch (Exception $ex) {
            echo 'Ha ocurrido un error...';
        }
        exit;
    }
    else if (isset($_POST['devoluciones']))
    {
        try {
            for ($i = 0; $i < count($_POST['Valor']); $i ++)
                $Inventario->InsertaMovimientoInventario($_POST['cmbConcepto'][$i], $_POST['Valor'][$i], 'V', 'C', $_POST['Fecha'][$i]);
            echo 'Se han agregado los campos correctamentos...';
        } catch (Exception $ex) {
            echo 'Ha ocurrido un error...';
        }
        exit;
    }
    else if (isset($_POST['descuentos']))
    {
        try {
            for ($i = 0; $i < count($_POST['Valor']); $i ++)
                $Inventario->InsertaMovimientoInventario($_POST['cmbConcepto'][$i], $_POST['Valor'][$i], 'I', 'D', $_POST['Fecha'][$i]);
            echo 'Se han agregado los campos correctamentos...';
        } catch (Exception $ex) {
            echo 'Ha ocurrido un error...';
        }
        exit;
    }
    else if (isset($_POST['final']))
    {
        try {
            for ($i = 0; $i < count($_POST['Valor']); $i ++)
                $Inventario->InsertaMovimientoInventario($_POST['cmbConcepto'][$i], $_POST['Valor'][$i], 'F', 'C', $_POST['Fecha'][$i]);
            echo 'Se han agregado los campos correctamentos...';
        } catch (Exception $ex) {
            echo 'Ha ocurrido un error...';
        }
        exit;
    }
    else if (isset($_POST['costoventa']))
    {
        try {
             echo 'Total: $ '. number_format($Inventario->TraeCostoVenta($_POST['mes'],$_POST['ano']), 2, ',', '.') ;
        }
        catch (Exception $ex) {
            echo 'Ha ocurrido un error...';
        }
        exit;
    }
