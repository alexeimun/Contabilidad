<?php

    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Parametros.php';


    if ($_GET['id'] == "") {
        echo '<script >
        self.location = "FormasDePago.php";
	</script>';
    }

    $Param = new cls_Parametros();

    if ($Param->EliminarFormaPago($_GET['id']) > 0) {

        echo '<script >
                      window.location.href = "FormasDePago.php";
                     </script>';
//             
    } else {
        echo '<script >
	alert("Error al eliminar" );
	</script>';
        header('location:FormasDePago.php');
    }





?>
