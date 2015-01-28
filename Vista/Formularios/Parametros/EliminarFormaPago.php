<?php

    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Parametros.php';


    if ($_GET['id'] == "") {
        echo '<script language = javascript>
        self.location = "FormasDePago.php"
	</script>';
    }

    $Param = new cls_Parametros();

    if ($Param->EliminarFormaPago($_GET['id']) > 0) {

        echo '<script language = javascript>
                      window.location.href = "FormasDePago.php"
                     </script>';
//             
    } else {
        echo '<script language = javascript>
	alert("Error al eliminar" )
	</script>';
        header('location:FormasDePago.php');
    }





?>
