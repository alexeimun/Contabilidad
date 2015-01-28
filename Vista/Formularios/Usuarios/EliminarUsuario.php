<?php

    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Usuarios.php';

    if ($_GET['id'] == "") echo '<script >self.location = "Usuarios.php"</script>';


    $Usuarios = new cls_Usuarios();

    $ID = $_GET['id'];

    if ($Usuarios->EliminarUsuario($ID) > 0) echo '<script >window.location.href = "Usuarios.php"</script>';

     else {
        echo '<script>alert("Error al eliminar" )</script>';
        header('location:Usuarios.php');
    }

?>
