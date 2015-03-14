
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Parametros.php';
    include '../../../Clases/cls_Contabilidad.php';

    session_start();
    if (isset($_SESSION['login']) == '' || (new cls_Usuarios())->TienePermiso(__FILE__, $_SESSION['login'][0]['ID_USUARIO']))
        echo '<script > self.location = "/"</script>';

    $Master = new Master();
    $menu = $Master->Menu();
    $Contabilidad = new cls_Contabilidad();


    $Cuenta = '<option value ="0">-- Seleccione Una Cuenta --</option>';

    foreach ($Contabilidad->TraeCuentas($_SESSION['login'][0]["ID_EMPRESA"]) as $llave1 => $valor1)
        $Cuenta .= '<option value ="' . $valor1['ID_CUENTA'] . '" >' . $valor1['CODIGO'] . ' - ' . $valor1['NOMBRE'] . '</option>';

?>
<html>
<head>
    <title>Moviemiento Contable</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>
    <script src="../../Js/jquery.dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>
    <script type="text/javascript" src="../../Js/Excel/jquery.battatech.excelexport.js"></script>
    <?php include '../../Css/css.php' ?>
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
                <h3><b>MOVIMIENTO CONTABLE</b></h3><br>

                <form action="">
                    <table style="width: 40%;color: #33373d;">
                        <tr>
                            <td style="text-align: right;">Desde:</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <input type="date" name="desde"  value="<?= date("Y").'-'.date("m").'-'.date("d") ?>" required>
                            </td>
                            <td style="text-align: right;">Hasta:</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <input type="date" name="hasta"  value="<?= date("Y").'-'.date("m").'-'.date("d") ?>" required>
                            </td>
                            <td style="text-align: right;">Cuenta</td>
                            <td style="padding-left: 10px;text-align: left;">
                                <select name="cmbCuenta" class="chosen-select" style="width:200px;">
                                    <?= $Cuenta ?>
                                </select>
                            </td>
                            <input type="hidden" value="contable" name="contable">
                        </tr>
                    </table>
                </form>
                <br>
                <input type="button" value="Generar" class="btnAzul" title="Generar una tabla de movimientos">
                <input type="button" value="Exportar" class="btnAzul" title="Exportar todos los movimientos a excel">
                <br><br>

                <div id="busqueda"></div>

                <table style="display: none;" id="texp">
                    <thead>
                    <tr>
                        <th style="text-align:left;">FECHA</th>
                        <th style="text-align:left;">D/C</th>
                        <th style="text-align:left;">VALOR</th>
                        <th style="text-align:left;">PRODUCTO/SERVICIO</th>
                        <th style="text-align:left;">DESCRIPCION</th>
                        <!--                        <th style="text-align:left;">SALDO</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </center>
        </div>
    </div>
</div>

<script>
    $(document).on('ready', function () {
        $('input[value=Generar]').on('click', function () {
            $('input[name=contable]').val('contable');
            $.ajax(
                {
                    url: 'Movimientos.php',
                    type: 'post',
                    data: $('form').serialize(),
                    success: function (data) {

                        $('#busqueda').html(data);
                    }
                });
        });

        $('input[value="Exportar"]').on('click', function () {
            $('input[name=contable]').val('todomov');
            $.ajax({
                url: 'Movimientos.php',
                type: 'post',
                data: $('form').serialize(),
                success: function (data) {
                    $('#texp tbody').html(data);
                    $("#texp").battatech_excelexport({
                        containerid: "texp", datatype: 'table', worksheetName: 'Movimiento Contable'
                    });
                }
            });
        });
    });

</script>

</body>
</html>