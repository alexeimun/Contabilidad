<?php
    session_start();
    if (isset($_SESSION['login']) == '' || (new cls_Usuarios())->TienePermiso(__FILE__,$_SESSION['login'][0]['ID_USUARIO']))
        echo '<script language = javascript> self.location = "../Otros/Login.php"</script>';

    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=file.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo $_SESSION['tabla'];
    exit;
?>