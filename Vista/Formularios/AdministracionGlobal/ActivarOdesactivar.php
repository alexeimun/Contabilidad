<?php

    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Clientes.php';

    $Vendedor = new cls_Clientes();

    $ID = $_GET['id'];

    $Vendedor->ActivaOdesactivaCliente($ID, $_GET['a']);

    echo '<script> window.location.href = "AdministrarCliente.php?id=' . $ID . '"</script>';
?>
