<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Documentos.php';

    session_start();

    /*
     * Se enumeran todas las posibles acciones
     * de la sección de documentos
     */
    if (isset($_POST['saldos'])) {
        try {
            $Documentos = new cls_Documentos();

            $Documentos->TraeParametrosSaldosIniciales($_SESSION['login'][0]["ID_EMPRESA"]);

            for ($i = 0; $i < count($_POST['Valor']); $i ++)
                $Documentos->InsertaMovimiento($_POST['cmbTercero'][$i], 0, $_POST['cmbCuenta'][$i], 'S', $Documentos->_ConsecutivoSaldosIniciales, 0, $i + 1, '', $_POST['cmbTipoMov'][$i], 0,
                    $_POST['Valor'][$i], 0, 0, $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], 'S');
            echo 'Se han agregado los campos correctamentos...';
        } catch (Exception $ex) {
            echo 'Ha ocurrido un error...';
        }
        exit;
    } else if (isset($_POST['nota'])) {
        try {
            $Documentos = new cls_Documentos();

            $Documentos->TraeParametrosNotaContable($_SESSION['login'][0]["ID_EMPRESA"]);

            for ($i = 0; $i < count($_POST['Valor']); $i ++)
                $Documentos->InsertaMovimiento($_POST['cmbTercero'][$i], 0, $_POST['cmbCuenta'][$i], 'N', $Documentos->_ConsecutivoNotaContable, 0, $i + 1, '', $_POST['cmbTipoMov'][$i], 0,
                    $_POST['Valor'][$i], 0, 0, $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], 'S');
            echo 'Se han agregado los campos correctamentos...';
        } catch (Exception $ex) {
            echo 'Ha ocurrido un error...';
        }
        exit;
    }