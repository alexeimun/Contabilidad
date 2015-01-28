<?php

    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Usuarios.php';


    if ($_GET['id'] == "") {
        echo '<script language = javascript>
        self.location = "Usuarios.php"
	</script>';
    }

    $Usuarios = new cls_Usuarios();

    $ID = $_GET['id'];

    if ($Usuarios->EliminarUsuario($ID) > 0) {

        echo '<script language = javascript>
                      window.location.href = "Usuarios.php"
                     </script>';
//             
    } else {
        echo '<script language = javascript>
	alert("Error al eliminar" )
	</script>';
        header('location:Usuarios.php');
    }





?>
