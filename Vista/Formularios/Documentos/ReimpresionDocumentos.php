<!DOCTYPE html>
<?php
    include '../../../Config/Conexion/config.php';
    include '../../../Generic/Database/DataBase.php';
    include '../../../Clases/Master.php';
    include '../../../Clases/cls_Parametros.php';
    include '../../../Clases/cls_Documentos.php';
    session_start();
    if (isset($_SESSION['login']) != '') {

        $Master = new Master();
        $menu = $Master->Menu();
        $cmbtipo = '';


        switch ($_GET['id']) {
            case 0:
                $cmbtipo = '<option value="0" selected>Factura</option>
            <option value="1">Recibo</option>
            <option value="2">Caja Menor</option>
            <option value="3">Egresos</option>';
                break;

            case 1:
                $cmbtipo = '<option value="1" selected>Recibo</option>
            <option value="0">Factura</option>
            <option value="2">Caja Menor</option>
            <option value="3">Egresos</option>';
                break;

            case 2:
                $cmbtipo = '<option value="2" selected>Caja Menor</option>
                <option value="0" >Factura</option>
                 <option value="1">Recibo</option>
                 <option value="3">Egresos</option>';

                break;

            case 3:
                $cmbtipo = '<option value="3"selected>Egresos</option>
                     <option value="0" >Factura</option>
                     <option value="1">Recibo</option>
                    <option value="2" >Caja Menor</option>';

                break;
        }
    } else  echo '<script>self.location = "../Otros/Login.php"</script>';

?>
<html>
<head>
    <title>Reimpresión Documentos</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../Css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="../../Css/style.css"/>
    <script src="../../Js/menu.js"></script>
    <script type="text/javascript" language="javascript" src="../../Js/jquery.dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="../../Css/stilos.css"/>
    <?php include '../../Css/css.php' ?>
</head>
<style type="text/css">

</style>

<script>
    $(document).ready(function () {
        $('#tablas').load('ValidaTablas.php?action=cambiatabla&id=' +<?= $_GET['id'];?>);
    });

    function AnularFactura(id) {
        if (confirm("Seguro que quieres anular esta factura?"))
            window.location.href = 'AnularFactura.php?tipodoc=F&id=' + id;
    }
    function AnularEgreso(id,doc) {
        if (confirm("Seguro que quieres anular esta factura?"))
            window.location.href = 'AnularFactura.php?tipodoc='+(doc==0?'E':'G')+'&id=' + id;
    }
    function AnularRecibo(id) {
        if (confirm("Seguro que quieres anular este recibo?"))
            window.location.href = 'AnularFactura.php?tipodoc=R&id=' + id;
    }
</script>

<body>
<div id="wrap">
    <div id="header">
        <a href=""><img src="<?= $_SESSION['login'][0]["LOGO_EMPRESA"] ?>"></img></a>

        <h1 id="logo"><span class="gray"><?= $_SESSION['login'][0]["NOMBRE_EMPRESA"] ?></span></h1>

        <h3><span><?= $_SESSION['login'][0]["NOMBRE_USUARIO"] ?></span></h3>
        <img style="float: right;margin-top: 10px;" src="../../Imagenes/logo.png">
    </div>

    <div id="content-wrap">
        <?= $menu ?>

        <div id="main">
            <center>
                <h3><b>REIMPRESIÓN DOCUMENTOS</b></h3><br>

                <select style="" class="chosen-select" id="cmbopcion" onchange="Change();">
                    <?= $cmbtipo; ?>
                </select>
                <br><br>

                <div id="tablas"></div>

            </center>
        </div>
    </div>

</div>
<script>
    function Change() {
        $('#tablas').load('ValidaTablas.php?action=cambiatabla&id=' + document.getElementById('cmbopcion').value);
    }
</script>
</body>
</html>
