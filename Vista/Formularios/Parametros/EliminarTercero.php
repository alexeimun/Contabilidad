<?php

    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Parametros.php';


    if ($_GET['id'] == "") {
        echo '<script >
        self.location = "Terceros.php"
	</script>';
    }

    $Ter = new cls_Parametros();

    $ID = $_GET['id'];

    if ($Ter->EliminarTercero($ID) > 0) {

        echo '<script >
                      window.location.href = "Terceros.php"
                     </script>';
//             
    } else {
        echo '<script >
	alert("Error al eliminar" )
	</script>';
        header('location:Terceros.php');
    }





?>
