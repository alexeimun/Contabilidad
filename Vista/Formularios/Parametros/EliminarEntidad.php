<?php

    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Componentes.php';

    if ($_GET['id'] == "")
        echo '<script language = javascript>self.location = "Entidades.php"</script>';

    $Ter = new Componentes();

    $ID = $_GET['id'];

    if ($Ter->EliminarEntidad($ID) > 0)
        echo '<script language = javascript>window.location.href = "Entidades.php" </script>';
//
    else {
        echo '<script language = javascript>
	           alert("Error al eliminar");</script>';
        header('location:Entidades.php');
    }