<?php

    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Vendedores.php';

    $Vendedor = new cls_Vendedores();

    $ID = $_GET['id'];

    $Vendedor->ActivaOdesactivaVendedor($ID, $_GET['a']);

    echo '<script language = javascript> window.location.href = "AdministrarVendedor.php?id=' . $ID . '"</script>';
?>
