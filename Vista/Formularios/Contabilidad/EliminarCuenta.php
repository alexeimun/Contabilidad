<?php

    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Contabilidad.php';
    if ($_GET['id'] == "") echo '<script>self.location = "PUC.php";</script>';


    $Cuentas = new cls_Contabilidad();

    $ID = $_GET['id'];

    if ($Cuentas->EliminarCuenta($ID) > 0) echo '<script>window.location.href = "PUC.php";</script>';
//             
    else echo '<script>alert("Error al eliminar" );</script>';

    header('location:PUC.php');
