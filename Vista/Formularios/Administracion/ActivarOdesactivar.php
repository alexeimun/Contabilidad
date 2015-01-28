<?php

    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Empresas.php';

    $Empresas = new cls_Empresas();

    $ID = $_GET['id'];

    $Empresas->ActivaOdesactivaEmpresa($ID, $_GET['a']);

    echo '<script> window.location.href = "AdministrarEmpresa.php?id=' . $ID . '"</script>';
?>
