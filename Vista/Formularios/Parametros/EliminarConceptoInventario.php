<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/cls_Parametros.php';

    if ($_GET['id'] == "")
        echo '<script >self.location = "ConceptosInventariophp"</script>';

    $Ter = new cls_Parametros();

    $ID = $_GET['id'];

    if ($Ter->EliminarConcepto($ID) > 0)
        echo '<script >window.location.href = "ConceptosInventario.php" </script>';
//
    else {
        echo '<script >
	           alert("Error al eliminar");</script>';
        header('location:ConceptosInventario.php');
    }
