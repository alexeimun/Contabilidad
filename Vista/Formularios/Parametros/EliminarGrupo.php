<?php

    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Parametros.php';


    if ($_GET['id'] == "") {
        echo '<script language = javascript>
        self.location = "ProductosServiciosGrupos.php?me=2"
	</script>';
    }

    $Ter = new cls_Parametros();

    $ID = $_GET['id'];

    if ($Ter->EliminarGrupo($ID) > 0) {

        echo '<script language = javascript>
                      window.location.href = "ProductosServicosGrupos.php?me=2"
                     </script>';
//             
    } else {
        echo '<script language = javascript>
	alert("Error al eliminar" )
	</script>';
        header('location:ProductosServicosGrupos.php?me=2');
    }





?>
