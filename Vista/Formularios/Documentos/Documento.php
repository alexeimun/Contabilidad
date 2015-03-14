
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Parametros.php';
    session_start();
    if (isset($_SESSION['login']) == '' || (new cls_Usuarios())->TienePermiso(__FILE__, $_SESSION['login'][0]['ID_USUARIO']))
        echo '<script > self.location = "/"</script>';


    $Master = new Master();
    $menu = $Master->Menu();
    $Parametros = new cls_Parametros();

    $tabla = '<table class="table" style="width:60%;">
            <th style="text-align:left;">TIPO</th>
            <th style="text-align:left;">NOMBRE</th>
            <th style="text-align:left;">NOMBRE IMPRESO</th>
            <th style="text-align:left;">CONSECUTIVO</th>
            <th style="text-align:center;">ACCIÓN</th>';
    foreach ($Parametros->TraeDocumentos($_SESSION['login'][0]["ID_EMPRESA"]) as $llave => $valor) {
        $idEmpresa = $valor['ID_EMPRESA'];
        $tabla .= '<tr><td style="text-align:left;">' . $valor['TIPO'] . '</td>';
        $tabla .= '<td style="text-align:left;">' . $valor['NOMBRE_DOCUMENTO'] . '</td>';
        $tabla .= '<td style="text-align:left;">' . $valor['NOMBRE_IMPRESO'] . '</td>';
        $tabla .= '<td style="text-align:left;">' . $valor['CONSECUTIVO'] . '</td>';
        $tabla .= '<td style="text-align:center;">
          <a href="ModificarDocumento.php?id=' . $valor['ID_DOCUMENTO'] . '"><img src="../../Imagenes/edit.png" title="Editar"></a>
                </td></tr>';
    }

    $tabla .= '</table>';

?>
<html>
<head>
    <title>Documentos</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>
    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>
</head>

<body>
<div id="wrap">
    <div id="header">
        <a href=""><img src="<?= $_SESSION['login'][0]["LOGO_EMPRESA"] ?>"></a>

        <h1 id="logo"><span class="gray"><?= $_SESSION['login'][0]["NOMBRE_EMPRESA"] ?></span></h1>

        <h3><span><?= $_SESSION['login'][0]["NOMBRE_USUARIO"] ?></span></h3>
        <img style="float: right;margin-top: 10px;" src="../../Imagenes/logo.png">
    </div>

    <div id="content-wrap">
        <?= $menu ?>

        <div id="main">
            <center>
                <h3><b>DOCUMENTOS</b></h3><br>
                <?= $tabla ?>

            </center>
        </div>
    </div>

</div>

</body>
</html>
