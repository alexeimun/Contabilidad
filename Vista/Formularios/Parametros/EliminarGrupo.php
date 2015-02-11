<?php

    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Parametros.php';


    if ($_GET['id'] == "") {
        echo '<script >
        self.location = "ProductosServiciosGrupos.php?me=2";
	</script>';
    }

    $Ter = new cls_Parametros();

    $ID = $_GET['id'];

    if ($Ter->EliminarGrupo($ID) > 0) {

        echo '<script >
                      window.location.href = "ProductosServicosGrupos.php?me=2";
                     </script>';
//             
    } else {
        echo '<script >
	alert("Error al eliminar" );
	</script>';
        header('location:ProductosServicosGrupos.php?me=2');
    }





?>
