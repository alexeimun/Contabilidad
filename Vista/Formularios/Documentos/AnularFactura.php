<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Documentos.php';
    session_start();
    $Documentos = new cls_Documentos();
    $ID = $_GET['id'];


    $Documentos->AnulaFactura($ID, $_SESSION['login'][0]["ID_USUARIO"], $_SESSION['login'][0]["ID_EMPRESA"], $_GET['tipodoc']);

    switch ($_GET['tipodoc']) {
        case 'F':
            echo '<script>  window.location.href = "ReimpresionDocumentos.php?id=0"</script>';
            break;

        case 'R':
            echo '<script>  window.location.href = "ReimpresionDocumentos.php?id=1"</script>';
            break;

        case 'C':
            echo '<script>  window.location.href = "ReimpresionDocumentos.php?id=2"</script>';
            break;

        case 'G':
        case 'E':
            echo '<script>  window.location.href = "ReimpresionDocumentos.php?id=3"</script>';
            break;
    }


