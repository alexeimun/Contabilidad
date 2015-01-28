<?php

    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Parametros.php';


    if ($_GET['id'] == "") {
        echo '<script language = javascript>
        self.location = "Terceros.php"
	</script>';
    }

    $Ter = new cls_Parametros();

    $ID = $_GET['id'];

    if ($Ter->EliminarTercero($ID) > 0) {

        echo '<script language = javascript>
                      window.location.href = "Terceros.php"
                     </script>';
//             
    } else {
        echo '<script language = javascript>
	alert("Error al eliminar" )
	</script>';
        header('location:Terceros.php');
    }





?>
