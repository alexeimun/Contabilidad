<?php
    session_start();
    if (isset($_SESSION['login']) == '' || $_SESSION['permisos'][28][1] == 0)
        echo '<script language = javascript> self.location = "../Otros/Login.php"</script>';

    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=file.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo $_SESSION['tabla'];
    exit;
?>